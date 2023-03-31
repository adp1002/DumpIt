<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Application\Filter;

use DumpIt\Shared\Infrastructure\Bus\Command\Command;

class CreateFilterCommand implements Command
{
    private string $name;

    private string $userId;
    
    private array $mods;

    public function __construct(string $name, string $userId, array $mods)
    {
        $this->name = $name;
        $this->userId = $userId;
        $this->mods = $mods;
    }

    public function name(): string
    {
        return $this->name;
    }
    
    public function userId(): string
    {
        return $this->userId;
    }

    public function mods(): array
    {
        return $this->mods;
    }
}
