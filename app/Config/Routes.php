<?php

namespace Config;

use Config\Services;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// Home
$routes->get('/', 'Home::index');

// -------------------------
// Cron (gehärtet + CLI)
// -------------------------
$routes->get('cron_content', 'Cron_content::index');            // warm + sitemap
$routes->get('cron_content/warm', 'Cron_content::warm');        // nur Cache vorwärmen
$routes->get('cron_content/sitemap', 'Cron_content::sitemap');  // nur Sitemap schreiben

$routes->cli('cron:content', 'Cron_content::index');
$routes->cli('cron:content:warm', 'Cron_content::warm');
$routes->cli('cron:content:sitemap', 'Cron_content::sitemap');

// -------------------------
// Admin / Backend (gehärtet)
// -------------------------
// statt Admin_category::$1 => zentraler Dispatcher action($action)
$routes->match(['get','post'], 'admin_category', 'Admin_category::index');
$routes->match(['get','post'], 'admin_category/(:segment)', 'Admin_category::action/$1');

$routes->match(['get','post'], 'admin_themengarten', 'Admin_themengarten::index');
$routes->match(['get','post'], 'admin_themengarten/(:segment)', 'Admin_themengarten::action/$1');

$routes->group('admin/menu', static function($r) {
    $r->get('invalidate','Admin\MenuAdmin::invalidateAll');
    $r->get('warmup','Admin\MenuAdmin::warmupAll');
    $r->post('reindex','Admin\MenuAdmin::reindexAll');
});
$routes->group('admin/schema', static function($r){
    $r->get('wr-content','Admin\SchemaAdmin::wrContent');      // dry-run
    $r->post('wr-content','Admin\SchemaAdmin::wrContent');     // apply=1 für Ausführung
});

// (optional) Admin Cache-Endpoints – falls AdminCache-Controller genutzt wird
$routes->group('admin/cache', static function($routes) {
    $routes->post('invalidate-all', 'AdminCache::invalidateAll');
    $routes->post('refresh/wein', 'AdminCache::refreshWeinHead');
    $routes->post('refresh/regionen', 'AdminCache::refreshRegionen');
    $routes->post('refresh/rebsorten', 'AdminCache::refreshRebsorten');
});

$routes->group('admin', ['namespace' => 'App\Controllers\Admin'], static function($routes) {
    $routes->get('content', 'ContentAdmin::index');
    $routes->get('content/create', 'ContentAdmin::create');
    $routes->get('content/edit/(:num)', 'ContentAdmin::edit/$1');

    $routes->post('content/save', 'ContentAdmin::save');
    $routes->post('content/delete/(:num)', 'ContentAdmin::delete/$1');

    // optionaler AJAX-Upload
    $routes->post('content/upload/(:num)', 'ContentAdmin::upload/$1');
});
// -------------------------
// Medien/Tools
// -------------------------
$routes->get('Headimg/(:segment)', 'Headimg::serve/$1');

// -------------------------
// Lexikon / Magazin (Slug-Handler)
// -------------------------
$routes->get('lexikon', 'Lexikon::index');
$routes->get('lexikon/(:segment)', 'Lexikon::page/$1');

$routes->get('magazin', 'Magazin::index');
$routes->get('magazin/(:segment)', 'Magazin::page/$1');

// -------------------------
// Auth
// -------------------------
$routes->get('logout', 'Logout::index');

// -------------------------
// Rebsorten
// -------------------------
$routes->get('rebsorten/(:segment)', 'Rebsorten::page/$1');

// -------------------------
// Rechnung / Test
// -------------------------
$routes->get('rechnung/(:segment)', 'Rechnung::page/$1');

$routes->get('test/(:segment)', 'Test::page/$1');
$routes->match(['get','post'], 'test_content/(:segment)', 'Test_content::action/$1');

// -------------------------
// Sale
// -------------------------
$routes->get('sale', 'Sale::index');

// -------------------------
// Wein (Slug-/POST-Handler)
// -------------------------
$routes->get('wein', 'Wein::index');
$routes->get('wein/(:segment)', 'Wein::page/$1');       // z.B. Produkt/Listen-Slug
$routes->post('wein', 'Wein::index');                   // Form-Submit auf Listing
$routes->post('wein/(:segment)', 'Wein::handle/$1');    // Form-Submit pro Seite/Slug

// -------------------------
// Weinraum
// -------------------------
$routes->get('weinraum/(:segment)', 'Weinraum::page/$1');
$routes->post('weinraum/(:segment)', 'Weinraum::handle/$1');

// -------------------------
// Winzer
// -------------------------
$routes->get('winzer', 'Winzer::index');
$routes->get('winzer/(:segment)', 'Winzer::show/$1');   // Detail über Slug

// -------------------------
// Regionen (tiefe 1–3 explizit, plus Spezial-Startseite)
// -------------------------
$routes->get('regionen', 'Regionen::frankreich_italien_deutschland');
$routes->get('regionen/(:segment)', 'Regionen::show/$1');
$routes->get('regionen/(:segment)/(:segment)', 'Regionen::show/$1/$2');
$routes->get('regionen/(:segment)/(:segment)/(:segment)', 'Regionen::show/$1/$2/$3');

// -------------------------
// Wein passend zu …
$routes->get('wein_passend_zu', 'Wein_passend_zu::salat_gemuese_fleisch_fisch');
$routes->get('wein_passend_zu/(:segment)', 'Wein_passend_zu::page/$1');

// -------------------------
// Weinliste / Weintypen
// -------------------------
$routes->get('weinliste/(:segment)', 'Weinliste::page/$1');

$routes->get('weintypen', 'Weintypen::index');
$routes->get('weintypen/(:segment)', 'Weintypen::page/$1');

// -------------------------
// AJAX (gehärtet)
// -------------------------
// statt Ajax::$1 => zentraler Dispatcher action($action)
$routes->match(['get','post'], 'ajax/(:segment)', 'Ajax::action/$1');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
