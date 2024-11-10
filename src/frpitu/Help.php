<?php

declare(strict_types=1);

namespace frpitu;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

final class Help extends PluginBase
{
    const CONSOLE_PREFIX = '§8>';

    /** @var Config */
    public $config;

    /**
     * @inheritDoc
     */
    function onLoad()
    {
        $info = [
            '§8>' . str_repeat('§c-_', 20) . '§8<',
            '               §cHelp plugin by: §7frpitu',
            '               §bTwitter: §f@frpitu',
            '               §0Git§fhub§7: §f@frpitu',
            '§8>' . str_repeat('§c-_', 20) . '§8<'
        ];

        foreach ($info as $msg) {
            $this->getLogger()->info($msg);
        }

        $this->getLogger()->notice(self::CONSOLE_PREFIX . ' §bThe plugin is loading. Please wait and do not stop the console.');
        
        $configFile = $this->getDataFolder() . "config.yml";
        
        if (!file_exists($configFile)) {
            $this->saveDefaultConfig();
        }
        
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);$configFile = $this->getDataFolder() . "config.yml";

        if ($this->getServer()->getCommandMap()->getCommand("help") !== null) {
            $this->getServer()->getCommandMap()->getCommand("help")->setLabel("help");
            $this->getServer()->getCommandMap()->getCommand("help")->setExecutor($this);
            $this->getServer()->getCommandMap()->getCommand("help")->unregister($this->getServer()->getCommandMap());
        }
    }

    /**
     * @inheritDoc
     */
    function onEnable()
    {
        $this->getLogger()->info('§aThe plugin is enabled');
    }

    /**
     * @inheritDoc
     */
    function onDisable()
    {
        $this->getLogger()->info('§cThe plugin is disabled');
    }

    function getDate()
    {
        $dateMode = $this->config->get('dateMode');

        switch ($dateMode) {
            case '0': // USA Date Mode
                return date('Y/m/d');
            case '1': // BR Date Mode
                return date('d/m/Y');
            default:
                return date('Y/m/d'); // Fallback date format
        }
    }

    /**
     * @inheritDoc
     *
     * @param CommandSender $sender
     * @param Command       $command
     * @param string        $label
     * @param array         $args
     *
     * @return bool
     */
    function onCommand(CommandSender $sender, Command $command, $label, array $args)
    {
        if ($command->getName() == 'help') {
            $msg = $this->config->get('message');

            $replace = str_replace(
                [
                    '{PLAYER}',
                    '{EOL}',
                    '{O}',
                    '{C}',
                    '{DATE}'
                ],
                [
                    $sender->getName(),
                    "\n",
                    count($this->getServer()->getOnlinePlayers()),
                    count($sender->getLevel()->getPlayers()),
                    $this->getDate()
                ],
                $msg
            );

            if (!$sender instanceof Player) {
                $sender->sendMessage($this->config->get('notplayer-msg'));
                return true;
            }

            $sender->sendMessage($replace);
            return true;
        }

        return false;
    }
}
?>
