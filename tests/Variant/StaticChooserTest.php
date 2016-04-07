<?php

namespace PhpAb\Variant;

class StaticChooserTest extends \PHPUnit_Framework_TestCase
{
    public function testChooseStatic()
    {
        // Arrange
        $chooser = new StaticChooser(3);

        // Act
        $result = $chooser->chooseVariant([1,2,3,4,5,6]);

        // Assert
        $this->assertEquals(4, $result);
    }
}
