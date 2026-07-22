<?php

/**
 * Test suite for OrgPortal email-domain attribution.
 *
 * Run:  D:\xampp\php\php.exe Modules/OrgPortal/Tests/run-tests.php [path-to-freescout]
 *
 * See bootstrap.php for why this does not use PHPUnit.
 */

$freescout = $argv[1] ?? 'D:/xampp/htdocs/freescout';

require __DIR__ . '/bootstrap.php';

$capsule = orgportal_test_bootstrap($freescout);

// Map Modules\OrgPortal\* to THIS repository, and prepend so it beats
// FreeScout's own autoloader — the live install carries a deployed copy of the
// module, and loading a mix of old and new classes produces failures that look
// like logic bugs but are not.
spl_autoload_register(function ($class) {
    if (!str_starts_with($class, 'Modules\\OrgPortal\\')) return;
    $path = __DIR__ . '/../' . str_replace('\\', '/', substr($class, strlen('Modules\\OrgPortal\\'))) . '.php';
    if (is_file($path)) require_once $path;
}, true, true);

use Illuminate\Database\Capsule\Manager as Cap;
use Modules\OrgPortal\Models\OrganizationDomain;
use Modules\OrgPortal\Models\OrganizationMember;
use Modules\OrgPortal\Services\MembershipService;
use Modules\OrgPortal\Services\OrgAttribution;

// ── Tiny assertion harness ──────────────────────────────────────────────────

$GLOBALS['tests']  = ['pass' => 0, 'fail' => 0];
$GLOBALS['failures'] = [];

function test(string $name, callable $fn): void
{
    reset_fixtures();
    try {
        $fn();
        $GLOBALS['tests']['pass']++;
        echo "  \xE2\x9C\x93 {$name}\n";
    } catch (Throwable $e) {
        $GLOBALS['tests']['fail']++;
        $GLOBALS['failures'][] = $name . ' — ' . $e->getMessage();
        echo "  \xE2\x9C\x97 {$name}\n      " . $e->getMessage() . "\n";
    }
}

function is_same($expected, $actual, string $what = ''): void
{
    if ($expected !== $actual) {
        throw new Exception(sprintf(
            '%sexpected %s, got %s',
            $what ? $what . ': ' : '',
            var_export($expected, true),
            var_export($actual, true)
        ));
    }
}

function is_true($actual, string $what = ''): void
{
    is_same(true, $actual, $what);
}

function is_false($actual, string $what = ''): void
{
    is_same(false, $actual, $what);
}

function section(string $name): void
{
    echo "\n{$name}\n";
}

function reset_fixtures(): void
{
    foreach (['organization_domains', 'organization_members', 'organization_units',
              'organizations', 'emails', 'customers', 'conversations', 'mailboxes'] as $table) {
        Cap::table($table)->delete();
    }
    Option::reset();
    Module::$active = [];
}

// ── Fixture helpers ─────────────────────────────────────────────────────────

function make_mailbox(int $id, string $name = 'Support'): int
{
    Cap::table('mailboxes')->insert(['id' => $id, 'name' => $name]);
    return $id;
}

function make_org(string $name, ?int $mailboxId = null): int
{
    return Cap::table('organizations')->insertGetId([
        'name' => $name, 'mailbox_id' => $mailboxId, 'is_active' => 1,
    ]);
}

function make_unit(int $orgId, string $name = 'Sales'): int
{
    return Cap::table('organization_units')->insertGetId([
        'organization_id' => $orgId, 'name' => $name,
    ]);
}

function make_customer(string $email): int
{
    $id = Cap::table('customers')->insertGetId(['first_name' => 'Test', 'last_name' => 'User']);
    Cap::table('emails')->insert(['customer_id' => $id, 'email' => $email]);
    return $id;
}

function add_email(int $customerId, string $email): void
{
    Cap::table('emails')->insert(['customer_id' => $customerId, 'email' => $email]);
}

function bind_domain(int $orgId, string $domain, ?int $mailboxId = null, ?int $unitId = null): int
{
    return Cap::table('organization_domains')->insertGetId([
        'organization_id' => $orgId,
        'mailbox_id'      => $mailboxId ?: 0,
        'domain'          => $domain,
        'unit_id'         => $unitId,
    ]);
}

