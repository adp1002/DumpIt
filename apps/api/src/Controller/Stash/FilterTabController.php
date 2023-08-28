<?php declare(strict_types=1);

namespace DumpIt\Api\Controller\Stash;

use DumpIt\Shared\Infrastructure\Bus\Query\QueryBus;
use DumpIt\StashFilter\Application\Stash\FilterTabQuery;
use League\Fractal\Manager;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/tabs/{id}/filter', name: 'filter_tab', methods: 'POST')]
class FilterTabController extends AbstractController
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

    public function __invoke(Request $request, string $id): JsonResponse
    {
        $payload = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $data = $this->queryBus->query(new FilterTabQuery($id, $this->security->getUser()->id(), $payload['filters']));

        return new JsonResponse($this->manager->createData($data));
    }
}
