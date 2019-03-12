<?php

use Illuminate\Database\Seeder;

class QuizAnswersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $questions = DB::table('quiz_questions')->get();
        foreach($questions as $question) {
	        DB::table('quiz_answers')->insert([
	        	'answer' => 'Answer 1', 
	        	'question_id' => $question->id,
	        	'correct' => 1,
	        	'created_at' =>  \Carbon\Carbon::now(), 
            	'updated_at' => \Carbon\Carbon::now(), 
	        ]);
	        DB::table('quiz_answers')->insert([
	        	'answer' => 'Answer 2', 
	        	'question_id' => $question->id,
	        	'correct' => 0,
	        	'created_at' =>  \Carbon\Carbon::now(), 
            	'updated_at' => \Carbon\Carbon::now(), 
	        ]);
	        DB::table('quiz_answers')->insert([
	        	'answer' => 'Answer 3', 
	        	'question_id' => $question->id,
	        	'correct' => 1,
	        	'created_at' =>  \Carbon\Carbon::now(), 
            	'updated_at' => \Carbon\Carbon::now(), 
	        ]);
	        DB::table('quiz_answers')->insert([
	        	'answer' => 'Answer 4', 
	        	'question_id' => $question->id,
	        	'correct' => 0,
	        	'created_at' =>  \Carbon\Carbon::now(), 
            	'updated_at' => \Carbon\Carbon::now(), 
	        ]);
        }
    }
}
