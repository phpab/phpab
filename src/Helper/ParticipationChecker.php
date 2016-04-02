<?php

namespace PhpAb\Helper;

use PhpAb\Event\ParticipationEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ParticipationChecker implements EventSubscriberInterface
{
    /**
     * @var ParticipationEvent[]
     */
    private $participations = [];

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            ParticipationEvent::PARTICIPATION => 'onParticipationEvent',
        ];
    }

    public function onParticipationEvent(ParticipationEvent $event)
    {
        $this->participations[$event->getTest()->getIdentifier()] = $event;
    }

    public function participatesInTest($testIdentifier)
    {
        if(isset($this->participations[$testIdentifier])) {
            return true;
        }

        return false;
    }

    public function participatesInVariant($variantIdentifier)
    {
        var_dump($this->participations);
        foreach($this->participations as $testEvent) {
            if($testEvent->getVariant()->getIdentifier() === $variantIdentifier) {
                return  true;
            }
        }

        return false;
    }

    public function participatesInVariantForTest($testIdentifier, $variantIdentifier)
    {
        if(! $this->participatesInTest($testIdentifier)) {
            return false;
        }

        $variant = $this->participations[$testIdentifier]->getVariant();
        if($variant->getIdentifier() === $variantIdentifier) {
            return true;
        }

        return false;
    }
}
