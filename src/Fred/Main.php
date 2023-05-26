<?php

declare(strict_types=1);

namespace Fred;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener {

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);

        // Membuat file konfigurasi jika belum ada
        $this->saveDefaultConfig();
    }

    public function onPlayerDeath(PlayerDeathEvent $event): void {
        $player = $event->getPlayer();
        $cause = $event->getEntity()->getLastDamageCause();
        if ($cause instanceof EntityDamageEvent) {
            $message = $this->getDeathMessage($cause);

            if ($message !== null) {
                // Mengganti placeholder {player} dengan nama pemain
                $message = str_replace("{player}", $player->getName(), $message);

                $event->setDeathMessage($message);
            }
        }
    }

    private function getDeathMessage(EntityDamageEvent $cause): ?string {
        $config = $this->getConfig();

        switch ($cause->getCause()) {
            case EntityDamageEvent::CAUSE_CONTACT:
                return $config->get("contact");
            case EntityDamageEvent::CAUSE_ENTITY_ATTACK:
                if ($cause instanceof EntityDamageByEntityEvent) {
                    $damager = $cause->getDamager();
                    if ($damager !== null) {
                        $message = $config->get("entity_attack");
                        return str_replace("{killer}", $damager->getName(), $message);
                    }
                }
                break;
            case EntityDamageEvent::CAUSE_PROJECTILE:
                return $config->get("projectile");
            case EntityDamageEvent::CAUSE_SUFFOCATION:
                return $config->get("suffocation");
            case EntityDamageEvent::CAUSE_FALL:
                return $config->get("fall");
            case EntityDamageEvent::CAUSE_FIRE:
                return $config->get("fire");
            case EntityDamageEvent::CAUSE_FIRE_TICK:
                return $config->get("fire_tick");
            case EntityDamageEvent::CAUSE_LAVA:
                return $config->get("lava");
            case EntityDamageEvent::CAUSE_DROWNING:
                return $config->get("drowning");
            case EntityDamageEvent::CAUSE_BLOCK_EXPLOSION:
            case EntityDamageEvent::CAUSE_ENTITY_EXPLOSION:
                return $config->get("explosion");
            case EntityDamageEvent::CAUSE_VOID:
                return $config->get("void");
            case EntityDamageEvent::CAUSE_SUICIDE:
                return $config->get("suicide");
            case EntityDamageEvent::CAUSE_MAGIC:
                return $config->get("magic");
            case EntityDamageEvent::CAUSE_CUSTOM:
                return $config->get("custom");
        }

        return null;
    }
}
