<?php

namespace misael38\XPSystem\Command;

use misael38\XPSystem\Main;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Server;
use pocketmine\Player;

class XP extends PluginCommand{

    private $plugin;

    public function __construct(Main $plugin)
    {
        parent::__construct("xp", $plugin);
        $this->setAliases(["xpsystem"]);
        $this->setDescription("xp system main command!");
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if($sender instanceof Player) {
   	    if(!isset($args[0]))return $this->plugin->MainExpForm($sender);
   	    
        if(strtolower($args[0]) == "admin"){
   	        if($sender->hasPermission("xp.command.admin") && $sender->hasPermission("xp.all")){
   	            $this->plugin->AdminExpForm($sender);
   	        } else {
   	            $sender->sendMessage("§cYou do not have permission to use this command");
   	        return true;
   	        }
   	        } else {
   	            $sender->sendMessage("§cPlease use this command in-game");
   	        }
        }
    }
}
