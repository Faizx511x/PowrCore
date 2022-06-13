<?php

namespace PowrCore\FaizDev\Command;

use pocketmine\Server;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginOwned;

use PowrCore\FaizDev\PowrCore;

class SocialMenuCommand extends Command implements PluginOwned{
    
    private $plugin;

    public function __construct(PowrCore $plugin){
        $this->plugin = $plugin;
        
        parent::__construct("socialmenu", "§rYour Social Menu", "§cUse: /socialmenu", ["socialmenu"]);
        $this->setAliases(["sm"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args){
        if(count($args) == 0){
            if($sender instanceof Player) {
                $this->plugin->SocialMenuForm($sender);
            } else {
                $sender->sendMessage("Use this command in-game");
            }
        }
        return true;
    }
    
    public function getPlugin(): Plugin{
        return $this->plugin;
    }

    public function getOwningPlugin(): PowrCore{
        return $this->plugin;
    }
}
