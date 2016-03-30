<?php

// this is pseude code because it's not implemented yet and we do not have tests

$app = new myShinyWhateverFramework();

$engine = new \PhpAb\Engine(
    new \PhpAb\Storage\CookieStorage(),
    new \PhpAb\Analytics\GoogleExperiments('UA-asfsafsaf')
);

$test = new \PhpAb\Test(
    'foo_test',
    new \PhpAb\ParticipationStrategy\LotteryParticipationStrategy(0.1),
    new \PhpAb\VariantChooser\RandomVariantChooser()
);
$test->addVariant(new \PhpAb\DifferentThemeVariant('different_checkout_steps', $app->getEventManager()));

// Add some tests
$engine->addTest($test);

// Start testing. Must occur before the EventCycle of the app starts
$engine->start();

// Start the app
// The Events of the app fire
// e.g. EVENT_MERGE_CONFIG where our Variant listens to
$app->run();
