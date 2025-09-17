@extends('Partials.app', ['activeMenu' => $activeMenu])

@section('title')
    {{ config('app.name') }} 
@endsection


@section('page_title')
    Edit Response
@endsection


@section('content')
<h2>Edit Question</h2>
<form method="POST" action="{{ route('questions.update', $question) }}">
    @csrf @method('PUT')
    <div class="mb-2">
        <label>Year</label>
        <input type="number" name="year" class="form-control" value="{{ $question->year }}" required>
    </div>
    <div class="mb-2">
        <label>Number</label>
        <input type="number" name="number" class="form-control" value="{{ $question->number }}" required>
    </div>
    <div class="mb-2">
        <label>Text</label>
        <textarea name="text" class="form-control" required>{{ $question->text }}</textarea>
    </div>
    <div class="mb-2">
        <label>Dimension</label>
        <input type="text" name="dimension" class="form-control" value="{{ $question->dimension }}">
    </div>
    <div class="mb-2">
        <label><input type="checkbox" name="reverse_coded" {{ $question->reverse_coded ? 'checked' : '' }}> Reverse Coded?</label>
    </div>
    <button class="btn btn-success">Update</button>
</form>
@endsection
