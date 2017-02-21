<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */
require_once __DIR__ . '/../vendor/autoload.php';

use PhpAb\Storage\Storage;
use PhpAb\Filter\Percentage;
use PhpAb\Chooser\RandomChooser;
use PhpAb\Engine\Engine;
use PhpAb\Test\Test;
use PhpAb\Variant\SimpleVariant;

// Create the tested subject
$user = new \PhpAb\Subject(new \PhpAb\Storage\CookieStorage('phpab'));

// Create the Engine
$engine = new Engine(new \PhpAb\Analytics\SimpleAnalytics());

// Create a tests and its variants
$test = new Test(
    'foo_test',
        [
            new SimpleVariant('control'),
            new SimpleVariant('variant1'),
            new SimpleVariant('variant2')
        ]
);

// Add the tests to the Engine
$engine->addTest($test, new Percentage(50), new RandomChooser());

// Start the engine
$engine->test($user);