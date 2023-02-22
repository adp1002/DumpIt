<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Domain\Stash;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'tabs', schema: 'dumpit')]
class Tab
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'string', length: 64)]
    private string $id;

    #[ORM\Column(name: 'name', type: 'string')]
    private string $name;

    #[ORM\Column(type: 'integer')]
    private int $index;

    #[ORM\Column(name: 'league', type: 'string')]
    private string $league;

    #[ORM\Column(name: 'last_sync', type: 'datetime')]
    private \DateTime|null $lastSync;

    #[ORM\OneToMany(targetEntity: Item::class, mappedBy: 'tab', cascade: ['all'])]
    /** @var Collection|Item[] */
    private Collection $items;

    #[ORM\Column(name: 'user_id', type: 'uuid')]
    private string $userId;

    public function __construct(string $id, string $name, int $index, string $league, \DateTime|null $lastSync)
    {
        $this->items = new ArrayCollection();

        $this->id = $id;
        $this->name = $name;
        $this->index = $index;
        $this->league = $league;
        $this->lastSync = $lastSync;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function index(): int
    {
        return $this->index;
    }

    public function league(): string
    {
        return $this->league;
    }

    public function lastSync(): \DateTime|null
    {
        return $this->lastSync;
    }

    public function items(): Collection
    {
        return $this->items;
    }
    
    public function userId(): string
    {
        return $this->userId;
    }

    public function changeName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function changeItems(array $items): self
    {
        $this->items = $items;

        return $this;
    }

    public function refreshSync(): self
    {
        $this->lastSync = new \DateTime();

        return $this;
    }
}
