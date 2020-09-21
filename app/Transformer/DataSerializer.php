<?php

namespace App\Transformer;

use League\Fractal\Serializer\ArraySerializer;

class DataSerializer extends ArraySerializer {
    public function collection($resourceKey, array $data)
    {
        return [
            'count' => count($data),
            'data' => $data
        ];
    }
}
