<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuizzesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('quizzes')->insert([
    		'name' => 'Quiz 1', 
    		'active' => 1,
        	'created_at' =>  \Carbon\Carbon::now(), 
        	'updated_at' => \Carbon\Carbon::now(), 
        ]);
        DB::table('quizzes')->insert([
        	'name' => 'Quiz 2', 
        	'active' => 1,
        	'created_at' =>  \Carbon\Carbon::now(), 
        	'updated_at' => \Carbon\Carbon::now(), 
        ]);
    }
}
