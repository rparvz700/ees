@extends('Partials.app', ['activeMenu' => $activeMenu])

@section('title')
    {{ config('app.name') }} 
@endsection


@section('page_title')
    Responses
@endsection

@section('content')
<h2>All Responses</h2>

<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>Year</th>
        <th>Question</th>
        <th>Answer</th>
        <th>Department</th>
        <th>Group</th>
        <th>Submitted At</th>
    </tr>
    @foreach($responses as $r)
    <tr>
        <td>{{ $r->id }}</td>
        <td>{{ $r->question->year }}</td>
        <td>{{ $r->question->text }}</td>
        <td>{{ $r->answer }}</td>
        <td>{{ $r->department }}</td>
        <td>{{ $r->group }}</td>
        <td>{{ $r->submitted_at }}</td>
    </tr>
    @endforeach
</table>

{{ $responses->links() }}
@endsection
