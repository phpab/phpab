<?php

namespace Phpab\Phpab\TestDummy;

use Phpab\Phpab\Exception\TestExecutionException;

class DummyCallbackClass
{

    private $result;

    public function __construct($result)
    {
        $this->result = $result;
    }

    public function callbackMethod()
    {
        return $this->result;
    }

    public function failingCallbackMethod()
    {
        throw new TestExecutionException();
    }
}
