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

use misael38\XPSystem\Event\PlayerMove;
use misael38\XPSystem\Command\XP;

class Main extends PluginBase{

    public $exp;
    
    public $money;
    
    private static $instance;

    public function onEnable() {
        @mkdir($this->getDataFolder() . "xp_data");
    $this->money = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
    $this->exp = new Config($this->getDataFolder() . "xp_data/xp.yml", Config::YAML);
    $this->getServer()->getCommandMap()->register("xp", new XP($this));
    self::$instance = $this;
    $this->getServer()->getPluginManager()->registerEvents(new PlayerMove($this), $this);
    }

    public function onDisable()
    {
    $this->exp->save();
    }
    
    public static function getInstance(){
    return self::$instance;
    }
   
   public function AdminExpForm(Player $sender) { 
        $form = new SimpleForm(function (Player $sender, $data){
            $result = $data;
            if($result === null) {
                return true;
            }             
            switch($result) {
                case 0:
                break;
                case 1:
                    $this->MainExpForm($sender);
                break;
                case 2:
                    if ($sender->hasPermission("xp.command.add") && $sender->hasPermission("xp.all")) {
                        $this->AddExpForm($sender);
                        } else {
                            $sender->sendMessage("§cYou do not have permission to use this command!");
                        }
                break;
                case 3:
                    if ($sender->hasPermission("xp.command.set") && $sender->hasPermission("xp.all")) {
                        $this->SetExpForm($sender);
                        } else {
                            $sender->sendMessage("§cYou do not have permission to use this command!");
                        }
                break;
                case 4:
                    if ($sender->hasPermission("xp.command.remove") && $sender->hasPermission("xp.all")) {
                        $this->RemoveExpForm($sender);
                        } else {
                            $sender->sendMessage("§cYou do not have permission to use this command!");
                        }
                break;
                case 5:
                    $this->SeeExpForm($sender);
                break;
       
            }
       });
       $form->setTitle("§lAdmin Panel");
       $form->setContent("choose one of the option!");
       $form->addButton("§cExit");
       $form->addButton("§lPlayer Form");
       $form->addButton("§lAdd XP");
       $form->addButton("§lSet XP");
       $form->addButton("§lRemove XP");
       $form->addButton("§lSee XP");
       $form->sendToPlayer($sender);
       return $form;
   }
   
   public function AddExpForm(Player $sender) { 
        $form = new CustomForm(function (Player $sender, $data){
            $result = $data;
            if($result === null) {
                $this->AdminExpForm($sender);
                return true;
            }
            $target = $this->getServer()->getPlayer($data[1]);
            if($target === null || !$target->isOnline()) {
            $sender->sendMessage("§cPlayer is not online!");
            return true;
            } else {
            }
            
            if(!is_numeric($data[2])){
            $sender->sendMessage("§cThe Amount is not a number!");
            return true;
            } else {
            }
            
            if(empty($data[1]) || empty($data[2])){
            $sender->sendMessage("§cplease fill in all the forms available");
            return true;
              
              } else {
                  $targetName = $target->getName();
                  $targetXP = $target->getXpLevel();
                  $addXP = $targetXP + $data[2];
                  $target->setXpLevel($addXP);
                  $target->sendMessage("You have earned §a" . $data[2] . " XP Level §rFrom " . $sender->getName());
                  $sender->sendMessage("successfully added " . $data[2] . " §aXP Level §rto $targetName !");
              }
        });
        $form->addLabel("You can add xp level to player in here! \n§cmake sure the player is online!");
        $form->setTitle("§b§lAdd §aXP");
        $form->addInput("§aPlayer Name", "Enter in here");
        $form->addInput("§aAmount XP level you want to add", "Enter the amount in here");
        $form->sendToPlayer($sender);
        return $form;
   }
   
   public function SetExpForm(Player $sender) { 
        $form = new CustomForm(function (Player $sender, $data){
            $result = $data;
            if($result === null) {
                $this->AdminExpForm($sender);
                return true;
            }
            $target = $this->getServer()->getPlayer($data[1]);
            if($target === null || !$target->isOnline()) {
            $sender->sendMessage("§cPlayer is not online!");
            return true;
            } else {
            }
            
            if(!is_numeric($data[2])){
            $sender->sendMessage("§cThe Amount is not a number!");
            return true;
            } else {
            }
            
            if(empty($data[1])){
            $sender->sendMessage("§cplease fill in all the forms available");
            return true;
            } else {
            }
            
            if($data[2] === 0 || empty($data[2])){
            $sender->sendMessage("§cYou can't set the xp level to 0, please choose between 1 - 24791");
            return true;    
            } else {
                  $targetName = $target->getName();
                  $targetXP = $target->getXpLevel();
                  $target->setXpLevel($data[2]);
                  $target->sendMessage("Your XP Level was set to §a" . $data[2] . " Level");
                  $sender->sendMessage("successfully set " . $targetName . "'s XP Level §rto §a" . $data[2]);
              }
        });
        $form->addLabel("You can set the player's xp level \nchoose between 1 - 24791 XP Level \n§cmake sure the player is online!");
        $form->setTitle("§b§lSet §aXP");
        $form->addInput("§aPlayer Name", "Enter in here");
        $form->addInput("§aAmount XP level you want to set", "Enter the amount in here");
        $form->sendToPlayer($sender);
        return $form;
   }
   
