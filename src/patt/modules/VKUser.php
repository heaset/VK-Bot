<?php

namespace patt\modules;

class VKUser {
	
	public $id = null;
	public $peer_id = null;
	public $last_message = null;
	
	public $bot = null;
	
	public $firstname = "";
	public $lastname = "";
	public $fullname = "";
	
	public $regdate = "";
	
	public function __construct(VKBot $bot, int $id, ?int $peer_id = null, ?string $last_message = null) {
		$this->id = $id;
		$this->peer_id = $peer_id;
		$this->last_message = $last_message;
		$this->bot = $bot;
		$this->initUser();
	}
	
	public function initUser() : void{
		$parser = xml_parser_create();
		xml_parse_into_struct($parser, file_get_contents("https://vk.com/foaf.php?id=".$this->id), $out);
		xml_parser_free($parser);
		$this->firstname = $out[10]["value"];
		$this->lastname = $out[12]["value"];
		$this->fullname = $out[14]["value"];
		$this->regdate = $out[20]["attributes"]["DC:DATE"];
	}
	
	public function sendMessage($text) : void{
		if($this->peer_id === null){
			$this->getBot()->sendMessage($text, $this->id);
			return;
		}
		$this->getBot()->sendMessage($text, $this->peer_id);
	}
	
	public function getID() : ?int {
		return $this->id;
	}
	
	public function getBot() : VKBot {
		return $this->bot;
	}
	
	public function getFullName() : string {
		return $this->fullname;
	}
	
	public function getRegDate() : string {
		return $this->regdate;
	}
	
	public function getFirstName() : string {
		return $this->firstname;
	}
	
	public function getLastName() : string {
		return $this->lastname;
	}
	
	public function setPeerID(int $peer_id) : void {
		$this->peer_id = $peer_id;
	}
	
	public function getPeerID() : ?int {
		return $this->peer_id;
	}
	
	public function setLastMessage(string $msg) : void {
		$this->last_message = $msg;
	}
	
	public function getLastMessage() : string {
		return $this->last_message;
	}
	
}