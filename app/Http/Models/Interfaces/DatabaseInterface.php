<?php

namespace App\Http\Models\Interfaces;

use PDO;

/**
 * Interface DatabaseInterface
 *
 * @package App\Http\Models\Interfaces
 */
interface DatabaseInterface
{
    /**
     * @param string $hostname
     * @param string $username
     * @param string $password
     * @param string $database
     */
    public function __construct(string $hostname = "", string $username = "", string $password = "", string $database = "");

    /**
     * @return PDO
     */
    public function connect(): PDO;
}
