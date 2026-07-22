<?php

namespace Modules\OrgPortal\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class OrganizationDomain extends Model
{
    protected $table    = 'organization_domains';
    protected $fillable = ['organization_id', 'mailbox_id', 'domain', 'unit_id'];

    protected $casts = [
        'mailbox_id' => 'integer',
        'unit_id'    => 'integer',
    ];

    /**
     * Sentinel stored in mailbox_id for organisations with no mailbox
     * (organizations.mailbox_id IS NULL, i.e. visible everywhere). See the
     * table migration for why this is 0 rather than NULL.
     */
    const GLOBAL_MAILBOX = 0;

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function unit()
    {
        return $this->belongsTo(OrganizationUnit::class, 'unit_id');
    }

    /**
     * Normalise a domain for storage and comparison: lowercase, trimmed, with
     * a leading '@', scheme, or trailing dot/slash stripped. Admins paste all
     * of "@company.com", "https://company.com/", "Company.COM " — they all mean
     * the same thing and must land on the same stored value, or the unique
     * index is decorative.
     *
     * Returns '' when nothing usable remains.
     */
    public static function normalize(?string $domain): string
    {
        $domain = strtolower(trim((string) $domain));
        if ($domain === '') return '';

        // Strip a scheme and any path, then a leading '@'.
        //
        // 'www.' is only dropped when the input actually looked like a URL.
        // Stripping it unconditionally would make www.company.com resolve to
        // company.com — the one subdomain that silently defeats exact matching
        // — and would quietly widen an admin's 'www.acme.com' entry to the
        // whole apex domain.
        $hadUrlShape = (bool) preg_match('~^[a-z][a-z0-9+.-]*://~', $domain);

        $domain = preg_replace('~^[a-z][a-z0-9+.-]*://~', '', $domain);
        if (str_contains($domain, '/')) {
            $hadUrlShape = true;
        }
        $domain = explode('/', $domain)[0];
        $domain = ltrim($domain, '@');

        if ($hadUrlShape) {
            $domain = preg_replace('~^www\.~', '', $domain);
        }

        $domain = trim($domain, ".\t\n\r\0\x0B ");

        return $domain;
    }

    /**
     * Extract the normalised domain part of an email address.
     * Returns '' if the address has no usable domain.
     */
    public static function fromEmail(?string $email): string
    {
        $email = strtolower(trim((string) $email));
        $at    = strrpos($email, '@');
        if ($at === false) return '';

        return static::normalize(substr($email, $at + 1));
    }

    /**
     * Whether a normalised domain is syntactically usable as a mail domain:
     * at least two labels, valid characters, a non-numeric TLD.
     */
    public static function isValidFormat(string $domain): bool
    {
        if ($domain === '' || strlen($domain) > 191) return false;

        return (bool) preg_match(
            '~^(?=.{1,253}$)([a-z0-9]([a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z]{2,63}$~',
            $domain
        );
    }

    /**
     * Last-resort blacklist, used when the config list cannot be read.
     *
     * The config file is the real list; this is the fail-safe. If config()
     * returns nothing — module config not published, container not booted,
     * a bad deploy — an empty blacklist would mean "no domain is public",
     * and binding gmail.com would sail through validation and hand hundreds
     * of unrelated customers access to each other's tickets. Failing open on
     * a security check is not acceptable, so the highest-volume providers are
     * hard-coded here where nothing can strip them.
     */
    const FALLBACK_PUBLIC_DOMAINS = [
        'gmail.com', 'googlemail.com', 'outlook.com', 'hotmail.com', 'live.com',
        'yahoo.com', 'icloud.com', 'me.com', 'aol.com', 'gmx.com', 'web.de',
        'protonmail.com', 'proton.me', 'zoho.com', 'yandex.ru', 'mail.ru',
        'qq.com', '163.com', 'ukr.net', 'i.ua', 'meta.ua',
    ];

