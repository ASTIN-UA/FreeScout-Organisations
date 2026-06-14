<?php

namespace Modules\OrgPortal\Console;

use Illuminate\Console\Command;
use Modules\OrgPortal\Services\OrgAttribution;

class BackfillOrgAttribution extends Command
{
    protected $signature = 'orgportal:backfill-attribution
                            {--limit=1000 : Conversations to process per run}';

    protected $description = 'Backfill org_id / org_unit_id snapshot on existing conversations';

    public function handle(): int
    {
        $limit     = (int) $this->option('limit');
        $processed = OrgAttribution::backfillBatch($limit);

        if ($processed === 0) {
            $this->info('[OrgPortal] Backfill complete — no unattributed conversations remain.');
            return 0;
        }

        $pending = OrgAttribution::pendingCount();
        $this->info("[OrgPortal] Backfill: processed {$processed} conversations. Remaining: {$pending}.");

        return 0;
    }
}
