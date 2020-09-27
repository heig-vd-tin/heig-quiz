<?php

namespace App\Transformer;

trait HasTransform
{
    public function newCollection(array $models = Array())
    {
        $transformer = $this->transformer;
        return new TransformableCollection($models, $transformer);
    }
}

class TransformableCollection extends \Illuminate\Database\Eloquent\Collection {

    protected $transformer = null;

    public function __construct($items = [], $transformer = null)
    {
        // Guess the transformer is missing
        if (!$transformer && count($items)) {
            $members = explode('\\', get_class($items[0]));
            $class = end($members);
            $transformer = "\\App\\Transformer\\{$class}Transformer";
        }

        $this->transformer = $transformer;
        return parent::__construct($items);
    }

    public function fractal() {
        if (!$this->transformer) return $this->toArray();
        return fractal($this, new $this->transformer)->toArray();
    }
}
