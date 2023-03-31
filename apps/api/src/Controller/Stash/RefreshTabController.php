<?php declare(strict_types=1);

namespace DumpIt\Api\Controller\Stash;

use DumpIt\Shared\Infrastructure\Bus\Command\CommandBus;
use DumpIt\StashFilter\Application\Stash\RefreshTabCommand;
use League\Fractal\Manager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/tabs/{id}/refresh', name: 'refresh_tab', methods: 'PUT')]
class RefreshTabController extends AbstractController
{
    private CommandBus $commandBus;

    private Manager $manager;

    private Security $security;

    public function __construct(CommandBus $commandBus, Manager $manager, Security $security)
    {
        $this->commandBus = $commandBus;
        $this->manager = $manager;
        $this->security = $security;
    }

    public function __invoke(Request $request, string $id): JsonResponse
    {        
        $this->commandBus->dispatch(new RefreshTabCommand($id, $this->security->getUser()->id()));

        return new JsonResponse(null, 201);
    }
}
