<?php declare(strict_types=1);

namespace DumpIt\Api\Fixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use DumpIt\StashFilter\Domain\Filter\Filter;
use DumpIt\StashFilter\Domain\Filter\FilterMod;
use DumpIt\StashFilter\Domain\Stash\ModRepositoryInterface;
use DumpIt\StashFilter\Domain\Stash\TabRepositoryInterface;

class FilterFixtures extends Fixture implements DependentFixtureInterface
{
    private const ID = 0;
    private const NAME = 1;
    private const USER_ID = 2;
    private const MODS = 3;

    private const FILTER_ID = 0;
    private const MOD_ID = 1;
    private const VALUES = 2;
    private const CONDITION = 3;

    private const LIFE_FILTER_ID = 'dc3f0458-cb7f-4fe7-8991-8e9a45ae9d42';
    private const PHYS_FILTER_ID = '6f8d4670-03b9-499e-bf23-05f6123eea2e';
    private const GARBAGE_FILTER_ID = 'bf6a7fe2-2d47-4216-905b-b5f2361bde3d';

    private ModRepositoryInterface $mods;

    private $tabs;

    public function __construct(ModRepositoryInterface $mods, TabRepositoryInterface $tabs)
    {
        $this->mods = $mods;
        $this->tabs = $tabs;
    }

    public function load(ObjectManager $manager)
    {
        $filterEntities = [];

        $filters = [
            [self::LIFE_FILTER_ID, 'Life filter', UserFixtures::MAIN_USER_ID, []],
            [self::PHYS_FILTER_ID, 'Phys weapon filter', UserFixtures::MAIN_USER_ID, []],
            [self::GARBAGE_FILTER_ID, 'Garbage filter', UserFixtures::SECOND_USER_ID, []],
        ];

        foreach ($filters as $filter) {
            $filterEntities[$filter[self::ID]] = new Filter(
                $filter[self::ID],
                $filter[self::NAME],
                $filter[self::USER_ID],
                $filter[self::MODS]
            );

            $manager->persist($filterEntities[$filter[self::ID]]);
        }

        $manager->flush();

        $modEntities = [];

        foreach ($this->mods->findAll() as $mod) {
            $modEntities[$mod->id()] = $mod;
        }

        $filtersMods = [
            [
                [self::LIFE_FILTER_ID, ModFixtures::LIFE_MOD_ID, [82], FilterMod::GREATER_THAN_OR_EQUAL],
            ],
            [
                [self::PHYS_FILTER_ID, ModFixtures::PHYS_MOD_ID, [160], FilterMod::GREATER_THAN_OR_EQUAL],
                [self::PHYS_FILTER_ID, ModFixtures::FLAT_PHYS_MOD_ID, [64, 86], FilterMod::GREATER_THAN_OR_EQUAL],
                [self::PHYS_FILTER_ID, ModFixtures::AS_MOD_ID, [24], FilterMod::GREATER_THAN_OR_EQUAL],
            ],
            [
                [self::GARBAGE_FILTER_ID, ModFixtures::COLD_RES_MOD_ID, [12], FilterMod::GREATER_THAN_OR_EQUAL],
            ],
        ];

        foreach ($filtersMods as $filterMods) {
            $filterModEntities = [];
            
            foreach ($filterMods as $filterMod){
                $filterModEntity = new FilterMod(
                    $filterEntities[$filterMod[self::FILTER_ID]],
                    $modEntities[$filterMod[self::MOD_ID]],
                    $filterMod[self::VALUES],
                    $filterMod[self::CONDITION]
                );

                $filterModEntities[] = $filterModEntity;

                $manager->persist($filterModEntity);
            }

            $filterEntities[$filterMods[0][self::FILTER_ID]]->changeMods($filterModEntities);
        }

        $manager->flush();
    }

	public function getDependencies() {
        return [ModFixtures::class];
	}
}