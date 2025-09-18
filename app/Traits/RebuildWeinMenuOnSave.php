<?php
namespace App\Traits;

use Config\Services;

trait RebuildWeinMenuOnSave
{
    /** Nach Region speichern/ändern/löschen aufrufen */
    protected function afterRegionSave(): void
    {
        Services::weinMenu()->build(true);
    }

    /** Nach Winzer speichern/ändern/löschen aufrufen */
    protected function afterProducerSave(): void
    {
        Services::weinMenu()->build(true);
    }
}
