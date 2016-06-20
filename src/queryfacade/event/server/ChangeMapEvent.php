<?php

namespace queryfacade\event\server;

use pocketmine\event\Cancellable;

class ChangeMapEvent extends QueryFacadeEvent implements Cancellable{
    /** @var \pocketmine\event\HandlerList */
    public static $handlerList = null;
    /** @var string */
    private $oldName;
    /** @var string */
    private $newName;
    /**
     * @param string $oldName
     * @param string $newName
     */
    public function __construct($oldName, $newName){
        $this->oldName = (string) $oldName;
        $this->newName = (string) $newName;
    }
    /**
     * @return string
     */
    public function getOldName(){
        return $this->oldName;
    }
    /**
     * @param string $name
     */
    public function setOldName($name){
        $this->oldName = (string) $name;
    }
    /**
     * @return string
     */
    public function getNewName(){
        return $this->newName;
    }
    /**
     * @param string $name
     */
    public function setNewName($name){
        $this->newName = (string) $name;
    }
}