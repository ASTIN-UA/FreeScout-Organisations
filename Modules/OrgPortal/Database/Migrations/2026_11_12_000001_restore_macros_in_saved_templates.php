<?php

use Illuminate\Database\Migrations\Migration;

/**
 * The notification templates saved in Manage → Organizations → Templates went
 * through HTMLPurifier, which turned href="{ticket_url}" into
 * href="%7Bticket_url%7D": the macro was never replaced and the e-mail's
 * «View ticket» button led nowhere. Once, the placeholders of the stored
 * templates are put back. Running it again changes nothing.
 */
class RestoreMacrosInSavedTemplates extends Migration
{
    public function up()
    {
        $rows = \DB::table('options')->where('name', 'like', 'orgportal.tpl_%')->get(['name', 'value']);
        foreach ($rows as $row) {
            if (!is_string($row->value)) {
                continue;
            }
            $fixed = \Modules\OrgPortal\Services\TemplateMacros::restore($row->value);
            if ($fixed !== $row->value) {
                \Option::set($row->name, $fixed);
            }
        }
    }

    public function down()
    {
        // Nothing to undo: the encoded form never worked.
    }
}
