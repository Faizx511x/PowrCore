<?php

declare(strict_types=1);

namespace PowrCore\FaizDev\Task;

use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\world\World;

class AlwaysDay extends Task
{
    public function onRun(): void {
        foreach(Server::getInstance()->getWorldManager()->getWorlds() as $worlds) {
            $worlds->setTime(World::TIME_DAY);
        }
    }

}
