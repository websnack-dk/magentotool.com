<?php

namespace App\Http\Models\Interfaces;

/**
 * Interface MapInterface
 *
 * @package App\Http\Models\Interfaces
 */
interface MapInterface
{
    /**
     * List all source/destination fields to ignore for a specific version
     *
     * @param string $version
     * @param bool   $readFromSource
     *
     * @return array
     */
    public function mapSourceOrDestination(string $version, bool $readFromSource): array;
}
