<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Domain\Stash;

use League\Fractal\TransformerAbstract;

class LeagueTransformer extends TransformerAbstract
{
    public function transform(League $league): array
    {
        return [
            'id' => $league->id(),
            'realm' => $league->realm(),
        ];
    }
}
