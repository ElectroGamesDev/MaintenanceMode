<?php

namespace Electro\MaintenanceMode;

use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
class Main extends PluginBase implements Listener{

    public function onEnable()
    {
        if(!is_dir($this->getDataFolder())) {
            mkdir($this->getDataFolder());
        }
        $this->saveDefaultConfig();
        $this->getResource("config.yml");

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onJoin(PlayerPreLoginEvent $event){
        $player = $event->getPlayer();

        if ($this->getConfig()->get("Maintenance_Mode_Active") === true && !$player->hasPermission("mm.bypass")){
            $player->kick($this->getConfig()->get("Maintenance_Mode_Message"), false);
        }
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool{
        if (!$sender->hasPermission("mm.cmd")){
            $sender->sendMessage("§cYou don't have permissions to use this command");
            return true;
        }
        if (!isset($args[0])){
            $sender->sendMessage("§cUsage: §a/mm on | off");
            return true;
        }
        switch($command->getName()){
            case "mm":
                switch (strtolower($args[0])){
                    case "on":
                        $this->getConfig()->set("Maintenance_Mode_Active", true);
                        $this->getConfig()->save();
                        $sender->sendMessage("§aYou have turned on maintenance mode");
                    break;
                    case "off":
                        $this->getConfig()->set("Maintenance_Mode_Active", false);
                        $this->getConfig()->save();
                        $sender->sendMessage("§aYou have turned off maintenance mode");
                    break;
                }

        }
        return true;
    }
}
