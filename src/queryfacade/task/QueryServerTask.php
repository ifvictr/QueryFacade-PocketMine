<?php

namespace queryfacade\task;

use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use queryfacade\QueryFacade;
use xPaw\MinecraftQuery;
use xPaw\MinecraftQueryException;

class QueryServerTask extends AsyncTask{
    /** @var string[] */
    private $targets;
    /** @var int */
    private $timeout;
    /** @var array */
    private $result = [];
    /**
     * @param array $targets
     * @param int $timeout
     */
    public function __construct($targets, $timeout = 3){
        $this->targets = $targets;
        $this->timeout = (int) $timeout;
    }
    public function onRun(){
        $result = [];
        $query = new MinecraftQuery();
        foreach($this->targets as $target){
            $address = explode(":", $target);
            try{
                $query->Connect($address[0], isset($address[1]) ? $address[1] : 19132);
                $result[] = ["info" => $query->GetInfo()/*, "players" => $query->GetPlayers()*/];
            }
            catch(MinecraftQueryException $exception){
                $result[] = $exception->getMessage();
            }
        }
        $this->result = $result;
    }
    /**
     * @param Server $server
     */
    public function onCompletion(Server $server){
        //var_dump($this->result);
        $numPlayers = count($server->getOnlinePlayers());
        $maxPlayers = $server->getMaxPlayers();
        foreach($this->result as $result){
            if(is_array($result)){
                $numPlayers += $result["info"]["numplayers"];
                $maxPlayers += $result["info"]["maxplayers"];
            }
            else{
                $server->getLogger()->critical($result);
            }
        }
        if(($plugin = $server->getPluginManager()->getPlugin("QueryFacade")) instanceof QueryFacade and $plugin->isEnabled()){
            $plugin->getHandler()->setPlayerCount($numPlayers);
            $plugin->getHandler()->setMaxPlayerCount($maxPlayers);
        }
    }
}