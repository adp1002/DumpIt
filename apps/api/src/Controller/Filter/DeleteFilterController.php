<?php declare(strict_types=1);

namespace DumpIt\Api\Controller\Filter;

use DumpIt\Shared\Infrastructure\Bus\Command\CommandBus;
use DumpIt\StashFilter\Application\Filter\DeleteFilterCommand;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/filters/{id}', name: 'delete_filter', methods: ['DELETE'])]
class DeleteFilterController extends AbstractController
{
    private CommandBus $commandBus;

    private Security $security;

    public function __construct(CommandBus $commandBus, Security $security)
    {
        $this->commandBus = $commandBus;
        $this->security = $security;
    }

    public function __invoke(Request $request, string $id): JsonResponse
    {
        $this->commandBus->dispatch(new DeleteFilterCommand($id, $this->security->getUser()->id()));

        return new JsonResponse(null, 201);
    }
}
