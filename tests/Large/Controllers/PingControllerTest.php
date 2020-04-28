<?php

namespace Tests\Large\Controllers;

use Tests\Large\LargeTestCase;

class PingControllerTest extends LargeTestCase
{
    public function testPing(): void
    {
        $this->get(route('ping'))
            ->assertNoContent();
    }
}