<?php declare(strict_types=1);

namespace DumpIt\Api\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/test", name="test", methods={"GET"})
 */
class TestController extends AbstractController
{
    public function __invoke(): JsonResponse
    {
        return new JsonResponse('Hello World');
    }
}