function make_conversation(int $customerId, ?int $mailboxId = null): int
{
    return Cap::table('conversations')->insertGetId([
        'customer_id' => $customerId, 'mailbox_id' => $mailboxId,
    ]);
}

// ── Normalisation and validation ────────────────────────────────────────────

section('Domain normalisation');

test('strips @, scheme, www, path, case and whitespace', function () {
    is_same('company.com', OrganizationDomain::normalize(' @Company.COM '));
    is_same('company.com', OrganizationDomain::normalize('https://www.company.com/support'));
    is_same('company.com', OrganizationDomain::normalize('COMPANY.com.'));
    is_same('', OrganizationDomain::normalize(''));
    is_same('', OrganizationDomain::normalize(null));
});

test('extracts the domain from an email address', function () {
    is_same('company.com', OrganizationDomain::fromEmail('John.Doe@Company.com'));
    is_same('company.com', OrganizationDomain::fromEmail('  jane@company.com  '));
    is_same('', OrganizationDomain::fromEmail('not-an-email'));
    is_same('', OrganizationDomain::fromEmail(null));
    // Last @ wins — a local part may legally contain a quoted @
    is_same('company.com', OrganizationDomain::fromEmail('"weird@local"@company.com'));
});

test('accepts real domains and rejects malformed ones', function () {
    is_true(OrganizationDomain::isValidFormat('company.com'));
    is_true(OrganizationDomain::isValidFormat('sub.company.co.uk'));
    is_true(OrganizationDomain::isValidFormat('my-company.io'));
    is_false(OrganizationDomain::isValidFormat('company'), 'no TLD');
    is_false(OrganizationDomain::isValidFormat('company.1'), 'numeric TLD');
    is_false(OrganizationDomain::isValidFormat('-bad.com'), 'leading hyphen');
    is_false(OrganizationDomain::isValidFormat(''), 'empty');
    is_false(OrganizationDomain::isValidFormat(str_repeat('a', 190) . '.com'), 'over length');
});

section('Public domain blacklist');

test('rejects well-known providers regardless of formatting', function () {
    is_true(OrganizationDomain::isPublicDomain('gmail.com'));
    is_true(OrganizationDomain::isPublicDomain('@GMAIL.com'));
    is_true(OrganizationDomain::isPublicDomain('ukr.net'));
    is_true(OrganizationDomain::isPublicDomain('mailinator.com'));
    is_false(OrganizationDomain::isPublicDomain('company.com'));
});

test('admin-configured extras extend the built-in list', function () {
    is_false(OrganizationDomain::isPublicDomain('local-isp.example'));
    Option::set('orgportal.public_domains_extra', 'local-isp.example, other.example');
    is_true(OrganizationDomain::isPublicDomain('local-isp.example'));
    is_true(OrganizationDomain::isPublicDomain('other.example'));
    // Built-ins survive the merge
    is_true(OrganizationDomain::isPublicDomain('gmail.com'));
});

test('falls back to the built-in list when config is unreadable', function () {
    $config = Illuminate\Container\Container::getInstance()->make('config');
    $saved  = $config->get('orgportal.public_domains');

    try {
        // Simulate unpublished/missing module config.
        $config->set('orgportal.public_domains', []);

        is_true(OrganizationDomain::isPublicDomain('gmail.com'), 'must still be blocked');
        is_true(OrganizationDomain::isPublicDomain('ukr.net'));
        is_false(OrganizationDomain::isPublicDomain('company.com'), 'real domains stay allowed');

        // Admin extras still merge on top of the fallback.
        Option::set('orgportal.public_domains_extra', 'local-isp.example');
        is_true(OrganizationDomain::isPublicDomain('local-isp.example'));
    } finally {
        $config->set('orgportal.public_domains', $saved);
    }
});

// ── Resolver ────────────────────────────────────────────────────────────────

section('Domain resolver');

