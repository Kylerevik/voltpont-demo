<?php

declare(strict_types=1);

function connect_database(): PDO
{
    $config = require __DIR__ . '/config.php';
    $dsn = sprintf(
        'mysql:host=%s;dbname=%s;charset=%s',
        $config['host'],
        $config['name'],
        $config['charset']
    );

    return new PDO($dsn, $config['user'], $config['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        // Valódi prepared statement, hogy a paraméterek sose kerüljenek az SQL szövegébe.
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
}
