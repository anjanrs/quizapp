<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuizQuestions;
use App\Models\QuizAnswers;
class QuizQuestionController extends Controller
{
    //
    public function getQuestions(Request $request) {
    	$postParams = $request->all();
    	
    	$quizQuestions = QuizQuestions::where('quiz_id', $postParams["quizid"])->with('quizAnswers')->get();
    	return array(
			"status" => "success",
			"message" => "",
			"quizQuestions" => $quizQuestions
		);
    }
}
