<?php

namespace Titantwentyone\FilamentCMS\Commands\Composites;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PHPUnit\Framework\Constraint\FileExists;

class StubHandler
{
    private array $stubs = [];

    public function __construct(
        private array $disks,
        private $stub_disk
    ) {}

    private function writeFile($disk, $file, $contents): void
    {
        Storage::disk($disk)->put($file, $contents);
    }

    private function generateStubContent($handle, $replacements): string
    {
        $contents = $this->getStubContents($handle);

        foreach($replacements as $to_replace => $replacement) {
            $contents = Str::of($contents)->replace("{{ {$to_replace} }}", $replacement, $contents);
        }

        return $contents;
    }

    public function writeStub($disk, $handle, $destination, $replacements)
    {
        if(Storage::disk($disk)->exists($destination)) {
            throw new \Exception("file {$destination} already exists");
        }

        if(!$this->isStubRegistered($handle)) {
            throw new \Exception('stub is not registered');
        }

        $contents = $this->generateStubContent($handle, $replacements);

        $this->writeFile($disk, $destination, $contents);
    }

    /**
     * @param array $stubs array of stubs to be used where key is a handle and value is the stub location
     * @return void
     */
    public function registerStubs(array $stubs)
    {
        foreach($stubs as $handle => $stub) {
            if(!$this->stub_disk->exists($stub)) {
                throw new \Exception("The stub file {$stub} does not exist");
            }

            $this->stubs[$handle] = $stub;
        }
    }

    private function isStubRegistered($handle)
    {
        return in_array($handle, array_keys($this->stubs));
    }

    private function getStubContents($handle): string
    {
        return $this->stub_disk->get($this->stubs[$handle]);
    }
}