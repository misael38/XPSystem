<?php

namespace misael38\XPSystem\Event;

use pocketmine\event\Listener;
use misael38\XPSystem\Main;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\Player;

class PlayerMove implements Listener{

    private $plugin;

    public function __construct(Main $plugin){
    $this->plugin = $plugin;
    }
    
    public function PlayerMove  (PlayerMoveEvent $event){
	$player = $event->getPlayer();
	$name = $player->getName();		
		
    $exp = $this->plugin->exp->getAll();
	$my = $player->getXpLevel();
	$exp = $this->plugin->exp->set($name, $my);
    }
}
