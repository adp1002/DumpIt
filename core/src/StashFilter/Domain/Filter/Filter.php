<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Domain\Filter;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'filters', schema: 'dumpit')]
class Filter
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    private string $id;

    #[ORM\Column(name: 'name', type: 'string')]
    private string $name;

    #[ORM\Column(name: 'user_id', type: 'uuid')]
    private string $userId;
    
    #[ORM\OneToMany(targetEntity: FilterMod::class, mappedBy: 'filter', cascade: ['all'], orphanRemoval: true)]
    /** @var Collection|FilterMod[] */
    private Collection $mods;

    private array|null $rawMods = null;
    
    public function __construct(string $id, string $name, string $userId, array $mods)
    {
        $this->id = $id;
        $this->changeName($name);
        $this->userId = $userId;
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

    /** @return FilterMod[] */
    public function mods(): Collection
    {
        return $this->mods;
    }

    public function changeName(string $name): void
    {
        $this->name = $name;
    }
    
    public function userId(): string
    {
        return $this->userId;
    }

    public function changeMods(array $mods): void
    {
        $this->mods = new ArrayCollection($mods);
    }

    public function filter($rawMods): bool
    {
        foreach ($this->rawMods as $mod => $values) {
            if (!array_key_exists($mod, $rawMods)) {
                return false;
            }
        }

        return true;
    }
}
