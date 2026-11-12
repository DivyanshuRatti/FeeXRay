<?php

// This file connects PHP to the MySQL database.
// Any page that needs data calls getConnection().

function getConnection()
{
    // Remember the connection so we only connect once per page
    static $connection = null;

    // If we already connected, reuse it
    if ($connection !== null) {
        return $connection;
    }

    // Load the settings (host, database name, user, password)
    $config = require __DIR__ . '/../config/config.php';

    // Where the database is and which one to use
    $host = $config['db_host'];
    $dbName = $config['db_name'];
    $address = "mysql:host=$host;dbname=$dbName;charset=utf8mb4";

    // Safety settings
    $settings = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,  //When something goes wrong, stop and tell me clearly.      // show clear errors
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,  // When you give me a row, label each value with its column name
        PDO::ATTR_EMULATE_PREPARES => false,               // safer queries
    ];

    // Connect
    $connection = new PDO($address, $config['db_user'], $config['db_pass'], $settings);

    return $connection;
}