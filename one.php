<?php

require_once 'vendor/autoload.php'; 
$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();

Tinify\setKey(getenv("API_KEY"));
$path = realpath(dirname(__FILE__) . getenv('FILE_PATH'));

if (empty($argv[1])) {
	print("Please specify file");
	exit();
}

$to_optimize = $path . "/" . $argv[1];
if (!is_file($to_optimize)) {
	print("not a file");
	exit();
}

$path_info =  pathinfo($to_optimize);
if ($path_info['extension'] != 'jpg' &&
	$path_info['extension'] != 'png') {
	print("not valid path");
	exit();
}

try {
	Tinify\fromFile($to_optimize)->toFile($to_optimize);
} catch (Exception $e) {
	exit();
}
