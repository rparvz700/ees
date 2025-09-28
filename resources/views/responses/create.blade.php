<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>{{ config('app.name') }} - Employee Survey</title>
    <meta name="description" content="Employee Survey Form">
    <meta name="robots" content="noindex, nofollow">

    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }
        
        .survey-container {
            background: white;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-radius: 15px;
            overflow: hidden;
            margin: 2rem auto;
            max-width: 800px;
        }
        
        .survey-header {
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .survey-header h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: 300;
        }
        
        .survey-header p {
            margin: 0.5rem 0 0 0;
            opacity: 0.9;
        }
        
        .survey-body {
            padding: 2rem;
        }
        
        .question-card {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }
        
        .question-card:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transform: translateY(-2px);
        }
        
        .question-number {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 1rem;
            flex-shrink: 0;
        }
        
        .question-text {
            font-size: 1.1rem;
            font-weight: 500;
            color: #2c3e50;
            margin-bottom: 1rem;
            line-height: 1.5;
        }
        
        .rating-container {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
            flex-wrap: wrap;
            margin: 1rem 0;
        }
        
        .rating-option {
            position: relative;
            flex: 1;
            min-width: 80px;
            max-width: 120px;
        }
        
        .rating-input {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            margin: 0;
            cursor: pointer;
        }
        
        .rating-label {
            display: block;
            background: white;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            padding: 0.75rem 0.5rem;
            text-align: center;
            font-weight: 500;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
        }
        
        .rating-input:checked + .rating-label {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: #667eea;
            color: white;
            transform: scale(1.05);
        }
        
        .rating-label:hover {
            border-color: #667eea;
            background: #f0f4ff;
        }
        
        .rating-input:checked + .rating-label:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .rating-scale {
            display: flex;
            justify-content: space-between;
            font-size: 0.85rem;
            color: #6c757d;
            margin-top: 0.5rem;
            padding: 0 0.5rem;
        }
        
        .submit-section {
            background: #f8f9fa;
            padding: 2rem;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }
        
        .btn-submit {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            color: white;
            padding: 0.75rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 25px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
            color: white;
        }
        
        .btn-outline-secondary {
            border: 2px solid #6c757d;
            color: #6c757d;
            padding: 0.75rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 25px;
            transition: all 0.3s ease;
            background: transparent;
        }
        
        .btn-outline-secondary:hover {
            background: #6c757d;
            border-color: #6c757d;
            color: white;
            transform: translateY(-1px);
        }
        
        .progress-indicator {
            height: 4px;
            background: #e9ecef;
            border-radius: 2px;
            margin: 1rem 0;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 2px;
            transition: width 0.5s ease;
            width: 0%;
        }
        
        .survey-footer {
            background: #2c3e50;
            color: white;
            text-align: center;
            padding: 1.5rem;
            font-size: 0.9rem;
        }
        
        .alert {
            border: none;
            border-radius: 10px;
            margin-bottom: 1.5rem;
        }
        
        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border-left: 4px solid #28a745;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f1b0b7 100%);
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
        
        @media (max-width: 768px) {
            .survey-container {
                margin: 1rem;
                border-radius: 10px;
            }
            
            .survey-header {
                padding: 1.5rem 1rem;
            }
            
            .survey-body {
                padding: 1.5rem 1rem;
            }
            
            .rating-container {
                gap: 0.3rem;
            }
            
            .rating-option {
                min-width: 70px;
            }
            
            .rating-label {
                padding: 0.5rem 0.25rem;
                font-size: 0.9rem;
            }
            
            .submit-section .d-flex {
                flex-direction: column;
                gap: 1rem !important;
            }
        }
    </style>
</head>

