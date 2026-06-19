<?php

use Illuminate\Database\Migrations\Migration;

/**
 * Migrate legacy locale-agnostic template Option keys to locale-prefixed keys.
 *
 * Before: orgportal.tpl_{event}_subject
 * After:  orgportal.tpl_en_{event}_subject
 *
 * The old keys are left untouched (non-destructive) so a rollback is trivial.
 * The new ServiceProvider reads locale-prefixed keys; the old ones become unused.
 */
class MigrateTplKeysToLocale extends Migration
{
    private array $events = ['new_ticket', 'reply_agent', 'reply_customer'];

    public function up(): void
    {
        foreach ($this->events as $event) {
            foreach (['subject', 'body'] as $field) {
                $oldKey = 'orgportal.tpl_' . $event . '_' . $field;
                $newKey = 'orgportal.tpl_en_' . $event . '_' . $field;

                $existing = \Option::get($oldKey, null);

                // Only copy if old key has data AND new locale key not yet set
                if ($existing !== null && $existing !== '' && \Option::get($newKey, null) === null) {
                    \Option::set($newKey, $existing);
                }
            }
        }
    }

    public function down(): void
    {
        // Non-destructive: old keys still exist. Nothing to reverse.
    }
}
