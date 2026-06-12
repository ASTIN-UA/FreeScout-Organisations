<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\OrgPortal\Models\OrganizationMember;

class CreateOrgNotificationSubscriptionsTable extends Migration
{
    public function up()
    {
        Schema::create('org_notification_subscriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('member_id');
            // 'new_ticket' | 'reply_agent' | 'reply_customer'
            $table->string('event', 20);
            // 'org' | 'unit'
            $table->string('scope_type', 10);
            // null for org scope, unit_id for unit scope
            $table->unsignedInteger('scope_id')->nullable();
            $table->timestamps();

            $table->foreign('member_id')
                  ->references('id')->on('organization_members')
                  ->onDelete('cascade');

            // MySQL-compatible unique: treat NULL scope_id as 0
            $table->unique(['member_id', 'event', 'scope_type', 'scope_id'], 'org_notif_subs_unique');
        });

        // Migrate existing notify_on_new_ticket=true members → org-scope new_ticket subscription
        if (Schema::hasColumn('organization_members', 'notify_on_new_ticket')) {
            $members = \DB::table('organization_members')
                ->where('notify_on_new_ticket', true)
                ->get(['id']);

            foreach ($members as $m) {
                \DB::table('org_notification_subscriptions')->insertOrIgnore([
                    'member_id'  => $m->id,
                    'event'      => 'new_ticket',
                    'scope_type' => 'org',
                    'scope_id'   => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down()
    {
        Schema::dropIfExists('org_notification_subscriptions');
    }
}
