<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Domain\Filter;

use Doctrine\ORM\Mapping as ORM;
use DumpIt\StashFilter\Domain\Stash\Mod;

#[ORM\Entity]
#[ORM\Table(name: 'filter_mods', schema: 'dumpit')]
class FilterMod
{
    public const EQUAL = 'eq';
    public const GREATER_THAN = 'gt';
    public const GREATER_THAN_OR_EQUAL = 'gte';
    public const LESSER_THAN = 'lt';
    public const LESSER_THAN_OR_EQUAL = 'lte';

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Filter::class)]
    #[ORM\JoinColumn(name: 'filter_id', referencedColumnName: 'id', nullable: false)]
    private Filter $filter;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Mod::class)]
    #[ORM\JoinColumn(name: 'mod_id', referencedColumnName: 'id', nullable: false)]
    private Mod $mod;

    #[ORM\Column(type: 'json')]
    private array $values;

    #[ORM\Column(type: 'string')]
    private string $condition;

    public function __construct(Filter $filter, Mod $mod, array $values, string $condition)
    {
        $this->filter = $filter;
        $this->mod = $mod;
        $this->values = $values;
        $this->condition = $condition;
    }

    public function mod(): Mod
    {
        return $this->mod;
    }

    public function values(): array
    {
        return $this->values;
    }

    public function condition(): string
    {
        return $this->condition;
    }
}
