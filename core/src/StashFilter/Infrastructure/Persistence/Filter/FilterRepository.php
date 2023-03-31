<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Infrastructure\Persistence\Filter;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DumpIt\StashFilter\Domain\Filter\Filter;
use DumpIt\StashFilter\Domain\Filter\FilterMod;
use DumpIt\StashFilter\Domain\Filter\FilterRepositoryInterface;
use DumpIt\StashFilter\Domain\Stash\ModRepositoryInterface;
use Ramsey\Uuid\Nonstandard\Uuid;

class FilterRepository extends ServiceEntityRepository implements FilterRepositoryInterface
{
    private ModRepositoryInterface $mods;

    public function __construct(ManagerRegistry $registry, ModRepositoryInterface $mods)
    {
        parent::__construct($registry, Filter::class);

        $this->mods = $mods;
    }

	public function byId(string $id): Filter
    {
        return $this->find($id);
	}

    function byUser(string $userId): array
    {
        $filters = $this->findBy(['userId' => $userId], ['name' => 'ASC']);

        return $filters;
	}

	public function create(string $name, string $userId, array $mods): void
    {
        $filter = new Filter((string) Uuid::uuid4(), $name, $userId, []);

        $mods = $this->buildMods($filter, $mods);

        $filter->changeMods($mods);

        $this->_em->persist($filter);
        $this->_em->flush();
	}
	
	public function edit(Filter $filter, ?string $name, ?array $mods): void
    {
        if (null !== $name) {
            $filter->changeName($name);
        }

        if (null !== $mods) {
            $filter->changeMods($mods);
        }

        $this->_em->flush();
    }

    public function delete(string $id): void
    {
        $filter = $this->byId($id);

        $this->_em->remove($filter);
        $this->_em->flush();
    }

    public function canUserAccess(string $id, string $userId): bool
    {
        return $this->_em
            ->getConnection()
            ->createQueryBuilder()
            ->select('f.user_id = :userId')
            ->from('dumpit.filters', 'f')
            ->where('f.id = :id')
            ->setParameter('id', $id)
            ->setParameter('userId', $userId)
            ->fetchOne()
        ;
    }

    //TODO this should not be here, rethink
    private function buildMods(Filter $filter, array $mods): array
    {
        $modEntities = $this->mods->byIds(array_column($mods, 'id'));

        return array_map(
            function (array $mod) use ($filter, $modEntities) {           
                return new FilterMod($filter, $modEntities[$mod['id']], $mod['values'], $mod['condition']);
            },
            $mods,
        );
    }
}
