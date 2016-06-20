<?php

namespace queryfacade\network;

use pocketmine\network\protocol\DataPacket;
use pocketmine\network\SourceInterface;
use pocketmine\Player;

class DummyInterface implements SourceInterface{
    /**
     * @param Player $player
     * @param DataPacket $packet
     * @param bool $needACK
     * @param bool $immediate
     * @return int
     */
    public function putPacket(Player $player, DataPacket $packet, $needACK = false, $immediate = true){
        return 0;
    }
    /**
     * @param Player $player
     * @param string $reason
     */
    public function close(Player $player, $reason = "unknown reason"){
    }
    /**
     * @param string $name
     */
    public function setName($name){
    }
    public function process(){
    }
    public function shutdown(){
    }
    public function emergencyShutdown(){
    }
}