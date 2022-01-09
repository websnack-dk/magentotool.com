<?php

namespace App\Http\Controllers;

use App\Http\Models\Database;
use App\Http\Models\XMLFile;
use eftec\bladeone\BladeOne;
use PDO;

class BaseController extends Database
{

    /**
     * Return template engine BladeOne
     *
     * @param string $views
     * @param string $compile
     * @return BladeOne
     */
    public function loadBladeTemplate(string $views = "app/views", string $compile = "app/compiles"): BladeOne
    {
        $blade = new BladeOne($views, $compile, BladeOne::MODE_DEBUG);
        $blade->csrfIsValid(true);  // new token per request.

        return $blade;
    }

    /**
     * Return version name
     *
     * @param string $version
     *
     * @return string
     */
    public static function version(string $version): string
    {
        return (new XMLFile())->rename($version);
    }

    /**
     * Debug, Dump and Die
     *
     * @param null $value
     * @param bool $die
     */
    public static function dd($value = null, bool $die = true): void
    {
        print '<pre>';
            if (is_array($value)) {
                print '<b>Array count: '.count($value).'</b><hr>';
                print_r($value);
            } else {
                var_dump($value);
            }
        print '</pre>';

        if ($die) {
            die;
        }
    }


}
