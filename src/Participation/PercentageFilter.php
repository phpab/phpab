<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Participation;

/**
 * A filter that acts based on a percentage.
 *
 * @package PhpAb
 */
class PercentageFilter implements FilterInterface
{
    /**
     * The chance of allowing a user to participate in a test.
     * This is a value between 0 and 100.
     *
     * @var int
     */
    private $propability;

    /**
     * Initializes a new instance of this class.
     *
     * @param int $propability The probability for the lottery in percent.
     * Should be 0 <=> 100
     * 0 is lowest probability for participation
     * 100 is the highest probability for participation
     */
    public function __construct($propability)
    {
        // ensure that we have a float since we cannot typehint
        // it in the constructor for PHP versions < 7
        if (! is_int($propability)) {
            throw new \InvalidArgumentException('The propability must be of type int.'.gettype($propability).' given');
        }

        if ($propability < 0 || $propability > 100) {
            throw new \InvalidArgumentException('the probability must be 0 <=> 100');
        }

        $this->propability = $propability;
    }


    /**
     * {@inheritDoc}
     */
    public function shouldParticipate()
    {
        $propability = $this->propability;

        if (100 === $propability) {
            return true;
        }

        if (0 === $propability) {
            // since we allow 0 as a value we have to check for it
            // to prevent division by zero error.
            return false;
        }

        return mt_rand(0, 100) <= $propability;
    }
}
