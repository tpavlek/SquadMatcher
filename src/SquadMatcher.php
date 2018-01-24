<?php

namespace App;

use Illuminate\Support\Collection;

class SquadMatcher
{
    private $participants;

    public function __construct($squad_size = 4)
    {
        $this->participants = collect();
    }

    /**
     * @return Collection
     */
    public function computeSquads()
    {
        $numGroups = ceil($this->participants->count() / 4);
        $chunkSize = ceil($this->participants->count() / $numGroups);

        return $this->participants
            ->shuffle()
            ->chunk($chunkSize)
            ->map(function (Collection $chunk, $i) {
                return new Squad($chunk, $i);
            });
    }

    public function addPlayer($name) {
        $this->participants->push($name);
    }
}
