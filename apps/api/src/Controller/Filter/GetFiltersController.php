<?php declare(strict_types=1);

namespace DumpIt\Api\Controller\Filter;

use DumpIt\Shared\Infrastructure\Bus\Query\QueryBus;
use DumpIt\StashFilter\Application\Filter\GetFiltersQuery;
use League\Fractal\Manager;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/filters', name: 'get_filters', methods: ['GET'])]
class GetFiltersController extends AbstractController
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
        $include = $request->get('include');

        $filter = $this->queryBus->query(new GetFiltersQuery($this->security->getUser()->id()));

        if (null !== $include) {
            $this->manager->parseIncludes($include);
        }

        return new JsonResponse($this->manager->createData($filter), 201);
    }
}
