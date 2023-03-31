<?php declare(strict_types=1);

namespace DumpIt\Api\Controller\Stash;

use DumpIt\Shared\Infrastructure\Bus\Query\QueryBus;
use DumpIt\StashFilter\Application\Stash\GetTabQuery;
use League\Fractal\Manager;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/tabs/{id}', name: 'get_tab', methods: 'GET')]
class GetTabController extends AbstractController
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
        $include = $request->get('include');

        $data = $this->queryBus->query(new GetTabQuery($id, $this->security->getUser()->id()));

        if (null !== $include) {
            $this->manager->parseIncludes($include);
        }

        return new JsonResponse($this->manager->createData($data));
    }
}