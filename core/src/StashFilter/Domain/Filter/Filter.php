<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Domain\Filter;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DumpIt\StashFilter\Domain\User\User;
use Ramsey\Uuid\Doctrine\UuidGenerator;

#[ORM\Entity]
#[ORM\Table(name: 'filters', schema: 'dumpit')]
class Filter
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private string $id;

    #[ORM\Column(name: 'name', type: 'string')]
    private string $name;

    #[ORM\OneToMany(targetEntity: FilterMod::class, mappedBy: 'item')]
    /** @var FilterMod[] */
    private Collection $mods;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\Column(name: 'user_id', type: 'uuid')]
    private User $user;
    
    public function __construct(string $id, string $name, array $mods, User $user)
    {
        $this->id = $id;

        $this->changeMods(new ArrayCollection($mods));
        $this->changeName($name);

        $this->user = $user;
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

    public function user(): User
    {
        return $this->user;
    }

    public function changeName(string $name): void
    {
        $this->name = $name;
    }

    public function changeMods(Collection $mods): void
    {
        $this->mods = $mods;
    }
}
