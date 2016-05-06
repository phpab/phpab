<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Analytics\Renderer\Google;

use PHPUnit_Framework_TestCase;

class GoogleUniversalAnalyticsTest extends PHPUnit_Framework_TestCase
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
cxApi.setChosenVariation(1, 'walter');
cxApi.setChosenVariation(0, 'bernard');
ga('send', 'event', 'PhpAb', 'testRun', 'testsAmout', 2);
</script>", $script);
    }

    public function testGetScriptWithApiClient()
    {
        // Arrange
        $gaRenderer = new GoogleUniversalAnalytics([
            'walter' => 1,
            'bernard' => 0
        ]);
        $gaRenderer->setApiClientInclusion(true);

        // Act
        $script = $gaRenderer->getScript(true);

        // Assert
        $this->assertSame("<script src=\"//www.google-analytics.com/cx/api.js\"></script>
<script>
cxApi.setChosenVariation(1, 'walter');
cxApi.setChosenVariation(0, 'bernard');
ga('send', 'event', 'PhpAb', 'testRun', 'testsAmout', 2);
</script>", $script);
    }

    public function testGetScriptWithApiClientWithoutEvent()
    {
        // Arrange
        $gaRenderer = new GoogleUniversalAnalytics([
            'walter' => 1,
            'bernard' => 0
        ]);
        $gaRenderer->setApiClientInclusion(true);
        $gaRenderer->setEventTriggerInclusion(false);

        // Act
        $script = $gaRenderer->getScript(true);

        // Assert
        $this->assertSame("<script src=\"//www.google-analytics.com/cx/api.js\"></script>
<script>
cxApi.setChosenVariation(1, 'walter');
cxApi.setChosenVariation(0, 'bernard');
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
