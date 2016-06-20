<?php

namespace queryfacade;

use pocketmine\plugin\PluginBase;
use queryfacade\command\QueryFacadeCommand;
use queryfacade\event\QueryFacadeListener;
use queryfacade\task\UpdateDataTask;
use queryfacade\utils\Handler;

class QueryFacade extends PluginBase{
    const PLUGINS = "plugins";
    const PLAYERS = "playerList";
    const COUNT = "playerCount";
    const MAX_COUNT = "maxPlayerCount";
    const MAP = "level";
    /** @var Handler */
    private $handler;
    public function onEnable(){
        $this->saveDefaultConfig();
        $this->handler = new Handler($this);
    	$this->getServer()->getCommandMap()->register("queryfacade", new QueryFacadeCommand($this));
    	$this->getServer()->getPluginManager()->registerEvents(new QueryFacadeListener($this), $this);
        if($this->getServer()->getConfigBoolean("enable-query", true)){
            if($this->isApplicable(self::PLUGINS) and is_array($plugins = $this->getConfig()->get("plugins"))){
                $this->getHandler()->setPlugins($plugins);
            }
            if($this->isApplicable(self::PLAYERS) and is_array($players = $this->getConfig()->get("playerList"))){
                $this->getHandler()->setPlayers($players);
            }
            if($this->isApplicable(self::COUNT)){
                $this->getHandler()->setPlayerCount($this->getConfig()->get("playerCount"));
            }
            if($this->isApplicable(self::MAX_COUNT)){
                $this->getHandler()->setMaxPlayerCount($this->getConfig()->get("maxPlayerCount"));
            }
            if($this->isApplicable(self::MAP)){
                $this->getHandler()->setWorld($this->getConfig()->get("level"));
            }
            if($this->getConfig()->get("combine") and is_array($this->getConfig()->get("servers"))){
                $this->getServer()->getScheduler()->scheduleRepeatingTask(new UpdateDataTask($this), 2400);
            }
        }
        else{
            $this->getServer()->getLogger()->alert("Query is not enabled for this server. You need to enable it for this plugin to work as intended.");
            $this->getPluginLoader()->disablePlugin($this);
        }
    }
    /**
     * @return Handler
     */
    public function getHandler(){
        return $this->handler;
    }
    /**
     * @param string $name
     * @return bool
     */
    public function isApplicable($name){
        return $this->getConfig()->getNested("apply.$name") === true;
    }
}
