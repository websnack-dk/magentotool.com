<?php

namespace App\Http\Controllers;

use App\Http\Models\Directory;
use App\Http\Models\XMLFile;
use App\Http\Traits\Helpers;
use Exception;
use RuntimeException;

/**
 * Class ContentDataController
 *
 * @package App\controllers
 */
class ContentDataController extends BaseController
{

    /**
     * Show details for specific version
     */
    public function show(): void
    {
        try {

            $version = ($_GET['from_version'] ?? "");

            // does version exist in vendor?
            (new Directory())->versionExist($version);

            $file          = new XMLFile();
            $source       = $file->mapSourceOrDestination($version, true)[0]['ignore'];
            $destination  = $file->mapSourceOrDestination($version, false)[0]['ignore'];

            $listVersions = Directory::getVersions();
            usort($listVersions, static function($a, $b) {
                return $b <=> $a;
            });

            echo $this->loadBladeTemplate()->setView('list_configs')->share([
                    "chosen_version"             => $version,
                    "migration_versions"         => $listVersions,
                    "list_map_source"            => $file->array_flatten($source),
                    "list_map_destination"       => $file->array_flatten($destination),
                    "list_config_steps"           => $file->stepSettings($version),
                ])
                ->run();

        } catch (Exception $e) {
            throw new RuntimeException("Something went wrong ". $e->getMessage());
        }
    }


    /**
     * Create a custom input checkbox field
     * Return bladeHTML via JSON
     *
     * @throws \JsonException
     * @throws \Exception
     */
    public function new_input_field()
    {
        $data       = file_get_contents('php://input');
        $decodeData = json_decode($data, true, 512, JSON_THROW_ON_ERROR);
        $postName   = $decodeData['name'];

        // Early return if name is empty
        if (empty(trim($postName))) {
            http_response_code(422);
            return json_encode(['success' => false], JSON_THROW_ON_ERROR);
        }

        $fieldName   = Helpers::strReplace($postName);
        $returnHTML = $this->loadBladeTemplate()->setView('axiosResponse.checkbox')
            ->share([
                'key'           => uniqid('', false),
                'field'          => $fieldName,
                'fieldNameInput' => $fieldName,
            ])->run();

        http_response_code(200);
        return json_encode([
                'success' => true,
                'inputData' => $returnHTML
            ],
            JSON_THROW_ON_ERROR
        );
    }

    /**
     * Save base config into session. (Database source/destination etc.)
     */
    public function save_configuration(): void
    {
        try {

            $requestJson = file_get_contents('php://input');
            $jsonData = json_decode($requestJson, true, 512, JSON_THROW_ON_ERROR);

            if(!isset($_SESSION)) {
                session_start();
            }

            $_SESSION['config']['module-name']           = $jsonData['module-name'];
            $_SESSION['config']['encryption-key']        = $jsonData['encryption-key'];

            # Database destination
            $_SESSION['config']['destination-host']      = $jsonData['destination-host'];
            $_SESSION['config']['destination-name']      = $jsonData['destination-name'];
            $_SESSION['config']['destination-user']      = $jsonData['destination-user'];
            $_SESSION['config']['destination-password']  = $jsonData['destination-password'];

            # Database source
            $_SESSION['config']['source-host']           = $jsonData['source-host'];
            $_SESSION['config']['source-name']           = $jsonData['source-name'];
            $_SESSION['config']['source-user']           = $jsonData['source-user'];
            $_SESSION['config']['source-password']       = $jsonData['source-password'];

        } catch (Exception $e) {
            throw new RuntimeException("Something went wrong ". $e->getMessage());
        }

    }

}
