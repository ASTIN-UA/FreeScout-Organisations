<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationDomainsTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('organization_domains')) return;

        Schema::create('organization_domains', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('organization_id');

            // Denormalised copy of organizations.mailbox_id, kept in sync when
            // the organisation moves between mailboxes. It exists so the DB can
            // enforce unique(mailbox_id, domain) and the resolver can hit one
            // index without joining organizations.
            //
            // 0 — not NULL — marks a global organisation (organizations.mailbox_id
            // IS NULL). MySQL treats NULLs as distinct inside a unique index, so a
            // nullable column would silently allow two global organisations to
            // claim the same domain: exactly the ambiguity this index exists to
            // prevent.
            $table->unsignedInteger('mailbox_id')->default(0);

            $table->string('domain', 191);
            $table->unsignedInteger('unit_id')->nullable();
            $table->timestamps();

            $table->foreign('organization_id')
                  ->references('id')->on('organizations')
                  ->onDelete('cascade');

            $table->foreign('unit_id')
                  ->references('id')->on('organization_units')
                  ->onDelete('set null');

            // A domain resolves to exactly one organisation per mailbox.
            $table->unique(['mailbox_id', 'domain']);
            $table->index('domain');
        });
    }

    public function down()
    {
        Schema::dropIfExists('organization_domains');
    }
}
