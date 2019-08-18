<?php
chdir(dirname(__DIR__));

use Framework\Http\Request;

require 'vendor/autoload.php';

$request = (new Request())
    ->withQueryParams($_GET)
    ->withQueryParams($_POST);

$name = $request->getQueryParams()['name'] ?? 'Guest';
header('X-Developer: Volodin V.');

echo 'Hello ' . $name . '!';