   public function RemoveExpForm(Player $sender) { 
        $form = new CustomForm(function (Player $sender, $data){
            $result = $data;
            if($result === null) {
                $this->AdminExpForm($sender);
                return true;
            }
            $target = $this->getServer()->getPlayer($data[1]);
            if($target === null || !$target->isOnline()) {
            $sender->sendMessage("§cPlayer is not online!");
            return true;
            } else {
            }
            
            if(!is_numeric($data[2])){
            $sender->sendMessage("§cThe Amount is not a number!");
            return true;
            } else {
            }
            
            if(empty($data[1]) || empty($data[2])){
            $sender->sendMessage("§cplease fill in all the forms available");
            return true;
              
              } else {
                  $targetName = $target->getName();
                  $targetXP = $target->getXpLevel();
                  $removeXP = $targetXP - $data[2];
                  $target->setXpLevel($removeXP);
                  $target->sendMessage("§a" . $data[2] . " XP Level §chas been removed from your account!");
                  $sender->sendMessage("§cRemoved §a" . $data[2] . " §aXP Level §cfrom $targetName !");
              }
        });
        $senderXP = $sender->getXpLevel();
        $form->addLabel("Remove the player's xp level \n§cmake sure the player's online!");
        $form->setTitle("§b§lRemove §aXP");
        $form->addInput("§aPlayer Name", "Enter in here");
        $form->addInput("§aPlayer XP level you want to remove", "Enter the amount in here");
        $form->sendToPlayer($sender);
        return $form;
   }
   
   public function MainExpForm(Player $sender) { 
        $form = new SimpleForm(function (Player $sender, $data){
            $result = $data;
            if($result === null) {
                return true;
            }             
            switch($result) {
                case 0:
                break;
                case 1:
                    $this->ExpShopForm($sender);
                break;
                case 2:
                    $this->SeeExpForm($sender);
                break;
                case 3:
                    $this->PayExpForm($sender);
                break;
                case 4:
                    $this->TopExpForm($sender);
                break;
       
            }
       });
       $form->setTitle("§lXP System");
       $form->setContent("choose one of the option!");
       $form->addButton("§cExit");
       $form->addButton("§lShop \n§r(Buy/Sell)");
       $form->addButton("§lSee XP");
       $form->addButton("§lPay XP");
       $form->addButton("§lTop XP");
       $form->sendToPlayer($sender);
       return $form;
   }
   
