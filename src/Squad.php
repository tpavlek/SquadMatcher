<?php

namespace App;

use Illuminate\Support\Collection;

class Squad
{

    /**
     * @var Collection
     */
    private $names;
    private $squadName;

    public function __call($name, $arguments)
    {
        return $this->names->{$name}(...$arguments);
    }

    public function __construct(Collection $names, $squadName)
    {
        $this->names = $names->map(function ($name) { return "@$name"; });
        $this->squadName = $squadName;
    }

    public function __toString()
    {
        return "Squad $this->squadName: " . $this->names->implode(', ');
    }

}
