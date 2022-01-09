<?php

namespace App\Controllers\generator;

use App\Controllers\PaymentCheckoutController;
use App\Http\Models\Database;
use App\Http\Models\XMLFile;
use App\Http\Traits\Helpers;
use Dotenv\Dotenv;
use Exception;
use RecursiveIteratorIterator;
use RuntimeException;
use Symfony\Component\Finder\Iterator\RecursiveDirectoryIterator;
use ZipArchive;
use Carbon\Carbon;

/**
 * Class ExportData
 *
 * @package App\Controllers\generator
 */
class ExportData extends XMLFile
{

    /**
     * Validate data before generating
     *
     * @throws \JsonException
     */
    protected function validateBefore(): bool
    {
        $moduleName      = $_SESSION['config']['module-name'] ?? "";
        $cryptKey        = $_SESSION['config']['encryption-key'] ?? "";

        $destinationHost = $_SESSION['config']['destination-host'] ?? "";
        $destinationName = $_SESSION['config']['destination-name'] ?? "";
        $destinationUser = $_SESSION['config']['destination-user'] ?? "";
        $destinationPass = $_SESSION['config']['destination-password'] ?? "";

        $sourceHost      = $_SESSION['config']['source-host'] ?? "";
        $sourceName      = $_SESSION['config']['source-name'] ?? "";
        $sourceUser      = $_SESSION['config']['source-user'] ?? "";
        $sourcePass      = $_SESSION['config']['source-password'] ?? "";

        // Return if missing data from session
        if (empty($moduleName)
            || empty($cryptKey)
            || empty($destinationHost)
            || empty($destinationName)
            || empty($destinationUser)
            || empty($destinationPass)
            || empty($sourceHost)
            || empty($sourceName)
            || empty($sourceUser)
            || empty($sourcePass)) {

            echo json_encode(["data" => "Missing migration config. Please fill all inputs."], JSON_THROW_ON_ERROR | http_response_code(422));
            exit();
        }

        return true;

    }

    /**
     * Retrieve all checkbox data
     *
     * @return array
     */
    protected function retrieveData(): array
    {
        try {
            $requestJson = file_get_contents('php://input');
            $jsonData = json_decode($requestJson, true, 512, JSON_THROW_ON_ERROR);

            $output = [];
            foreach ($jsonData as $key => $value) {

                $ignore_field = explode('--', $key);

                /**
                 * Ignore db table fields (map.xml)
                 */
                if ($ignore_field[0] === 'source_ignore') {
                    $output['source_ignore'][] = $ignore_field[1];
                }
                if ($ignore_field[0] === 'destination_ignore') {
                    $output['destination_ignore'][] = $ignore_field[1];
                }

                /**
                 * Steps (config.xml)
                 */
                if ($ignore_field[0] === 'settings_step') {
                    $output['settings_step'][] = $ignore_field[1];
                }
                if ($ignore_field[0] === 'settings_data') {
                    $output['settings_data'][] = $ignore_field[1];
                }

                /**
                 * Get chosen version
                 */
                if ($ignore_field[0] === 'version') {
                    $output['version'] = $value;
                }
            }

            return $output;

        } catch (Exception $e) {
            throw new RuntimeException("Something went wrong ". $e->getMessage());
        }
    }

    /**
     * Copy data files from vendor to temp folder
     *
     * @param        $source
     * @param string $dest
     *
     * @return bool
     */
    protected static function copyVendorDirectoryToTemp($source, string $dest): bool
    {
        $sourceHandle = opendir($source);

        if (!$sourceHandle) {
            echo 'failed to copy directory: failed to open source ' . $source;
            return false;
        }

        while ($file = readdir($sourceHandle)) {

            if ($file === '.' || $file === '..') {
                continue;
            }

            if (is_dir($source . '/' . $file)) {
                if (!file_exists($dest . '/' . $file) && !mkdir(
                        $concurrentDirectory = $dest . '/' . $file,
                        0755
                    ) && !is_dir($concurrentDirectory)) {
                        throw new \RuntimeException(
                            sprintf('Directory "%s" was not created', $concurrentDirectory)
                        );
                    }
                self::copyVendorDirectoryToTemp($source . '/' . $file, $dest . '/' . $file);
            } else {
                copy($source . '/' . $file, $dest . '/' . $file);
            }
        }

        return true;
    }

