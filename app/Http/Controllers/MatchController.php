<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Src\Entities\Player;
use Src\Entities\Team;

class MatchController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $team_home_id = $request->get('home');
        $team_away_id = $request->get('away');

        $time_home_obj = $this->getTeam($team_home_id);
        $time_away_obj = $this->getTeam($team_away_id);;

        $time_home_players = $this->getPlayersTeams($team_home_id);
        $time_away_players = $this->getPlayersTeams($team_away_id);


        $time_home = new Team($time_home_obj->name, $time_home_players, '4-4-2');
        $time_away = new Team($time_away_obj->name, $time_away_players, '4-4-2');

        $tempo = 0;
        $placar = ['home' => 0, 'away' => 0];

        while ($tempo < 90) {
            $jogadaHome = $this->jogada($time_home, $time_away);
            $tempo += $jogadaHome['tempo'];
            echo "====================\nLance {$time_home->name} aos {$tempo} min\n{$jogadaHome['lance']}\n";
            if ($jogadaHome['gol']) $placar['home']++;

            if ($tempo >= 90) break;

            $jogadaAway = $this->jogada($time_away, $time_home);
            $tempo += $jogadaAway['tempo'];
            echo "====================\nLance {$time_away->name} aos {$tempo} min\n{$jogadaAway['lance']}\n";
            if ($jogadaAway['gol']) $placar['away']++;
        }

        echo "===================\nResultado Final:\n{$time_home->name} {$placar['home']} - {$placar['away']} {$time_away->name}\n";

        $this->registerMatch($time_home_obj, $placar['home'], $time_away_obj, $placar['away']);
    }

    private function roll(): int
    {
        return rand(1, 100);
    }

    private function jogada($time_atk, $time_def): array
    {
        $evento = $this->roll();
        if ($evento < 30) {
            return ['gol' => false, 'lance' => 'Posse de bola disputada', 'tempo' => $this->roll() % 4 + 2];
        }
        return $this->lance($time_atk, $time_def);
    }

    private function lance($time_atk, $time_def): array
    {
        if ($this->roll() > $time_atk->mid - $time_def->mid + 50) {
            return ['gol' => false, 'lance' => 'Perdeu no meio-campo', 'tempo' => $this->roll() % 4 + 2];
        }
        if ($this->roll() > $time_atk->atk - $time_def->def + 50) {
            return ['gol' => false, 'lance' => 'Perdeu no ataque', 'tempo' => $this->roll() % 4 + 2];
        }
        if ($this->roll() > $time_atk->atk - $time_def->gol + 50) {
            return ['gol' => false, 'lance' => 'Chutou pra fora', 'tempo' => $this->roll() % 3 + 2];
        }
        return ['gol' => true, 'lance' => 'GOOOOL!!!', 'tempo' => $this->roll() % 2 + 1];
    }

    private function getPlayersTeams($teamId): Collection
    {
        return DB::table('players_team')
            ->join('player', 'players_team.player_id', '=', 'player.id')
            ->where('team_id', '=', $teamId)
            ->get();
    }

    private function getTeam($teamId)
    {
        return DB::table('team')
            ->where('id', '=', $teamId)
            ->first();
    }

    private function registerMatch($timeHome, $placarHome, $timeAway, $placarAway, $championshipId = 1)
    {
        DB::table('match')
            ->insert(
                [
                    'championship_id' => $championshipId,
                    'team_home_id' => $timeHome->id,
                    'team_away_id' => $timeAway->id,
                    'team_home_score' => $placarHome,
                    'team_away_score' => $placarAway
                ]
            );

    }
}
