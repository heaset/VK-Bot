<?php

namespace patt\command;

use patt\modules\{
	VKUser,
	VKBot
};

class Command {
	
	public $msg = null;
	public $user = null;
	
	public $args = [];
	public $command = null;
	public $obj = null;
	public $lowermsg = null;
	
	public function __construct(VKUser $user, string $message, array $obj) {
		$this->msg = $message;
		$this->user = $user;
		$this->obj = $obj;
		$this->makeCommand();
	}
	
	public function makeCommand() : void {
		$this->lowermsg = mb_strtolower($this->msg, "UTF-8");
		$args = explode(" ", $this->msg);
		unset($args[0]);
		$this->args = explode(" ", implode(" ", $args));
		$this->command = explode(" ", $this->lowermsg)[0];
	}
	
	public function getUser() : VKUser {
		return $this->user;
	}
	
	public function getObject() : array {
		return $this->obj;
	}
	
	public function getArgs() : array {
		return $this->args;
	}
	
	public function getName() : string {
		return $this->command;
	}
	
	public function getBot() : VKBot {
		return $this->user->getBot();
	}
	
	public function asFullString() : string {
		return $this->msg;
	}
	
	public function asFullLowerString() : string {
		return $this->lowermsg;
	}
}