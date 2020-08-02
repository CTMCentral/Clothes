<?php

namespace TungstenVn\Clothes;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use TungstenVn\Clothes\checkStuff\checkClothes;
use TungstenVn\Clothes\checkStuff\checkRequirement;
use TungstenVn\Clothes\form\clothesForm;
use TungstenVn\Clothes\form\cosplaysForm;
use TungstenVn\Clothes\skinStuff\saveSkin;

class Clothes extends PluginBase implements Listener {

    /** @var self $instance */
    public static $instance;

    // sth like ["wing","lefthand"]:
    public $clothesTypes = [];
    public $cosplaysTypes = [];
    //something like ["wing" =>["wing1","wing2"]]:
    public $clothesDetails = [];
    public $cosplaysDetails = [];

    public function onEnable() {
        self::$instance = $this;
        $this->getServer()->getPluginManager()->registerEvents($this, $this);

        $a = new checkRequirement();
        $a->checkRequirement();

        $a = new checkClothes();
        $a->checkClothes();
        $a->checkCos();
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool {
        if($sender instanceof Player) {
            switch(strtolower($command->getName())) {
                case "clo":
                case "clothes":
                    $form = new clothesForm($this);
                    $form->mainform($sender, "");
                break;
                case "cos":
                case "cosplay":
                    $form = new cosplaysForm($this);
                    $form->mainform($sender, "");
                break;
            }
        }else {
            $sender->sendMessage("§cOnly work in game");
        }
        return true;
    }

    public function onJoin(PlayerJoinEvent $e) {
        $name = $e->getPlayer()->getName();
        $skin = $e->getPlayer()->getSkin();
        $saveSkin = new saveSkin();
        $saveSkin->saveSkin($skin, $name);
    }
}
