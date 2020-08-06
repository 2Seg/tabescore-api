<?php

namespace App\Orchestrators;

interface OrchestratorInterface
{
    /**
     * Orchestrate the logic between components
     *
     * @param  array $data
     * @return array
     */
    public function orchestrate(array $data);
}