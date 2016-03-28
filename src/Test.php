<?php

namespace PhpAb;

use InvalidArgumentException;
use PhpAb\Exception\ChoiceNotFoundException;
use PhpAb\Participation\Strategy\StrategyInterface;

/**
 * The definition of a test.
 */
class Test implements TestInterface
{
    /**
     * The name of the test.
     *
     * @var string
     */
    private $name;

    /**
     * The callback that should be executed when the A-case is executed.
     *
     * @var callback
     */
    private $control;

    /**
     * The callback that should be executed when the B-case is executed.
     *
     * @var callback
     */
    private $variants;

    /**
     * @var StrategyInterface
     */
    private $participationStrategy;

    /**
     * @var \PhpAb\VariantChooserInterface
     */
    private $variantChooser;

    /**
     * Initializes a new instance of this class.
     *
     * @param string $name The name of the test case.
     * @param callable $control The A-case callback.
     * @param callable[] $variants All possible variants.
     * @param StrategyInterface $participationStrategy The strategy that decides the case to execute.
     * @param VariantChooserInterface $variantChooser The VariantChooser decides which of the variants is chosen
     */
    public function __construct($name, $control, $variants, StrategyInterface $participationStrategy, VariantChooserInterface $variantChooser)
    {
        if (! is_callable($control)) {
            throw new InvalidArgumentException('Control is not callable.');
        }

        if (! count($variants)) {
            throw new ChoiceNotFoundException('There must be at least one possible choice.');
        }

        foreach ($variants as $k=>$variant) {
            if (!is_callable($variant)) {
                throw new InvalidArgumentException('Callback '.$k.' is not callable.');
            }
        }

        $this->name = $name;
        $this->control = $control;
        $this->variants = $variants;
        $this->participationStrategy = $participationStrategy;
        $this->variantChooser = $variantChooser;
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function getControlVariant()
    {
        return $this->control;
    }


    /**
     * @inheritDoc
     */
    public function getVariant($choice)
    {
        $variants = $this->getVariants();

        if (! isset($variants[$choice])) {
            throw new ChoiceNotFoundException('The choice "' . $choice . '" is not allowed in ABTest. Only [A,B] are allowed');
        }

        return $variants[$choice];
    }

    public function getVariants()
    {
        return array_merge(
            ['A' => $this->control],
            $this->variants
        );
    }

    /**
     * @inheritDoc
     */
    public function getParticipationStrategy()
    {
        return $this->participationStrategy;
    }

    /**
     * @inheritDoc
     */
    public function getVariantChooser()
    {
        return $this->variantChooser;
    }
}
