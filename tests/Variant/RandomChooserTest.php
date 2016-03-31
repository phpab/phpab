<?php

namespace PhpAb\Variant;

/**
 * Override array_rand from global namespace
 *
 * @return int
 */
function array_rand()
{
    return 1;
}

class RandomChooserTest extends \PHPUnit_Framework_TestCase
{

    public function testChooseVariants()
    {
        // Arrange
        $variant1 = $this->getMock(VariantInterface::class, [], ['v1']);
        $variant2 = $this->getMock(VariantInterface::class, [], ['v2']);
        $variant3 = $this->getMock(VariantInterface::class, [], ['v3']);

        $chooser = new RandomChooser();

        // Act
        $chosen = $chooser->chooseVariant([
            $variant1,
            $variant2,
            $variant3,
        ]);

        // Assert
        $this->assertSame($variant2, $chosen);
    }

    public function testChooseVariantsFromEmpty()
    {
        // Arrange
        $chooser = new RandomChooser();

        // Act
        $chosen = $chooser->chooseVariant([]);

        // Assert
        $this->assertEquals(null, $chosen);
    }
}
