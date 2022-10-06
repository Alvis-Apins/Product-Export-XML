<?php

namespace App;

class MysqlSetup
{
    public function mysqlConnect(string $servername, string $username, string $password): \mysqli
    {
        $conn = mysqli_connect($servername, $username, $password);
        if (!$conn) die("Connection failed: " . mysqli_connect_error());

        return $conn;
    }

    public function createDatabase(\mysqli $connection, string $dbName)
    {
        $sql = "CREATE DATABASE $dbName";
        if ($connection->query($sql) !== TRUE) die("Error creating database: " . $connection->error);

        echo "Database created successfully" . PHP_EOL;
    }

    public function seedDatabase($lines, \mysqli $connection)
    {
        $tempLine = '';
        foreach ($lines as $line) {
            $tempLine .= $line;
            if (substr(trim($line), -1, 1) == ';') {
                mysqli_query($connection, $tempLine) or print("Error in " . $tempLine . ":" . mysqli_error());
                $tempLine = '';
            }
        }

        echo "Tables imported successfully" . PHP_EOL;
    }
}