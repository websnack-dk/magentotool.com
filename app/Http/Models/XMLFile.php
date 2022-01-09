<?php

namespace App\Http\Models;

use App\Http\Models\Interfaces\MapInterface;
use App\Http\Traits\Helpers;
use Exception;
use RuntimeException;

/**
 * Class XMLFile
 *
 * @package App\Http\Models
 */
class XMLFile extends Directory implements MapInterface
{
    use Helpers;

    protected const CONFIG_FILE                     = 'config.xml.dist';
    protected const MAP_FILE                        = 'map.xml.dist';

    // old versions (from version 1.6.0.0 -> 1.6.2.0)
    protected const MAP_CUSTOMER                    = 'map-customer.xml.dist';
    protected const MAP_ATTRIBUTE_CUSTOMER_GROUPS   = 'customer-attribute-groups.xml.dist';

    // @Todo: Includes extra files to manipulate. Fix later.
    protected array $OLD_VERSIONS                   = [
        '1.6.2.0',
        '1.6.1.0',
        '1.6.0.0',
    ];

    // @Todo: Newer versions includes extra files to manipulate. From version 1.9.3.0.
    protected const MAP_TIER_PRICE                  = 'map-tier-price.xml.dist';

    /**
     * Load XML file
     *
     * @param $xmlFile
     * @param $magVersion
     *
     * @return object
     */
    public function load($xmlFile, $magVersion): object
    {
        try {

            $directory = $_ENV['VENDOR_MIGRATION_PATH'] . '/' . $magVersion;
            if (! is_dir($directory)) {
                return new \stdClass();
            }

            $xml = simplexml_load_string(file_get_contents($directory.'/' . $xmlFile));
            return (object)$this->XMLToArray($xml);

        } catch (Exception $e) {
            throw new RuntimeException("Something went wrong". $e->getMessage());
        }

    }

    /**
     * Map.xml file. Check source or destination data
     *
     * @param string $version
     * @param bool   $readFromSource
     *
     * @return array
     */
    public function mapSourceOrDestination(string $version, bool $readFromSource): array
    {
        if (empty($version)) {
            return [];
        }

        $load = $this->load(self::MAP_FILE, $this->rename($version));
        $returnData = $load->destination;

        if ($readFromSource) {
            $returnData = $load->source;
        }

        return $this->readMapDocumentRules($returnData);

    }

    /**
     * Return Source/Destination document rules
     *
     * @param array $xmlSource
     *
     * @return array
     */
    protected function readMapDocumentRules(array $xmlSource): array
    {
        if (! $this->isEmpty((array)$xmlSource)) {
            return [];
        }

        $output = [];
        foreach ($xmlSource as $ignore ) {
            $output[] = $ignore;
        }
        return $output;
    }


    /**
     * Config.xml - Return config steps data
     *
     * @param string $version
     *
     * @return object
     */
    protected function configListSteps(string $version): object
    {
        return $this->load(self::CONFIG_FILE, $version);
    }

    /**
     * Config.xml - Return config steps settings
     *
     * @param $version
     *
     * @return array
     */
    public function stepSettings($version): array
    {
        $returnData = [];
        foreach ($this->configListSteps($version) as $key => $steps) {
            $returnData[] = $steps;
        }

        return $returnData[0];
    }

}
