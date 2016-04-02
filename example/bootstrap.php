<?php

require_once __DIR__.'/../vendor/autoload.php';

// Engine and Analytics have to be implemented before this works

$engine = new \PhpAb\Engine(
    new \PhpAb\Storage\Session()
);

$test = new \PhpAb\Test(
    'foo_test',
    new \PhpAb\Participation\PercentageFilter(10),
    new \PhpAb\Variant\RandomChooser()
);

$test->addVariant(new \PhpAb\Variant\SimpleVariant('_control'));
$test->addVariant(new \PhpAb\Variant\SimpleVariant('variantA'));
$test->addVariant(new \PhpAb\Variant\SimpleVariant('variantB'));
$test->addVariant(new \PhpAb\Variant\SimpleVariant('variantC'));

// Add some tests
$engine->addTest($test);

// Start testing. Must occur before the EventCycle of the app starts
$engine->start();