    /**
     * Rename XML file from .dist to .xml
     *
     * @param string $temp_map_file
     *
     * @return bool
     */
    protected function renameXMLFile(string $temp_map_file): bool
    {
        return rename($temp_map_file, substr($temp_map_file, 0, -5));
    }


    /**
     * Create XML elements (Source/Destination ignore)
     *
     * @param string $mapFile
     * @param string $element
     * @param string $retrieveDataFrom
     *
     * @return void
     */
    protected function createMapXMLNodeElement(string $mapFile, string $element = "source", string $retrieveDataFrom = "source_ignore"): void
    {
        $domLoad = new \DOMDocument('1.0','UTF-8');
        $domLoad->preserveWhiteSpace = false;
        $domLoad->formatOutput       = true;

        $domLoad->load($mapFile);
        $xpath  = new \DOMXpath($domLoad);

        // Remove ignore tags from source
        foreach ($xpath->query('//map/'.$element.'/document_rules/ignore') as $data) {
            $data->parentNode->removeChild($data);
        }

        // Add data from user
        $retrieveData   = $this->retrieveData()[$retrieveDataFrom] ?? null;
        $document_rules = $xpath->query('//map/'.$element.'/document_rules')->item(0);

        // Create ignore elements
        if ($retrieveData !== null) {

            krsort($retrieveData); // sort by descending order

            foreach ($retrieveData as $data) {
                $create_ignore_node   = $domLoad->createElement('ignore');
                $create_document_node = $domLoad->createElement('document', $data);
                $create_ignore_node->appendChild($create_document_node);

                if ($document_rules !== null) {
                    $document_rules->insertBefore($create_ignore_node, $document_rules->firstChild);
                }
            }
        }

        $domLoad->save($mapFile);
    }

    /**
     * Modify Setting steps (Data/Settings) in config.xml
     *
     * @param string $configFile
     * @param string $retrieveName
     * @param string $stepMode
     */
    protected function configSteps(string $configFile, string $retrieveName, string $stepMode): void
    {
        $domLoad = new \DOMDocument('1.0','UTF-8');
        $domLoad->preserveWhiteSpace = false;
        $domLoad->formatOutput       = true;

        $domLoad->load($configFile);
        $xpath  = new \DOMXpath($domLoad);

        $retrieveData  = $this->retrieveData()[$retrieveName] ?? null;
        $title_attrs   = $xpath->query('//steps[@mode="'. $stepMode .'"]/step[@title]');

        foreach ($title_attrs as $node) {

            if ($retrieveData !== null) {

                foreach ($retrieveData as $data) {
                    $dataName = Helpers::strReplace($data, true, false);

                    // Compare step=title attribute with checked checkbox
                    if ($dataName === $node->attributes->item(0)->nodeValue) {
                        $node->parentNode->removeChild($node);
                    }
                }

            }
        }

        $domLoad->save($configFile);
    }

    /**
     * Override base info from config.xml
     
     * @param $configFile
     * @param $version
     * @param $moduleName
     */
    protected function overrideConfigOptions($configFile, $version, $moduleName): void
    {
        $domLoad = new \DOMDocument('1.0','UTF-8');
        $domLoad->preserveWhiteSpace = false;
        $domLoad->formatOutput       = true;

        $domLoad->load($configFile);
        $xpath  = new \DOMXpath($domLoad);

        // Override files
        $overrideData = $xpath->query('//options/map_file');
        $mapFileName  = substr($overrideData->item(0)->nodeValue, 0, -5);

        // set crypt key
        $domLoad->getElementsByTagName('crypt_key')->item(0)->nodeValue = $_SESSION['config']['encryption-key'];

        // Get rid of extension .dist
        $domLoad->getElementsByTagName("map_file")->item(0)->nodeValue  = 'app/code/'. $moduleName .'/Migration/'. $mapFileName;

        $domLoad->save($configFile);

    }

