<?php

$envPath = __DIR__ .'/../.env';
if (!file_exists($envPath)){
    die('Missing .env file. Copy .env.example to .env and fill in your settings.');
}
$env=parse_ini_file($envPath);
return[
    'db_host' => $env['DB_HOST'],
    'db_name' => $env['DB_NAME'],
    'db_user' => $env['DB_USER'],
    'db_pass' => $env['DB_PASS'],
];
