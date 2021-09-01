<?php
namespace Yaser\Penalty;

use Yaser\Penalty\lib\CustomForm;
use pocketmine\plugin\PluginBase;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginManager;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\command\ConsoleCommandSender;
use onebone\economyapi\EconomyAPI;
use pocketmine\utils\config;

class Main extends PluginBase {

    public function onCommand(\pocketmine\command\CommandSender $sender, \pocketmine\command\Command $command, string $label, array $args): bool
    {
        if($command->getName() == "penalty"){
            if($sender instanceof Player){
				if ($sender->hasPermission("penalty.use")){
                    $this->Penaltymenu($sender);
				}else{
					$sender->sendMessage("§cYou Cant Use This Command");
				}
            }else{
                $sender->sendMessage("§cUse This Command In Game");
            }

        }

        return true;
    }
	public function Penaltymenu(player $player){
		$name = $player->getName();
		$form = new CustomForm(function (Player $player, $data = null) use ($name) : void {
			

			if($data === null){
			}
			
			if(strtolower($data[1]) === strtolower($player->getName())){
				$player->sendMessage("§cYou Cant Penalty Your Self");
				return;
			}
			if($data === null){
			}
		    if(!is_numeric($data[2])){
				$player->sendMessage("§cPlease Use Number For Penalty a Player");
				return;
		    }
			
			if($data[2] <= 0){
			$player->sendMessage("§cPlease Use Number For Penalty a Player");
				return;
			}
			
		    $target = $this->getServer()->getPlayer($data[1]);
		    if ($target !== null){
				$namet = $target->getName();
                $economy = $this->getServer()->getPluginManager()->getPlugin('EconomyAPI');
				
			    if ($economy->myMoney($target) >= $data[2]) {
					EconomyAPI::getInstance()->reduceMoney($target,$data[2]);	
				    $player->sendMessage("§aYou Find $namet For§f $data[2]");
					$target->sendMessage("§c$name Fine You For§f $data[2]");
				}else{
					$player->sendMessage("§cOpps $namet Cant Pay This Money§6 Reason:§e He Dont Have Money");
				}
			}else{
				$player->sendMessage("§cThis Player Is Not Online");
			}
			
		 
		});	

        $form->setTitle("§ePenalty a Player");
        $form->addLabel("§eHow Much Money do You Want To Penalty a Player?");
		$form->addInput("Player Name?", "Type here...");
		$form->addInput("How Much Money want to Penalty player", "Type here...");
        $form->sendToPlayer($player);
		
		return $form;
	}
}
