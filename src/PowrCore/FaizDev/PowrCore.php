<?php

namespace PowrCore\FaizDev;

// PowrCore
use PowrCore\FaizDev\Command\SettingsCommand;
use PowrCore\FaizDev\Command\FlyCommand;
use PowrCore\FaizDev\Command\NameColorCommand;
use PowrCore\FaizDev\Command\GamesCommand;
use PowrCore\FaizDev\Command\SocialMenuCommand;
use PowrCore\FaizDev\Command\HubCommand;
use PowrCore\FaizDev\Events\PlayerQuitEvents; //Soon...

// POCKETMINE
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageByChildEntityEvent;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\player\Player;
use pocketmine\player\GameMode;
use pocketmine\event\EventPriority;
use pocketmine\event\entity\ProjectileHitEvent;
use pocketmine\entity\projectile\EnderPearl;
use pocketmine\entity\Living;
use pocketmine\item\ItemFactory;
use pocketmine\math\Vector3;
use pocketmine\world\Position;
use pocketmine\world\World;
use pocketmine\utils\TextFormat as C;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\inventory\InventoryTransactionEvent;
// FORM
use PowrCore\FaizDev\Form\CustomForm;
use PowrCore\FaizDev\Form\SimpleForm;
//Task
use PowrCore\FaizDev\Task\AlwaysDay;

class PowrCore extends PluginBase implements Listener {
  
  public function onEnable(): void{
      $this->getLogger()->info("§aEnabled PowrCore");
      $this->getServer()->getPluginManager()->registerEvents($this, $this);
      $this->BetterPearl();
      $this->getScheduler()->scheduleRepeatingTask(new AlwaysDay(), 40);
      @mkdir($this->getDataFolder());
      $this->saveDefaultConfig();
      $this->getServer()->getPluginManager()->registerEvents(new PlayerQuitEvents(), $this);
      $this->getServer()->getCommandMap()->register("settings", new SettingsCommand($this));
      $this->getServer()->getCommandMap()->register("fly", new FlyCommand($this));
      $this->getServer()->getCommandMap()->register("namecolor", new NameColorCommand($this));
      $this->getServer()->getCommandMap()->register("games", new GamesCommand($this));
      $this->getServer()->getCommandMap()->register("socialmenu", new SocialMenuCommand($this));
      $this->getServer()->getCommandMap()->register("hub", new HubCommand($this));
  }
  
  public function onDiable(): void{
      $this->getLogger()->info("§cDisabled PowrCore");
  }
  
  public function SettingsForm($player){
       $form = new SimpleForm(function(Player $player, int $data = null){
            if($data === null){
                return true;
            }
            switch($data){
                case 0:
                    $this->FlyForm($player);
                    $player->sendMessage("§aYou Have Left the Settings to Fly!");
                break;
            
                case 1:
	            $this->getServer()->getCommandMap()->dispatch($player, "nick");
                    $player->sendMessage("§aYou Have Left the Settings to Nick!");
                break;
			    
		case 2:
	            $this->getServer()->getCommandMap()->dispatch($player, "size");
                    $player->sendMessage("§aYou Have Left the Settings to Size!");
	        break;

                case 3:
                    $this->NameColorForm($player);
                    $player->sendMessage("§aYou Have Left the Settings to NameColor!");
                break;

                case 4:
                   
                break;
            
            }
       });
       $form->setTitle("§bCosmetics");
       $form->setContent("§fPick THe Setting!");
       $form->addButton("§aFly");
       $form->addButton("§6Nick");
       $form->addButton("§cSize");
       $form->addButton("§bNameColors");
       $form->addButton("§cEXIT");
       $form->sendToPlayer($player);
       return $form;
  }
  
