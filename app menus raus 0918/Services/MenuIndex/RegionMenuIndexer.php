<?php namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Services\MenuIndex\RegionMenuIndexer;
use App\Services\MenuIndex\WinzerMenuIndexer;
use App\Services\MenuIndex\WeinTreeBuilder;

class MenuAdmin extends Controller
{
    public function bootstrapWein(): string
    {
        // Einmalige Erstbefüllung aus der Trad-DB (Status quo), dann sofort Tree bauen.
        $content = db_connect();                 // default
        $trad    = db_connect('trad');           // zweite DB-Gruppe (konfiguriert)

        $region = new RegionMenuIndexer($trad, $content);
        $winzer = new WinzerMenuIndexer($trad, $content);
        $tree   = new WeinTreeBuilder($content);

        $region->rebuild();      // schreibt wr_menu_region_index
        $winzer->rebuild();      // schreibt wr_menu_winzer_index
        $tree->rebuild('de');    // erzeugt Snapshot in wr_menu_tree (section='wein')

        return 'Bootstrap OK: Regionen+Winzer indiziert, Wein-Menü gebaut.';
    }

    public function rebuildWein(): string
    {
        // Spätere Aktualisierung NUR aus den Content-Indices (kein Trad-Zugriff mehr)
        $content = db_connect();
        $tree = new WeinTreeBuilder($content);
        $tree->rebuild('de');
        return 'Wein-Menü aktualisiert.';
    }
}
