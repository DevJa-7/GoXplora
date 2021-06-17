<?php

namespace App\Http\Controllers\API;

use App\Models\GameAnswer;
use App\Models\GameQuestion;
use App\Models\GameRanking;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Validator;

class GameAPIController extends APIController
{

    private function getUserGamificationData($user)
    {
        return $user->ranking()->select(['score', 'credits', 'total_answers', 'total_correct'])->first();
    }

    public function addVisitedModules(Request $request)
    {
        $user = Auth::user();
        $modules = $request->input('modules');

        $request->validate([
            'modules' => 'required',
        ]);

        $old = $user->visited()->pluck('id')->toArray();
        $new = json_decode($modules);

        foreach ($new as $key => $value) {
            if (in_array($value, $old)) {
                unset($new[$key]);
            }
        }

        if (count($new)) {
            $user->visited()->syncWithoutDetaching($new);

            $ranking = GameRanking::firstOrCreate(['user_id' => $user->id]);
            $ranking->score += count($new) * config('gamification.visited.points');
            $ranking->credits += count($new) * config('gamification.visited.credits');
            $ranking->save();
        }

        return json_response([
            'game' => $this->getUserGamificationData($user),
            'visited' => $user->visited()->pluck('id')->toArray(),
        ]);
    }

    public function getModulesStatus(Request $request)
    {
        $user = Auth::user();

        $answered_ids = GameAnswer::where('user_id', $user->id)->pluck('question_id')->toArray();
        $answered_modules_ids = GameQuestion::whereIn('id', $answered_ids)->pluck('module_id')->toArray();
        $visited_ids = $user->visited()->get()->pluck('id')->toArray();

        return json_response([
            'visited' => $visited_ids,
            'answered' => $answered_modules_ids,
            'unanswered' => array_values(array_diff($visited_ids, $answered_modules_ids)),
        ]);
    }

    public function getGameQuestion(Request $request)
    {
        $module_id = $request->input('module_id');
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'module_id' => 'required|exists:modules,id',
        ]);

        $validator->validate();

        // Validate user visited the module
        $visited = $user->visited()->where('module_id', $module_id)->first();

        if (!$visited) {
            $validator->errors()->add('module_id', __('You must visit the module to be able to answer questions about it.'));
            throw new ValidationException($validator);
        }

        // Validate user hasn't answered the module_id
        $answered_ids = GameAnswer::where('user_id', $user->id)->pluck('question_id')->toArray();
        $answered_modules_ids = GameQuestion::whereIn('id', $answered_ids)->pluck('module_id')->toArray();

        if (in_array($module_id, $answered_modules_ids)) {
            $validator->errors()->add('question_id', __('You cannot answer questions for this module anymore.'));
            throw new ValidationException($validator);
        }

        // Get Question
        $question = GameQuestion::inRandomOrder()
            ->select(['id', 'title', 'content', 'images', 'option_a', 'option_b', 'option_c', 'option_d'])
            ->where('module_id', $module_id)
            ->first();

        return json_response($question);
    }

    public function setGameAnswer(Request $request)
    {
        $question_id = $request->input('question_id');
        $user_answer = $request->input('answer');
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'question_id' => 'required|exists:game_questions,id',
            'answer' => 'required|in:0,1,2,3',
        ]);

        $validator->validate();

        // Validate if user hasn't answer yet
        $answer = GameAnswer::where([
            'user_id' => $user->id,
            'question_id' => $question_id,
        ])->first();

        if ($answer) {
            $validator->errors()->add('question_id', __('You have already answered this question. You cannot answer questions for this module anymore.'));
            throw new ValidationException($validator);
        }

        $question = GameQuestion::select('correct')
            ->where('id', $question_id)
            ->first();

        $correct = $question->correct == $user_answer;
        $ranking = GameRanking::firstOrCreate(['user_id' => $user->id]);

        if ($correct) {
            $ranking->score += config('gamification.correct.points');
            $ranking->credits += config('gamification.correct.credits');
            $ranking->total_correct += 1;
        }

        $ranking->total_answers += 1;
        $ranking->save();

        // Save answer
        $answer = new GameAnswer();
        $answer->fill($request->all());
        $answer->user_id = $user->id;
        $answer->correct = $correct;
        $answer->save();

        return json_response([
            'correct' => $correct,
            'correct_id' => $question->correct,
            'game' => $this->getUserGamificationData($user),
        ]);
    }

    public function getRankings(Request $request)
    {
        $user = Auth::user();
        $timespan = $request->input('timespan');
        $past = $request->input('past') == 1;

        $request->validate([
            'timespan' => 'required|in:day,week,month,year',
            'past' => 'in:0,1',
        ]);

        $where;
        switch ($timespan) {
            case 'day':
                $where = 'DATE(updated_at) LIKE CURRENT_DATE' . ($past ? ' - INTERVAL 1 DAY' : '');
                break;

            case 'week':
                $where = 'WEEK(updated_at) LIKE WEEK(CURRENT_DATE' . ($past ? ' - INTERVAL 1 WEEK' : '') . ')';
                break;

            case 'month':
                $where = 'MONTH(updated_at) LIKE MONTH(CURRENT_DATE' . ($past ? ' - INTERVAL 1 MONTH' : '') . ')';
                break;

            case 'year':
                $where = 'YEAR(updated_at) LIKE YEAR(CURRENT_DATE' . ($past ? ' - INTERVAL 1 YEAR' : '') . ')';
                break;
        }

        $rankings = GameRanking::select(['score', 'total_answers', 'total_correct', 'user_id'])
            ->with(['user' => function ($query) {
                return $query->select(['id', 'name', 'avatar', 'country']);
            }])
            ->whereRaw($where)
            ->orderBy('score', 'desc')
            ->orderBy('total_answers', 'asc')
            ->limit(10)
            ->get()
            ->toArray();

        return json_response($rankings);
    }
}