  public function FlyForm($player){
      $form = new CustomForm(function(Player $player, $data){
          if($data === null){
              return true;
          }
          switch($data){
              case true:
                  $player->setFlying(true);
                  $player->setAllowFlight(true);
                  $player->sendMessage("§aFly Is Active");
              break;
            
              case false:
                  $player->setFlying(false);
                  $player->setAllowFlight(true);
                  $player->sendMessage("§cFly Is Disabled");
              break;
           }
      });
      $form->setTitle("§aFly");
      $form->addLabel("§fChoose if you want fly to be off or on");
      $form->addToggle("§fFly", true);
      $form->sendToPlayer($player);
      return $form;
  }
  
  public function NameColorForm(Player $player){
		  $form = new SimpleForm(function (Player $player, $data = null){
			    if($data === null){
		          return true;
	        }
		      switch($data){
				      case 0:
					        $player->setDisplayName("§f" . $player->getName() . "§f");
					        $player->setNameTag("§f" . $player->getName() . "§f");
					        $player->sendMessage("§anickname color has been changed to §fWhite!");
				      break;

				      case 1:
					        $player->setDisplayName("§c" . $player->getName() . "§f");
					        $player->setNameTag("§c" . $player->getName() . "§f");
					        $player->sendMessage("§aYour nickname color has been changed to §cRed!");
				      break;

				      case 2:
					        $player->setDisplayName("§b" . $player->getName() . "§f");
					        $player->setNameTag("§b" . $player->getName() . "§f");
					        $player->sendMessage("§aYour nickname color has been changed to §bBlue!");
				      break;

				      case 3:
					        $player->setDisplayName("§e" . $player->getName() . "§f");
					        $player->setNameTag("§e" . $player->getName() . "§f");
					        $player->sendMessage("§aYour nickname color has been changed to §eYellow!");
				      break;

				      case 4:
					        $player->setDisplayName("§6" . $player->getName() . "§f");
					        $player->setNameTag("§6" . $player->getName() . "§f");
					        $player->sendMessage("§aYour nickname color has been changed to §6Orange!");
				      break;

				      case 5:
					        $player->setDisplayName("§d" . $player->getName() . "§f");
					        $player->setNameTag("§d" . $player->getName() . "§f");
					        $player->sendMessage("§aYour nickname color has been changed to §dPurple!");
				      break;
           
                                      case 6:
					        $player->setDisplayName("§0" . $player->getName() . "§f");
					        $player->setNameTag("§0" . $player->getName() . "§f");
					        $player->sendMessage("§aYour nickname color has been changed to §0Black!");
                                      break;
			          }
		        return true;
         });
		  $form->setTitle("§bNameColors");
		  $form->setContent("§fSelect your color you prefer to your name!");
		  $form->addButton("White");
		  $form->addButton("§cRed");
		  $form->addButton("§bBlue");
		  $form->addButton("§eYellow");
		  $form->addButton("§6Orange");
		  $form->addButton("§dPurple");
                  $form->addButton("§0Black");
		  $form->sendToPlayer($player);
		  return $form;
  }
	
  public function Games($player){
       $form = new SimpleForm(function(Player $player, int $data = null){
            if($data === null){
                return true;
            }
            switch($data){
                case 0:
                    $this->getServer()->getCommandMap()->dispatch($player, $this->getConfig()->get("GameUi-1"));
                break;
            
                case 1:
                    $this->getServer()->getCommandMap()->dispatch($player, $this->getConfig()->get("GameUi-2"));
                break;
			    
		case 2:
                    $this->getServer()->getCommandMap()->dispatch($player, $this->getConfig()->get("GameUi-3"));
	        break;
			    
		case 3:
		    $this->getServer()->getCommandMap()->dispatch($player, $this->getConfig()->get("GameUi-4"));
	        break;

                case 4:
                    $this->getServer()->getCommandMap()->dispatch($player, $this->getConfig()->get("GameUi-5"));
                break;

                case 5:
                    
                break;


            }
       });
       $form->setTitle("§bGames");
       $form->setContent("§fChoose the minigame you wanna play!");
       $form->addButton("§aSky§7Wars");
       $form->addButton("§aBed§eWars");
       $form->addButton("§9The§cBridge");
       $form->addButton("§eFi§ast");
       $form->addButton("§eF§bF§aA");
       $form->addButton("§cEXIT");
       $form->sendToPlayer($player);
       return $form;
  }
	
