<?php

namespace queryfacade\task;

use pocketmine\scheduler\PluginTask;
use queryfacade\QueryFacade;

class UpdateDataTask extends PluginTask{
    /** @var QueryFacade */
    private $plugin;
    /**
     * @param QueryFacade $plugin
     */
    public function __construct(QueryFacade $plugin){
        parent::__construct($plugin);
        $this->plugin = $plugin;
    }
    /**
     * @param int $currentTick
     */
    public function onRun($currentTick){
        $this->plugin->getServer()->getScheduler()->scheduleAsyncTask(new QueryServerTask($this->plugin->getConfig()->get("servers"), $this->plugin->getConfig()->get("timeout")));
    }
}