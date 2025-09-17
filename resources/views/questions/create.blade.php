@extends('Partials.app', ['activeMenu' => 'questions'])

@section('title')
    {{ config('app.name') }} 
@endsection

@section('page_title')
    Add Question
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">
@endsection

@section('content')
<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">Add Question</h3>
        </div>
        <div class="block-content fs-sm data-content">
            <form class="mb-4" action="{{ route('questions.store') }}" method="POST" autocomplete="off">
                @csrf
                <!-- Static Year Field -->
                <div class="row">
                    <div class="col-md-6 col-sm-12 mb-4">
                        <label class="form-label" for="year">Year<span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="year" value="{{ old('year', date('Y')) }}" min="2025" max="2100" step="1" required>
                    </div>
                </div>

                <!-- Dynamic Question Container -->
                <div id="questions-container">
                    <div class="question-item border rounded p-3 mb-3">
                        <h3 class="question-number-label mb-3">Question 1</h3>
                        <input type="hidden" name="questions[0][number]" value="1" class="question-number-input">
                        <div class="row">
                            
                            <div class="col-md-6 col-sm-12 mb-4">
                                <label class="form-label">Dimension<span class="text-danger">*</span></label>
                                <select class="js-select2 form-select" name="questions[0][dimension][]" multiple="multiple" data-placeholder="Select Dimensions" required>
                                    @php
                                        $allDimensions = ['Leadership','Skills','Wellbeing','Attrition Risk','Engagement','Performance'];
                                        $selected = old('questions.0.dimension', []);
                                    @endphp
                                    @foreach($allDimensions as $dim)
                                        <option value="{{ $dim }}" {{ in_array($dim, $selected) ? 'selected' : '' }}>{{ $dim }}</option>
                                    @endforeach
                                </select>
                                @error('questions.0.dimension')<div class="text-danger"><small>{{ $message }}</small></div>@enderror
                            </div>

                            <div class="col-md-4 col-sm-12 mb-4">
                                <label class="form-label">Reverse Coded?</label>
                                <select class="form-select" name="questions[0][reverse_coded]">
                                    <option value="0" {{ old('questions.0.reverse_coded') == 0 ? 'selected' : '' }}>No</option>
                                    <option value="1" {{ old('questions.0.reverse_coded') == 1 ? 'selected' : '' }}>Yes</option>
                                </select>
                            </div>
                            <div class="col-12 mb-4">
                                <label class="form-label">Question Text<span class="text-danger">*</span></label>
                                <textarea class="form-control" name="questions[0][text]" rows="3" required>{{ old('questions.0.text') }}</textarea>
                                @error('questions.0.text')<div class="text-danger"><small>{{ $message }}</small></div>@enderror
                            </div>
                            <div class="col-md-6 col-sm-12 mb-4 d-flex align-items-end">
                                <button type="button" class="btn btn-danger btn-sm remove-question" style="display:none;">Remove</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <button type="button" class="btn btn-success btn-sm" id="add-question">Add Another Question</button>
                </div>

                <button type="submit" class="btn btn-primary">Save Questions</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/plugins/select2/js/select2.full.js') }}"></script>

<script>
let questionIndex = 1;

function updateQuestionNumbers() {
    const questionItems = document.querySelectorAll('.question-item');
    questionItems.forEach((item, index) => {
        const questionNumber = index + 1;
        const label = item.querySelector('.question-number-label');
        const hiddenInput = item.querySelector('.question-number-input');
        
        label.textContent = `Question ${questionNumber}`;
        hiddenInput.value = questionNumber;
    });
}

document.getElementById('add-question').addEventListener('click', function() {
    const container = document.getElementById('questions-container');
    
    // Get the original template and clone it BEFORE any Select2 operations
    const originalTemplate = container.children[0];
    
    // Temporarily destroy Select2 on original to get clean HTML
    const originalSelect = originalTemplate.querySelector('.js-select2');
    const wasSelect2Initialized = $(originalSelect).hasClass('select2-hidden-accessible');
    
    if (wasSelect2Initialized) {
        $(originalSelect).select2('destroy');
    }
    
    // Clone the clean template
    const template = originalTemplate.cloneNode(true);
    
    // Re-initialize Select2 on the original immediately
    if (wasSelect2Initialized) {
        $(originalSelect).select2({
            placeholder: "Select Dimensions",
            allowClear: true
        });
    }
    
    questionIndex++;
    
    // Update indices and clear values in the cloned template
    template.querySelectorAll('input, textarea, select').forEach(el => {
        const currentIndex = questionIndex - 1;
        el.name = el.name.replace(/\[\d+\]/, `[${currentIndex}]`);
        
        if (el.classList.contains('js-select2')) {
            // Clear all selections and reset to default state
            el.querySelectorAll('option').forEach(opt => opt.selected = false);
            // Remove any Select2 attributes that might have been cloned
            el.removeAttribute('data-select2-id');
            el.classList.remove('select2-hidden-accessible');
            el.removeAttribute('tabindex');
            el.removeAttribute('aria-hidden');
        } else if (el.classList.contains('question-number-input')) {
            // Will be set by updateQuestionNumbers()
        } else {
            el.value = el.tagName === 'SELECT' && el.name.includes('reverse_coded') ? '0' : '';
        }
    });
    
    // Remove any cloned Select2 wrapper elements
    template.querySelectorAll('.select2').forEach(el => el.remove());
    
    // Remove data-select2-id attributes from all elements
    template.querySelectorAll('[data-select2-id]').forEach(el => {
        el.removeAttribute('data-select2-id');
    });
    
    // Show remove button and add event listener
    const removeBtn = template.querySelector('.remove-question');
    removeBtn.style.display = 'block';
    removeBtn.addEventListener('click', function() {
        // Destroy select2 instance before removing
        $(template).find('.js-select2').select2('destroy');
        template.remove();
        updateQuestionNumbers(); // Renumber remaining questions
    });
    
    container.appendChild(template);
    
    // Initialize Select2 for the new template only
    $(template).find('.js-select2').select2({
        placeholder: "Select Dimensions",
        allowClear: true
    });
    
    updateQuestionNumbers(); // Update all question numbers
    // Scroll to the newly added question with smooth animation
    template.scrollIntoView({ 
        behavior: 'smooth', 
        block: 'start' 
    });
});

// Initialize select2 on page load
$(document).ready(function() {
    $('.js-select2').select2({
        placeholder: "Select Dimensions",
        allowClear: true
    });
});

// Show remove button for existing items when there are multiple
document.addEventListener('DOMContentLoaded', function() {
    const items = document.querySelectorAll('.question-item');
    if (items.length > 1) {
        items.forEach(item => {
            const removeBtn = item.querySelector('.remove-question');
            removeBtn.style.display = 'block';
            removeBtn.addEventListener('click', function() {
                $(item).find('.js-select2').select2('destroy');
                item.remove();
                updateQuestionNumbers(); // Renumber remaining questions
            });
        });
    }
});
</script>
@endsection