  public function SocialMenuForm($player){
       $form = new SimpleForm(function(Player $player, int $data = null){
            if($data === null){
                return true;
            }
            switch($data){
                case 0:
                    $this->getServer()->getCommandMap()->dispatch($player, "party");
                break;
            
                case 1:
                    $this->getServer()->getCommandMap()->dispatch($player, "friend");
                break;
			        
		case 2:
		            
	        break;
            }
       });
       $form->setTitle("§dSocial Menu");
       $form->setContent("§fChoose the minigame you wanna play!");
       $form->addButton("§9Party");
       $form->addButton("§aFriends");
       $form->addButton("§cEXIT");
       $form->sendToPlayer($player);
       return $form;
  }
  
  private function FlyMWCheck(Entity $entity) : bool{
        if(!$entity instanceof Player) return false;
	if($this->getConfig()->get("Fly") === "on"){
		if(!in_array($entity->getWorld()->getDisplayName(), $this->getConfig()->get("Worlds"))){
			$entity->sendMessage("This world does not allow flight!");
			if(!$entity->isCreative()){
				$entity->setFlying(false);
				$entity->setAllowFlight(false);
			}
			return false;
		}
	}elseif($this->getConfig()->get("Fly") === "off") return true;
	return true;
  }
  public function respawn(PlayerRespawnEvent $event){
     $player = $event->getPlayer();
     $player->setGamemode(GameMode::ADVENTURE());
     $this->onJoin($player);
   }
	
   public function onHub(Player $player){
       if($this->getConfig()->get("Lobby-Worlds") === true){
           if(!in_array($player->getWorld()->getDisplayName(), $this->getConfig()->get("LobbyWorld"))){
	                 $player->getInventory()->clearAll();
			 $player->getArmorInventory()->clearAll();
                         $item1 = ItemFactory::getInstance()->get(130, 0, 1);
                         $item2 = ItemFactory::getInstance()->get(145, 0, 1);
                         $item3 = ItemFactory::getInstance()->get(345, 0, 1);
                         $item4 = ItemFactory::getInstance()->get(433, 0, 1);
                         $item5 = ItemFactory::getInstance()->get(399, 0, 1);
                         $item5 = ItemFactory::getInstance()->get(399, 0, 1);
                         $item1->setCustomName($this->getConfig()->get("item1-name"));
                         $item2->setCustomName($this->getConfig()->get("item2-name"));
                         $item3->setCustomName($this->getConfig()->get("item3-name"));
                         $item4->setCustomName($this->getConfig()->get("item4-name"));
                         $item5->setCustomName($this->getConfig()->get("item5-name"));
                         $player->getInventory()->setItem(0, $item1);
                         $player->getInventory()->setItem(4, $item2);
                         $player->getInventory()->setItem(8, $item3);
		         $player->getInventory()->setItem(0, $item1);
                         $player->getInventory()->setItem(1, $item2);
                         $player->getInventory()->setItem(4, $item3);
                         $player->getInventory()->setItem(7, $item4);
                         $player->getInventory()->setItem(8, $item5);	
               }
          }
   }

