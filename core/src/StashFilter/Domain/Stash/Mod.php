<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Domain\Stash;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'mods', schema: 'dumpit')]
class Mod
{
    #[ORM\Id]
    #[ORM\Column(type: 'string')]
    private string $id;

    #[ORM\Column(type: 'string')]
    private string $text;

    #[ORM\Column(type: 'integer')]
    private int $placeholders;

    public function __construct(string $id, string $text, int $placeholders)
    {
        $this->id = $id;
        $this->text = $text;
        $this->placeholders = $placeholders;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function text(): string
    {
        return $this->text;
    }

    public function placeholders(): int
    {
        return $this->placeholders;
    }
}
