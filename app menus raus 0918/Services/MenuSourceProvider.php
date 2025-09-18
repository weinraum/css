<?php
declare(strict_types=1);

namespace App\Services;

use Config\Database;                 // <-- hinzufügen
use CodeIgniter\Database\BaseConnection;
use App\Services\MenuSources\LexikonAlphaSource;
use App\Services\MenuSources\RegionenSource;
use App\Services\MenuSources\WinzerSource;
use App\Services\MenuSources\WeinWinzerSource;

/**
 * Liefert die Rohquelle (JSON) für Menü-Sections.
 * - Statische Sections kommen aus wr_menu_source (JSON im Feld "source")
 * - Dynamische Sections werden aus internen Sources gebaut (Lexikon/Regionen/Winzer)
 *
 * Rückgabe:
 *   JSON-String im Format {"items":[ ... ]}  – oder '' wenn nicht vorhanden.
 */
final class MenuSourceProvider
{
    private $db;

    public function __construct()
    {
        // Default war: $this->db = db_connect();
        $this->db = Database::connect('content'); // <- zweite DB-Connection
    }
    public function get(string $section, string $locale = 'de'): string
    {
        switch ($section) {
            // Dynamisch: Lexikon A–Z -> /lexikon/{identifer}
            case 'lexikon':
                return (new LexikonAlphaSource($this->db))->getJson();

            // Dynamisch: Regionen für Commerce -> /wein/regionen/{identifer}
            case 'regionen':
                return (new RegionenSource($this->db))->getJson(prefix: '/wein/regionen/');

            // Dynamisch: Regionen für Editorial -> /regionen/{identifer}
            case 'regionen_content':
                return (new RegionenSource($this->db))->getJson(prefix: '/regionen/');

            // Dynamisch: Winzer A–Z -> /winzer/{identifer}
            case 'winzer':
                return (new WinzerSource($this->db))->getJson();

            // Dynamisch: Region -> Winzer (für Wein-Kontext), Links bleiben /winzer/{identifer}
            case 'wein_winzer':
                return (new WeinWinzerSource($this->db))->getJson();

            // Statisch: aus wr_menu_source (journal, weinwissen, weinraum, main, admin, account, …)
            default:
                $row = $this->db->table('wr_menu_source')
                    ->select('source')
                    ->where(['section' => $section, 'locale' => $locale])
                    ->get()
                    ->getRowArray();

                $source = (string)($row['source'] ?? '');

                if ($source === '') {
                    // Hinweis ins Log, damit fehlende Sources sichtbar sind – bricht den Flow aber nicht.
                    if (function_exists('log_message')) {
                        log_message(
                            'warning',
                            "MenuSourceProvider: no static source for section='{$section}', locale='{$locale}'"
                        );
                    }
                }

                return $source;
        }
    }
}
