@extends('Partials.app', ['activeMenu' => $activeMenu])

@section('title')
    {{ config('app.name') }} 
@endsection


@section('page_title')
    Submit Response
@endsection

@section('content')
<h2>Survey for Year {{ $year }}</h2>
<form method="POST" action="{{ route('responses.store') }}">
    @csrf
    <div class="mb-2">
        <label>Department</label>
        <input type="text" name="department" class="form-control">
    </div>
    <div class="mb-2">
        <label>Group</label>
        <input type="text" name="group" class="form-control">
    </div>

    <h4>Questions</h4>
    @foreach($questions as $q)
        <div class="mb-3">
            <label>{{ $q->number }}. {{ $q->text }}</label><br>
            @for($i=1; $i<=5; $i++)
                <label>
                    <input type="radio" name="answers[{{ $q->id }}]" value="{{ $i }}" required> {{ $i }}
                </label>
            @endfor
        </div>
    @endforeach

    <button class="btn btn-primary">Submit Survey</button>
</form>
@endsection
