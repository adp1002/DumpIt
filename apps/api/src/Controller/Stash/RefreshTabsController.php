<?php declare(strict_types=1);

namespace DumpIt\Api\Controller\Stash;

use DumpIt\Shared\Infrastructure\Bus\Command\CommandBus;
use DumpIt\StashFilter\Application\Stash\RefreshTabsCommand;
use League\Fractal\Manager;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/tabs/refresh', name: 'refresh_tabs', methods: 'POST')]
class RefreshTabsController extends AbstractController
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

    public function __invoke(Request $request): JsonResponse
    {
        $payload = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        
        $this->commandBus->dispatch(new RefreshTabsCommand($this->security->getUser()->id(), $payload['leagueId']));

        return new JsonResponse(null, 201);
    }
}
