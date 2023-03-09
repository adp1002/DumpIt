<?php declare(strict_types=1);

namespace DumpIt\Shared\Infrastructure\Bus\Command;

use Symfony\Component\Messenger\MessageBusInterface;

class CommandBus
{
    private $messageBus;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->messageBus = $commandBus;
    }

    public function dispatch(Command $command)
    {
        return $this->messageBus->dispatch($command);
    }
}
