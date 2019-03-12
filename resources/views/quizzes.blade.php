<script>
	var quizzes = @json($quizzes);
</script>
@extends('layouts.quizapp')

@section('title', 'Quizzes')

@section('content')
	<vc-quiz-list></vc-quiz-list>
@endsection