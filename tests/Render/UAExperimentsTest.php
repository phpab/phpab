<?php

namespace PhpAb\Render;

class UAExperimentsTest extends \PHPUnit_Framework_TestCase
{

    public function testGetScript()
    {
        // Arrange
        $gaRenderer = new UAExperiments([
            'walter' => 1,
            'bernard' => 0
        ]);

        // Act
        $script = $gaRenderer->getScript();

        // Assert
        $this->assertSame("<script>
ga('set', 'walter', 1);
ga('set', 'bernard', 0);
</script>", $script);
    }

    public function testGetScriptEmpty()
    {
        // Arrange
        $gaRenderer = new UAExperiments([]);

        // Act
        $script = $gaRenderer->getScript();

        // Assert
        $this->assertSame("", $script);
    }

}
