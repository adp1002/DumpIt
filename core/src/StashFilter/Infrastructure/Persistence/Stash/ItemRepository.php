<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Infrastructure\Persistence\Stash;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use DumpIt\StashFilter\Domain\Stash\Item;
use DumpIt\StashFilter\Domain\Stash\ItemRepositoryInterface;
use Doctrine\Persistence\ManagerRegistry;

class ItemRepository extends ServiceEntityRepository implements ItemRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }
	
    public function byTab(string $id): array
    {
        return $this->findBy(['tabId' => $id]);
	}

    public function refresh(string $tabId, array $items): void
    {
        $this->deleteByTab($tabId);

        foreach($items as $item) {
            $this->_em->persist($item['item']);
            $this->_em->flush();

            $item['item']->changeMods($item['mods']);
        }

        $this->_em->flush();
    }

    private function deleteByTab(string $tabId): void
    {
        $delete = <<<SQL
            DELETE FROM dumpit.item_mods
            WHERE item_id IN (
                SELECT i.id
                FROM dumpit.items i
                WHERE tab_id = '$tabId'
            )
        SQL;

        $this->_em
            ->getConnection()
            ->executeStatement($delete)
        ;
        
        $delete = <<<SQL
            DELETE FROM dumpit.items
            WHERE tab_id = '$tabId'
        SQL;

        $this->_em
            ->getConnection()
            ->executeStatement($delete)
        ;
    }
}
