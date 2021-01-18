<?php

namespace misael38\XPSystem;

use pocketmine\Server;
use pocketmine\Player;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use pocketmine\plugin\PluginBase;

use pocketmine\utils\Config;

use onebone\economyapi\EconomyAPI;

use libs\FormAPI\SimpleForm;
use libs\FormAPI\CustomForm;

class XPSystem extends PluginBase{

    /* @var $money ECOAPI */
    public $money;

    public function onEnable(){
        @mkdir($this->getDataFolder());
        $this->saveResource("config.yml");
        if(is_string($this->getConfig()->get("prefix"))){
        }else{
            $this->getServer()->getLogger()->critical("prefix is not a string, please change it.");
        }
        $this->money = EconomyAPI::getInstance();
    }

    public function onDisable(){
    }

    public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args) : bool {
        switch($cmd->getName()) {
            case "xp":
                if($sender instanceof Player) {
                   $this->MainExpForm($sender);
                } else {
                   $sender->sendMessage("§cUse In-Game only!");
                   return true;
                }
                return true;
            }
        return true;
   }
   
   public function MainExpForm(Player $sender) {
        $form = new SimpleForm(function (Player $sender, $data){
            $result = $data;
            if($result === null) {
                $sender->sendMessage($this->getConfig()->get("prefix") . " §cExit the menu");
                return true;
            }             
            switch($result) {
                case 0:
                break;
                case 1:
                    $this->BuyExpForm($sender);
                break;
                case 2:
                    $this->SellExpForm($sender);
                break;
       
            }
       });
       $form->setTitle("§lXP System");
       $form->setContent("Buy or sell your XP!");
       $form->addButton("§cExit");
       $form->addButton("§lBuy XP");
       $form->addButton("§lSell XP");
       $form->sendToPlayer($sender);
       return $form;
   }
   
   public function BuyExpForm(Player $sender) { 
        $form = new CustomForm(function (Player $sender, $data){
            $result = $data;
            if($result === null) {
                $this->ExpForm($sender);
                return true;
            }             
            if(!empty($data[1])){
                if(is_numeric($data[1])){
                    $price = $this->getConfig()->get("price_buy");
                    $prefix = $this->getConfig()->get("prefix");
                    $total = $data[1] * $price;
                        if($this->money->myMoney($sender) >= $total){
                                $sender->setXpLevel($sender->getXpLevel() + $data[1]);
                                $this->money->reduceMoney($sender->getName(), $total);
                                $sender->sendMessage("$prefix §l§aSuccefully buy §b" . $data[1] . " §aXP levels! \n§r$prefix §l§e$" . $total . " §cHas be diminished from your account!");
                            }else{
                                $sender->sendMessage($this->getConfig()->get("prefix") . " §cYou do not have enough money!");
                            }
                            }else{
                            $sender->sendMessage($this->getConfig()->get("prefix") . " §cThe amount specified is not a number");
                        }
                    }else{
                        $sender->sendMessage($this->getConfig()->get("prefix") . " §cPlease specify an amount");
                    }
       });
       $price = $this->getConfig()->get("price_buy");
       $xp = 24791 - $sender->getXpLevel();
       $money = $this->money->myMoney($sender);
       $form->addLabel("§fYour Money: §e$money \n§fPrice per XP level: §e$price \n§fMax XP level you can buy: §e$xp §axp levels");
       $form->setTitle("§b§lBuy §aXP");
       $form->addInput("§aAmount XP level you want to buy", "Enter the amount in here");
       $form->sendToPlayer($sender);
       return $form;
   }
   
   public function SellExpForm(Player $sender) { 
       $form = new CustomForm(function (Player $sender, $data){
            $result = $data;
            if($result === null) {
                $this->ExpForm($sender);
                return true;
            }             
            if(!empty($data[1])){
                if(is_numeric($data[1])){
                    if($sender->getXpLevel() - $data[1] >= 0){
                        $sender->setXpLevel($sender->getXpLevel() - $data[1]);
                                $price = $this->getConfig()->get("price_sell");
                                $prefix = $this->getConfig()->get("prefix");
                                $total = $data[1] * $price;
                                $this->money->addMoney($sender, $total);
                                $sender->sendMessage("$prefix §l§aSuccefully sell §b" . $data[1] . " §aXP levels! \n§r$prefix §l§a$" . $total . " §aAdded to your account!");
                            }else{
                                $sender->sendMessage($this->getConfig()->get("prefix") . " §cYou do not have enough xp");
                            }
                        }else{
                            $sender->sendMessage($this->getConfig()->get("prefix") . " §cThe amount specified is not a number");
                        }
                    }else{
                        $sender->sendMessage($this->getConfig()->get("prefix") . " §cPlease specify an amount");
                    }
       });
       $price = $this->getConfig()->get("price_sell");
       $xp = $sender->getXpLevel();
       $form->addLabel("§fPrice sell per XP level: §e$price \n§fXP level you can sell: §e$xp §axp levels");
       $form->setTitle("§b§lSell §aXP");
       $form->addInput("§aAmount XP level you want to sell", "Enter the amount in here");
       $form->sendToPlayer($sender);
       return $form;
   }
}
