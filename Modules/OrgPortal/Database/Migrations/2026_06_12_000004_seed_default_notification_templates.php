<?php

use Illuminate\Database\Migrations\Migration;

class SeedDefaultNotificationTemplates extends Migration
{
    public function up()
    {
        $locale = config('app.locale', 'en');
        $dir    = __DIR__ . '/../../Resources/templates/';

        $path = $dir . $locale . '.php';
        if (!file_exists($path)) {
            $base = explode('-', $locale)[0];
            $path = null;
            foreach (glob($dir . $base . '*.php') ?: [] as $candidate) {
                $path = $candidate;
                break;
            }
            if (!$path || !file_exists($path)) {
                $path = $dir . 'en.php';
            }
        }

        $templates = (file_exists($path)) ? require $path : [];

        foreach ($templates as $event => $tpl) {
            $subjectKey = 'orgportal.tpl_' . $event . '_subject';
            $bodyKey    = 'orgportal.tpl_' . $event . '_body';

            if (empty(\Option::get($subjectKey))) {
                \Option::set($subjectKey, $tpl['subject'] ?? '');
            }
            if (empty(\Option::get($bodyKey))) {
                \Option::set($bodyKey, $tpl['body'] ?? '');
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
