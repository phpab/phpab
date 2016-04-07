<?php

namespace PhpAb\Variant;

use phpmock\functions\FixedValueFunction;
use phpmock\Mock;
use phpmock\MockBuilder;

class RandomChooserTest extends \PHPUnit_Framework_TestCase
{

    public function testChooseVariants()
    {
        // Override mt_rand
        $builder = new MockBuilder();
        $builder->setNamespace(__NAMESPACE__)
                ->setName('mt_rand')
                ->setFunctionProvider(new FixedValueFunction(2));
        $mock = $builder->build();
        $mock->enable();

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
        $this->assertSame($variant3, $chosen);
    }

    public function testChooseVariantsWithToHeightKey()
    {
        // Override mt_rand
        $builder = new MockBuilder();
        $builder->setNamespace(__NAMESPACE__)
            ->setName('mt_rand')
            ->setFunctionProvider(new FixedValueFunction(3));
        $mock = $builder->build();
        $mock->enable();

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
        $this->assertSame($variant1, $chosen);
    }

    public function testChooseVariantsWithKeys()
    {
        // Override mt_rand
        $builder = new MockBuilder();
        $builder->setNamespace(__NAMESPACE__)
            ->setName('mt_rand')
            ->setFunctionProvider(new FixedValueFunction(0));
        $mock = $builder->build();
        $mock->enable();

        // Arrange
        $variant1 = $this->getMock(VariantInterface::class, [], ['v1']);
        $variant2 = $this->getMock(VariantInterface::class, [], ['v2']);

        $chooser = new RandomChooser();

        // Act
        $chosen = $chooser->chooseVariant([
            'Walter' => $variant1,
            'White' => $variant2,
        ]);

        // Assert
        $this->assertSame($variant1, $chosen);
    }

    public function testChooseVariantsFromEmpty()
    {
        // Arrange
        $chooser = new RandomChooser();

        // Act
        $chosen = $chooser->chooseVariant([]);

        // Assert
        $this->assertNull($chosen);
    }

    public function tearDown()
    {
        // disable all mocked functions
        Mock::disableAll();
    }
}
