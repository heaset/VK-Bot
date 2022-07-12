<?php

namespace patt\utils;

class Logger {
	
	public static function info(string $message) : void {
		$date = date('H:i:s');
		echo "\n\e[0;32m$date [INFO] $message\e[0m";
		return;
	}
	
	public static function warn(string $message) : void {
		$date = date('H:i:s');
		echo "\n\e[1;33m$date [WARN] $message\e[0m";
		return;
	}
	
	public static function notice(string $message) : void {
		$date = date('H:i:s');
		echo "\n\e[0;36m$date [NOTICE] $message\e[0m";
		return;
	}
	
	public static function error(string $message) : void {
		$date = date('H:i:s');
		echo "\n\e[0;31m$date [ERROR] $message\e[0m";
		return;
	}

	
}