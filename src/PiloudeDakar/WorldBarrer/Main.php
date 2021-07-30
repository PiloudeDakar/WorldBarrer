<?php

namespace PiloudeDakar\WorldBarrer;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener {

    public function onEnable()
    {
        $this->saveDefaultConfig();
        $db = new Config($this->getDataFolder() . 'config.yml', Config::YAML);
        $this->getServer()->getPluginManager()->registerEvents($this,$this);
        if (is_string($db->get('level')) === true or is_int($db->get('x_minimum')) === true or is_int($db->get('y_minimum')) === true or is_int($db->get('x_maximum')) === true or is_int($db->get('y_maximum') === true)){
            $this->getServer()->getLogger()->info('[WorldBarrer] the plugin\'s configuration is good.');
        } else {
            $this->getServer()->getLogger()->alert('[WorldBarrer] The plugin\'s configuration isn\'t good !!! Go into the file plugin_data\WorldBarrer\config.yml or do the plugin\'s commands(/wb help) to change it.');
        }
    }

    public function onPlayerMove(PlayerMoveEvent $event)
    {
        $db = new Config($this->getDataFolder() . 'config.yml', Config::YAML);
        if (is_string($db->get('level')) === true or is_int($db->get('x_minimum')) === true or is_int($db->get('y_minimum')) === true or is_int($db->get('x_maximum')) === true or is_int($db->get('y_maximum') === true)) {
            $player = $event->getPlayer();
            $x = $player->getX();
            $z = $player->getZ();
            if ($player->getLevel() != $db->get('level')) {
                if ($x > $db->get('x_maximum')) {
                    $player->teleport([$db->get('x_maximum'), $player->getY(), $player->getZ(), $player->getLevel()]);
                }
                if ($x > $db->get('x_minimum')) {
                    $player->teleport([$db->get('x_minimum'), $player->getY(), $player->getZ(), $player->getLevel()]);
                }
                if ($z > $db->get('z_maximum')) {
                    $player->teleport([$player->getX(), $player->getY(), $db->get('z_maximum'), $player->getLevel()]);
                }
                if ($z > $db->get('z_minimum')) {
                    $player->teleport([$player->getX(), $player->getZ(), $db->get('z_minimum'), $player->getLevel()]);
                }
            }
        }
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {

        $db = new Config($this->getDataFolder() . 'config.yml', Config::YAML);
        switch ($command) {
            case 'setXMaximum':
                if (is_int($args[1]) === false) {
                    $this->sendHelp($sender);
                    return false;
                }
                $db->set('x_maximum', $args[1]);
                $db->save();
                break;
            case 'setYMaximum':
                if (is_int($args[1]) === false) {
                    $this->sendHelp($sender);
                    return false;
                }
                $db->set('y_maximum', $args[1]);
                $db->save();
                break;
            case 'setXMinimum':
                if (is_int($args[1]) === false) {
                    $this->sendHelp($sender);
                    return false;
                }
                $db->set('x_minimum', $args[1]);
                $db->save();
                break;
            case 'setYMinimum':
                if (is_int($args[1]) === false) {
                    $this->sendHelp($sender);
                    return false;
                }
                $db->set('y_minimum', $args[1]);
                $db->save();
                break;
            case 'setLevel':
                if (is_string($args[1]) === false) {
                    $this->sendHelp($sender);
                    return false;
                }
                $db->set('level', $args[1]);
                $db->save();
                break;
            case 'help':
                $this->sendHelp($sender);
        }
        return true;
    }

    public function sendHelp(CommandSender $sender){
        $sender->sendMessage('§1[§4WorldBarrer§1]§2Help :
                - §3/setLevel [LevelName(string)] §2| Set the level of the barrer.
                - §3/setXMaximum [maximal x block(int)] §2| Set the x maximum of the barrer.
                - §3/setXMinimum [minimal x block(int)] §2| Set the x minimal of the barrer.
                - §3/setYMaximum [maximal y block(int)] §2| Set the y maximum of the barrer.
                - §3/setYMinimum [minimal y block(int)] §2| Set the y minimal of the barrer.');
    }
}