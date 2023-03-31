<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Infrastructure\Persistence\Stash;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DumpIt\StashFilter\Domain\Stash\League;
use DumpIt\StashFilter\Domain\Stash\LeagueRepositoryInterface;

class LeagueRepository extends ServiceEntityRepository implements LeagueRepositoryInterface
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, League::class);
    }

	public function refresh(array $refreshedLeagues): void
    {
        $refreshedIds = array_column($refreshedLeagues, 'id');

        $leagues = $this->findAll();

        foreach ($leagues as $league) {
            $key = array_search($league->id(), $refreshedIds);

            if (false === $key) {
                $this->_em->remove($league);
            } else {
                unset($refreshedLeagues[$key]);
            }
        }

        foreach($refreshedLeagues as $league) {
            $this->_em->persist(new League($league['id'], $league['realm']));
        }

        $this->_em->flush();
	}
}