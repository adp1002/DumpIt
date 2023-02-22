<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Infrastructure\Stash;

use DumpIt\StashFilter\Domain\Stash\Tab;
use DumpIt\StashFilter\Domain\Stash\TabRepositoryInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class TabRepository extends ServiceEntityRepository implements TabRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tab::class);
    }

	public function byId(string $id): Tab
    {
        return $this->find($id);
	}

    public function byUser(string $userId): array
    {
        return $this->findBy(['user_id' => $userId]);
    }

    public function byUserAndLeague(string $userId, string $leagueId): array
    {
        return $this->findBy(['user_id' => $userId, 'league_id' => $leagueId]);
    }
}
