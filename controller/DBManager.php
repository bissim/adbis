<?php

namespace controller;

class DBManager
{
    private $serverName;
    private $username;
    private $password;
    private $dbName;
    private $port;
    private $pdo;

    public function __construct()
    {
        // retrieve configurations from file
        $configs = parse_ini_file('../db/dbconfig.ini', true);

        // set DB connection parameters
        $this->serverName = $configs['connection']['server'];
        $this->username = $configs['connection']['username'];
        $this->password = $configs['connection']['password'];
        $this->dbName = $configs['connection']['database'];
        $this->port = $configs['connection']['port'];
    }

    // Connessione al database
    public function isConnected(): bool
    {
        return $this->pdo ? true : false;
    }

    /**
     * @throws \PDOException
     */
    public function connect(): void
    {

        $pdoOptions = array(
            \PDO::ATTR_EMULATE_PREPARES   => false, // turn off emulation mode for "real" prepared statements
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION, //turn on errors in the form of exceptions
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC, //make the default fetch be an associative array
            \PDO::ATTR_PERSISTENT => true // make connections reusable
        );

        $dsn = "mysql:host={$this->serverName};dbname={$this->dbName}";
        $user = $this->username;
        $pwd = $this->password;
        echo "User {$user} is trying to connect to {$this->serverName}...";
        try {
            $this->pdo = new \PDO(
                $dsn,
                $user,
                $pwd,
                $pdoOptions
            );
        }
        catch (\PDOException $pdoe)
        {
            error_log("A database error occurred: {$pdoe->getMessage()}.");
        }
        catch (\Exception $e)
        {
            error_log("An error occurred: {$e->getMessage()}.");
        }
    }

    public function disconnect(): void
    {
        $this->pdo = null;
    }

    public function execute(string $instruction, array $params): void
    {
        // execute instruction
        $stmt = $this->pdo->prepare($instruction);
        $stmt->execute($params);

        // dismiss statement
        $stmt = null;
    }

    public function query(string $instruction, array $params): array
    {
        // execute query
        $stmt = $this->pdo->prepare($instruction);
        $stmt->execute($params);

        // retrieve results
        $results = $stmt->fetchAll();

        // dismiss statement
        $stmt = null;

        return $results;
    }

    public function __toString()
    {
        return $this->pdo ? "Connected to {$this->username}@{$this->serverName}." : "Not connected.";
    }
}