    /**
     * Change Database information in Config.xml
     *
     * @param string $configFile
     * @param string $database
     * @param array  $sessionConfig
     */
    protected function setDatabaseInfo(string $configFile, string $database, array $sessionConfig = []): void
    {
        $domLoad = new \DOMDocument('1.0','UTF-8');
        $domLoad->preserveWhiteSpace = false;
        $domLoad->formatOutput       = true;

        $domLoad->load($configFile);
        $xpath  = new \DOMXpath($domLoad);

        $sourceDatabase = $xpath->query('//'.$database.'/database');
        foreach ($sourceDatabase as $sourceNode) {

            // retrieve standard db info from xml file
            $databaseHost = $sourceNode->attributes->item(0)->nodeValue;
            $databaseName = $sourceNode->attributes->item(1)->nodeValue;
            $databaseUser = $sourceNode->attributes->item(2)->nodeValue;

            // replace with user data
            $sourceNode->setAttribute('host', $sessionConfig[$database.'-host']);
            $sourceNode->setAttribute('name', $sessionConfig[$database.'-name']);
            $sourceNode->setAttribute('user', $sessionConfig[$database.'-user']);
            $sourceNode->setAttribute('password', $sessionConfig[$database.'-password']);

        }

        $domLoad->save($configFile);
    }


    /**
     * Generate module and zip or send it.
     *
     * @throws \JsonException
     */
    public function generateModule(): void
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . "./../../../../");
        $dotenv->load();

        // Check if sessions a created before going further
        $this->validateBefore();

        $data = $this->retrieveData();

        // Retrieve base information
        $moduleName     = ucfirst($_SESSION['config']['module-name']);

        $version        = $data['version'];
        $hash           = sha1(5).'_'. time().'_';
        $temp_folder    = 'app/public/migrations/_temp/'. $hash. Helpers::strReplace($moduleName) .'_'.$version;
        $mapXMLFile     = XMLFile::MAP_FILE;
        $mapXMLConfig   = XMLFile::CONFIG_FILE;
        $vendorPath     = $_ENV['VENDOR_MIGRATION_PATH'];

         // Create tmp folder
        if (! is_dir('app/public/migrations/download')) {
            if (!mkdir($concurrentDirectory = 'app/public/migrations/download', 0755) && !is_dir($concurrentDirectory)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
            }
        }

        if (!mkdir($concurrentDirectory = $temp_folder, 0755, true) && !is_dir($concurrentDirectory)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
        }


        // copy folder from vendor
        $copy_from_vendor_path     = $vendorPath.'/'. $version;
        $copy_to_temp_destination  = $temp_folder;
        $temp_map_file             = $temp_folder.'/'. $mapXMLFile;
        $temp_config_file          = $temp_folder.'/'. $mapXMLConfig;

        self::copyVendorDirectoryToTemp($copy_from_vendor_path, $copy_to_temp_destination);

        // Remove .dist extension in filename
        $this->renameXMLFile($temp_map_file);
        $mapFile = $temp_folder.'/'. substr($mapXMLFile, 0, -5);

        // Create XML files for map.xml
        $this->createMapXMLNodeElement($mapFile);
        $this->createMapXMLNodeElement($mapFile, "destination", "destination_ignore");

        // Modify Config.xml
        $this->renameXMLFile($temp_config_file);
        $configFile = $temp_folder.'/'. substr($mapXMLConfig, 0, -5);

        $this->configSteps($configFile, "settings_step", "settings");
        $this->configSteps($configFile, "settings_data", "data");

        $this->overrideConfigOptions($configFile, $version, $moduleName);

        // Modify Database information etc.
        $this->setDatabaseInfo($configFile, 'source', $_SESSION['config']);
        $this->setDatabaseInfo($configFile, 'destination', $_SESSION['config']);


        // Create folder to zip
        if (!mkdir($vendorDirectory = $temp_folder.'/'. $moduleName .'/Migration/etc/opensource-to-opensource/'. $version, 0755, true) && !is_dir($vendorDirectory)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $vendorDirectory));
        }

        // Registration.php
        $registrationFile = fopen($temp_folder."/".$moduleName."/Migration/registration.php", 'wb') or die("can't open file");
