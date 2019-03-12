<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quizzes extends Model
{
    public function quizReports()
    {
    	return $this->hasMany('App\Models\QuizReports', 'quiz_id', 'id');
    }

    public function quizQuestions() {
    	return $this->hasMany('App\Models\QuizQuestions', 'quiz_id', 'id');	
    }

   public function quizQuestionResponses() {
    	return $this->hasMany('App\Models\QuizQuestionUserResponses', 'quiz_id', 'id');	
   }

}
