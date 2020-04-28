<?php

namespace Tests\Small;

use Tests\TestCase;

abstract class SmallTestCase extends TestCase
{
    abstract protected function init();
}