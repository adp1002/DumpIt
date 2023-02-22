<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Infrastructure\Persistence\Stash;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DumpIt\StashFilter\Domain\Stash\Mod;
use DumpIt\StashFilter\Domain\Stash\ModRepositoryInterface;

class ModRepository extends ServiceEntityRepository implements ModRepositoryInterface
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Mod::class);
    }

    public function findAll(): array
    {
        return parent::findAll();
	}

    public function refresh(array $refreshedMods): void
    {
        $refreshedIds = array_column($refreshedMods, 'id');

        $mods = $this->findAll();

        foreach ($mods as $mod) {
            $key = array_search($mod->id(), $refreshedIds);

            if (false === $key) {
                $this->_em->remove($mod);
            } else {
                unset($refreshedMods[$key]);
            }
        }

        foreach($refreshedMods as $mod) {
            //TODO maybe add Mod::fromPoeApi()
            $this->_em->persist(new Mod($mod['id'], $mod['text'], substr_count($mod['text'], '#')));
        }
        
        $this->_em->flush();

        $deleteDupes = <<<SQL
            DELETE FROM dumpit.mods m USING (
                SELECT MIN(ctid) as ctid, text
                    FROM dumpit.mods 
                    GROUP BY text HAVING COUNT(*) > 1
            ) b
            WHERE m.text = b.text 
            AND m.ctid <> b.ctid
        SQL;

        $this->_em->getConnection()->executeStatement($deleteDupes);
    }

    /** @return array|Mod[] */
    public function matchByNames(array $mods): array
    {
        $modsRegex = array_map(
            function (string $mod) {
                return preg_replace('/[+-]?\d+/', '(#|[+-]?\d+)', $mod);
            },
            $mods,
        );

        $mods = $this->_em
            ->getConnection()
            ->createQueryBuilder()
            ->select('m.id, m.text, m.placeholders')
            ->from('dumpit.mods', 'm')
            ->where('m.text SIMILAR TO :mods')
            ->setParameter('mods', join('|', $modsRegex))
            ->executeQuery()
            ->fetchAllAssociative()
        ;

        return array_map(
            function (array $mod) {
                return new Mod($mod['id'], $mod['text'], $mod['placeholders']);
            },
            $mods,
        );
    }
}
