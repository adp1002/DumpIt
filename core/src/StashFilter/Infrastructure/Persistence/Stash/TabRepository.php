<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Infrastructure\Persistence\Stash;

use DumpIt\StashFilter\Domain\Stash\Tab;
use DumpIt\StashFilter\Domain\Stash\TabRepositoryInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use DumpIt\StashFilter\Domain\Stash\TabTransformer;
use League\Fractal\Resource\Collection;

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

    public function refresh(array $refreshedTabs, string $userId, string $leagueId): Collection
    {
        $tabs = $this->byUserAndLeague($userId, $leagueId);

        foreach ($tabs as $tab) {
            if (empty($refreshedTabs[$tab->id()])) {
                $this->_em->remove($tab);
                continue;
            }

            $this->updateTab($tab, $refreshedTabs[$tab->id()]);
            unset($refreshedTabs[$tab->id()]);
        }

        foreach ($refreshedTabs as $tab) {
            $tab = new Tab($tab['id'], $tab['name'], $tab['index'], $leagueId, $userId, new \DateTime());

            $this->_em->persist($tab);
        }

        $this->_em->flush();

        return new Collection($tabs, new TabTransformer(), 'data');
    }

    public function refreshTab(Tab $tab, array $refreshedTab)
    {
        $this->updateTab($tab, $refreshedTab);
        $this->_em->flush();
    }

    private function updateTab(Tab $tab, array $refreshedTab)
    {
        $tab
            ->changeName($refreshedTab['name'])
            ->changeIndex($refreshedTab['index'])
            ->refreshSync()
        ;

        if (isset($refreshedTab['items'])) {
            $tab->changeItems($refreshedTab['items']);
        }

        $this->_em->persist($tab);
    }
}
