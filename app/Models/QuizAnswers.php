<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAnswers extends Model
{
    public function quizUserAnswers()
    {
    	return $this->hasMany('App\Models\QuizQuestionUserAnswers', 'answer_id', 'id');
    }
}
