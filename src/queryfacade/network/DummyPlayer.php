<?php

namespace queryfacade\network;

use pocketmine\Player;
use pocketmine\Server;

//TODO: Make it less hacky, so there isn't a chance it'll break other plugins
class DummyPlayer extends Player{
    /** @var string */
    protected $username;
    /** @var string */
    protected $ip;
    /** @var int */
    protected $port;
    /** @var Server */
    protected $server;
    /** @var DummyInterface */
    protected $interface;
    /**
     * @param string $username
     * @param string $ip
     * @param int $port
     */
    public function __construct($username, $ip = "DUMMY", $port = 19132){
        $this->username = (string) $username;
        $this->ip = $ip;
        $this->port = (int) $port;
        $this->server = Server::getInstance();
        $this->interface = new DummyInterface();
    }
}