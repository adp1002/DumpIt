<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Infrastructure\Filter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;
use DumpIt\StashFilter\Domain\Filter\Filter;
use DumpIt\StashFilter\Domain\Filter\FilterRepositoryInterface;
use DumpIt\StashFilter\Domain\User\User;

class FilterRepository extends ServiceEntityRepository implements FilterRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Filter::class);
    }

	public function byId(string $id): Filter
    {
        return $this->find($id);
	}

    function byUser(string $userId): array
    {
        $filters = $this->findBy(['user_id' => $userId]);

        if (0 === count($filters)) {
            //TODO Exception
            throw new \Exception();
        }

        return $filters;
	}

	public function create(string $id, string $name, array $mods, User $user): void
    {
        $filter = new Filter($id, $name, $mods, $user);

        $this->persist($filter);
        $this->flush();
	}
	
	public function edit(string $id, ?string $name, ?Collection $mods): void
    {
        $filter = $this->byId($id);

        if (null !== $name) {
            $filter->changeName($name);
        }

        if (null !== $mods) {
            $filter->changeMods($mods);
        }

        $this->_em->persist($filter);
        $this->_em->flush();
    }

    public function delete(string $id): void
    {
        $filter = $this->byId($id);

        $this->_em->remove($filter);
        $this->_em->flush();
    }

}
