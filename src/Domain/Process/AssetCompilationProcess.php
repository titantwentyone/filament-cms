<?php

namespace Titantwentyone\FilamentCMS\Domain\Process;

use Symfony\Component\Process\Process;

class AssetCompilationProcess
{
    private Process $process;

    public function __construct(Process $process)
    {
        $this->process = $process;
    }

    public function get()
    {
        return $this->process;
    }
}