    /**
     * Public/disposable domains that may never be bound to an organisation.
     * Config list plus any extras the admin added; admins can extend it but
     * not shrink it — removing gmail.com from the list is never a fix for
     * anything, and the fallout (cross-organisation ticket access) is silent.
     */
    public static function publicDomains(): array
    {
        $base = [];

        // config() can be unavailable outside a booted container; treat that
        // exactly like an empty list rather than letting the error escape into
        // an admin-facing save.
        try {
            $base = (array) config('orgportal.public_domains', []);
        } catch (\Throwable $e) {
            $base = [];
        }

        // Normalise the config entries too: isPublicDomain() compares strictly,
        // so a stray '@GMAIL.com' in a published config file would silently
        // stop blocking gmail.com.
        $base = array_map([static::class, 'normalize'], $base);

        // The built-ins are a floor, not a default. Merging them unconditionally
        // means a config that was trimmed — deliberately or by a bad deploy —
        // still cannot open up the major providers.
        $base = array_merge(static::FALLBACK_PUBLIC_DOMAINS, $base);

        $extra = '';
        try {
            $extra = (string) \Option::get('orgportal.public_domains_extra', '');
        } catch (\Throwable $e) {
            $extra = '';
        }

        $extra = preg_split('~[\s,;]+~', $extra, -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $extra = array_map([static::class, 'normalize'], $extra);

        return array_values(array_unique(array_filter(array_merge($base, $extra))));
    }

    public static function isPublicDomain(string $domain): bool
    {
        return in_array(static::normalize($domain), static::publicDomains(), true);
    }

    /**
     * Resolve a customer email to an organisation binding, scoped to the
     * mailbox the conversation arrived in.
     *
     * A mailbox-specific binding beats a global one: the more precise rule
     * wins, otherwise an admin's per-mailbox setup would silently do nothing.
     *
     * Returns ['org_id' => int, 'unit_id' => int|null] or null.
     */
    public static function resolveByEmail(?string $email, ?int $mailboxId = null): ?array
    {
        if (!Schema::hasTable('organization_domains')) return null;

        $domain = static::fromEmail($email);
        if ($domain === '' || static::isPublicDomain($domain)) return null;

        $slots = $mailboxId
            ? [$mailboxId, static::GLOBAL_MAILBOX]
            : [static::GLOBAL_MAILBOX];

        foreach ($slots as $slot) {
            $row = static::where('domain', $domain)
                ->where('mailbox_id', $slot)
                // A deactivated organisation must stop pulling people in;
                // otherwise "deactivate" silently keeps enrolling customers.
                ->whereExists(function ($q) {
                    $q->selectRaw('1')
                      ->from('organizations')
                      ->whereColumn('organizations.id', 'organization_domains.organization_id')
                      ->where('organizations.is_active', true);
                })
                ->first();

            if ($row) {
                return ['org_id' => (int) $row->organization_id, 'unit_id' => $row->unit_id];
            }
        }

        return null;
    }

    /**
     * All domains bound to an organisation.
     */
    public static function forOrg(int $orgId)
    {
        return static::where('organization_id', $orgId)->orderBy('domain')->get();
    }

    /**
     * Re-stamp the denormalised mailbox_id of an organisation's domains after
     * the organisation moves between mailboxes.
     *
     * A binding that cannot move because the target mailbox already uses that
     * domain is DELETED, not left behind: the organisation no longer belongs to
     * the old mailbox, so a row still pointing there would keep enrolling
     * customers into an organisation that is not part of their mailbox at all.
     * The dropped domains are returned so the caller can tell the admin.
     */
    public static function syncMailbox(int $orgId, ?int $mailboxId): array
    {
        if (!Schema::hasTable('organization_domains')) return [];

        $slot    = $mailboxId ?: static::GLOBAL_MAILBOX;
        $dropped = [];

        foreach (static::where('organization_id', $orgId)->get() as $row) {
            if ((int) $row->mailbox_id === $slot) continue;

            // Note: no organization_id filter. A collision with this same
            // organisation means the row is redundant, and skipping it would
            // let $row->save() hit unique(mailbox_id, domain) and surface a raw
            // QueryException in the middle of saving the organisation.
            $taken = static::where('domain', $row->domain)
                ->where('mailbox_id', $slot)
                ->where('id', '!=', $row->id)
                ->exists();

            if ($taken) {
                $dropped[] = $row->domain;
                $row->delete();
                continue;
            }

            $row->mailbox_id = $slot;
            $row->save();
        }

        return array_values(array_unique($dropped));
    }
}
