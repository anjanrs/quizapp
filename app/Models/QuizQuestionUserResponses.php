<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizQuestionUserResponses extends Model
{
    
    public function quizUserAnswers()
    {
    	return $this->hasMany('App\Models\QuizQuestionUserAnswers', 'quiz_user_response_id', 'id');
    }

}
