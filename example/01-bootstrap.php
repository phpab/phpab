<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */
require_once __DIR__ . '/../vendor/autoload.php';

use PhpAb\Storage\Adapter\Cookie;
use PhpAb\Storage\Storage;
use PhpAb\Participation\Manager;
use PhpAb\Analytics\DataCollector\Google;
use PhpAb\Filter\Percentage;
use PhpAb\Chooser\RandomChooser;
use PhpAb\Engine\Engine;
use PhpAb\Test\Test;
use PhpAb\Variant\SimpleVariant;
use PhpAb\Analytics\Renderer\Google\GoogleUniversalAnalytics;

// Create a Storage and its Adapter
$adapter = new Cookie('phpab');
$storage = new Storage($adapter);

// Create a Participation Manager
$manager = new Manager($storage);

$dispatcher->addSubscriber($analyticsData);

// Create a Data Collector
$analyticsData = new Google();

// Create the Engine
$engine = new Engine();
$engine->addSubscriber($analyticsData);

// Create a tests and its variants
$test = new Test('foo_test', [], [Google::EXPERIMENT_ID => 'exp1']);
$test->addVariant(new SimpleVariant('_control'));
$test->addVariant(new SimpleVariant('_variant1'));
$test->addVariant(new SimpleVariant('_variant2'));

// Add the tests to the Engine
$engine->addTest($test, new Percentage(50), new RandomChooser());

// Pseudo: if($user->isAdmin)
// If the user is admin, he should not participate at the test
// $manager->participate('foo_test', null);
// Pseudo: if($app->inDevelopment() and $_GET['phpab']['foo_test])
// $manager->participate('foo_test', $_GET['phpab']['foo_test]);
// Start testing. Must occur before the EventCycle of the app starts
// Start the engine
$engine->test($manager);

// Create the Analytics object and pass the Data Collector data to it
$analytics = new GoogleUniversalAnalytics($analyticsData->getTestsData());

// Execute the Analytics functionality
var_dump($analytics->getScript());
