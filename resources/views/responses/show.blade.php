@extends('Partials.app', ['activeMenu' => $activeMenu])

@section('title')
    {{ config('app.name') }} 
@endsection


@section('page_title')
    Response Details
@endsection

@section('content')
<h2>Response Details</h2>

<div class="card mb-3">
    <div class="card-body">
        <h5 class="card-title">Question</h5>
        <p class="card-text">{{ $response->question->number }}. {{ $response->question->text }}</p>
    </div>
</div>

<ul class="list-group mb-3">
    <li class="list-group-item"><strong>Year:</strong> {{ $response->question->year }}</li>
    <li class="list-group-item"><strong>Answer:</strong> {{ $response->answer }}</li>
    <li class="list-group-item"><strong>Department:</strong> {{ $response->department ?? '-' }}</li>
    <li class="list-group-item"><strong>Group:</strong> {{ $response->group ?? '-' }}</li>
    <li class="list-group-item"><strong>Submitted:</strong> {{ $response->submitted ? 'Yes' : 'No' }}</li>
    <li class="list-group-item"><strong>Submitted At:</strong> {{ $response->submitted_at ?? '-' }}</li>
</ul>

<a href="{{ route('responses.index') }}" class="btn btn-secondary">Back to Responses</a>
@endsection
