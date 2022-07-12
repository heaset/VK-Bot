<?php

namespace patt;

use patt\plugin\Plugin;
use patt\utils\Logger;
use patt\modules\VKUser;
use patt\command\Command;
use patt\utils\Config;
use patt\utils\Utils;

class Main extends Plugin{
	
	public function onEnable() : void{
	}
	
	public function onMessage(VKUser $user, Command $command, string $message) : bool{
		$cmd = $command->getName();
		$args = $command->getArgs();
		return true;
	}
	
	public function onDislike(VKUser $user, int $object_id, string $type, array $object) : bool {
		$user->sendMessage("Зачем ты убрал лайк?");
		return true;
	}
	
	public function onJoinGroup(VKUser $user, string $type, array $object) : bool{
		$user->sendMessage("Привествуем тебя в нашей группе!");
		return true;
	}
	
	public function onLeaveGroup(VKUser $user, array $object) : bool{
		$user->sendMessage("Ну и пошел нахер :)");
		return true;
	}
	
	public function onDeleteComment(VKUser $user, array $object) : bool{
		return true;
	}
	
	public function onComment(VKUser $user, array $object) : bool{
		return true;
	}
	
	public function onLike(VKUser $user, int $object_id, string $type, array $object) : bool {
		$user->sendMessage("Спасибо за лайк!");
		return true;
	}
	
	public function onNewPost(VKUser $user, array $object) : bool{
		$user->sendMessage("Ты создал пост");
		Utils::sendChatNotification($object, "Новый пост в группе.");
		return true;
	}
}