<?php

namespace App;

use mysqli;

class mysqlSetup
{
    public function mysqlConnect(string $servername, string $username, string $password): mysqli
    {
        $conn = mysqli_connect($servername, $username, $password);
        if (!$conn) die("Connection failed: " . mysqli_connect_error());

        return $conn;
    }

    public function createDatabase(mysqli $connection, string $dbName):void
    {
        $sql = "CREATE DATABASE $dbName";
        if ($connection->query($sql) !== TRUE) die("Error creating database: " . $connection->error);

        echo "Database created successfully" . PHP_EOL;
    }

    public function seedDatabase($lines, mysqli $connection):void
    {
        $temp_line = '';
        foreach ($lines as $line) {
            $temp_line .= $line;
            if (substr(trim($line), -1, 1) == ';') {
                mysqli_query($connection, $temp_line) or print("Error in " . $temp_line . ":" . mysqli_error($connection));
                $temp_line = '';
            }
        }

        echo "Tables imported successfully" . PHP_EOL;
    }
}