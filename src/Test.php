<?php

/**
 * @license MIT License
 * @copyright  2016 Phpab Development Team
 */

namespace Phpab\Phpab;

/**
 *
 */
class Test
{

    /**
     *
     * @var string
     */
    protected $name;

    /**
     *
     * @var int
     */
    protected $variant;

    /**
     *
     * @var VariantChooser\VariantChooserInterface
     */
    protected $variant_chooser;

    /**
     *
     * @var ParticipationStrategy\ParticipationStrategyInterface
     */
    protected $participation_strategy;

    /**
     *
     * @param string $name Name of the test
     * @param \Phpab\Phpab\VariantChooser\VariantChooserInterface $variant_chooser
     * @param \Phpab\Phpab\ParticipationStrategy\ParticipationStrategyInterface $participation_strategy
     */
    public function __construct($name, VariantChooser\VariantChooserInterface $variant_chooser, ParticipationStrategy\ParticipationStrategyInterface $participation_strategy = null) {
        
        $this->name = (string) $name;

        $this->variant_chooser = $variant_chooser;

        $this->participation_strategy = $participation_strategy;

    }

    /**
     *
     * @return mixed int|null
     */
    public function getVariant() {

        if ($this->participation_strategy) {

            if (!$this->participation_strategy->isParticipant()) {

                // In which case it will be null;
                return $this->variant;
            }
        }

        return $this->variant_chooser->chooseVariant();

    }

    /**
     *
     * @return string
     */
    function getName() {

        return $this->name;

    }

}
