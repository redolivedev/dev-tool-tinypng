<?php

require_once 'vendor/autoload.php'; 
$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();

Tinify\setKey(getenv("API_KEY"));
$path = realpath(dirname(__FILE__) . getenv('FILE_PATH'));

function compress($path) {

	$scan = scandir($path);
	foreach($scan as $file) {
		if ($file == '..' || $file == '.') continue;

		if (is_dir($path . "/" . $file)) {
			compress($path . "/" . $file);
			continue;
		}
		
		$limit = getenv("SIZE_LIMIT_KB") * 1028;
		$to_optimize = $path . "/" . $file;

		$file_size = filesize($to_optimize);
		if ($file_size > $limit) {

			$path_arg = pathinfo($to_optimize);
			if ($path_arg['extension'] != 'jpg' && 
				$path_arg['extension'] != 'png') continue;

			try {
				Tinify\fromFile($to_optimize)->toFile($to_optimize);
			} catch (Exception $e) {
				continue;
			}
			clearstatcache();
			print("optimmized $to_optimize \n");
			$currentsize = filesize($to_optimize);
			print("current size: " . $currentsize . "\n");
			print("old size: " . $file_size . "\n");
			$saved = ($file_size - $currentsize) / 1024;
			print('saved ' . $saved . "\n");
			print("--------------------------------------");

		} else {
			//print("skipping " . $to_optimize . "\n");
		}
		
	}
}


compress($path);

