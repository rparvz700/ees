<a href="{{ route('questions.edit', $question->id) }}" class="btn btn-sm btn-warning">Edit</a>
<form action="{{ route('questions.destroy', $question->id) }}" method="POST" style="display:inline-block;">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-sm btn-danger delete-button" data-question-id="{{ $question->id }}">Delete</button>
</form>
