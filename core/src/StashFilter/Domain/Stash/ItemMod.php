<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Domain\Stash;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'item_mods', schema: 'dumpit')]
class ItemMod
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Item::class)]
    #[ORM\JoinColumn(name: 'item_id', referencedColumnName: 'id')]
    private Item $item;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Mod::class)]
    #[ORM\JoinColumn(name: 'mod_id', referencedColumnName: 'id')]
    private Mod $mod;

    #[ORM\Column(type: 'json')]
    private array $values;

    public function __construct(Item $item, Mod $mod, array $values)
    {
        $this->item = $item;
        $this->mod = $mod;
        $this->values = $values;
    }

    public function mod(): Mod
    {
        return $this->mod;
    }

    public function values(): array
    {
        return $this->values;
    }
}
