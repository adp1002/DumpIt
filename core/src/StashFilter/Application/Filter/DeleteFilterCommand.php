<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Application\Filter;

use DumpIt\Shared\Infrastructure\Bus\Command\Command;

class DeleteFilterCommand implements Command
{
    private string $id;

    private string $userId;

    public function __construct(string $id, string $userId)
    {
        $this->id = $id;
        $this->userId = $userId;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function userId(): string
    {
        return $this->userId;
    }
}
