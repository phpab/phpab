<?php

namespace PhpAb\Analytics\Renderer;

class GoogleUniversalAnalyticsTest extends \PHPUnit_Framework_TestCase
{

    public function testGetScript()
    {
        // Arrange
        $gaRenderer = new GoogleUniversalAnalytics([
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
        $gaRenderer = new GoogleUniversalAnalytics([]);

        // Act
        $script = $gaRenderer->getScript();

        // Assert
        $this->assertSame('', $script);
    }

    public function testGetParticipations()
    {
        // Arrange
        $data = [
            'walter' => 1,
            'bernard' => 0
        ];
        $gaRenderer = new GoogleUniversalAnalytics($data);

        // Act
        $returnedData = $gaRenderer->getParticipations();

        // Assert
        $this->assertSame($data, $returnedData);
    }
}
