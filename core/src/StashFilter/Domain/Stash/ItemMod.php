<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Domain\Stash;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'item_mods', schema: 'dumpit')]
class ItemMod
{
    #[ORM\Id]
    #[ORM\Column(name: 'value', type: 'string')]
    private string $itemId;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Mod::class)]
    #[ORM\JoinColumn(name: 'mod_id', referencedColumnName: 'id', nullable: false)]
    private Mod $mod;

    #[ORM\Column(type: 'json')]
    private array $values;

    public function __construct(string $itemId, Mod $mod, array $values)
    {
        $this->itemId = $itemId;
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
