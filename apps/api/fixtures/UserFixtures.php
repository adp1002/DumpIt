<?php declare(strict_types=1);

namespace DumpIt\Api\Fixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use DumpIt\Api\Repository\UserRepository;
use DumpIt\StashFilter\Domain\User\User;
use DumpIt\Api\Entity\User as ApiUser;

class UserFixtures extends Fixture
{
    private const ID = 0;
    private const USERNAME = 1;
    private const REALM = 2;
    private const TOKEN = 3;
    private const TYPE = 4;
    private const PASSWORD = '1234';

    public const MAIN_USER_ID = '5e1f443d-d260-4323-851e-723df89c6400';
    public const SECOND_USER_ID = 'cc66aea3-0dc1-4591-bb55-30ab724cc469';

    private UserRepository $apiUsers;

    public function __construct(UserRepository $apiUsers)
    {
        $this->apiUsers = $apiUsers;
    }

    public function load(ObjectManager $manager)
    {
        $users = [
            [self::MAIN_USER_ID, 'Gigachad', User::PC, self::PASSWORD, User::POESESSID],
            [self::SECOND_USER_ID, 'Tradecuck', User::PC, self::PASSWORD, User::POESESSID],
        ];

        foreach ($users as $user) {
            $manager->persist(
                new User(
                    $user[self::ID],
                    $user[self::USERNAME],
                    $user[self::REALM],
                    $user[self::TOKEN],
                    $user[self::TYPE],
                )
            );

            $apiUser = $manager->find(ApiUser::class, $user[self::ID]);

            if (null !== $apiUser) {
                $manager->remove($apiUser);
                $manager->flush();
            }

            $this->apiUsers->registerUser($user[self::ID], $user[self::USERNAME], self::PASSWORD);
        }
    }
}
