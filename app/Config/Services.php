<?php

namespace Config;

use CodeIgniter\Config\BaseService;
use App\Repositories\DbContentRepository;
use App\Services\ContentService;
use App\Services\HomeTeaserService;
use App\Services\ImagePipelineService;
use App\Services\WeinMenuSnapshotBuilder;

class Services extends BaseService
{
    // Repository für Inhalte (trad)
    public static function contentRepo(bool $getShared = true): DbContentRepository
    {
        if ($getShared) return static::getSharedInstance('contentRepo');
        return new DbContentRepository(db_connect()); // h737390_trad
    }

    // WICHTIG: Pipeline für Content-Bilder (behalten)
    public static function imagePipelineService(bool $getShared = true): ImagePipelineService
    {
        if ($getShared) return static::getSharedInstance('imagePipelineService');
        return new ImagePipelineService(\Config\Database::connect('default'), cache());
    }

    // ContentService muss die Image-Pipeline bekommen, NICHT cache()
/*
    public static function contentService(bool $getShared = true): ContentService
    {
        // WICHTIG: Inhalte kommen aus 'trad'
        $dbTrad = db_connect();              // <- deine Live-Inhalts-DB
        return new ContentService($dbTrad, cache());
    }
 * 
 */
public static function contentService(bool $getShared = true): \App\Services\ContentService
{
    if ($getShared) return static::getSharedInstance('contentService');
    // WICHTIG: 2. Parameter ist die Pipeline, nicht cache()

    return new ContentService(db_connect(), cache());
}
    // Startseiten-Teaser (falls genutzt)
    public static function homeTeaserService(bool $getShared = true): HomeTeaserService
    {
        if ($getShared) return static::getSharedInstance('homeTeaserService');
        return new HomeTeaserService(db_connect());
    }

    // Snapshot-Builder für das Wein-Menü (Frontend liest nur /writable/cache/menus/wein.html)
    public static function weinMenu(bool $getShared = true): WeinMenuSnapshotBuilder
    {
        if ($getShared) return static::getSharedInstance('weinMenu');
        return new WeinMenuSnapshotBuilder(db_connect(), WRITEPATH . 'cache/menus');
    }
}
