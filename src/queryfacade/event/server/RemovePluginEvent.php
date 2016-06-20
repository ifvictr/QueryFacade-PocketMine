<?php

namespace queryfacade\event\server;

use queryfacade\network\DummyPlugin;

class RemovePluginEvent extends QueryFacadeEvent{
    /** @var \pocketmine\event\HandlerList */
    public static $handlerList = null;
    /** @var DummyPlugin */
    private $plugin;
    /**
     * @param DummyPlugin $plugin
     */
    public function __construct(DummyPlugin $plugin){
        $this->plugin = $plugin;
    }
    /**
     * @return DummyPlugin
     */
    public function getPlugin(){
        return $this->plugin;
    }
}