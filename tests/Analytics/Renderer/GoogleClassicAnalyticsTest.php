<?php

namespace PhpAb\Analytics\Renderer;

class GoogleClassicAnalyticsTest extends \PHPUnit_Framework_TestCase
{

    public function testGetScript()
    {
        // Arrange
        $gaRenderer = new GoogleClassicAnalytics([
            'walter' => 1,
            'bernard' => 0
        ]);

        // Act
        $script = $gaRenderer->getScript();

        // Assert
        $this->assertSame("<script>
cxApi.setChosenVariation(1, 'walter')
cxApi.setChosenVariation(0, 'bernard')
</script>", $script);
    }

    public function testGetScriptEmpty()
    {
        // Arrange
        $gaRenderer = new GoogleClassicAnalytics([]);

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
        $gaRenderer = new GoogleClassicAnalytics($data);

        // Act
        $returnedData = $gaRenderer->getParticipations();

        // Assert
        $this->assertSame($data, $returnedData);
    }
}
