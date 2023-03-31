<?php declare(strict_types=1);

namespace DumpIt\Api\Controller\Filter;

use DumpIt\Shared\Infrastructure\Bus\Command\CommandBus;
use DumpIt\StashFilter\Application\Filter\CreateFilterCommand;
use DumpIt\StashFilter\Application\Filter\EditFilterCommand;
use League\Fractal\Manager;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/filters/{id}', name: 'save_filter', methods: ['POST', 'PATCH'])]
class SaveFilterController extends AbstractController
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

    public function __invoke(Request $request, ?string $id = null): JsonResponse
    {
        $payload = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        if (null === $id) {
            $this->commandBus->dispatch(new CreateFilterCommand(
                $payload['name'],
                $this->security->getUser()->id(),
                $payload['mods'],
            ));
        } else {
            $this->commandBus->dispatch(new EditFilterCommand(
                $id,
                $payload['name'] ?? null,
                $this->security->getUser()->id(),
                $payload['mods'] ?? null,
            ));
        }

        return new JsonResponse(null, 201);
    }
}
