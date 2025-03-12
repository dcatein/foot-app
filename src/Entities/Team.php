<?php

namespace Src\Entities;

use Illuminate\Support\Collection;

class Team
{
    public string $name, $strategy;
    public int $atk, $def, $mid, $gol;
    public Collection $players;

    public function __construct($name, $players, $strategy)
    {
        $this->name = $name;
        $this->players = $players;
        $this->strategy = $strategy;
        $this->calculateStrength();
    }

    private function calculateStrength(): void
    {
        $startPlayers = $this->selectStartPlayers();

        $totalAtk = $totalDef = $totalMid = $totalGol = 0;
        $atkCount = $defCount = $midCount = $golCount = 0;

        foreach ($startPlayers as $player) {
            switch ($player->position) {
                case 'Ataque':
                    $totalAtk += $player->atk;
                    $atkCount++;
                    break;
                case 'Defesa':
                    $totalDef += $player->def;
                    $defCount++;
                    break;
                case 'Meio':
                    $totalMid += $player->mid;
                    $midCount++;
                    break;
                case 'Goleiro':
                    $totalGol += $player->gol;
                    $golCount++;
                    break;
            }
        }

        $this->atk = $atkCount > 0 ? round($totalAtk / $atkCount) + ($atkCount * 2) : 0;
        $this->def = $defCount > 0 ? round($totalDef / $defCount) + ($defCount * 2) : 0;
        $this->mid = $midCount > 0 ? round($totalMid / $midCount) + ($midCount * 2) : 0;
        $this->gol = $golCount > 0 ? round($totalGol / $golCount) : 0;
    }

    private function selectStartPlayers()
    {
        $selected = new Collection();

        $strategy = explode('-', $this->strategy);

        $goleiros = $this->players->filter(function ($player){
            if($player->position == 'Goleiro') {
                return $player;
            }
            return false;
        });
        $goleiroSort = $goleiros->sortByDesc('gol');
        $selected->add($goleiroSort->first());

        $defesa = $this->players->filter(function ($player){
            if($player->position == 'Defesa') {
                return $player;
            }
            return false;
        });
        $defesaSort = $defesa->sortByDesc('def');
        $defesaCut = $defesaSort->chunk($strategy[0])[0];

        foreach ($defesaCut as $defesa) {
            $selected->add($defesa);
        }

        $meio = $this->players->filter(function ($player){
            if($player->position == 'Meio') {
                return $player;
            }
            return false;
        });
        $meioSort = $meio->sortByDesc('def');
        $meioCut = $meioSort->chunk($strategy[1])[0];

        foreach ($meioCut as $meio) {
            $selected->add($meio);
        }

        $ataque = $this->players->filter(function ($player){
            if($player->position == 'Ataque') {
                return $player;
            }
            return false;
        });
        $ataqueSort = $ataque->sortByDesc('def');
        $ataqueCut = $ataqueSort->chunk($strategy[2])[0];

        foreach ($ataqueCut as $ataque) {
            $selected->add($ataque);
        }

        return $selected;
    }
}
