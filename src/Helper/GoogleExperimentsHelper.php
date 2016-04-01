<?php

namespace PhpAb\Helper;

use PhpAb\Event\ParticipationEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class GoogleExperimentsHelper implements EventSubscriberInterface
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

    public function getScript()
    {
        $script = '&lt;script&gt;';
        $script .= '{foo: bar, do: while}';
        $script .= '&lt;/script&gt;';

        return $script;
    }

    public function getData()
    {
        // sample method. Maybe to pass it to a template-engine or so
    }

    public function whatEverFunction()
    {
        // Do whatever you want
    }
}