test('matches exactly and ignores subdomains', function () {
    $org = make_org('Acme');
    bind_domain($org, 'company.com');

    $hit = OrganizationDomain::resolveByEmail('jane@company.com');
    is_same($org, $hit['org_id']);

    is_same(null, OrganizationDomain::resolveByEmail('jane@mail.company.com'), 'subdomain must not match');
    is_same(null, OrganizationDomain::resolveByEmail('jane@notcompany.com'));
});

test('mailbox-specific binding beats the global one', function () {
    $mb       = make_mailbox(5);
    $globalOrg = make_org('Global Org');
    $mbOrg     = make_org('Mailbox Org', $mb);

    bind_domain($globalOrg, 'company.com');          // global slot (0)
    bind_domain($mbOrg,     'company.com', $mb);     // mailbox slot

    is_same($mbOrg, OrganizationDomain::resolveByEmail('jane@company.com', $mb)['org_id']);
    is_same($globalOrg, OrganizationDomain::resolveByEmail('jane@company.com', 99)['org_id'],
        'other mailbox falls back to global');
    is_same($globalOrg, OrganizationDomain::resolveByEmail('jane@company.com')['org_id'],
        'no mailbox context uses global');
});

test('a mailbox-scoped binding is invisible to other mailboxes', function () {
    $mb  = make_mailbox(5);
    $org = make_org('Mailbox Org', $mb);
    bind_domain($org, 'company.com', $mb);

    is_same(null, OrganizationDomain::resolveByEmail('jane@company.com', 99));
    is_same(null, OrganizationDomain::resolveByEmail('jane@company.com'));
});

test('a blacklisted domain never resolves even if a row exists', function () {
    $org = make_org('Acme');
    bind_domain($org, 'gmail.com');   // bypasses validation, as a stale row would

    is_same(null, OrganizationDomain::resolveByEmail('jane@gmail.com'));
});

test('the unique index blocks two orgs claiming a domain in one mailbox', function () {
    $a = make_org('A');
    $b = make_org('B');
    bind_domain($a, 'company.com');

    $threw = false;
    try {
        bind_domain($b, 'company.com');
    } catch (Throwable $e) {
        $threw = true;
    }
    is_true($threw, 'expected a unique constraint violation');
});

// ── Membership service ──────────────────────────────────────────────────────

section('MembershipService');

test('creates a membership with the minimum permissions', function () {
    $org = make_org('Acme');
    $cus = make_customer('jane@company.com');

    is_true(MembershipService::addByDomain($org, $cus));

    $m = OrganizationMember::where('customer_id', $cus)->first();
    is_same('member', $m->role);
    is_same('domain', $m->source);
    is_false((bool) $m->can_manage_org);
    is_false((bool) $m->can_view_stats);
    is_false((bool) $m->notify_on_new_ticket);
    is_true((bool) $m->is_active);
});

test('never overrides an active membership elsewhere', function () {
    $acme  = make_org('Acme');
    $other = make_org('Other');
    $cus   = make_customer('jane@company.com');

    MembershipService::addOrActivate($other, $cus);

    is_false(MembershipService::addByDomain($acme, $cus), 'manual assignment must win');
    is_same(1, OrganizationMember::where('customer_id', $cus)->count());
    is_same($other, (int) OrganizationMember::where('customer_id', $cus)->first()->organization_id);
});

test('reactivates a deactivated row instead of hitting the unique index', function () {
    $org = make_org('Acme');
    $cus = make_customer('jane@company.com');

    MembershipService::addOrActivate($org, $cus);
    OrganizationMember::where('customer_id', $cus)->update([
        'is_active' => false, 'deactivated_at' => now(),
    ]);

    $result = MembershipService::addOrActivate($org, $cus);

    is_same('reactivated', $result['status']);
    is_same(1, OrganizationMember::where('customer_id', $cus)->count(), 'no duplicate row');
    $m = OrganizationMember::where('customer_id', $cus)->first();
    is_true((bool) $m->is_active);
    is_same(null, $m->deactivated_at);
});

test('reports an existing active membership in the same org', function () {
    $org = make_org('Acme');
    $cus = make_customer('jane@company.com');

    MembershipService::addOrActivate($org, $cus);
    $result = MembershipService::addOrActivate($org, $cus);

    is_same('already_member', $result['status']);
    is_false(MembershipService::addByDomain($org, $cus), 'no membership was created');
});