  public function onJoin(PlayerJoinEvent $event) : void{
	$player = $event->getPlayer();
	if($this->getConfig()->get("FlyReset") === true){
		if($player->isCreative()) return;
		$player->setAllowFlight(false);
		$player->sendMessage($this->getConfig()->get("FlyMessage"));
		if($this->getConfig()->get("LC-MW") === true){
		     if(!in_array($player->getWorld()->getDisplayName(), $this->getConfig()->get("LC-Worlds"))){
                         $player = $event->getPlayer();
                         $player->getInventory()->clearAll();
                         $player->getArmorInventory()->clearAll();
                         $item1 = ItemFactory::getInstance()->get(130, 0, 1);
                         $item2 = ItemFactory::getInstance()->get(145, 0, 1);
                         $item3 = ItemFactory::getInstance()->get(345, 0, 1);
                         $item4 = ItemFactory::getInstance()->get(433, 0, 1);
                         $item5 = ItemFactory::getInstance()->get(399, 0, 1);
                         $item1->setCustomName($this->getConfig()->get("item1-name"));
                         $item2->setCustomName($this->getConfig()->get("item2-name"));
                         $item3->setCustomName($this->getConfig()->get("item3-name"));
                         $item4->setCustomName($this->getConfig()->get("item4-name"));
                         $item5->setCustomName($this->getConfig()->get("item5-name"));
                         $player->getInventory()->setItem(0, $item1);
                         $player->getInventory()->setItem(1, $item2);
                         $player->getInventory()->setItem(4, $item3);
                         $player->getInventory()->setItem(7, $item4);
                         $player->getInventory()->setItem(8, $item5);
		     }
	      }
	}
  }

	
  public function onClick(PlayerInteractEvent $event){
        $player = $event->getPlayer();
        $itn = $player->getInventory()->getItemInHand()->getCustomName();
        if($itn == $this->getConfig()->get("item1-name")){
            $this->getServer()->getCommandMap()->dispatch($player, $this->getConfig()->get("item1-cmd"));
        }
        if($itn == $this->getConfig()->get("item2-name")){
            $this->getServer()->getCommandMap()->dispatch($player, $this->getConfig()->get("item2-cmd"));
        }
        if($itn == $this->getConfig()->get("item3-name")){
            $this->getServer()->getCommandMap()->dispatch($player, $this->getConfig()->get("item3-cmd"));
        }
        if($itn == $this->getConfig()->get("item4-name")){
            $this->getServer()->getCommandMap()->dispatch($player, $this->getConfig()->get("item4-cmd"));
        }
        if($itn == $this->getConfig()->get("item5-name")){
            $this->getServer()->getCommandMap()->dispatch($player, $this->getConfig()->get("item5-cmd"));
        }
 }
	
 public function onInventory(InventoryTransactionEvent $event){
      $event->cancel();
 }

  public function onLevelChange(EntityTeleportEvent $event) : void{
	$entity = $event->getEntity();
	if($entity instanceof Player) $this->FlyMWCheck($entity);
  }
	
  public function onChange(EntityTeleportEvent $event) : void{
       	$entity = $event->getEntity();
	if($entity instanceof Player) $this->clear($entity);
  }
	
  public function clear($player){
      $player->getInventory()->clearAll();
      $player->getArmorInventory()->clearAll();
  }
	
  public function onEntityDamageEventByEntity(EntityDamageByEntityEvent $event): void{
	$damager = $event->getDamager();
	if(!$event instanceof EntityDamageByChildEntityEvent and $damager instanceof Living and $damager->isSprinting()){
		$event->setKnockback(1.9*$event->getKnockback());
		$damager->setSprinting(false);
	}
  }

  public function BetterPearl(){
       $this->getServer()->getPluginManager()->registerEvent(ProjectileHitEvent::class, static function (ProjectileHitEvent $event) : void{
           $projectile = $event->getEntity();
           $entity = $projectile->getOwningEntity();
           if ($projectile instanceof EnderPearl and $entity instanceof Player) {
               $vector = $event->getRayTraceResult()->getHitVector();
               (function() use($vector) : void{ //HACK : Closure bind hack to access inaccessible members
                   $this->setPosition($vector);
               })->call($entity);
               $location = $entity->getLocation();
               $entity->getNetworkSession()->syncMovement($location, $location->yaw, $location->pitch);
               $projectile->setOwningEntity(null);
           }
       }, EventPriority::NORMAL, $this);
   }
}        
 
