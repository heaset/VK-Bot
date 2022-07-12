<?php

namespace patt\utils;

class Utils {

	public static function createResponse(?string $url, ?array $params) : ?array {
		$url = $url . "?" . http_build_query($params);
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		$response = curl_exec($curl);
		curl_close($curl);
		return json_decode($response, true);
	}

	public static function createRequest(string $method, array $params) : string|array {
		$params["access_token"] = TOKEN;
		$params["v"] = 5.95;
		$res = self::createResponse("https://api.vk.com/method/$method", $params);
		return (isset($res["response"]) ? $res["response"] : $res["error"]);
	}
	
	public static function sendChatNotification(array $object, string $message) : void{
		$params = [
			'owner_id' => $object["owner_id"],
			'random_id' => mt_rand(1, 32),
			'chat_id' => 2, //тут айди чата, в который бот должен отправить сообщение (смотреть айди чата нужно от имени группы, а не от пользователя)
			'message' => $message,
			'attachment' => 'wall-'.abs($object["from_id"]).'_'.$object["id"]
		];
		var_dump(self::createRequest('messages.send', $params));
	}
}