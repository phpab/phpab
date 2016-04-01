<?php

require __DIR__.'/../vendor/autoload.php';

$participationViewHelper = new \PhpAb\Helper\ParticipationChecker();
$googleHelper = new \PhpAb\Helper\GoogleExperimentsHelper();

$dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
$dispatcher->addSubscriber($participationViewHelper);
$dispatcher->addSubscriber($googleHelper);

$engine = new \PhpAb\Engine\Engine($dispatcher);
$engine->run();

// lets get a nicer name in our view
$abUser = $participationViewHelper;


// Then later in the View
if($abUser->participatesInVariantForTest('test1', 'variant1')) {
    echo 'yay, participates in variant1 for test1';
} elseif ($abUser->participatesInVariantForTest('test1', 'variant2') ){
    echo 'yay, participates in variant2 for test1';
} else {
    echo 'old business case';
}

echo '<br/>';
echo '<br/>';

if($abUser->participatesInVariantForTest('test2', 'variant1')) {
    echo 'yay, participates in variant1 for test2';
} else {
    echo 'old business case for test2';
}

echo '<br/>';
echo '<br/>';

// get the data from a different driver
echo $googleHelper->getScript();
