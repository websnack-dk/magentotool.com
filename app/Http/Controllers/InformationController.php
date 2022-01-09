<?php

namespace App\Http\Controllers;

use App\Http\Models\Directory;
use eftec\bladeone\BladeOne;
use Exception;
use RuntimeException;

/**
 * Class InformationController
 *
 * @package App\Http\Controllers
 */
class InformationController extends BaseController
{
    public function show(): void
    {
        try {

            $vendor_path = $_ENV['VENDOR_MIGRATION_PATH'];
            $versions    = Directory::getVersions();

            usort($versions, static function($a, $b) {
                return $b <=> $a;
            });

            echo $this->loadBladeTemplate()->setView('information.info')->share([
                    "version"            => "",
                    "migration_versions" => $versions,
                    "vendor_path"        => $vendor_path,
                ])
                ->run();

        } catch (Exception $e) {
            throw new RuntimeException("Something went wrong ". $e->getMessage());
        }
    }

}
