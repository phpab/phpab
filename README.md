# phpab

[![Build Status](https://travis-ci.org/phpab/phpab.svg?branch=master)](https://travis-ci.org/phpab/phpab)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/phpab/phpab/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/phpab/phpab/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/phpab/phpab/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/phpab/phpab/?branch=master)
[![Dependency Status](https://www.versioneye.com/user/projects/553543707f43bc3f4400001c/badge.svg?style=flat)](https://www.versioneye.com/user/projects/553543707f43bc3f4400001c)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/601290d3-b870-46f8-bceb-bdaaa3e808e3/mini.png)](https://insight.sensiolabs.com/projects/601290d3-b870-46f8-bceb-bdaaa3e808e3)

This is a PHP library to implement A/B testing.

## Features

* Very generic setup of the library:
    * Supports web applications.
    * Supports console applications.
    * Complete freedom in how to handle tests.
    * Simply provide two callbacks that should be executed. One for your A-test and one for your B-test.
* Participation management
    * Let people participate based on your own choices.
    * Choose per test how to handle participants.
    * Provides complete freedom, simply create a class that implements `PhpAb\Participation\Strategy\StrategyInterface`.
* Identify users
    * Provides a CookieStorage to store the choice in a cookie.
    * Provides a SessionStorage to store the choice in the current session.
    * Provides complete freedom, simply create a class that implements `PhpAb\Storage\StorageInterface`.
* Analytics
    * Recognizes existing visitors and new visitors.
    * Provides complete freedom, simply create a class that implements `PhpAb\Analytics\AnalyticsInterface`.
* Unit tested

## Requirements

This library runs on PHP 5.3, PHP 5.4, PHP 5.5, PHP 5.6, PHP 7 and HHVM.

## Installation

It's recommended to install this library via [Composer](https://getcomposer.org).

```json
{
    "require": {
        "phpab/phpab": "dev-master"
    }
}
```

The current master branch is considered stable. The badges on top of this document should confirm this.

## Getting started

Take a look at example/example.php for a complete example.
