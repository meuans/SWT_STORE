<?php

declare(strict_types=1);

use Dotenv\Dotenv;

// Load environment variables from project root .env
if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->safeLoad();
}

// Provide sane defaults
$_ENV['APP_ENV'] = $_ENV['APP_ENV'] ?? 'local';
$_ENV['APP_DEBUG'] = $_ENV['APP_DEBUG'] ?? 'true';
$_ENV['APP_NAME'] = $_ENV['APP_NAME'] ?? 'Asian Store API';
$_ENV['MEALDB_BASE_URL'] = $_ENV['MEALDB_BASE_URL'] ?? 'https://www.themealdb.com/api/json/v1/1';
$_ENV['DEFAULT_AREA'] = $_ENV['DEFAULT_AREA'] ?? 'Chinese';


