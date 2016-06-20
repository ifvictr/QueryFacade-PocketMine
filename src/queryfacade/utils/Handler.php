<?php

namespace queryfacade\utils;

use queryfacade\event\server\AddPlayerEvent;
use queryfacade\event\server\AddPluginEvent;
use queryfacade\event\server\ChangeMapEvent;
use queryfacade\event\server\ChangeMaxPlayerCountEvent;
use queryfacade\event\server\ChangePlayerCountEvent;
use queryfacade\event\server\RemovePlayerEvent;
use queryfacade\event\server\RemovePluginEvent;
use queryfacade\network\DummyPlayer;
use queryfacade\network\DummyPlugin;
use queryfacade\QueryFacade;

class Handler{
    /** @var QueryFacade */
    private $plugin;
    /** @var DummyPlugin[] */
    private $plugins = [];
    /** @var DummyPlayer[] */
    private $players = [];
    /** @var int */
    private $playerCount = 0;
    /** @var int */
    private $maxPlayerCount = 0;
    /** @var string */
    private $map = "world";
    /**
     * @param QueryFacade $plugin
     */
    public function __construct(QueryFacade $plugin){
        $this->plugin = $plugin;
    }
    /**
     * @return DummyPlugin[]
     */
    public function getPlugins(){
        return $this->plugins;
    }
    /**
     * @param string[] $plugins
     */
    public function setPlugins(array $plugins){
        foreach($plugins as $plugin){
            $info = explode(";", $plugin);
            $this->addPlugin($info[0], isset($info[1]) ? $info[1] : "1.0.0");
        }
    }
    /**
     * @param string $name
     * @param string $version
     */
    public function addPlugin($name, $version = "1.0.0"){
        $plugin = new DummyPlugin($name, $version);
        $this->plugin->getServer()->getPluginManager()->callEvent(new AddPluginEvent($plugin));
        $this->plugins[strtolower($name)] = $plugin;
    }
    /**
     * @param string $name
     * @return bool
     */
    public function removePlugin($name){
        if(array_key_exists(strtolower($name), $this->plugins)){
            $plugin = $this->plugins[strtolower($name)];
            $this->plugin->getServer()->getPluginManager()->callEvent(new RemovePluginEvent($plugin));
            unset($plugin);
            return true;
        }
        return false;
    }
    /**
     * TODO: Remove this in the future
     * @return string
     */
    public function listPlugins(){
        $names = "";
        foreach($this->getPlugins() as $plugin){
            $names .= $plugin->getDescription()->getFullName().", ";
        }
        return substr($names, 0, -2);
    }
    /**
     * @return DummyPlayer[]
     */
    public function getPlayers(){
        return $this->players;
    }
    /**
     * @param string[] $players
     */
    public function setPlayers(array $players){
        foreach($players as $player){
            $info = explode(";", $player);
            $this->addPlayer($info[0], isset($info[1]) ? $info[1] : "DUMMY", isset($info[2]) ? $info[2] : 19132);
        }
    }
    /**
     * @param string $name
     * @param string $ip
     * @param int $port
     */
    public function addPlayer($name, $ip = "DUMMY", $port = 19132){
        $player = new DummyPlayer($name, $ip, $port);
        $this->plugin->getServer()->getPluginManager()->callEvent(new AddPlayerEvent($player));
        $this->players[strtolower($name)] = $player;
    }
    /**
     * @param string $name
     * @return bool
     */
    public function removePlayer($name){
        if(array_key_exists(strtolower($name), $this->players)){
            $player = $this->players[strtolower($name)];
            $this->plugin->getServer()->getPluginManager()->callEvent(new RemovePlayerEvent($player));
            unset($player);
            return true;
        }
        return false;
    }
    /**
     * TODO: Remove this in the future
     * @return string
     */
    public function listPlayers(){
        $names = "";
        foreach($this->getPlayers() as $player){
            $names .= $player->getName().", ";
        }
        return substr($names, 0, -2);
    }
    /**
     * @return int
     */
    public function getPlayerCount(){
        return $this->playerCount;
    }
    /**
     * @param int $count
     */
    public function setPlayerCount($count){
        $event = new ChangePlayerCountEvent($this->getPlayerCount(), $count);
        $this->plugin->getServer()->getPluginManager()->callEvent($event);
        if(!$event->isCancelled()){
            $this->playerCount = (int) $count;
        }
    }
    /** 
     * @return int
     */
    public function getMaxPlayerCount(){
        return $this->maxPlayerCount;
    }
    /**
     * @param int $count
     */
    public function setMaxPlayerCount($count){
        $event = new ChangeMaxPlayerCountEvent($this->getMaxPlayerCount(), $count);
        $this->plugin->getServer()->getPluginManager()->callEvent($event);
        if(!$event->isCancelled()){
            $this->maxPlayerCount = (int) $count;
        }
    }
    /**
     * @return string
     */
    public function getWorld(){
        return $this->map;
    }
    /**
     * @param string $name
     */
    public function setWorld($name){
        $event = new ChangeMapEvent($this->getWorld(), $name);
        $this->plugin->getServer()->getPluginManager()->callEvent($event);
        if(!$event->isCancelled()){
            $this->map = (string) $name;
        }
    }
}