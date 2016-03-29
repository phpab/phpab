<?php

namespace Phpab\Phpab;

class DifferentThemeVariant implements VariantInterface
{

    private $eventManager;

    /**
     * DifferentThemeVariant constructor.
     *
     * @param $eventManager Event manager. E.G. from ZEND
     */
    public function __construct($eventManager)
    {
        $this->eventManager = $eventManager;
    }

    /**
     * @inheritDoc
     */
    public function getIdentifier()
    {
        // TODO: Implement getIdentifier() method.
    }

    /**
     * @inheritDoc
     */
    public function run()
    {
        // TODO: Zend example
        $this->eventManager->attach(ModuleEvent__EVENT_MERGE_CONFIG, array($this, 'onMergeConfig'));
    }

}
