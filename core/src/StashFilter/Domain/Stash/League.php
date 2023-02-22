<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Domain\Stash;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'leagues', schema: 'dumpit')]
class League
{
    #[ORM\Id]
    #[ORM\Column(type: 'string')]
    private string $id;

    #[ORM\Column(type: 'string')]
    private string $realm;

    public function __construct($id, $realm)
    {
        $this->id = $id;
        $this->realm = $realm;
    }

    public function id(): string
    {
        return $this->id;
    }
}