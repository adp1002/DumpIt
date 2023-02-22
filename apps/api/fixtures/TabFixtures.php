<?php declare(strict_types=1);

namespace DumpIt\Api\Fixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use DumpIt\StashFilter\Domain\Stash\Item;
use DumpIt\StashFilter\Domain\Stash\ItemId;
use DumpIt\StashFilter\Domain\Stash\ItemMod;
use DumpIt\StashFilter\Domain\Stash\Mod;
use DumpIt\StashFilter\Domain\Stash\ModId;
use DumpIt\StashFilter\Domain\Stash\Tab;
use DumpIt\StashFilter\Domain\Stash\TabId;

class TabFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $tab = new Tab(TabId::from('1'), 'tab 1', null);
        $manager->persist($tab);

        $item = new Item(ItemId::from('1'), 'item1', 50, 'basetype', $tab);
        $manager->persist($item);

        $mod = new Mod('mod1');
        $manager->persist($mod);
        
        $itemMod = new ItemMod($item, $mod, 2);
        $manager->persist($itemMod);

        $manager->flush();
    }
}