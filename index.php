<?php

/**
 * ------------------------------------------------------------------
 * Cradle
 * ------------------------------------------------------------------
 *
 * Cradle is a lightweight MVC framework for building web apps with PHP.
 *
 * It is made with the aim to help php developers avoid working with spaghetti code
 * and embrace the MVC software architecture in as little time as possible. It is totally free to use and open source.
 *
 * @package Cradle
 * @version 1.0
 * @author Mohammed Adekunle (Iyiola) <adekunle3317@gmail.com>
 */

define('BASE_DIR', __DIR__); // Define the base directory

require_once BASE_DIR . '/vendor/autoload.php'; // Include the composer autoloader

Dotenv\Dotenv::createImmutable(BASE_DIR)->load(); // Load environment values from the .env file

require_once BASE_DIR . '/public/index.php'; // Bootstrap and run cradle