<body>
    <div class="survey-container">
        <!-- Professional Header -->
        <div class="survey-header">
            <h1><i class="fas fa-clipboard-list me-2"></i>{{ config('app.name') }}</h1>
            <p>Employee Survey {{ $submission->year }}</p>
        </div>

        <!-- Survey Body -->
        <div class="survey-body">
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="alert alert-success">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>{{ session('success') }}</strong>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Please review the following:</strong>
                    </div>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Progress Indicator -->
            <div class="progress-indicator">
                <div class="progress-fill" id="progressFill"></div>
            </div>
            @if($draft)
            <div class="text-center mb-4">
                <small class="text-muted">
                    <span id="progressText">0 of {{ count($questions) }}</span> questions completed
                </small>
            </div>
            @else
            <div class="text-center mb-4">
                <small class="text-muted">
                    This survey has been submitted and is read-only.
                </small>
            </div>
            @endif

            <!-- Survey Form -->
            <form action="{{ route('responses.store', $submission->id) }}" method="POST" id="surveyForm">
                @csrf
                <input type="hidden" name="submission_id" value="{{ $submission->id ?? '' }}">
                <input type="hidden" name="action" id="actionInput" value="">

                @foreach($questions as $index => $question)
                    @php
                        $response = $responsesByQuestion->get($question->id);
                    @endphp

                    <div class="question-card" data-question="{{ $index + 1 }}">
                        <div class="d-flex align-items-start">
                            <div class="question-number">{{ $question->number }}</div>
                            <div class="flex-grow-1">
                                <div class="question-text">{{ $question->text }}</div>
                                
                                <input type="hidden" name="responses[{{ $question->id }}][question_id]" value="{{ $question->id }}">
                                
                                <div class="rating-container">
                                    @for($i = 1; $i <= 5; $i++)
                                        <div class="rating-option">
                                            <input type="radio" 
                                                   name="responses[{{ $question->id }}][answer]" 
                                                   id="answer_{{ $question->id }}_{{ $i }}"
                                                   value="{{ $i }}"
                                                   class="rating-input"
                                                   {{ old("responses.$question->id.answer", $response?->answer) == $i ? 'checked' : '' }}>
                                            <label for="answer_{{ $question->id }}_{{ $i }}" class="rating-label">
                                                <div class="fw-bold">{{ $i }}</div>
                                                <div style="font-size: 0.75rem;">
                                                    @switch($i)
                                                        @case(1) Strongly<br>Disagree @break
                                                        @case(2) Disagree @break
                                                        @case(3) Neutral @break
                                                        @case(4) Agree @break
                                                        @case(5) Strongly<br>Agree @break
                                                    @endswitch
                                                </div>
                                            </label>
                                        </div>
                                    @endfor
                                </div>
                                
                                <div class="rating-scale">
                                    <span>Strongly Disagree</span>
                                    <span>Strongly Agree</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </form>
        </div>

        <!-- Submit Section -->
        @if($draft) 
        <div class="submit-section">
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <button type="button" class="btn btn-outline-secondary" id="draftBtn">
                    <i class="fas fa-save me-2"></i>
                    Save as Draft
                </button>
                <button type="button" class="btn btn-submit" id="submitBtn">
                    <i class="fas fa-paper-plane me-2"></i>
                    Submit Survey
                </button>
            </div>
            <div class="mt-3">
                <small class="text-muted">
                    <strong>Save as Draft:</strong> Save your progress without validation. You can complete it later.<br>
                    <strong>Submit Survey:</strong> Final submission after answering all questions.
                </small>
            </div>
        </div>
        @endif

        <!-- Professional Footer -->
        <div class="survey-footer">
            <div class="container-fluid">
                <p class="mb-0">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved. | 
                    <i class="fas fa-shield-alt me-1"></i> Your privacy is protected
                </p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('surveyForm');
            const progressFill = document.getElementById('progressFill');
            const progressText = document.getElementById('progressText');
            const totalQuestions = {{ count($questions) }};
            
            function updateProgress() {
                const answeredQuestions = form.querySelectorAll('input[type="radio"]:checked').length;
                const percentage = (answeredQuestions / totalQuestions) * 100;
                
                progressFill.style.width = percentage + '%';
                progressText.textContent = `${answeredQuestions} of ${totalQuestions}`;
                
                // Add completion feedback
                if (answeredQuestions === totalQuestions) {
                    progressText.textContent += ' - Ready to submit!';
                    progressText.className = 'text-success fw-bold';
                } else {
                    progressText.className = 'text-muted';
                }
            }
            
            // Update progress when radio buttons change
            form.addEventListener('change', updateProgress);
            
            // Initial progress calculation
            updateProgress();
            
            // Smooth scroll to next question after answering
            form.addEventListener('change', function(e) {
                if (e.target.type === 'radio') {
                    const currentCard = e.target.closest('.question-card');
                    const nextCard = currentCard.nextElementSibling;
                    
                    if (nextCard && nextCard.classList.contains('question-card')) {
                        setTimeout(() => {
                            nextCard.scrollIntoView({ 
                                behavior: 'smooth', 
                                block: 'center' 
                            });
                        }, 300);
                    }
                }
            });
            
            // Button click handlers
            const draftBtn = document.getElementById('draftBtn');
            const submitBtn = document.getElementById('submitBtn');
            const actionInput = document.getElementById('actionInput');
            
            draftBtn.addEventListener('click', function() {
                actionInput.value = 'draft';
                
                // Show loading state for draft
                this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving Draft...';
                this.disabled = true;
                submitBtn.disabled = true;
                
                // Submit the form
                form.submit();
            });
            
            submitBtn.addEventListener('click', function() {
                const answeredQuestions = form.querySelectorAll('input[type="radio"]:checked').length;
                
                // Validate for submit
                if (answeredQuestions < totalQuestions) {
                    alert(`Please answer all questions. You have ${totalQuestions - answeredQuestions} questions remaining.`);
                    return false;
                }
                
                // Confirmation for final submit
                if (!confirm('Are you sure you want to submit your survey? This action cannot be undone.')) {
                    return false;
                }
                
                actionInput.value = 'submit';
                
                // Show loading state for submit
                this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Submitting...';
                this.disabled = true;
                draftBtn.disabled = true;
                
                // Submit the form
                form.submit();
            });
            
            // Remove the old form submission handler as we're handling it with buttons now
            // form.addEventListener('submit', function(e) { ... });
            
            // Auto-save draft every 2 minutes (optional feature)
            let autoSaveInterval;
            let hasChanges = false;
            
            // Track changes
            form.addEventListener('change', function() {
                hasChanges = true;
                clearInterval(autoSaveInterval);
                
                // Set up auto-save after 2 minutes of inactivity
                autoSaveInterval = setTimeout(function() {
                    if (hasChanges) {
                        // Create a temporary form submission for draft
                        const formData = new FormData(form);
                        formData.set('action', 'draft');
                        
                        fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || form.querySelector('input[name="_token"]').value
                            }
                        }).then(response => {
                            if (response.ok) {
                                // Show subtle notification
                                showAutoSaveNotification();
                                hasChanges = false;
                            }
                        }).catch(error => {
                            console.log('Auto-save failed:', error);
                        });
                    }
                }, 120000); // 2 minutes
            });
            
            function showAutoSaveNotification() {
                // Create a subtle notification
                const notification = document.createElement('div');
                notification.className = 'alert alert-success position-fixed';
                notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; opacity: 0; transition: opacity 0.3s ease;';
                notification.innerHTML = '<i class="fas fa-check-circle me-2"></i>Draft auto-saved';
                
                document.body.appendChild(notification);
                
                // Fade in
                setTimeout(() => notification.style.opacity = '1', 100);
                
                // Fade out and remove after 3 seconds
                setTimeout(() => {
                    notification.style.opacity = '0';
                    setTimeout(() => notification.remove(), 300);
                }, 3000);
            }
        });
    </script>
</body>
</html>