test('rejects a unit belonging to another organization', function () {
    $acme  = make_org('Acme');
    $other = make_org('Other');
    $unit  = make_unit($other);
    $cus   = make_customer('jane@company.com');

    $result = MembershipService::addOrActivate($acme, $cus, $unit);

    is_same('unit_not_found', $result['status']);
    is_same(0, OrganizationMember::count());
});

test('stores the unit from the domain binding', function () {
    $org  = make_org('Acme');
    $unit = make_unit($org);
    $cus  = make_customer('jane@company.com');

    MembershipService::addByDomain($org, $cus, $unit);

    is_same($unit, (int) OrganizationMember::where('customer_id', $cus)->first()->unit_id);
});

// ── Attribution ─────────────────────────────────────────────────────────────

section('Conversation attribution');

test('stamps a conversation and enrols the customer by domain', function () {
    $org  = make_org('Acme');
    $cus  = make_customer('jane@company.com');
    bind_domain($org, 'company.com');
    $conv = make_conversation($cus);

    OrgAttribution::attribute(App\Conversation::find($conv));

    $row = Cap::table('conversations')->find($conv);
    is_same($org, (int) $row->org_id);
    is_same(1, OrganizationMember::where('customer_id', $cus)->where('source', 'domain')->count());
});

test('an existing membership wins over the domain binding', function () {
    $acme  = make_org('Acme');
    $other = make_org('Other');
    $cus   = make_customer('jane@company.com');
    bind_domain($acme, 'company.com');
    MembershipService::addOrActivate($other, $cus);
    $conv = make_conversation($cus);

    OrgAttribution::attribute(App\Conversation::find($conv));

    is_same($other, (int) Cap::table('conversations')->find($conv)->org_id);
});

test('respects the mailbox of the conversation', function () {
    $mb        = make_mailbox(5);
    $globalOrg = make_org('Global Org');
    $mbOrg     = make_org('Mailbox Org', $mb);
    bind_domain($globalOrg, 'company.com');
    bind_domain($mbOrg,     'company.com', $mb);

    $cus  = make_customer('jane@company.com');
    $conv = make_conversation($cus, $mb);

    OrgAttribution::attribute(App\Conversation::find($conv));

    is_same($mbOrg, (int) Cap::table('conversations')->find($conv)->org_id);
});

test('a customer with several addresses matches on any of them', function () {
    $org = make_org('Acme');
    $cus = make_customer('jane@gmail.com');       // public — must be skipped
    add_email($cus, 'jane@company.com');
    bind_domain($org, 'company.com');
    $conv = make_conversation($cus);

    OrgAttribution::attribute(App\Conversation::find($conv));

    is_same($org, (int) Cap::table('conversations')->find($conv)->org_id);
});

test('leaves the conversation unattributed when nothing matches', function () {
    $cus  = make_customer('jane@company.com');
    $conv = make_conversation($cus);

    OrgAttribution::attribute(App\Conversation::find($conv));

    $row = Cap::table('conversations')->find($conv);
    is_same(null, $row->org_id);
    is_true($row->org_attributed_at !== null, 'still stamped as processed');
});

section('Batch backfill');

test('counts domain matches separately and enrols the customers', function () {
    $org = make_org('Acme');
    bind_domain($org, 'company.com');

    $byDomain = make_customer('jane@company.com');
    $byMember = make_customer('bob@other.com');
    MembershipService::addOrActivate($org, $byMember);

    $unmatched = make_customer('nobody@nowhere.com');

    make_conversation($byDomain);
    make_conversation($byMember);
    make_conversation($unmatched);

    $result = OrgAttribution::backfillBatchDetailed();

    is_same(3, $result['processed']);
    is_same(1, $result['by_domain']);
    is_same(1, $result['by_member']);
    is_same(1, $result['unmatched']);
    is_same(1, OrganizationMember::where('customer_id', $byDomain)->count());
});

