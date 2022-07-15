<?php

namespace patt\utils;

class Rcon{

  private $host;
  private $port;
  private $password;
  private $timeout;

  private $socket;

  private $authorized = false;
  private $lastResponse = '';

  private const PACKET_AUTHORIZE = 5;
  private const PACKET_COMMAND = 6;

  private const SERVERDATA_AUTH = 3;
  private const SERVERDATA_AUTH_RESPONSE = 2;
  private const SERVERDATA_EXECCOMMAND = 2;
  private const SERVERDATA_RESPONSE_VALUE = 0;

  public function __construct(string $host, int $port, string $password, int $timeout){
      $this->host = $host;
      $this->port = $port;
      $this->password = $password;
      $this->timeout = $timeout;
    }

    public function getResponse() : string{
      return $this->lastResponse;
    }

    public function connect() : bool{
      $this->socket = fsockopen($this->host, $this->port, $errno, $errstr, $this->timeout);
      if(!$this->socket){
        $this->lastResponse = $errstr;
        return false;
      }
      stream_set_timeout($this->socket, 3, 0);
      return $this->authorize();
    }

    public function disconnect() : void{
      if($this->socket){
        fclose($this->socket);
      }
    }

    public function isConnected() : bool{
      return $this->authorized;
    }

    public function sendCommand(string $command) : mixed{
      if(!$this->isConnected()){
        return false;
      }
      $this->writePacket(self::PACKET_COMMAND, self::SERVERDATA_EXECCOMMAND, $command);
      $response_packet = $this->readPacket();
      if($response_packet['id'] === self::PACKET_COMMAND){
        if($response_packet['type'] === self::SERVERDATA_RESPONSE_VALUE) {
          $this->lastResponse = $response_packet['body'];
          return $response_packet['body'];
        }
      }
      return false;
    }

    public function authorize() : bool{
      $this->writePacket(self::PACKET_AUTHORIZE, self::SERVERDATA_AUTH, $this->password);
      $response_packet = $this->readPacket();
      if($response_packet['type'] === self::SERVERDATA_AUTH_RESPONSE){
        if($response_packet['id'] === self::PACKET_AUTHORIZE){
          $this->authorized = true;
          return true;
        }
      }
      $this->disconnect();
      return false;
    }

    private function writePacket($packetId, $packetType, string $packetBody) : void{
      $packet = pack('VV', $packetId, $packetType);
      $packet = $packet.$packetBody."\x00";
      $packet = $packet."\x00";
      $packet_size = strlen($packet);
      $packet = pack('V', $packet_size).$packet;
      fwrite($this->socket, $packet, strlen($packet));
    }

    private function readPacket() : array{
      $size_data = fread($this->socket, 4);
      $size_pack = unpack('V1size', $size_data);
      $size = $size_pack['size'];
      $packet_data = fread($this->socket, $size);
      $packet_pack = unpack('V1id/V1type/a*body', $packet_data);
      return $packet_pack;
    }
}
