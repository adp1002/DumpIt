<?php declare(strict_types=1);

namespace DumpIt\Api\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        $request   = $event->getRequest();

        if ('json' === $request->getContentTypeFormat()) {
            $response = $this->createApiResponse($exception);
            $event->setResponse($response);
        }
    }

    private function createApiResponse(\Exception $exception)
    {
        $statusCode = $exception instanceof HttpExceptionInterface ? $exception->getStatusCode() : $exception->getCode();

        //TODO make this better
        return new JsonResponse(null, $statusCode);
    }
}
