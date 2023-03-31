<?php declare(strict_types=1);

namespace DumpIt\Shared\Domain\Serializer;

use League\Fractal\Serializer\ArraySerializer;

class FractalSerializer extends ArraySerializer
{
    public function collection(?string $resourceKey, array $data): array
    {
        if ($resourceKey) {
            return [$resourceKey => $data];
        }

        return $data;
    }

    public function item(?string $resourceKey, array $data): array
    {
        if ($resourceKey) {
            return [$resourceKey => $data];
        }
    
        return $data;
    }
}
