<?php

require_once __DIR__.'/../vendor/autoload.php';

// Engine and Analytics have to be implemented before this works

$storage = new \PhpAb\Storage\Runtime();
$manager = new \PhpAb\Participation\Manager($storage);
$dispatcher = new \PhpAb\Event\Dispatcher();

$filter = new \PhpAb\Participation\PercentageFilter(50);
$chooser = new \PhpAb\Variant\RandomChooser();

$engine = new PhpAb\Engine\Engine($manager, $dispatcher, $filter, $chooser);

$test = new \PhpAb\Test\Test('foo_test');
$test->addVariant(new \PhpAb\Variant\SimpleVariant('_control'));
$test->addVariant(new \PhpAb\Variant\CallbackVariant('v1', function () {
    echo 'v1';
}));
$test->addVariant(new \PhpAb\Variant\CallbackVariant('v2', function () {
    echo 'v2';
}));
$test->addVariant(new \PhpAb\Variant\CallbackVariant('v3', function () {
    echo 'v3';
}));

// Add some tests
$engine->addTest($test);

// Pseudo: if($user->isAdmin)
// If the user is admin, he should not participate at the test
// $manager->participate('foo_test', null);

// Pseudo: if($app->inDevelopment() and $GET['phpab']['foo_test])
// $manager->participate('foo_test', $GET['phpab']['foo_test]);

// Start testing. Must occur before the EventCycle of the app starts
$engine->start();
