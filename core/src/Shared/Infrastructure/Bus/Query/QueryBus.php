<?php declare(strict_types=1);

namespace DumpIt\Shared\Infrastructure\Bus\Query;

use League\Fractal\Resource\ResourceAbstract;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class QueryBus
{
    use HandleTrait;

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->messageBus = $queryBus;
    }

    public function query(Query $query): ResourceAbstract
    {
        return $this->handle($query);
    }
}
