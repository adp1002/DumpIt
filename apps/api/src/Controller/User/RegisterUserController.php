<?php declare(strict_types=1);

namespace DumpIt\Api\Controller\User;
use DumpIt\Api\Repository\UserRepository;
use DumpIt\Shared\Infrastructure\Bus\Command\CommandBus;
use DumpIt\StashFilter\Application\User\RegisterUserCommand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/users/register', name: 'register_user', methods: 'POST')]
class RegisterUserController extends AbstractController
{
    private CommandBus $commandBus;

    private UserRepository $users;

    public function __construct(CommandBus $commandBus, UserRepository $users)
    {
        $this->commandBus = $commandBus;
        $this->users = $users;
    }

    public function __invoke(Request $request): Response
    {
        $username = $request->request->get('username');
        $token = $request->request->get('token');
        $type = $request->request->get('type');

        if (null === $username || null === $token) {
            throw new BadRequestException();
        }

        $envelope = $this->commandBus->dispatch(new RegisterUserCommand($username, $token, $type));

        $handledStamp = $envelope->last(HandledStamp::class);
        $user = $handledStamp->getResult();

        $this->users->registerUser($user->id(), $user->username(), $user->token());

        return new JsonResponse(null, 201);
    }
}
