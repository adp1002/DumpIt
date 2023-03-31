<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Domain\Stash;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'items', schema: 'dumpit')]
class Item
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'string', length: 64)]
    private string $id;
    
    #[ORM\Column(name: 'name', type: 'string')]
    private string $name;

    #[ORM\Column(name: 'ilvl', type: 'integer')]
    private int $ilvl;

    #[ORM\Column(name: 'base_type', type: 'string')]
    private string $basetype;

    #[ORM\ManyToOne(targetEntity: Tab::class)]
    #[ORM\JoinColumn(name: 'tab_id', referencedColumnName: 'id')]
    private Tab $tab;

    #[ORM\OneToMany(targetEntity: ItemMod::class, mappedBy: 'item', cascade: ['all'])]
    /** @var ItemMod[] */
    private Collection $mods;

    public function __construct(string $id, string $name, int $ilvl, string $basetype, Tab $tab, array $mods)
    {      
        $this->id = $id;
        $this->name = $name;
        $this->ilvl = $ilvl;
        $this->basetype = $basetype;
        $this->tab = $tab;
        $this->changeMods($mods);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function mods(): Collection
    {
        return $this->mods;
    }

    public function changeMods(array $mods): void
    {
        $this->mods = new ArrayCollection($mods);
    }
}
