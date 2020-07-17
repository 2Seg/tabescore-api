<?php

namespace App\Orchestrators;

interface OrchestratorInterface
{
    /**
     * Orchestrate the logic between components
     *
     * @param  array $data
     * @return mixed
     */
    public function orchestrate(array $data);
}