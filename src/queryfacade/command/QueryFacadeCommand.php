<?php
namespace queryfacade\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use queryfacade\QueryFacade;

class QueryFacadeCommand extends Command{
    /** @var QueryFacade */
    private $plugin;
    /**
     * @param QueryFacade $plugin
     */
    public function __construct(QueryFacade $plugin){
        parent::__construct("queryfacade", "Shows all QueryFacade commands", null, ["qf"]);
        $this->setPermission("queryfacade.command.queryfacade");
        $this->plugin = $plugin;
    }
    /** 
     * @param CommandSender $sender 
     */
    private function sendCommandHelp(CommandSender $sender){
        $commands = [
            "addplayer" => "Adds a player to the player list",
            "addplugin" => "Adds a plugin to the plugin list",
            "help" => "Shows all QueryFacade commands",
            "map" => "Changes the server's current default map name",
            "maxplayercount" => "Changes the server's max player count",
            "playercount" => "Changes the server's player count",
            "players" => "Returns a list of players being sent in query",
            "plugins" => "Returns a list of plugins being sent in query",
            "removeplayer" => "Removes the specified player from the player list",
            "removeplugin" => "Removes the specified plugin from the plugin list"
        ];
        $sender->sendMessage("QueryFacade commands:");
        foreach($commands as $name => $description){
            $sender->sendMessage("/queryfacade $name: $description");
        }
    }
    /**
     * @param CommandSender $sender
     * @param string $label
     * @param string[] $args
     * @return bool
     */
    public function execute(CommandSender $sender, $label, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if(isset($args[0])){
            $handler = $this->plugin->getHandler();
            switch(strtolower($args[0])){
                case "apr":
                case "addplayer":
                    if(isset($args[1])){
                        $handler->addPlayer($args[1], isset($args[2]) ? $args[2] : "DUMMY", isset($args[3]) ? $args[3] : 19132);
                        $sender->sendMessage(TextFormat::GREEN."Added $args[1] to the player list.");
                    }
                    else{
                        $sender->sendMessage(TextFormat::RED."Failed to add player, no name specified.");
                    }
                    break;
                case "apn":
                case "addplugin":
                    if(isset($args[1])){
                        $version = isset($args[2]) ? $args[2] : "1.0.0";
                        $handler->addPlugin($args[1], $version);
                        $sender->sendMessage(TextFormat::GREEN."Added $args[1] v$version to the plugin list.");
                    }
                    else{
                        $sender->sendMessage(TextFormat::RED."Failed to add plugin, no name/version specified.");
                    }
                    break;
                case "help":
                    $this->sendCommandHelp($sender);
                    break;
                case "map":
                    if(isset($args[1])){
                        $handler->setWorld($args[1]);
                        $sender->sendMessage(TextFormat::GREEN."Set map name to $args[1].");
                    }
                    else{
                        $sender->sendMessage(TextFormat::YELLOW."Current map name is ".$handler->getWorld().".");
                    }
                    break;
                case "mpc":
                case "maxplayercount":
                    if(isset($args[1])){
                        if(is_numeric($args[1])){
                            $handler->setMaxPlayerCount($args[1]);
                            $sender->sendMessage(TextFormat::GREEN."Set max player count to $args[1].");
                        }
                        else{
                            $sender->sendMessage(TextFormat::RED."The specified amount is not an integer.");
                        }
                    }
                    else{
                        $sender->sendMessage(TextFormat::YELLOW."Current max player count is ".$handler->getMaxPlayerCount().".");
                    }
                    break;
                case "pc":
                case "playercount":
                    if(isset($args[1])){
                        if(is_numeric($args[1])){
                            $handler->setPlayerCount($args[1]);
                            $sender->sendMessage(TextFormat::GREEN."Set player count to $args[1].");
                        }
                        else{
                            $sender->sendMessage(TextFormat::RED."The specified amount is not an integer.");
                        }
                    }
                    else{
                        $sender->sendMessage(TextFormat::YELLOW."Current player count is ".$handler->getPlayerCount().".");
                    }
                    break;
                case "pr":
                case "players":
                    $sender->sendMessage(TextFormat::YELLOW."There are currently ".count($handler->getPlayers())." player(s)".(count($handler->getPlayers()) > 0 ? ": ".$handler->listPlayers() : "").".");
                    break;
                case "pn":
                case "plugins":
                    $sender->sendMessage(TextFormat::YELLOW."There are currently ".count($handler->getPlugins())." plugin(s)".(count($handler->getPlugins()) > 0 ? ": ".$handler->listPlugins() : "").".");
                    break;
                case "rpr":
                case "removeplayer":
                    if(isset($args[1])){
                        if($handler->removePlayer($args[1])){
                            $sender->sendMessage(TextFormat::GREEN."Removed $args[1] from the player list.");
                        }
                        else{
                            $sender->sendMessage(TextFormat::RED."That player couldn't be found.");
                        }
                    }
                    else{
                        $sender->sendMessage(TextFormat::RED."Please specify a player.");
                    }
                    break;
                case "rpn":
                case "removeplugin":
                    if(isset($args[1])){
                        if($handler->removePlugin($args[1])){
                            $sender->sendMessage(TextFormat::GREEN."Removed $args[1] from the plugin list.");
                        }
                        else{
                            $sender->sendMessage(TextFormat::RED."That plugin couldn't be found.");
                        }
                    }
                    else{
                        $sender->sendMessage(TextFormat::RED."Please specify a plugin.");
                    }
                    break;
                default:
                    $sender->sendMessage("Usage: /queryfacade <sub-command> [parameters]");
                    return false;
            }
        }
        else{
            $this->sendCommandHelp($sender);
            return false;
        }
        return true;
    }
}