test('backfillBatch attributes domain matches too', function () {
    $org = make_org('Acme');
    bind_domain($org, 'company.com');
    $cus  = make_customer('jane@company.com');
    $conv = make_conversation($cus);

    is_same(1, OrgAttribution::backfillBatch());
    is_same($org, (int) Cap::table('conversations')->find($conv)->org_id);
});

section('Mailbox move');

test('re-stamps domain rows when the organization changes mailbox', function () {
    $mb  = make_mailbox(5);
    $org = make_org('Acme');
    bind_domain($org, 'company.com');

    $conflicts = OrganizationDomain::syncMailbox($org, $mb);

    is_same([], $conflicts);
    is_same($mb, (int) OrganizationDomain::where('organization_id', $org)->first()->mailbox_id);
});

test('drops domains the target mailbox already uses instead of stranding them', function () {
    $mb    = make_mailbox(5);
    $acme  = make_org('Acme');
    $other = make_org('Other', $mb);

    bind_domain($acme,  'company.com');        // global
    bind_domain($other, 'company.com', $mb);   // already taken in mailbox 5

    $conflicts = OrganizationDomain::syncMailbox($acme, $mb);

    is_same(['company.com'], $conflicts);
    // The row must not survive pointing at the mailbox the org just left —
    // it would keep enrolling customers into an org that is no longer there.
    is_same(0, OrganizationDomain::where('organization_id', $acme)->count());
    is_same($other, (int) OrganizationDomain::where('domain','company.com')->first()->organization_id);
});

test('a same-org duplicate does not blow up the mailbox move', function () {
    $mb  = make_mailbox(5);
    $org = make_org('Acme');

    // Reachable state: one row global, one already in the target mailbox.
    bind_domain($org, 'company.com');
    bind_domain($org, 'company.com', $mb);

    $dropped = OrganizationDomain::syncMailbox($org, $mb);

    is_same(['company.com'], $dropped);
    is_same(1, OrganizationDomain::where('organization_id', $org)->count());
    is_same($mb, (int) OrganizationDomain::where('organization_id', $org)->first()->mailbox_id);
});

test('moving to global uses the sentinel slot', function () {
    $mb  = make_mailbox(5);
    $org = make_org('Acme', $mb);
    bind_domain($org, 'company.com', $mb);

    OrganizationDomain::syncMailbox($org, null);

    is_same(0, (int) OrganizationDomain::where('organization_id', $org)->first()->mailbox_id);
});

// ── Regressions found by the adversarial review ─────────────────────────────

section('Revocation must stick');

test('domain matching never reactivates a deactivated membership', function () {
    $org = make_org('Acme');
    $cus = make_customer('jane@company.com');
    bind_domain($org, 'company.com');

    MembershipService::addByDomain($org, $cus);
    // Admin revokes access
    OrganizationMember::where('customer_id', $cus)->update([
        'is_active' => false, 'deactivated_at' => now(),
    ]);

    // Customer emails again
    is_false(MembershipService::addByDomain($org, $cus), 'automation must not resurrect');
    is_false((bool) OrganizationMember::where('customer_id', $cus)->first()->is_active);

    // A human still can
    $result = MembershipService::addOrActivate($org, $cus);
    is_same('reactivated', $result['status']);
});

test('attribute() does not re-enrol a deactivated customer', function () {
    $org = make_org('Acme');
    $cus = make_customer('jane@company.com');
    bind_domain($org, 'company.com');
    MembershipService::addByDomain($org, $cus);
    OrganizationMember::where('customer_id', $cus)->update(['is_active' => false]);

    $conv = make_conversation($cus);
    OrgAttribution::attribute(App\Conversation::find($conv));

    is_false((bool) OrganizationMember::where('customer_id', $cus)->first()->is_active);
    is_same(null, Cap::table('conversations')->find($conv)->org_id);
});

test('a manual membership never has its source downgraded to domain', function () {
    $org = make_org('Acme');
    $cus = make_customer('jane@company.com');

    MembershipService::addOrActivate($org, $cus);   // manual
    OrganizationMember::where('customer_id', $cus)->update(['is_active' => false]);
    MembershipService::addOrActivate($org, $cus, null, 'member', MembershipService::SOURCE_DOMAIN);

    is_same('manual', OrganizationMember::where('customer_id', $cus)->first()->source,
        'otherwise a remove-domain sweep would deactivate a human-made membership');
});

