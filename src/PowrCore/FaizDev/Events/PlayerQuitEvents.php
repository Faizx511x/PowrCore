<?php

namespace PowrCore\FaizDev\Events;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;

class PlayerQuitEvents implements Listener
{
    public function OnQuit(PlayerQuitEvent $event)
    {
        $player = $event->getPlayer();
      //* Soon...
    }
}
