<?php

namespace patt\modules;

use patt\utils\{
	Config, 
	Utils,
	Logger
};
use patt\plugin\Plugin;
use patt\command\Command;

class VKBot {
	
	public $token;
	public $gid;
	public $rectime = 5;
	
	public $plugins = [];
	
	public $ts;
	public $server;
	public $key;
	
	public $users = [];
	
	public function __construct(){
		$conf = new Config(BOT_PATH . "settings.yml", Config::YAML, [
		'group_token' => '',
		'group_id' => 0,
		'reconnect_time' => 30
		]);
		$conf = $conf->getAll();
		$this->token = $conf['group_token'];
		$this->gid = $conf['group_id'];
		$this->rectime = $conf['reconnect_time'];
		
		define('TOKEN', $this->token);
		define('GROUP_ID', $this->gid);
		define('RECTIME', $this->rectime);
		$this->enablePlugins();
		Logger::info("Бот успешно запущен.");
		$this->getLongPolling();
		$this->startLongPolling();
	}
	
	private function startLongPolling() : void{
		while(true){
			foreach($this->updates() as $update){
				switch($update["type"]){
					case "like_add":
						$all = $update['object'];
						$from = $all["liker_id"];
						$type = $all["object_type"];
						$id = $all['object_id'];
						if(!isset($this->users[$from])){ 
							$this->users[$from] = new VKUser($this, $from);
						}
						foreach($this->plugins as $plugin){ 
							$plugin->onLike($this->users[$from], $id, $type, $all);
						}
					break;
					case "like_remove":
						$all = $update['object'];
						$from = intval($all["liker_id"]);
						$type = $all["object_type"];
						$id = intval($all['object_id']);
						if(!isset($this->users[$from])){ 
							$this->users[$from] = new VKUser($this, $from);
						}
						foreach($this->plugins as $plugin){
							$plugin->onDislike($this->users[$from], $id, $type, $all);
						}
					break;
					case "message_new":
						$all = $update['object'];
						$message = $all['text'];
						$peer_id = intval($all['peer_id']);
						$from = intval($all['from_id']);
						if(!isset($this->users[$from])){ 
							$this->users[$from] = new VKUser($this, $from, $peer_id, $message);
						}
						$this->users[$from]->setPeerID($peer_id);
						$this->users[$from]->setLastMessage($message);
						$cmd = new Command($this->users[$from], $message, $all);
						foreach($this->plugins as $plugin){
							$plugin->onMessage($this->users[$from], $cmd, $message);
						}
					break;
					case "group_join":
						$all = $update['object'];
						$from = intval($all["user_id"]);
						$type = $all["join_type"];
						if(!isset($this->users[$from])){
							$this->users[$from] = new VKUser($this, $from);
						}
						foreach($this->plugins as $plugin){
							$plugin->onJoinGroup($this->users[$from], $type, $all);
						}
					break;
					case "group_leave":
						$all = $update['object'];
						$from = intval($all["user_id"]);
						if(!isset($this->users[$from])){
							$this->users[$from] = new VKUser($this, $from);
						}
						foreach($this->plugins as $plugin){
							$plugin->onLeaveGroup($this->users[$from], $all);
						}
					break;
					case "wall_reply_new":
						$all = $update['object'];
						$from = intval($all["from_id"]);
						if(!isset($this->users[$from])){
							$this->users[$from] = new VKUser($this, $from);
						}
						foreach($this->plugins as $plugin){
							$plugin->onComment($this->users[$from], $all);
						}
					break;
					case "wall_reply_delete":
						$all = $update['object'];
						$from = intval($all["from_id"]);
						if(!isset($this->users[$from])){
							$this->users[$from] = new VKUser($this, $from);
						}
						foreach($this->plugins as $plugin){
							$plugin->onDeleteComment($this->users[$from], $all);
						}
					break;
					case "wall_post_new":
						$all = $update['object'];
						$from = intval($all["created_by"]);
						if(!isset($this->users[$from])){
							$this->users[$from] = new VKUser($this, $from);
						}
						foreach($this->plugins as $plugin){
							$plugin->onNewPost($this->users[$from], $all);
						}
					break;
				}
			}
		}
	}
	
	public function enablePlugins() : void{
		foreach(glob("plugins/*") as $file){
			if(is_dir($file) && file_exists($yml_file = $file . "/" . "plugin.yml")){
				$yml = yaml_parse_file($yml_file);
				if(isset($yml["name"]) && isset($yml["main"])){
					Logger::info("Загрузка плагина {$yml['name']}\n");
					if(file_exists($main = dirname($yml_file) . "/src/" . str_replace("\\", "/", $yml["main"]) . ".php")){
						require_once $main;
						if(class_exists($yml["main"])){
							$plugin = new $yml["main"]($this);
							if($plugin instanceof Plugin){
								$this->plugins[] = $plugin;
								$plugin->onEnable();
							}
						}
					}
				}
			}
		}
	}
	
	public function updates() : mixed{
		$params = [
			"act"=> "a_check", "key" => $this->key,
			"ts"=> $this->ts, "mode" => 2, 
			"version"=> 2, "wait" => RECTIME
			];
		$updates = Utils::createResponse($this->server, $params);
		if(isset($updates["failed"])){
			$this->getLongPolling();
			return $this->updates();
		}
		$this->ts = $updates["ts"];
		if(isset($updates["updates"])){
			return $updates["updates"];
		}
	}
	
	public function getLongPolling() {
		$data = Utils::createRequest("groups.getLongPollServer", ["group_id" => $this->gid]);
		if($data === false){
			exit("Ошибка получения данных с запроса.\n");
		}
		$this->server = $data["server"];
		$this->key = $data["key"];
		$this->ts = $data["ts"];
	}
	
	public function sendMessage($message, $peer_id, $attachments = []) { 
		return Utils::createRequest('messages.send', [
			'random_id' => rand(),
			'peer_id' => $peer_id,
			'message' => $message,
			'payload' => 1000,
			'attachment' => implode(',', $attachments) 
		]);
	}
}