fwrite($registrationFile, "<?php
\Magento\Framework\Component\ComponentRegistrar::register(
    \Magento\Framework\Component\ComponentRegistrar::MODULE,
    '".$moduleName."_Migration',
    __DIR__
);
");
        fclose($registrationFile);

        // Module.xml
        $moduleXML = fopen($temp_folder."/".$moduleName."/Migration/etc/module.xml", 'wb') or die("can't open file");
fwrite($moduleXML, '<?xml version="1.0"?>
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:Module/etc/module.xsd">
    <module name="'.$moduleName.'_Migration" setup_version="1.0.0">
        <sequence>
            <module name="Magento_DataMigrationTool"/>
        </sequence>
    </module>
</config>
');
        fclose($moduleXML);

        // Composer.json
        $data = '{
    "name": "vendor/migration",
    "description": "Providing config for migration between v.'. $version.' to v'. $_ENV['COMPOSER_MIGRATION_VERSION'] .'",
    "config": {
        "sort-packages": true
    },
    "require": {
        "magento/framework": "*",
        "magento/data-migration-tool": "*"
    },
    "require-dev": {
        "roave/security-advisories": "dev-latest"
    },
    "type": "magento2-module",
    "autoload": {
        "files": [
            "registration.php"
        ],
        "psr-4": {
            "Vendor\\\Migration\\\": ""
        }
    },
    "version": "1.0.0"
}
';
        $jsonComposer = fopen($temp_folder."/".$moduleName."/Migration/composer.json", 'wb') or die("can't open file");
fwrite($jsonComposer, $data);
        fclose($jsonComposer);

        // Move Config/Map files into module folder
        copy($configFile, $temp_folder."/".$moduleName."/Migration/etc/opensource-to-opensource/". $version.'/config.xml');
        copy($mapFile, $temp_folder."/".$moduleName."/Migration/etc/opensource-to-opensource/". $version.'/map.xml');

        unlink($mapFile);
        unlink($configFile);

        $zipName = 'module_'.time().md5(8).'_'.$moduleName.'.zip';
        $this->zipModule($temp_folder.'/'.$moduleName, 'app/public/migrations/download/'.$zipName, $moduleName);
        $this->removeTmpDirectory($temp_folder);

        # Save to database
        # $db = (new Database())->connect();

        # $data = [
        #     'm1_version'                    => $version,
        #     'm2_version'                    => $_ENV['COMPOSER_MIGRATION_VERSION'],
        #     'module_name'                   => ucfirst($moduleName),
        #     'ignore_map_destination_fields'  => json_encode($this->retrieveData()['destination_ignore'],JSON_THROW_ON_ERROR),
        #     'ignore_map_source_fields'       => json_encode($this->retrieveData()['source_ignore'], JSON_THROW_ON_ERROR),
        #     'created_at'                    => Carbon::now(),
        #     'download_link'                 => $zipName
        # ];

        # $sql = "INSERT INTO
        #             generated_xml_setups
        #                 (m1_version, m2_version, module_name, ignore_map_destination_fields, ignore_map_source_fields, created_at, download_link)
        #         VALUES
        #                (:m1_version, :m2_version, :module_name, :ignore_map_destination_fields, :ignore_map_source_fields, :created_at, :download_link)
        #    ";
        # $db->prepare($sql)->execute($data);

        # $_SESSION['id'] = $db->lastInsertId();

        http_response_code(200);
        echo json_encode([
                'status'     => true,
                'downloadZip' => '/download?module='.$zipName
            ],
            JSON_THROW_ON_ERROR
        );

    }

    /**
     * Zip file
     * https://stackoverflow.com/questions/1334613/how-to-recursively-zip-a-directory-in-php
     *
     * @param string $source
     * @param string $destination
     * @param string $moduleName
     *
     * @return string|null
     */
    public function zipModule(string $source, string $destination, string $moduleName = ""): ?string
    {
        if (!extension_loaded('zip') || !file_exists($source)) {
            return false;
        }

        $zip = new ZipArchive();
        if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
            return false;
        }

        $source = str_replace('\\', '/', realpath($source));

        if (is_dir($source) === true) {

            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::SELF_FIRST
            );

            foreach ($files as $file) {

                $file = str_replace('\\', '/', $file);

                // Ignore "." and ".." folders
                if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) ) {
                    continue;
                }

                $file = realpath($file);

                if (is_dir($file) === true) {
                    $zip->addEmptyDir($moduleName.'/'.str_replace($source . '/', '', $file . '/'));
                } elseif (is_file($file) === true) {
                    $zip->addFromString($moduleName.'/'.str_replace($source . '/', '', $file), file_get_contents($file));
                }
            }
        } elseif (is_file($source) === true) {
            $zip->addFromString($moduleName.'/'.basename($source), file_get_contents($source));
        }

        return $zip->close();
    }

    /**
     * Clear tmp folder after module zip generation
     *
     * @param $path
     */
    protected function removeTmpDirectory($path): void
    {
        $files = glob($path . '/*');
        foreach ($files as $file) {
            is_dir($file) ? $this->removeTmpDirectory($file) : unlink($file);
        }
        rmdir($path);
    }

}