section('Mailbox isolation of memberships');

test('a mailbox-scoped org does not claim conversations from other mailboxes', function () {
    $mb5 = make_mailbox(5);
    $mb9 = make_mailbox(9, 'Other');
    $org = make_org('Mailbox Org', $mb5);
    bind_domain($org, 'company.com', $mb5);

    $cus = make_customer('jane@company.com');

    // Enrolled through mailbox 5
    $c5 = make_conversation($cus, $mb5);
    OrgAttribution::attribute(App\Conversation::find($c5));
    is_same($org, (int) Cap::table('conversations')->find($c5)->org_id);

    // Same customer writes to mailbox 9 — membership must not leak there
    $c9 = make_conversation($cus, $mb9);
    OrgAttribution::attribute(App\Conversation::find($c9));
    is_same(null, Cap::table('conversations')->find($c9)->org_id, 'leaked into another mailbox');
});

test('a global org still applies in every mailbox', function () {
    $mb9 = make_mailbox(9);
    $org = make_org('Global Org');
    $cus = make_customer('jane@company.com');
    MembershipService::addOrActivate($org, $cus);

    $conv = make_conversation($cus, $mb9);
    OrgAttribution::attribute(App\Conversation::find($conv));

    is_same($org, (int) Cap::table('conversations')->find($conv)->org_id);
});

test('batch backfill honours mailbox isolation too', function () {
    $mb5 = make_mailbox(5);
    $mb9 = make_mailbox(9, 'Other');
    $org = make_org('Mailbox Org', $mb5);
    $cus = make_customer('jane@company.com');
    MembershipService::addOrActivate($org, $cus);

    $c9 = make_conversation($cus, $mb9);
    OrgAttribution::backfillBatch();

    is_same(null, Cap::table('conversations')->find($c9)->org_id);
});

section('Exact matching and blacklist hardening');

test('www. is not stripped from an email domain', function () {
    $org = make_org('Acme');
    bind_domain($org, 'company.com');

    is_same('www.company.com', OrganizationDomain::fromEmail('bob@www.company.com'));
    is_same(null, OrganizationDomain::resolveByEmail('bob@www.company.com'),
        'www is a subdomain, and subdomains must not match');

    // A pasted URL is still cleaned up
    is_same('company.com', OrganizationDomain::normalize('https://www.company.com/support'));
});

test('the built-in blacklist is a floor, not a default', function () {
    $config = Illuminate\Container\Container::getInstance()->make('config');
    $saved  = $config->get('orgportal.public_domains');

    try {
        // Someone trims the published config down to a couple of entries
        $config->set('orgportal.public_domains', ['example-only.test']);

        is_true(OrganizationDomain::isPublicDomain('gmail.com'), 'built-ins must survive a trimmed config');
        is_true(OrganizationDomain::isPublicDomain('example-only.test'));
    } finally {
        $config->set('orgportal.public_domains', $saved);
    }
});

test('config entries are normalised before comparison', function () {
    $config = Illuminate\Container\Container::getInstance()->make('config');
    $saved  = $config->get('orgportal.public_domains');

    try {
        $config->set('orgportal.public_domains', ['@EXAMPLE-Odd.test.']);
        is_true(OrganizationDomain::isPublicDomain('example-odd.test'));
    } finally {
        $config->set('orgportal.public_domains', $saved);
    }
});

test('a deactivated organization stops enrolling by domain', function () {
    $org = make_org('Acme');
    bind_domain($org, 'company.com');
    Cap::table('organizations')->where('id', $org)->update(['is_active' => 0]);

    is_same(null, OrganizationDomain::resolveByEmail('jane@company.com'));
});

// ── Summary ─────────────────────────────────────────────────────────────────

$t = $GLOBALS['tests'];
echo "\n" . str_repeat('─', 60) . "\n";
echo "passed: {$t['pass']}   failed: {$t['fail']}\n";

if ($t['fail']) {
    echo "\nFailures:\n";
    foreach ($GLOBALS['failures'] as $f) echo "  - {$f}\n";
    exit(1);
}

exit(0);
