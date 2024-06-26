<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Infrastructure\Persistence\Stash;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
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

        foreach ($refreshedMods as $mod) {
            //TODO maybe add Mod::fromPoeApi()
            if ('(Local)' === substr($mod['text'], -7)) {
                $mod['text'] = substr($mod['text'], 0, -8);
            }

            $this->_em->persist(new Mod($mod['id'], $mod['text'], substr_count($mod['text'], '#')));
        }

        $this->_em->flush();

        $deleteDupes = <<<SQL
            DELETE FROM dumpit.mods m USING (
                SELECT MIN(ctid) as ctid, text
                    FROM dumpit.mods 
                    GROUP BY text
                    HAVING COUNT(*) > 1
            ) b
            WHERE m.text = b.text 
            AND m.ctid <> b.ctid
        SQL;

        $this->_em->getConnection()->executeStatement($deleteDupes);
    }

    /** @return array|Mod[] */
    public function matchByNames(array $mods): array
    {
        //TODO "reduced" mods do not exist, it's increased with a negative value.
        $modsRegex = array_map(
            function (string $mod) {
                $mod = $this->handleSpecialMods($mod);
                $mod = '(^' . $mod . '$)';
                return preg_replace('/[+-]?\d+/', '[+-]?(#|\d+)', $mod);
            },
            $mods,
        );

        $rsm = new ResultSetMapping();
        $rsm->addEntityResult(Mod::class, 'm');
        $rsm->addFieldResult('m', 'id', 'id');
        $rsm->addFieldResult('m', 'text', 'text');
        $rsm->addMetaResult('m', 'placeholders', 'placeholders');

        $mods = $this->_em
            ->createNativeQuery(
                sprintf(
                    'SELECT * FROM dumpit.mods m WHERE m.text ~ %s',
                    "'" . join('|', $modsRegex) . "'",
                ),
                $rsm
            )
            ->setParameter('mods', join('|', $modsRegex))
            ->getResult()
        ;

        return $mods;
    }

    public function byIds(array $ids): array
    {
        $mods = [];

        foreach ($this->findBy(['id' => $ids]) as $mod) {
            $mods[$mod->id()] = $mod;
        }

        return $mods;
    }

    private function handleSpecialMods(string $mod)
    {
        if (preg_match('/^[+-]?(#|\d+)% Chance to Block$/', $mod)) {
            return $mod . ' \(Shields\)';
        }

        return $mod;
    }
}
