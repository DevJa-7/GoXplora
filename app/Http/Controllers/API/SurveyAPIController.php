<?php

namespace App\Http\Controllers\API;

use App\Models\SurveyAnswer;
use App\Models\SurveyQuestion;
use Illuminate\Http\Request;

class SurveyAPIController extends APIController
{
    public function getSurvey()
    {
        $result = SurveyQuestion::select('id', 'title', 'type', 'options')
            ->orderBy('lft')
            ->get();

        return json_response($result);
    }

    public function saveSurvey(Request $request)
    {
        $code = 1;

        $answers = json_decode($request->request->get('answers'));
        $result = [];

        if ($answers) {
            foreach ($answers as $answer) {
                $result[] = SurveyAnswer::create([
                    'answer' => $answer->answer ?? null,
                    'question_id' => $answer->question_id,
                    'rating' => $answer->rating ?? null,
                    'user_id' => \Auth::user()->id,
                ]);
            }
        }

        return json_response($result);
    }
}
