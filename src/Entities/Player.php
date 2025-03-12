<?php

namespace Src\Entities;

class Player
{
    public $atk, $def, $mid, $gol, $position, $name;

    public function __construct($atk, $def, $mid, $gol, $position, $name)
    {
        $this->atk = $atk;
        $this->def = $def;
        $this->mid = $mid;
        $this->gol = $gol;
        $this->position = $position;
        $this->name = $name;
    }
}
