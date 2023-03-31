<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Application\Filter;

use DumpIt\Shared\Infrastructure\Bus\Command\CommandHandler;
use DumpIt\StashFilter\Domain\Filter\FilterRepositoryInterface;

class DeleteFilterCommandHandler implements CommandHandler
{
    private FilterRepositoryInterface $filters;
    
    public function __construct(FilterRepositoryInterface $filters)
    {
        $this->filters = $filters;
    }

    public function __invoke(DeleteFilterCommand $command): void
    {
        if (!$this->filters->canUserAccess($command->id(), $command->userId())) {
            throw new \Exception('', 404);
        } 

        $this->filters->delete($command->id());
    }
}
