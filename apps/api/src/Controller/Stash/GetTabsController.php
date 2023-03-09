<?php declare(strict_types=1);

namespace DumpIt\Api\Controller\Stash;

use DumpIt\StashFilter\Application\Stash\GetTabsQuery;
use DumpIt\Shared\Infrastructure\Bus\Query\QueryBus;
use League\Fractal\Manager;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/tabs', name: 'get_tabs', methods: 'GET')]
class GetTabsController extends AbstractController
{
    private QueryBus $queryBus;

    private Manager $manager;

    private Security $security;

    public function __construct(QueryBus $queryBus, Manager $manager, Security $security)
    {
        $this->queryBus = $queryBus;
        $this->manager = $manager;
        $this->security = $security;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $data = $this->queryBus->query(new GetTabsQuery($this->security->getUser()->id()));

        return new JsonResponse($this->manager->createData($data));
    }
}
