<?php declare(strict_types=1);

namespace DumpIt\Api\Fixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use DumpIt\StashFilter\Domain\Stash\Tab;

class TabFixtures extends Fixture implements DependentFixtureInterface
{
    private const ID = 0;
    private const NAME = 1;
    private const INDEX = 2;
    private const LEAGUE_ID = 3;
    private const USER_ID = 4;
    private const DATETIME = 5;

    public const DUMP_TAB_ID = '1';
    public const BUILD_TAB_ID = '2';
    public const TRADE_TAB_ID = '3';

    public function load(ObjectManager $manager)
    {
        $tabs = [
            [self::DUMP_TAB_ID, 'DumpTab', 0, LeagueFixtures::SSF_LEAGUE_ID, UserFixtures::MAIN_USER_ID, new  \DateTime()],
            [self::BUILD_TAB_ID, 'BuildItems', 1, LeagueFixtures::SSF_LEAGUE_ID, UserFixtures::MAIN_USER_ID, new  \DateTime()],
            [self::TRADE_TAB_ID, 'TradeTab', 0, LeagueFixtures::SSF_LEAGUE_ID, UserFixtures::SECOND_USER_ID, new  \DateTime()],
        ];

        foreach ($tabs as $tab) {
            $manager->persist(new Tab($tab[self::ID], $tab[self::NAME], $tab[self::INDEX], $tab[self::LEAGUE_ID], $tab[self::USER_ID], $tab[self::DATETIME]));
        }

        $manager->flush();
    }

	public function getDependencies() {
        return [UserFixtures::class, LeagueFixtures::class];
	}
}