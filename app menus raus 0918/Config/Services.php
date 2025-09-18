<?php

namespace Config;

use CodeIgniter\Config\BaseService;
use App\Repositories\DbContentRepository;
use App\Services\ContentService;
use App\Services\HomeTeaserService;
use App\Services\ImagePipelineService;

/**
 * Services Configuration file.
 *
 * Services are simply other classes/libraries that the system uses
 * to do its job. This is used by CodeIgniter to allow the core of the
 * framework to be swapped out easily without affecting the usage within
 * the rest of your application.
 *
 * This file holds any application-specific services, or service overrides
 * that you might need. An example has been included with the general
 * method format you should use for your service methods. For more examples,
 * see the core Services file at system/Config/Services.php.
 */
class Services extends BaseService
{
public static function menuRenderer(bool $getShared = true): \App\Services\MenuRenderer
{
    if ($getShared) {
        return static::getSharedInstance('menuRenderer');
    }
    return new \App\Services\MenuRenderer();
}


public static function imagePipeline(bool $getShared = true): ImagePipeline
{
if ($getShared) return static::getSharedInstance('imagePipeline');
return new ImagePipeline();
}

public static function contentRepo(bool $getShared = true)
    {
        if ($getShared) return static::getSharedInstance('contentRepo');
        return new DbContentRepository(db_connect()); // nutzt "trad" oder fallback "default"
    }

    public static function contentService(bool $getShared = true): ContentService
    {
        if ($getShared) return static::getSharedInstance('contentService');

        // WICHTIG: Inhalte kommen aus 'trad'
        $dbTrad = db_connect();              // <- deine Live-Inhalts-DB
        return new ContentService($dbTrad, cache());
    }
   public static function homeTeaserService(bool $getShared = true): HomeTeaserService
    {
        if ($getShared) return static::getSharedInstance('homeTeaserService');
        return new HomeTeaserService(db_connect());        // default = trad
    }

public static function imagePipelineService(bool $getShared = true): ImagePipelineService
{
    if ($getShared) return static::getSharedInstance('imagePipelineService');
    return new ImagePipelineService(\Config\Database::connect('default'), cache());
}

public static function weinMenu(bool $getShared = true): \App\Services\WeinMenuSnapshotBuilder
{
    if ($getShared) return static::getSharedInstance('weinMenu');
    return new \App\Services\WeinMenuSnapshotBuilder(db_connect(), WRITEPATH . 'cache/menus');
}
}
