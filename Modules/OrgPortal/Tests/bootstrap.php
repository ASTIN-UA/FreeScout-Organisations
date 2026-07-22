<?php

/**
 * Minimal test bootstrap for the OrgPortal module.
 *
 * The module ships inside FreeScout, whose production installs run
 * `composer install --no-dev` — PHPUnit is simply not there. Rather than force
 * dev dependencies into a working install, this boots the pieces the module
 * actually touches (Eloquent, the container, the facades it calls) against an
 * in-memory SQLite database.
 *
 * Usage:  php Modules/OrgPortal/Tests/run-tests.php [path-to-freescout]
 */

use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Support\Facades\Facade;

function orgportal_test_bootstrap(string $freescoutPath): Capsule
{
    require_once $freescoutPath . '/vendor/autoload.php';

    $container = new Container();
    Container::setInstance($container);
    Facade::setFacadeApplication($container);

    $capsule = new Capsule($container);
    $capsule->addConnection([
        'driver'   => 'sqlite',
        'database' => ':memory:',
        'prefix'   => '',
    ]);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    // SQLite ignores foreign keys unless asked; the schema relies on them.
    $capsule->getConnection()->statement('PRAGMA foreign_keys = ON');

    $container->instance('db', $capsule->getDatabaseManager());
    $container->instance('db.schema', $capsule->getConnection()->getSchemaBuilder());

    // Capsule seeds container['config'] with an Illuminate\Support\Fluent, which
    // has no dot-notation support — config('orgportal.public_domains') would
    // silently return null. Swap in a real Repository, carrying Capsule's
    // connection settings across so the database keeps working.
    $existing = $container->bound('config') ? $container->make('config') : null;
    $carried  = $existing instanceof ConfigRepository
        ? $existing->all()
        : (is_object($existing) && method_exists($existing, 'toArray') ? $existing->toArray() : []);

    $config = new ConfigRepository($carried);
    $config->set('orgportal', require __DIR__ . '/../Config/config.php');
    $container->instance('config', $config);

    // FreeScout registers these as aliases in config/app.php; standalone we
    // create them ourselves, or every \DB:: / \Schema:: call in the module
    // fails with "Class not found".
    if (!class_exists('DB', false)) {
        class_alias(\Illuminate\Support\Facades\DB::class, 'DB');
    }
    if (!class_exists('Schema', false)) {
        class_alias(\Illuminate\Support\Facades\Schema::class, 'Schema');
    }

    orgportal_test_migrate($capsule);

    return $capsule;
}

/**
 * The subset of the schema the domain feature touches. Mirrors the module
 * migrations — including unique(organization_id, customer_id) on members,
 * which is the constraint MembershipService exists to work around.
 */
function orgportal_test_migrate(Capsule $capsule): void
{
    $schema = $capsule->schema();

    $schema->create('mailboxes', function ($t) {
        $t->increments('id');
        $t->string('name')->nullable();
    });

    $schema->create('customers', function ($t) {
        $t->increments('id');
        $t->string('first_name')->nullable();
        $t->string('last_name')->nullable();
    });

    $schema->create('emails', function ($t) {
        $t->increments('id');
        $t->unsignedInteger('customer_id');
        $t->string('email');
    });

    $schema->create('organizations', function ($t) {
        $t->increments('id');
        $t->string('name');
        $t->string('color')->nullable();
        $t->unsignedInteger('mailbox_id')->nullable();
        $t->boolean('is_active')->default(true);
        $t->timestamps();
    });

    $schema->create('organization_units', function ($t) {
        $t->increments('id');
        $t->unsignedInteger('organization_id');
        $t->string('name');
        $t->timestamps();
    });

    $schema->create('organization_members', function ($t) {
        $t->increments('id');
        $t->unsignedInteger('organization_id');
        $t->unsignedInteger('unit_id')->nullable();
        $t->unsignedInteger('customer_id');
        $t->string('role')->default('member');
        $t->boolean('can_manage_org')->default(false);
        $t->boolean('can_view_stats')->default(false);
        $t->boolean('notify_on_new_ticket')->default(false);
        $t->boolean('is_active')->default(true);
        $t->dateTime('deactivated_at')->nullable();
        $t->string('locale')->nullable();
        $t->string('source', 20)->default('manual');
        $t->timestamps();
        $t->unique(['organization_id', 'customer_id']);
    });

    $schema->create('organization_domains', function ($t) {
        $t->increments('id');
        $t->unsignedInteger('organization_id');
        $t->unsignedInteger('mailbox_id')->default(0);
        $t->string('domain', 191);
        $t->unsignedInteger('unit_id')->nullable();
        $t->timestamps();
        $t->unique(['mailbox_id', 'domain']);
    });

    $schema->create('conversations', function ($t) {
        $t->increments('id');
        $t->unsignedInteger('customer_id')->nullable();
        $t->unsignedInteger('mailbox_id')->nullable();
        $t->unsignedInteger('org_id')->nullable();
        $t->unsignedInteger('org_unit_id')->nullable();
        $t->dateTime('org_attributed_at')->nullable();
        // App\Conversation is a timestamped Eloquent model; updates touch these.
        $t->timestamps();
    });
}

/**
 * Stand-in for FreeScout's Option facade (settings stored in the DB).
 */
class Option
{
    public static array $values = [];

    public static function get($name, $default = null)
    {
        return static::$values[$name] ?? $default;
    }

    public static function set($name, $value): void
    {
        static::$values[$name] = $value;
    }

    public static function reset(): void
    {
        static::$values = [];
    }
}

/**
 * Stand-in for the nwidart Module facade. Domain matching never requires the
 * Tags module, so reporting everything inactive keeps attribution on the
 * member/domain path.
 */
class Module
{
    public static array $active = [];

    public static function isActive($name): bool
    {
        return in_array($name, static::$active, true);
    }
}
