<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Infrastructure\Persistence\Stash;

use Doctrine\ORM\Mapping\ClassMetadata;
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
        return $this->findBy(['userId' => $userId], ['id' => 'ASC']);
    }

    public function byUserAndLeague(string $userId, string $leagueId): array
    {
        return $this->findBy(['userId' => $userId, 'leagueId' => $leagueId], ['name' => 'ASC']);
    }

    public function findAll(): array
    {
        return parent::findAll();
    }

    public function refresh(array $refreshedTabs, string $userId, string $leagueId): void
    {
        $tabs = $this->byUserAndLeague($userId, $leagueId);

        $refreshedIds = array_column($refreshedTabs, 'id');

        foreach ($tabs as $tab) {
            $key = array_search($tab->id(), $refreshedIds);

            if (false === $key) {
                $this->_em->remove($tab);
            } else {
                //TODO update tabs name, index, etc
                unset($refreshedTabs[$key]);
            }
        }

        foreach ($refreshedTabs as $tab) {
            $tab = new Tab($tab['id'], $tab['name'], $tab['index'], $leagueId, $userId, new \DateTime());

            $this->_em->persist($tab);
        }

        $this->_em->flush();
    }

    public function refreshTab(Tab $tab, array $refreshedTab)
    {
        $tab
            ->changeName($refreshedTab['name'])
            ->changeIndex($refreshedTab['index'])
            ->changeItems($refreshedTab['items'])
            ->refreshSync()
        ;

        $this->_em->flush();
    }
}
