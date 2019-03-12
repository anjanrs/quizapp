<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizUsers extends Model
{
    
    public function quizQuestionResponses()
    {
    	return $this->hasMany('App\Models\QuizQuestionUserResponses', 'quiz_user_id', 'id');
    }

    public function quizReports()
    {
    	return $this->hasMany('App\Models\QuizReports', 'quiz_user_id', 'id');
    }

}
