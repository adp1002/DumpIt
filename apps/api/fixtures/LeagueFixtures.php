<?php declare(strict_types=1);

namespace DumpIt\Api\Fixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use DumpIt\StashFilter\Domain\Stash\league;
use DumpIt\StashFilter\Domain\User\User;

class LeagueFixtures extends Fixture
{
    private const ID = 0;
    private const REALM = 1;

    public const SSF_LEAGUE_ID = 'ssf_boatleague';
    public const TRADE_LEAGUE_ID = 'boatleague';

    public function load(ObjectManager $manager)
    {
        $leagues = [
            ['ssf_boatleague', User::PC],
            ['boatleague', User::PC],
        ];

        foreach ($leagues as $league) {
            $manager->persist(new League($league[self::ID], $league[self::REALM]));
        }

        $manager->flush();
    }
}