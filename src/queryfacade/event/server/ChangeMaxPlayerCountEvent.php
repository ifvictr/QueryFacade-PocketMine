<?php

namespace queryfacade\event\server;

use pocketmine\event\Cancellable;

class ChangeMaxPlayerCountEvent extends QueryFacadeEvent implements Cancellable{
    /** @var \pocketmine\event\HandlerList */
    public static $handlerList = null;
    /** @var int */
    private $oldCount;
    /** @var int */
    private $newCount;
    /**
     * @param int $oldCount
     * @param int $newCount
     */
    public function __construct($oldCount, $newCount){
        $this->oldCount = (int) $oldCount;
        $this->newCount = (int) $newCount;
    }
    /**
     * @return int
     */
    public function getOldCount(){
        return $this->oldCount;
    }
    /**
     * @param int $count
     */
    public function setOldCount($count){
        $this->oldCount = (int) $count;
    }
    /**
     * @return int
     */
    public function getNewCount(){
        return $this->newCount;
    }
    /**
     * @param int $count
     */
    public function setNewCount($count){
        $this->newCount = (int) $count;
    }
}