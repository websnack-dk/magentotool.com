<?php

namespace App\Http\Controllers;

use App\Http\Models\Directory;
use Exception;
use RuntimeException;

class HomeController extends BaseController
{
    public function home(): void
    {
        try {
            $vendor_path = $_ENV['VENDOR_MIGRATION_PATH'];
            $versions    = Directory::getVersions();

            usort($versions, static function($a, $b) {
                return $b <=> $a;
            });

            echo $this->loadBladeTemplate()->setView('main')->share([
                     "version"            => "",
                     "migration_versions" => $versions,
                     "vendor_path"        => $vendor_path,
                 ])
                ->run();

        } catch (Exception $e) {
            throw new RuntimeException("Something went wrong ". $e->getMessage());
        }
    }


    public function download(): ?string
    {
        $downloadDir = 'app/public/migrations/download/';
        $getName     = $_GET['module'];

        header('Content-disposition: attachment; filename='.$getName.'');
        header('Content-type: application/zip');

        readfile($downloadDir.$getName);
        exit();
    }

}
