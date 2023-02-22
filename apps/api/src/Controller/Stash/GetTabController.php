<?php declare(strict_types=1);

namespace DumpIt\Api\Controller\Stash;

use DumpIt\StashFilter\Application\Stash\GetTabsQuery;
use DumpIt\Shared\Infrastructure\Bus\Query\QueryBus;
use League\Fractal\Manager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/tabs/{id}', name: 'get_tab', methods: 'GET')]
class GetTabController extends AbstractController
{
    private QueryBus $queryBus;

    private Manager $manager;

    public function __construct(QueryBus $queryBus, Manager $manager)
    {
        $this->queryBus = $queryBus;
        $this->manager = $manager;
    }

    public function __invoke(Request $request, string $id): JsonResponse
    {
        //TODO add user auth
        // $data = $this->queryBus->query(new GetTabsQuery());

        return new JsonResponse(/* $this->manager->createData($data) */);
    }
}
