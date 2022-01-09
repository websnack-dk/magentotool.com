<?php

namespace App\Http\Models;

use FilesystemIterator;
use Symfony\Component\Finder\Iterator\RecursiveDirectoryIterator;

/**
 * Class Directory
 *
 * @package App\Models
 */
class Directory
{
    /**
     * Scan migration directories
     *
     * @return RecursiveDirectoryIterator
     */
    protected function sortDirectory(): RecursiveDirectoryIterator
    {
        return new RecursiveDirectoryIterator($_ENV['VENDOR_MIGRATION_PATH'], FilesystemIterator::SKIP_DOTS);
    }


    public function retrieveVersionNumber(string $version): string
    {
        return substr($version, strlen($_ENV['VENDOR_MIGRATION_PATH'])+1,10);
    }

    protected function recursiveDirectory(): array
    {
        return iterator_to_array($this->sortDirectory());
    }

    public function rename(string $version, bool $revert = false): string
    {
        return str_replace((!$revert ? '_' : '.'),(!$revert ? '.' : '_'), $version);
    }

    /**
     * Output magento migration versions
     *
     * @return array
     */
    public static function getVersions(): array
    {
        return array_filter((new self())->recursiveDirectory(), static function($directory) {

            if (! $directory->isDir()) {
                return [];
            }
            return $directory->isDir();

        });
    }
    /**
     * Output versions as an array to validate version
     *
     * @return array
     */
    public static function arrayVersions(): array
    {
        $version = [];
        foreach (array_keys(self::getVersions()) as $listVersion) {
            $version[] .= (new self())->retrieveVersionNumber($listVersion);
        }

        return $version;
    }

    /**
     * Check if current version exist (vendor folder)
     *
     * @param $check_version
     */
    public function versionExist($check_version): void
    {
        if (! in_array($check_version, self::arrayVersions(), true)) {
            redirect('/'); exit();
        }
    }


}
