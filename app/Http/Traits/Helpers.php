<?php

namespace App\Http\Traits;

/**
 * Trait Helpers
 *
 * @package App\Http\Traits
 */
trait Helpers
{
    public static function strReplace($data, bool $revert = false, bool $strToLower = true): string
    {
        $search  = " ";
        $replace = "_";
        $dataString = strtolower($data);

        if ($revert) {
            $search  = "_";
            $replace = " ";
        }

        if (! $strToLower) {
            $dataString = $data;
        }

        return str_replace($search, $replace, $dataString);
    }

    /**
     * Flatten arrays
     *
     * @param array $array
     * @param array $return
     *
     * @return array
     */
    public function array_flatten(array $array, array $return = []): array
    {
        foreach ($array as $value) {
            if(is_array($value)) {
                $return = self::array_flatten($value, $return);
            } elseif (isset($value)) {
                $return[] = $value;
            }
        }
        return array_unique($return, SORT_REGULAR);
    }

    /**
     * Validate data input from XML
     *
     * @param array $xmlSource
     *
     * @return bool
     */
    protected function isEmpty(array $xmlSource): bool
    {
        if (! $xmlSource) {
            return false;
        }
        return true;
    }


    /**
     * Convert XML file to output array
     *
     * @param object $xml
     *
     * @return array
     */
    protected function XMLToArray(object $xml): array
    {
        $array = [];
        foreach ($xml->children() as $k => $value) {

            $child = self::XMLToArray($value);

            if (count($child) === 0) {
                $child = (string) $value;
            }

            foreach ($value->attributes() as $ak => $av) {
                if (!is_array($child)) {
                    $child = ["value" => $child];
                }
                $child[$ak] = (string) $av;
            }

            if (!array_key_exists($k, $array)) {
                $array[$k] = $child;
            } else {
                if (is_string($array[$k]) || !isset($array[$k][0])) {
                    $array[$k] = [$array[$k]];
                }
                $array[$k][] = $child;
            }

        }
        return $array;
    }


}
