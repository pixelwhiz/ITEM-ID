<?php

declare(strict_types=1);

namespace hasiyfgaming\itemid;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;

use pocketmine\utils\Config;

use pocketmine\Player;
use pocketmine\Server;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerRespawnEvent;

class Main extends PluginBase implements Listener {
	
	public $helder = [];
	public $config;
	
	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->saveResource("Config.yml");
        $this->config = new Config($this->getDataFolder() . "Config.yml", Config::YAML);
	}
	
	public function onLoading(){
		
	}
	
	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args) : bool {
		switch($cmd->getName()){
			case "id":
				if($sender instanceof Player){
					if(!isset($this->helder[$sender->getName()])){
						$sender->sendMessage($this->getConfig()->get("enable-message"));
						#$this->color("enable-message") ;
						$this->helder[$sender->getName()] = $sender->getName();
					}else{
						$sender->sendMessage($this->getConfig()->get("disable-message"));
						#$this->color("disable-message");
						unset($this->helder[$sender->getName()]);
					}
				}else{
					$sender->sendMessage("Use this command in game!");
				}
			break;
		}
		return true;
		
	}
	
	public function onHeld(PlayerItemHeldEvent $event){
		$player = $event->getPlayer();
		$item = $event->getItem();
		
		$itemid = $this->getConfig()->get("item-message");
		$itemid = str_replace('{id}', $item->getId(), $itemid);
		$itemid = str_replace('{damage}', $item->getDamage(), $itemid);
		#$this->color("item-message");
		
		if(isset($this->helder[$player->getName()])){
			if($player instanceof Player){
				$player->sendTip($itemid);
			}
		}else{
			return true;
		}
	}
	
	public function onJoin(PlayerJoinEvent $event){
		$player = $event->getPlayer();
		unset($this->helder[$player->getName()]);
	}
	
	public function onRespawn(PlayerRespawnEvent $event){
		$player = $event->getPlayer();
		unset($this->helder[$player->getName()]);
	}
	
	public function onDisable(){
		
	}

}
