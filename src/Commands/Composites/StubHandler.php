<?php

namespace Titantwentyone\FilamentCMS\Commands\Composites;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;
use PHPUnit\Framework\Constraint\FileExists;

class StubHandler
{
    private array $stubs = [];

    public function __construct(
        private array $disks,
        private $stub_disk
    ) {}

    private function writeFile(string $disk, string $file, string $contents): void
    {
        Storage::disk($disk)->put($file, $contents);
    }

    private function generateStubContent(string $handle, array $replacements = []): string
    {
        $contents = $this->getStubContents($handle);

        $replacements = collect($replacements);

//        foreach($replacements as $to_replace => $replacement) {
//            $contents = Str::of($contents)->replace("{{ {$to_replace} }}", $replacement, $contents);
//        }

        $replacements->each(function($replacement, $to_replace) use (&$contents) {
            $contents = Str::of($contents)->replace("{{ {$to_replace} }}", $replacement, $contents);
        });

        return $contents;
    }

    public function writeStub(string $disk, string $handle, string $destination, array $replacements)
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
     * @param string[] $stubs array of stubs to be used where key is a handle and value is the stub location
     * @return void
     */

    public function registerStubs(array $stubs): void
    {
        $stubs = collect($stubs);

        if(!$stubs->count()) {
            throw new \Exception('The array cannot be empty');
        }

        $stubs->each(function($stub, $handle) {
            $this->isValidStub($stub);
            $this->stubs[$handle] = $stub;
        });
    }

    private function isValidStub($stub): void
    {
        if(!is_string($stub)) {
            throw new \Exception('Stubs must be file paths given as strings');
        }

        if(!$this->stub_disk->exists($stub)) {
            throw new \Exception("The stub file {$stub} does not exist");
        }
    }

    private function isStubRegistered(string $handle)
    {
        return in_array($handle, array_keys($this->stubs));
    }

    private function getStubContents(string $handle): string
    {
        return $this->stub_disk->get($this->stubs[$handle]);
    }
}