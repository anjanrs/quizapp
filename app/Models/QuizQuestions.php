<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizQuestions extends Model
{
    public function quizAnswers() {
    	return $this->hasMany('App\Models\QuizAnswers', 'question_id', 'id');	
    }

    public function quizQuestionResponses()
    {
    	return $this->hasMany('App\Models\QuizQuestionUserResponses', 'question_id', 'id');
    }
}
