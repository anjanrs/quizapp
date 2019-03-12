<?php

use Illuminate\Database\Seeder;

class QuizQuestionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $quizzes = DB::table('quizzes')->get();
        foreach($quizzes as $quiz) {
	        DB::table('quiz_questions')->insert([
	        	'question' => 'Quiz'. $quiz->id . ' Question 1', 
	        	'quiz_id' => $quiz->id,
	        	'created_at' =>  \Carbon\Carbon::now(), 
            	'updated_at' => \Carbon\Carbon::now(), 
	        ]);
	        DB::table('quiz_questions')->insert([
	        	'question' => 'Quiz'. $quiz->id . ' Question 2', 
	        	'quiz_id' => $quiz->id,
	        	'created_at' =>  \Carbon\Carbon::now(), 
            	'updated_at' => \Carbon\Carbon::now(), 
	        ]);
	        DB::table('quiz_questions')->insert([
	        	'question' => 'Quiz'. $quiz->id . ' Question 3', 
	        	'quiz_id' => $quiz->id,
	        	'created_at' =>  \Carbon\Carbon::now(), 
            	'updated_at' => \Carbon\Carbon::now(), 
	        ]);
	        DB::table('quiz_questions')->insert([
	        	'question' => 'Quiz'. $quiz->id . ' Question 4', 
	        	'quiz_id' => $quiz->id,
	        	'created_at' =>  \Carbon\Carbon::now(), 
            	'updated_at' => \Carbon\Carbon::now(), 
	        ]);
	        DB::table('quiz_questions')->insert([
	        	'question' => 'Quiz'. $quiz->id . ' Question 5', 
	        	'quiz_id' => $quiz->id,
	        	'created_at' =>  \Carbon\Carbon::now(), 
            	'updated_at' => \Carbon\Carbon::now(), 
	        ]);
        }
        
    }
}
