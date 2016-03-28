<?php
/**
 * This file is part of phpab/phpab (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE MIT
 */

require '../vendor/autoload.php';

use PhpAb\AbRunner;
use PhpAb\AbTest;
use PhpAb\Analytics\AnalyticsInterface;
use PhpAb\Participation\Strategy\StrategyInterface;
use PhpAb\RunnerInterface;
use PhpAb\Storage\CookieStorage;
use PhpAb\TestInterface;

class BrowserStrategy implements StrategyInterface
{
    public function isParticipating(RunnerInterface $runner)
    {
        // Only execute in Chrome.
        return strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== false;
    }
}

class Analytics implements AnalyticsInterface
{
    public function registerHit(TestInterface $test)
    {
        echo 'Registering a new hit for test "' . $test->getName() . '".<br />';
    }

    public function registerExistingVisitor(TestInterface $test, $choice)
    {
        echo 'Registering choice "' . $choice . '" in test "' . $test->getname() . '" for existing visitor.<br />';
    }

    public function registerNewVisitor(TestInterface $test, $choice)
    {
        echo 'Registering choice "' . $choice . '" in test "' . $test->getname() . '" for new visitor.<br />';
    }
}

class PercentageStrategy implements StrategyInterface
{
    private $percentage;

    public function __construct($percentage)
    {
        $this->percentage = $percentage;
    }

    public function isParticipating(RunnerInterface $runner)
    {
        $random = mt_rand() / mt_getrandmax();

        return $random <= $this->percentage;
    }
}

$callbackA = function (AbRunner $phpab, AbTest $test, $choice) {
    echo 'Executing test A<br />';
};

$callbackB = function (AbRunner $phpab, AbTest $test, $choice) {
    echo 'Executing test B<br />';
};

$phpab = new AbRunner(new \PercentageStrategy(1.0));
$phpab->setAnalytics(new Analytics());
$phpab->setStorage(new CookieStorage('abtest', 3600));
$phpab->addTest(new AbTest('My Test', $callbackA, $callbackB, null));
$phpab->test();
