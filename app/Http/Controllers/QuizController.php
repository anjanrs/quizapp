<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Quizzes;
use App\Models\QuizUsers;
use App\Models\QuizQuestions;
use App\Models\QuizAnswers;
use App\Models\QuizQuestionUserResponses;
use App\Models\QuizQuestionUserAnswers;
use App\Models\QuizReports;

class QuizController extends Controller
{
    /**
     * Show the list of quizzes for the user
     *
     * @param  int  $id
     * @return View
     */
    public function show()
    {
    	$queries = Quizzes::where('active', 1)->get();
        return view('quizzes', ['quizzes' => $queries]);
    }

    public function registerUser(Request $request) {
    	$postParams = $request->all();
    	
    	//check if user has already taken the test
    	$quizUser = QuizUsers::where('email', $postParams["email"])->first();
    	if(!$quizUser) {
    		//register new user and return user id
		 	$quizUser = new QuizUsers;
		 	$quizUser->email = $postParams["email"];
		 	$quizUser->save();
    		
    	} else {
    		$quizReports = QuizReports::where('quiz_id', $postParams["quizid"])
    						->where('quiz_user_id', $quizUser["id"])
    						->get();	
	    	if (count($quizReports) > 0) {
	    		return array (
	    			"status" => "error",
	    			"message" => "User has already taken this test"
	    		);
	    	}
		}

		return array(
			"status" => "success",
			"message" => "User registred",
			"registeredUser" => $quizUser
		);
    	
    }

    public function submitAnswers(Request $request) {
		$postParams = $request->all();
		$quizId = $postParams["quizId"];
		$userId = $postParams["userId"];
		$questionsAnswers = $postParams["questionsAnswers"];

		//clear all user resonses if any is there
    	QuizQuestionUserResponses::where('quiz_id', $quizId)->where('quiz_user_id', $userId)->delete();

    	//save user answers
    	foreach($questionsAnswers as $key => $question) {

    		$selectedAnswers = array();
    		foreach($question["quiz_answers"] as $keyAnswer => $answer) {
    			if(isset($answer["selected"]) && $answer["selected"]=="1") {
    				$selectedAnswers[] = array("answer_id" => $answer["id"]);
    			}
    		}

    		$newQuestionResponse = new QuizQuestionUserResponses;
    		$newQuestionResponse->quiz_user_id = $userId;
    		$newQuestionResponse->quiz_id = $quizId;
    		$newQuestionResponse->question_id = $question["id"];
    		$newQuestionResponse->response = count($selectedAnswers) >0 ? 'answered' : 'skipped';
    		$newQuestionResponse->save();

    		if (count($selectedAnswers)>0) {
    			$newQuestionResponse->quizUserAnswers()->createMany($selectedAnswers);
    		}
    		$newQuestionResponse->save();
    	}

    	//save user quiz result
    	$quizQuestions = QuizQuestions::where('quiz_id', $quizId)->get();
    	$totalQuestions = count($quizQuestions);
    	$correctAnswersCount = 0;
    	$incorrectAnswersCount = 0;
    	$skippedAnswersCount =0;
    	foreach($quizQuestions as $key => $question) {
    		$correctAnswers = QuizAnswers::where('question_id', $question["id"])
					    		->where('correct', 1)
					    		->select(['id'])
					    		->get();

    		$userResponses = QuizQuestionUserResponses::where('question_id', $question["id"])
				    		->where('quiz_id', $quizId)
				    		->where('quiz_user_id', $userId)
				    		->with('quizUserAnswers')
				    		->get();

    		$userAnswers = array();
			foreach($userResponses as $key => $userRes) {
				foreach($userRes["quizUserAnswers"] as $k => $answer) {
					$userAnswers[] = $answer["answer_id"];
				}
			}
			$correctAnswers = array_column($correctAnswers->toArray(), 'id');
			sort($correctAnswers);
			sort($userAnswers);

			if($correctAnswers == $userAnswers) {
				$correctAnswersCount++;
			} else {
				$incorrectAnswersCount++;
			}
			if(count($userAnswers)==0) {
				$skippedAnswersCount++;
			}
    	}
    	$quizReport = new QuizReports;
    	$quizReport->total = $totalQuestions;
    	$quizReport->correct = $correctAnswersCount;
    	$quizReport->incorrect = $incorrectAnswersCount;
    	$quizReport->skipped = $skippedAnswersCount;
    	$quizReport->quiz_id = $quizId;
    	$quizReport->quiz_user_id = $userId;
    	$quizReport->save();
    	
    	return array(
			"status" => "success",
			"message" => "User answers saved",
		);

    }

    public function getResults(Request $request) {
    	$postParams = $request->all();
		$quizId = $postParams["quizId"];
		$userId = $postParams["userId"];

    	$quizResult = QuizReports::where('quiz_id', $quizId)->where('quiz_user_id', $userId)->first();
    	return array(
			"status" => "success",
			"message" => "",
			"quizResult" => $quizResult
		);
    }
}
