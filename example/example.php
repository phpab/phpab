<?php
require '../vendor/autoload.php';

use PhpAb\AbRunner;
use PhpAb\AbTest;
use PhpAb\Analytics\AnalyticsInterface;
use PhpAb\Participation\Strategy\StrategyInterface;
use PhpAb\Storage\CookieStorage;

class BrowserStrategy implements StrategyInterface
{
    public function isParticipating(AbRunner $runner)
    {
        return strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== false;
    }
}

class Analytics implements AnalyticsInterface
{
    public function registerHit(AbTest $test)
    {
        echo 123;
    }

    public function registerExistingVisitor(AbTest $test, $choice)
    {
        echo 123;
    }

    public function registerNewVisitor(AbTest $test, $choice)
    {
        echo 456;
    }
}

class PercentageStrategy implements StrategyInterface
{
    private $percentage;

    public function __construct($percentage)
    {
        $this->percentage = $percentage;
    }

    public function isParticipating(AbRunner $runner)
    {
        $random = mt_rand() / mt_getrandmax();

        return $random <= $this->percentage;
    }
}

$callbackA = function(AbRunner $phpab, AbTest $test, $choice) {
    echo __FUNCTION__;
};

$callbackB = function(AbRunner $phpab, AbTest $test, $choice) {
    echo __FUNCTION__;
};

$phpab = new AbRunner(new \PercentageStrategy(0.1));
$phpab->setAnalytics(new Analytics());
$phpab->setStorage(new CookieStorage('abtest', 3600));
$phpab->addTest(new AbTest('My Test', $callbackA, $callbackB, null));
$phpab->test();