   public function ExpShopForm(Player $sender) { 
        $form = new SimpleForm(function (Player $sender, $data){
            $result = $data;
            if($result === null) {
                $this->MainExpForm($sender);
                return true;
            }             
            switch($result) {
                case 0:
                    $this->MainExpForm($sender);
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
       $form->addButton("§cBack");
       $form->addButton("§lBuy XP");
       $form->addButton("§lSell XP");
       $form->sendToPlayer($sender);
       return $form;
   }
   
   public function SeeExpForm(Player $sender) { 
        $form = new CustomForm(function (Player $sender, $data){
            $result = $data;
            if($result === null) {
                $this->MainExpForm($sender);
                return true;
            }
            $target = $this->getServer()->getPlayer($data[1]);
            if($target === null || !$target->isOnline()) {
            $sender->sendMessage("§cPlayer is not online!");
            return true;
            } else {
            }
            
            if(empty($data[1])){
            $sender->sendMessage("§cPlease type the target is name!");
            return true;
              } else {
                  $targetName = $target->getName();
                  $targetXP = $target->getXpLevel();
                  $sender->sendMessage($targetName . "'s XP Level: " . $targetXP);
              }
        });
        $form->addLabel("See another player is xp level \n§cMake sure the player is online!");
       $form->setTitle("§b§lSee §aXP");
       $form->addInput("§aPlayer Name", "Enter in here");
       $form->sendToPlayer($sender);
       return $form;
   }
   
   public function PayExpForm(Player $sender) { 
        $form = new CustomForm(function (Player $sender, $data){
            $result = $data;
            if($result === null) {
                $this->MainExpForm($sender);
                return true;
            }
            $target = $this->getServer()->getPlayer($data[1]);
            if($target === null || !$target->isOnline()) {
            $sender->sendMessage("§cPlayer is not online");
            return true;
            } else {
            }
            
            if(!is_numeric($data[2])){
            $sender->sendMessage("§cThe Amount is not a number!");
            return true;
            } else {
            }
            
            if($sender->getXpLevel() - $data[2] <= 0){
            $sender->sendMessage("You do not have a enough xp level!");
            return true;
            } else {
            }
            
            if(empty($data[1]) || empty($data[2])){
            $sender->sendMessage("§cplease fill in all the forms available");
            return true;
              
              } else {
                  $targetName = $target->getName();
                  $targetXP = $target->getXpLevel();
                  $senderXP = $sender->getXpLevel();
                  $removeXP = $senderXP - $data[2];
                  $sender->setXpLevel($removeXP);
                  $payXP = $targetXP + $data[2];
                  $target->setXpLevel($payXP);
                  $target->sendMessage($sender->getName() . " give you §a" . $data[2] . " XP Level!");
                  $sender->sendMessage("successfully paid §a" . $data[2] . " §rto " . $targetName . "!");
              }
        });
        $senderXP = $sender->getXpLevel();
        $form->addLabel("Available to pay: $senderXP \n§cMake sure the target you want to give the xp level is online!");
       $form->setTitle("§b§lPay §aXP");
       $form->addInput("§aPlayer Name", "Enter in here");
       $form->addInput("§aAmount XP level you want to give", "Enter the amount in here");
       $form->sendToPlayer($sender);
       return $form;
   }
   
   public function TopExpForm(Player $sender) { 
       $top_exp = $this->exp->getAll();
       $message = "";
		if(count($top_exp) > 0){
			arsort($top_exp);
			$i = 1;
			foreach($top_exp as $name => $exp){
				$message .= "§6» §f".$i."§a. §r§f".$name."§f: §r§e".$exp." §aLevel\n";
				if($i >= 10){
					break;
					}
					++$i;
				}}
	   $form = new SimpleForm(function (Player $sender, $data){
            $result = $data;
            if($result === null) {
                $this->MainExpForm($sender);
                return true;
            }             
            switch($result) {
                case 0:
                    $this->MainExpForm($sender);
                break;
                case 1:
                break;
       
            }
       });
       $form->setTitle("§lTop XP Level");
       $form->setContent("".$message);
       $form->addButton("§l§cBack");
       $form->addButton("§l§cExit");
       $form->sendToPlayer($sender);
       return $form;
   }
   
   public function BuyExpForm(Player $sender) { 
        $form = new CustomForm(function (Player $sender, $data){
            $result = $data;
            if($result === null) {
                $this->MainExpForm($sender);
                return true;
            }             
            if(!empty($data[1])){
                if(is_numeric($data[1])){
                    $price = $this->getConfig()->get("price_buy");
                    $total = $data[1] * $price;
                        if($this->money->myMoney($sender) >= $total){
                                $sender->setXpLevel($sender->getXpLevel() + $data[1]);
                                $this->money->reduceMoney($sender->getName(), $total);
                                $sender->sendMessage("§aSuccefully buy §b" . $data[1] . " §aXP levels! \n§e$" . $total . " §cHas be diminished from your account!");
                            }else{
                                $sender->sendMessage("§cYou do not have enough money!");
                            }
                            }else{
                            $sender->sendMessage("§cThe amount is not a number");
                        }
                    }else{
                        $sender->sendMessage("§cPlease specify an amount");
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
                $this->MainExpForm($sender);
                return true;
            }             
            if(!empty($data[1])){
                if(is_numeric($data[1])){
                    if($sender->getXpLevel() - $data[1] >= 0){
                        $sender->setXpLevel($sender->getXpLevel() - $data[1]);
                                $price = $this->getConfig()->get("price_sell");
                                $total = $data[1] * $price;
                                $this->money->addMoney($sender, $total);
                                $sender->sendMessage("§aSuccefully sell §b" . $data[1] . " §aXP levels! \n§a$" . $total . " §aAdded to your account!");
                            }else{
                                $sender->sendMessage("§cYou do not have enough xp");
                            }
                        }else{
                            $sender->sendMessage("§cThe amount is not a number");
                        }
                    }else{
                        $sender->sendMessage("§cPlease specify an amount");
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
