<?php declare(strict_types=1);

namespace DumpIt\Api\Controller\Stash;

use DumpIt\StashFilter\Application\Stash\GetTabItemsQuery;
use DumpIt\Shared\Infrastructure\Bus\Query\QueryBus;
use DumpIt\StashFilter\Domain\Stash\TabId;
use League\Fractal\Manager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/tabs/{id}/items', name: 'get_tab_items', methods: 'GET')]
class GetTabItemsController extends AbstractController
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
        $data = $this->queryBus->query(new GetTabItemsQuery(TabId::from($id)));

        $this->manager->parseIncludes($request->get('include'));

        return new JsonResponse($this->manager->createData($data));
    }
}