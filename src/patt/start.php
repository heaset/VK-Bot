<?php
	
	start();
	
	function makeClassLoader() : void {
		require_once(BOT_PATH . "src/patt/modules/VKBot.php");
		require_once("utils/Config.php");
		require_once("utils/Utils.php");
		require_once("plugin/Plugin.php");
		require_once("utils/Logger.php");
		require_once("modules/VKUser.php");
		require_once("command/Command.php");
	}
	
	function prepare() : void {
		define('BOT_PATH', realpath(getcwd()) . DIRECTORY_SEPARATOR);
		define('START_TIME', time());
		return;
	}
	
	function start() : void {
		prepare();
		makeClassLoader();
		new \patt\modules\VKBot();
	}