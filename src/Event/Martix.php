<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Event;

use Ghostwriter\Compliance\Service\Job;
use Ghostwriter\Json\Interface\JsonInterface;
use Throwable;

final class Martix
{
    /**
     * @param array<string>                                                                                                             $exclude
     * @param array<array{name:string,command:string,extensions:list<string>,os:string,php:string,dependency:string,experimental:bool}> $include
     */
    public function __construct(
        public JsonInterface $json,
        public array $include = [],
        public array $exclude = [],
    ) {}

    /**
     * @param array<string> $matrices
     */
    public function exclude(array $matrices): void
    {
        foreach ($matrices as $matrix) {
            $this->exclude[] = $matrix;
        }
    }

    /**
     * @throws Throwable
     */
    public function toString(): string
    {
        if ($this->include === []) {
            $this->include(Job::noop());
        }

        return $this->json->encode([
            'include' => $this->include,
            'exclude' => $this->exclude,
        ]);
    }

    public function include(Job $job): void
    {
        $this->include[] = $job->toArray();
    }
}
