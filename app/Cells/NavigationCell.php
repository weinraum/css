<?php
namespace App\Cells;

use CodeIgniter\View\Cells\Cell;
use CodeIgniter\Exceptions\LogicException;

final class NavigationCell extends Cell
{
    public string $part = 'top';          // 'left' | 'top'
    public bool   $showWeinMenu = true;
    public bool   $showLexikonBox = false;
    public string $weinMenuTitle = 'Wein';
    public string $lexikonBoxTitle = 'Lexikon';

    public function render(): string
    {
        // Primär unter app/Views/content/cells/, Fallback app/Views/cells/
        $fileLeft = [
            APPPATH . 'Views/content/cells/nav_left.php',
            APPPATH . 'Views/cells/nav_left.php',
        ];
        $fileTop = [
            APPPATH . 'Views/content/cells/nav_top.php',
            APPPATH . 'Views/cells/nav_top.php',
        ];

        $candidates = $this->part === 'left' ? $fileLeft : $fileTop;

        $file = null;
        foreach ($candidates as $p) {
            if (is_file($p)) { $file = $p; break; }
        }
        if (!$file) {
            throw new LogicException(
                "NavigationCell: View-Datei nicht gefunden. Versucht:\n- " . implode("\n- ", $candidates)
            );
        }

        // Variablen für die View bereitstellen
        $showWeinMenu    = $this->showWeinMenu;
        $showLexikonBox  = $this->showLexikonBox;
        $weinMenuTitle   = $this->weinMenuTitle;
        $lexikonBoxTitle = $this->lexikonBoxTitle;

        // View “roh” einbinden (ohne Locator). Helper-Aufrufe in der View bleiben möglich.
        ob_start();
        include $file;
        return (string) ob_get_clean();
    }
}
