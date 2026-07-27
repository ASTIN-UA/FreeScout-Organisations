<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\File;

/**
 * Removes files that never belonged to a public OrgPortal build.
 *
 * The 2.0.6 and 2.0.7 release archives were built from the wrong revision and
 * shipped a set of files that are not part of this module. A module update only
 * extracts the new archive over the existing folder — it never deletes files
 * that disappeared between versions — so those files stay on disk indefinitely.
 * This migration cleans them up.
 */
class RemoveStaleBuildFiles extends Migration
{
    /**
     * Paths relative to the module root.
     */
    const STALE_MODULE_FILES = [
        'Database/Migrations/2026_07_04_000001_add_can_view_stats_to_organization_members_table.php',
        'Database/Migrations/2026_07_05_000001_add_call_duration_to_conversations_table.php',
        'Database/Migrations/2026_07_05_000002_create_org_report_dimensions_table.php',
        'Models/OrgReportDimension.php',
        'Public/js/date-range-picker.js',
        'Public/js/org-charts.js',
        'Public/js/vendor/redoc.standalone.js',
        'Resources/views/partials/call_duration_field.blade.php',
        'Resources/views/partials/stats_tickets_modal.blade.php',
        'Resources/views/portal/stats.blade.php',
        'Services/OrgStats.php',
        'Services/OrgStatsExport.php',
        'Services/ReportDimensionRegistry.php',
        'vendor-libs/xlsx/.gitignore',
        'vendor-libs/xlsx/composer.json',
        'vendor-libs/xlsx/composer.lock',
    ];

    /**
     * The same assets once published into the public folder.
     */
    const STALE_PUBLISHED_ASSETS = [
        'modules/orgportal/js/date-range-picker.js',
        'modules/orgportal/js/org-charts.js',
        'modules/orgportal/js/vendor/redoc.standalone.js',
    ];

    public function up()
    {
        $moduleRoot = realpath(__DIR__.'/../..');

        if ($moduleRoot) {
            foreach (self::STALE_MODULE_FILES as $relativePath) {
                $this->deleteFile($moduleRoot.DIRECTORY_SEPARATOR.$relativePath);
            }

            // vendor-libs/xlsx also carried an installed dependency tree.
            $this->deleteDirectory($moduleRoot.DIRECTORY_SEPARATOR.'vendor-libs');
        }

        foreach (self::STALE_PUBLISHED_ASSETS as $relativePath) {
            $this->deleteFile(public_path($relativePath));
        }

        $this->deleteDirectory(public_path('modules/orgportal/js/vendor'));
    }

    public function down()
    {
        // Nothing to restore — these files are not part of this module.
    }

    private function deleteFile($path)
    {
        try {
            if (File::isFile($path)) {
                File::delete($path);
            }
        } catch (\Exception $e) {
            // A read-only file must not abort the update.
        }
    }

    private function deleteDirectory($path)
    {
        try {
            if (File::isDirectory($path)) {
                File::deleteDirectory($path);
            }
        } catch (\Exception $e) {
            // Same here — cleanup is best effort.
        }
    }
}
