<?php

namespace Help;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

    public function onEnable(){
        $this->getLogger()->info("Plugin enabled");
    }

    public function onDisable(){
        $this->getLogger()->info("Plugin disabled");
    }
    public function onLoad(){
    $this->config = new Config($this->getDataFolder()."config.yml", Config::YAML);
    }
    public function onCommand(CommandSender $sender, Command $command, $label, array $args) {
        if ($command->getName() === "ajuda") {
            $message = $this->getConfig()->get("message");
            $msg = str_replace("{line}", PHP_EOL, $message);
            if($sender instanceof Player){
                $sender->sendMessage($msg);
            }
        }
    }
}