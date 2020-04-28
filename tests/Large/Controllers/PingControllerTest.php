<?php

namespace Tests\Large\Controllers;

use Tests\TestCase;

class PingControllerTest extends TestCase
{
    protected function init(): void
    {
        //
    }

    public function testPing(): void
    {
        $this->get(route('ping'))
            ->assertNoContent();
    }
}