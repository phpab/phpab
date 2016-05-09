<?php

/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */
require_once __DIR__ . '/../vendor/autoload.php';

use PhpAb\Storage\RuntimeAdapter;
use PhpAb\Storage\Storage;
use PhpAb\Participation\Manager;
use PhpAb\Analytics\DataCollector\Google;
use PhpAb\Event\Dispatcher;
use PhpAb\Participation\Filter\Percentage;
use PhpAb\Variant\Chooser\RandomChooser;
use PhpAb\Engine\Engine;
use PhpAb\Test\Test;
use PhpAb\Variant\SimpleVariant;
use PhpAb\Analytics\Renderer\Google\GoogleUniversalAnalytics;

$runtimeAdapter = new RuntimeAdapter();
$storage = new Storage($runtimeAdapter);

$manager = new Manager($storage);

$analyticsData = new Google();

$dispatcher = new Dispatcher();
$dispatcher->addSubscriber($analyticsData);

$filter = new Percentage(50);
$chooser = new RandomChooser();

$engine = new Engine($manager, $dispatcher, $filter, $chooser);

$test = new Test('foo_test', [], [Google::EXPERIMENT_ID => 'exp1']);
$test->addVariant(new SimpleVariant('_control'));
$test->addVariant(new SimpleVariant('_variant1'));
$test->addVariant(new SimpleVariant('_variant2'));

$test2 = new Test('bar_test', [], [Google::EXPERIMENT_ID => 'exp2']);
$test2->addVariant(new SimpleVariant('_control'));
$test2->addVariant(new SimpleVariant('_variant1'));
$test2->addVariant(new SimpleVariant('_variant2'));

// Add some tests
$engine->addTest($test);
$engine->addTest($test2);

// Pseudo: if($user->isAdmin)
// If the user is admin, he should not participate at the test
// $manager->participate('foo_test', null);
// Pseudo: if($app->inDevelopment() and $GET['phpab']['foo_test])
// $manager->participate('foo_test', $GET['phpab']['foo_test]);
// Start testing. Must occur before the EventCycle of the app starts

$engine->start();

$analytics = new GoogleUniversalAnalytics($analyticsData->getTestsData());
var_dump($analyticsData->getTestsData());
var_dump($analytics->getScript());
