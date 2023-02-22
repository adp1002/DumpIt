<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Application\Filter;

use DumpIt\Shared\Infrastructure\Bus\Command\Command;

class CreateFilterCommand implements Command
{
    private string $name;

    private array $mods;

    private string $userId;

    public function __construct(string $name, array $mods, string $userId)
    {
        $this->name = $name;
        $this->mods = $mods;
        $this->userId = $userId;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function mods(): array
    {
        return $this->mods;
    }

    public function userId(): string
    {
        return $this->userId;
    }
}
