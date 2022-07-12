<?php

namespace patt\plugin;

use patt\modules\{
	VKBot,
	VKUser
};
use patt\command\Command;

abstract class Plugin{

	protected $bot;

	public function __construct(VKBot $bot){
		$this->bot = $bot;
	}
	
	public function getBot() : VKBot {
		return $this->bot;
	}

	abstract public function onEnable() : void;

	abstract public function onMessage(VKUser $user, Command $cmd, string $message) : bool;
	
	abstract public function onLike(VKUser $user, int $object_id, string $type, array $object) : bool;
	
	abstract public function onDislike(VKUser $user, int $object_id, string $type, array $object) : bool;
	
	abstract public function onComment(VKUser $user, array $object) : bool;

	abstract public function onDeleteComment(VKUser $user, array $object) : bool;

	abstract public function onLeaveGroup(VKUser $user, array $object) : bool;
	
	abstract public function onJoinGroup(VKUser $user, string $type, array $object) : bool;
	
	abstract public function onNewPost(VKUser $user, array $object) : bool;
}
