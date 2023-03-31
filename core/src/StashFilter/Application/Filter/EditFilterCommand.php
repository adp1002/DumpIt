<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Application\Filter;

use DumpIt\Shared\Infrastructure\Bus\Command\Command;

class EditFilterCommand implements Command
{
    private string $id;

    private ?string $name;

    private string $userId;

    private ?array $mods;

    public function __construct(string $id, ?string $name, string $userId, ?array $mods)
    {
        $this->id = $id;
        $this->name = $name;
        $this->userId = $userId;
        $this->mods = $mods;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): ?string
    {
        return $this->name;
    }

    public function userId(): string
    {
        return $this->userId;
    }
    
    public function mods(): ?array
    {
        return $this->mods;
    }
}
