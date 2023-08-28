<?php declare(strict_types=1);

namespace DumpIt\Api\Fixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use DumpIt\StashFilter\Domain\Stash\Mod;

class ModFixtures extends Fixture
{
    private const ID = 0;
    private const TEXT = 1;
    private const PLACEHOLDERS = 2;

    public const LIFE_MOD_ID = '1';
    public const COLD_RES_MOD_ID = '2';
    public const DEX_MOD_ID = '3';
    public const PHYS_MOD_ID = '4';
    public const FLAT_PHYS_MOD_ID = '5';
    public const AS_MOD_ID = '6';

    public function load(ObjectManager $manager)
    {
        $mods = [
            ['1', '# to maximum Life', 1],
            ['2', '#% to Cold Resistance', 1],
            ['3', '# to Dexterity', 1],
            ['4', '#% increased Physical Damage', 1],
            ['5', 'Adds # to # Physical Damage to Attacks', 2],
            ['6', '#% increased Attack Speed', 1],
        ];

        foreach ($mods as $mod) {
            $manager->persist(new Mod($mod[self::ID], $mod[self::TEXT], $mod[self::PLACEHOLDERS]));
        }

        $manager->flush();
    }
}
