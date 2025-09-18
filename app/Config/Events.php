<?php

namespace Config;

use CodeIgniter\Events\Events;
use CodeIgniter\Exceptions\FrameworkException;
use CodeIgniter\HotReloader\HotReloader;

/*
 * --------------------------------------------------------------------
 * Application Events
 * --------------------------------------------------------------------
 * Events allow you to tap into the execution of the program without
 * modifying or extending core files. This file provides a central
 * location to define your events, though they can always be added
 * at run-time, also, if needed.
 *
 * You create code that can execute by subscribing to events with
 * the 'on()' method. This accepts any form of callable, including
 * Closures, that will be executed when the event is triggered.
 *
 * Example:
 *      Events::on('create', [$myInstance, 'myMethod']);
 */

Events::on('pre_system', static function (): void {
    if (ENVIRONMENT !== 'testing') {
        if (ini_get('zlib.output_compression')) {
            throw FrameworkException::forEnabledZlibOutputCompression();
        }

        while (ob_get_level() > 0) {
            ob_end_flush();
        }

        ob_start(static fn ($buffer) => $buffer);
    }

    /*
     * --------------------------------------------------------------------
     * Debug Toolbar Listeners.
     * --------------------------------------------------------------------
     * If you delete, they will no longer be collected.
     */
    if (CI_DEBUG && ! is_cli()) {
        Events::on('DBQuery', 'CodeIgniter\Debug\Toolbar\Collectors\Database::collect');
        service('toolbar')->respond();
        // Hot Reload route - for framework use on the hot reloader.
        if (ENVIRONMENT === 'development') {
            service('routes')->get('__hot-reload', static function (): void {
                (new HotReloader())->run();
            });
        }
    }
});

Events::on('wine:changed', static function (int $productId) {
    // Shop-Daten geändert → nur Wein-bezogene Menüs
    service('menuService')->invalidateSection('wein', 'de');
    service('menuService')->invalidateSection('wein_winzer', 'de'); // falls im Menü mitgeführt
});

Events::on('grapes:changed', static function (int $productId) {
    service('menuService')->invalidateSection('wein', 'de');
});

Events::on('producer:changed', static function (int $producerId) {
    service('menuService')->invalidateSection('winzer', 'de');
    service('menuService')->invalidateSection('wein_winzer', 'de');
});

Events::on('content:menuChanged', static function (?int $pol1Id = null, ?int $pol2Id = null, ?int $pol3Id = null) {
    // Content-Struktur (Lexikon/Regionen) geändert
    service('menuService')->invalidateSection('lexikon', 'de');
    service('menuService')->invalidateSection('regionen', 'de');
    service('menuService')->invalidateSection('regionen_content', 'de');
});
