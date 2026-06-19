<?php

use Illuminate\Database\Migrations\Migration;

class SeedDefaultNotificationTemplates extends Migration
{
    public function up()
    {
        $templates = \Modules\OrgPortal\Http\Controllers\OrgPortalAdminController::defaultTemplates(
            config('app.locale', 'en')
        );

        foreach ($templates as $event => $tpl) {
            $subjectKey = 'orgportal.tpl_' . $event . '_subject';
            $bodyKey    = 'orgportal.tpl_' . $event . '_body';

            if (empty(\Option::get($subjectKey))) {
                \Option::set($subjectKey, $tpl['subject']);
            }
            if (empty(\Option::get($bodyKey))) {
                \Option::set($bodyKey, $tpl['body']);
            }
        }
    }

    public function down()
    {
        foreach (['new_ticket', 'reply_agent', 'reply_customer'] as $event) {
            \Option::remove('orgportal.tpl_' . $event . '_subject');
            \Option::remove('orgportal.tpl_' . $event . '_body');
        }
    }
}
