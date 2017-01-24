<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Chooser;

use PHPUnit_Framework_TestCase;

class IdentifierChooserTest extends PHPUnit_Framework_TestCase
{
    public function testChooseStatic()
    {
        // Arrange
        $chooser = new IdentifierChooser(3);

        // Act
        $result = $chooser->chooseVariant([1,2,3,4,5,6]);

        // Assert
        $this->assertEquals(4, $result);
    }

    public function testChooseVariantByNamedKey()
    {
        // Arrange
        $chooser = new IdentifierChooser('homer');

        // Act
        $result = $chooser->chooseVariant([
            'walter' => 'white',
            'homer' => 'simpson'
        ]);

        // Assert
        $this->assertEquals('simpson', $result);
    }

    public function testChooseStaticFails()
    {
        // Arrange
        $chooser = new IdentifierChooser(3);

        // Act
        $result = $chooser->chooseVariant([1,2]);

        // Assert
        $this->assertNull($result);
    }
}
