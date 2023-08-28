<?php declare(strict_types=1);

namespace DumpIt\Api\Fixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use DumpIt\StashFilter\Domain\Stash\Item;
use DumpIt\StashFilter\Domain\Stash\ItemMod;
use DumpIt\StashFilter\Domain\Stash\ModRepositoryInterface;
use DumpIt\StashFilter\Domain\Stash\TabRepositoryInterface;

class ItemFixtures extends Fixture implements DependentFixtureInterface
{
    private const ID = 0;
    private const NAME = 1;
    private const ILVL = 2;
    private const BASETYPE = 3;
    private const TAB_ID = 4;
    private const MODS = 5;

    private const ITEM_ID = 0;
    private const MOD_ID = 1;
    private const VALUES = 2;

    private const AMULET_ITEM_ID = '1';
    private const TWO_HANDER_ITEM_ID = '2';
    private const BELT_ITEM_ID = '3';

    private ModRepositoryInterface $mods;

    private $tabs;

    public function __construct(ModRepositoryInterface $mods, TabRepositoryInterface $tabs)
    {
        $this->mods = $mods;
        $this->tabs = $tabs;
    }

    public function load(ObjectManager $manager)
    {
        $tabs = [];

        foreach ($this->tabs->findAll() as $tab) {
            $tabs[$tab->id()] = $tab;
        }

        $itemEntities = [];
        $items = [
            [self::AMULET_ITEM_ID, 'Amulet', 84, 'Citrine Amulet', TabFixtures::DUMP_TAB_ID, new  \DateTime()],
            [self::TWO_HANDER_ITEM_ID, '2Hander', 86, 'Karui Chopper', TabFixtures::BUILD_TAB_ID, new  \DateTime()],
            [self::BELT_ITEM_ID, 'Headhunter', 38, 'Leather Belt', TabFixtures::TRADE_TAB_ID, new  \DateTime()],
        ];

        foreach ($items as $item) {
            $itemEntities[$item[self::ID]] = new Item($item[self::ID], $item[self::NAME], $item[self::ILVL], $item[self::BASETYPE], $tabs[$item[self::TAB_ID]], []);
            $manager->persist($itemEntities[$item[self::ID]]);
        }

        $manager->flush();

        $modEntities = [];

        foreach ($this->mods->findAll() as $mod) {
            $modEntities[$mod->id()] = $mod;
        }

        $itemsMods = [
            [
                [self::AMULET_ITEM_ID, ModFixtures::COLD_RES_MOD_ID, [30]],
                [self::AMULET_ITEM_ID, ModFixtures::DEX_MOD_ID, [12]],
                [self::AMULET_ITEM_ID, ModFixtures::LIFE_MOD_ID, [82]],
            ],
            [
                [self::TWO_HANDER_ITEM_ID, ModFixtures::PHYS_MOD_ID, [160]],
                [self::TWO_HANDER_ITEM_ID, ModFixtures::FLAT_PHYS_MOD_ID, [64, 86]],
                [self::TWO_HANDER_ITEM_ID, ModFixtures::AS_MOD_ID, [24]],
            ],
            [
                [self::BELT_ITEM_ID, ModFixtures::LIFE_MOD_ID, [30]],
            ],
        ];

        foreach ($itemsMods as $itemMods) {
            $itemModEntities = [];
            
            foreach ($itemMods as $itemMod){
                $itemModEntity = new ItemMod($itemEntities[$itemMod[self::ITEM_ID]], $modEntities[$itemMod[self::MOD_ID]], $itemMod[self::VALUES]);

                $itemModEntities[] = $itemModEntity;

                $manager->persist($itemModEntity);
            }

            $itemEntities[$itemMods[0][self::ITEM_ID]]->changeMods($itemModEntities);
        }

        $manager->flush();
    }

	public function getDependencies() {
        return [TabFixtures::class, ModFixtures::class];
	}
}
