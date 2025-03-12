<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Src\Entities\Player;

class DraftController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $teamsIds = $request->get('teams');
        $draftRounds = 11;

        /**
         * @TODO Regras de Ordem de pick
         */
        $teams = $this->getTeams($teamsIds);

        $draftList = $this->filterByPosition(new Collection($this->generateDraftList(20)));
        $draftedPlayers = [];

        for ($i = 0; $i < $draftRounds; $i++) {

            foreach ($teams as $team) {
                $teamId = $team->id;

                $teamList = $this->getTeamCurrentList($teamId);
                $teamByPosition = $this->filterByPosition($teamList);

                if (!array_key_exists($teamId, $draftedPlayers)){
                    $draftedPlayers[$teamId] = [
                        'Goleiro' => [],
                        'Defesa' => [],
                        'Meio' => [],
                        'Ataque' => []
                    ];
                }

                $positionToPick = $this->getPositionToPick(
                    $this->getDraftStrategy(),
                    $teamByPosition,
                    $draftedPlayers[$teamId]
                );

                echo 'Time: ' . $team->name . " pegando: $positionToPick". PHP_EOL;

                if(empty($positionToPick)){
                    continue;
                }

                $playerPicked = $this->pickPlayer($positionToPick, $draftList);

                if(empty($playerPicked)){
                    continue;
                }

                echo 'Time: ' . $team->name . PHP_EOL;
                echo 'Selecionou: ' . "[$playerPicked->position] " . $playerPicked->name .PHP_EOL;

                $draftedPlayers[$teamId][$playerPicked->position][] = $playerPicked;

                $this->registerPlayersTeam($playerPicked, $teamId);
            }

        }
    }

    private function getTeamCurrentList(int $teamId): Collection
    {
        return DB::table('players_team')
            ->join('player', 'players_team.player_id', '=', 'player.id')
            ->where('team_id', '=', $teamId)->get();
    }

    private function filterByPosition(Collection $teamList): array
    {
        return [
            'Goleiro' => $teamList->filter(function ($player){
                if($player->position == 'Goleiro') {
                    return $player;
                }
                return false;
            }),
            'Defesa' => $teamList->filter(function ($player){
                if($player->position == 'Defesa') {
                    return $player;
                }
                return false;
            }),
            'Meio' => $teamList->filter(function ($player){
                if($player->position == 'Meio') {
                    return $player;
                }
                return false;
            }),
            'Ataque' => $teamList->filter(function ($player){
                if($player->position == 'Ataque') {
                    return $player;
                }
                return false;
            })
        ];
    }

    private function pickPlayer(string $playerPosition, array &$draftList): ?Player
    {

        $positionList = $draftList[$playerPosition];
                $caracter = $this->positionAbrev()[$playerPosition] . '_rng_max';
        $sorted = $positionList->sortByDesc($caracter)->toArray();

        if(!$sorted){
            return null;
        }
        $selected = array_shift($sorted);

        $draftListFilter = array_filter($draftList[$playerPosition]->toArray(), function ($player) use ($selected){
            if($player->identifier == $selected->identifier){
                return false;
            }
            return true;
        });
        $draftList[$playerPosition] = new Collection($draftListFilter);

        if(!$selected){
            dd($selected);
        }
        return $this->mapDraftPlayer($selected);
    }

    private function positionAbrev(): array
    {
        return [
            'Goleiro' => 'gol',
            'Defesa' => 'def',
            'Meio' => 'mid',
            'Ataque' => 'atk'
        ];
    }

    private function registerPlayersTeam(Player $player, int $teamId): void
    {

        $playerId = DB::table('player')
            ->insertGetId(
                ['atk' => $player->atk,
                'mid' => $player->mid,
                'def' => $player->def,
                'gol' => $player->gol,
                'position' => $player->position,
                'name' => $player->name
                ]);

        DB::table('players_team')
            ->insert(
            [
                'player_id' => $playerId,
                'team_id' => $teamId
            ]);
    }

    private function generateDraftList(int $qnt)
    {
        $result = new Collection();
        $faker = Faker::create();
        $positions = $this->positionAbrev();

        foreach ($positions as $posKey => $posValue){

            for ($i = 0; $i < $qnt; $i++) {

                $rng_min = $faker->numberBetween(0, 80);
                $player = [
                    'name' => $faker->firstName . ' ' . $faker->lastName,
                    'position' => $posKey,
                    'atk_rng_min' => $rng_min,
                    'atk_rng_max' => $faker->numberBetween($rng_min, 99),
                    'mid_rng_min' => $rng_min,
                    'mid_rng_max' => $faker->numberBetween($rng_min, 99),
                    'def_rng_min' => $rng_min,
                    'def_rng_max' => $faker->numberBetween($rng_min, 99),
                    'gol_rng_min' => $rng_min,
                    'gol_rng_max' => $faker->numberBetween($rng_min, 99),
                    'identifier' => $faker->unique()->numberBetween(0, 9999)
                ];
                $result->add(
                    (object) $player
                );
            }
        }
        return $result->getIterator();
    }

    private function mapDraftPlayer($player): Player
    {
        $faker = Faker::create();

        return new Player(
            atk: $faker->numberBetween($player->atk_rng_min, $player->atk_rng_max),
            def: $faker->numberBetween($player->def_rng_min, $player->def_rng_max),
            mid: $faker->numberBetween($player->mid_rng_min, $player->mid_rng_max),
            gol: $faker->numberBetween($player->gol_rng_min, $player->gol_rng_max),
            position: $player->position,
            name: $player->name
        );
    }

    private function getTeam($teamId)
    {
        return DB::table('team')
            ->where('id', '=', $teamId)
            ->first();
    }

    private function getTeams(array $teamIds): Collection
    {
        return DB::table('team')
            ->whereIn('id', $teamIds)
            ->get();
    }

    private function getDraftStrategy()
    {
        /**
         * @TODO Personalizar a estratégia para cada time
         */
        return ['Goleiro' => 2, 'Defesa' => 8, 'Meio' => 8, 'Ataque' => 4];
    }

    private function getPositionToPick(array $teamStrategy, array $teamList, array $draftedList): ?string
    {
        $maxPlayers = 30;

        foreach ($teamStrategy as $positionsKey => $positionValue) {
            $count = (int) $teamList[$positionsKey]->count() + (int) count($draftedList[$positionsKey]);

            if ($count < $positionValue){
                return $positionsKey;
            }
        }

        return false;
    }
}
