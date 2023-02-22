<?php declare(strict_types=1);

namespace DumpIt\StashFilter\Domain\Stash;

use League\Fractal\TransformerAbstract;

class ModTransformer extends TransformerAbstract
{
    public function transform(Mod $mod): array
    {
        return [
            'id' => $mod->id(),
            'text' => $mod->text(),
        ];
    }
}
