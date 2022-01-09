<?php

namespace App\Http\Models;
use Dotenv\Dotenv;
use PDO;

/**
 * Class Database
 *
 * @package App\Models
 */
class Database implements Interfaces\DatabaseInterface
{
    private string $host;
    private string $user;
    private string $pass;
    private string $db;

    /**
     * Database constructor.
     *
     * @param string $hostname
     * @param string $username
     * @param string $password
     * @param string $database
     */
    public function __construct(string $hostname = "", string $username = "", string $password = "", string $database ="")
    {

        $dotenv = Dotenv::createImmutable(__DIR__ . "./../../../");
        $dotenv->load();

        $this->host  = $_ENV['APP_HOST']     ?? $hostname;
        $this->user  = $_ENV['APP_USERNAME'] ?? $username;
        $this->pass  = $_ENV['APP_PASSWORD'] ?? $password;
        $this->db    = $_ENV['APP_DATABASE'] ?? $database;
    }

    /**
     * Open database connection
     *
     * @return PDO
     */
    public function connect(): PDO
    {
        try {
            $db = new PDO('mysql:host='. $this->host .';dbname='. $this->db, $this->user, $this->pass);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

            return $db;

        } catch (\PDOException $exception) {
            throw new \PDOException("Connection failed. ". $exception->getMessage(). ' - Error code:' . $exception->getCode());
        }
    }

}
