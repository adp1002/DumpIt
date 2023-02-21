<?php declare(strict_types=1);

namespace DumpIt\Shared\Domain;

class AggregateId
{
    private string|int $id;

    public static function from(string|int $id): self
    {
        return new static($id);
    }

    private function __construct(string|int $id)
    {
        $this->id = $id;
    }

    public function id(): string|int
    {
        return $this->id;
    }
}