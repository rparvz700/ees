@extends('Partials.app', ['activeMenu' => $activeMenu])

@section('title')
    {{ config('app.name') }} 
@endsection


@section('page_title')
    Response Details
@endsection

@section('content')

<div class="content">
    <!-- Submission Overview -->
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">
                <i class="fa fa-clipboard-list me-1 text-muted"></i>
                Submission #{{ $submission['id'] }}
            </h3>
            <div class="block-options">
                <span class="badge {{ $submission['submitted'] ? 'bg-success' : 'bg-warning' }}">
                    {{ $submission['submitted'] ? 'Submitted' : 'Draft' }}
                </span>
            </div>
        </div>
        <div class="block-content">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th class="fw-semibold text-muted" width="30%">Year:</th>
                            <td>{{ $submission['year'] }}</td>
                        </tr>
                        <tr>
                            <th class="fw-semibold text-muted">Employee Code:</th>
                            <td><code>{{ $submission['employee_code'] }}</code></td>
                        </tr>
                        <tr>
                            <th class="fw-semibold text-muted">HR ID:</th>
                            <td><code>{{ $submission['hr_id'] }}</code></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th class="fw-semibold text-muted" width="30%">Submitted At:</th>
                            <td>{{ \Carbon\Carbon::parse($submission['submitted_at'])->format('M d, Y \a\t h:i A') }}</td>
                        </tr>
                        <tr>
                            <th class="fw-semibold text-muted">Total Responses:</th>
                            <td>{{ count($submission['responses']) }}</td>
                        </tr>
                        <tr>
                            <th class="fw-semibold text-muted">Status:</th>
                            <td>
                                @if($submission['submitted'])
                                    <span class="text-success"><i class="fa fa-check-circle"></i> Complete</span>
                                @else
                                    <span class="text-warning"><i class="fa fa-clock"></i> In Progress</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Statistics -->
    @if(count($submission['responses']) > 0)
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">
                <i class="fa fa-chart-bar me-1 text-muted"></i>
                Response Summary
            </h3>
        </div>
        <div class="block-content">
            <div class="row text-center">
                <div class="col-6 col-md-3">
                    <div class="fs-2 fw-bold text-primary">{{ $totalResponses }}</div>
                    <div class="fw-semibold text-uppercase text-muted">Total Questions</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="fs-2 fw-bold text-info">{{ $averageScore }}</div>
                    <div class="fw-semibold text-uppercase text-muted">Average Score</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="fs-2 fw-bold text-success">{{ $highScores }}</div>
                    <div class="fw-semibold text-uppercase text-muted">High Scores (4-5)</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="fs-2 fw-bold text-danger">{{ $lowScores }}</div>
                    <div class="fw-semibold text-uppercase text-muted">Low Scores (1-2)</div>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-12">
                    <h6 class="fw-semibold text-muted mb-2">Dimensions Covered:</h6>
                    @foreach($uniqueDimensions as $dimension)
                        <span class="badge bg-secondary me-1 mb-1">{{ $dimension }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- All Responses -->
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">
                <i class="fa fa-list-alt me-1 text-muted"></i>
                All Question Responses ({{ count($submission['responses']) }})
            </h3>
        </div>
        <div class="block-content">
            @forelse($submission['responses'] as $index => $response)
                <div class="response-item border rounded p-4 mb-4 {{ $index % 2 == 0 ? 'bg-light' : 'bg-white' }}">
                    <!-- Question Header -->
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h4 class="h5 mb-1">
                                <span class="badge bg-primary me-2">Q{{ $response['question']['number'] }}</span>
                                Question {{ $response['question']['number'] }}
                            </h4>
                            <small class="text-muted">Response ID: {{ $response['id'] }}</small>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold fs-3 text-{{ $response['answer'] >= 4 ? 'success' : ($response['answer'] >= 3 ? 'warning' : 'danger') }}">
                                {{ $response['answer'] }}/5
                            </div>
                            <small class="text-muted">Score</small>
                        </div>
                    </div>

                    <!-- Question Text -->
                    <div class="mb-3 p-3 bg-white border-start border-primary border-4">
                        <p class="mb-0 fs-6 fw-medium">{{ $response['question']['text'] }}</p>
                    </div>

                    <!-- Question & Response Details -->
                    <div class="row">
                        <div class="col-lg-8">
                            <!-- Question Properties -->
                            <div class="row mb-3">
                                <div class="col-sm-6">
                                    <span class="fw-semibold text-muted d-block mb-1">Dimensions:</span>
                                    @foreach($response['question']['dimension'] as $dimension)
                                        <span class="badge bg-info me-1 mb-1">{{ $dimension }}</span>
                                    @endforeach
                                </div>
                                <div class="col-sm-6">
                                    <span class="fw-semibold text-muted d-block mb-1">Reverse Coded:</span>
                                    <span class="badge {{ $response['question']['reverse_coded'] ? 'bg-warning' : 'bg-secondary' }}">
                                        {{ $response['question']['reverse_coded'] ? 'Yes' : 'No' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Response Context -->
                            <div class="row">
                                <div class="col-sm-6">
                                    <span class="fw-semibold text-muted d-block mb-1">Department:</span>
                                    <span class="text-dark">{{ $response['department'] ?: 'Not specified' }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <span class="fw-semibold text-muted d-block mb-1">Group:</span>
                                    <span class="text-dark">{{ $response['group'] ?: 'Not specified' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Answer Visualization -->
                        <div class="col-lg-4">
                            <div class="text-center p-3 border rounded">
                                <div class="mb-2">
                                    <span class="fw-semibold text-muted">Response Level</span>
                                </div>
                                @php
                                    $answerLabels = [
                                        1 => 'Strongly Disagree',
                                        2 => 'Disagree', 
                                        3 => 'Neutral',
                                        4 => 'Agree',
                                        5 => 'Strongly Agree'
                                    ];
                                    $answerColors = [
                                        1 => 'danger',
                                        2 => 'warning', 
                                        3 => 'secondary',
                                        4 => 'success',
                                        5 => 'success'
                                    ];
                                @endphp
                                <div class="badge bg-{{ $answerColors[$response['answer']] }} fs-6 p-2">
                                    {{ $answerLabels[$response['answer']] }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar for Visual Score -->
                    <div class="mt-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <small class="text-muted">Score Progress</small>
                            <small class="text-muted">{{ ($response['answer'] / 5) * 100 }}%</small>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-{{ $answerColors[$response['answer']] }}" 
                                 style="width: {{ ($response['answer'] / 5) * 100 }}%"></div>
                        </div>
                    </div>
                </div>

                @if(($index + 1) % 5 == 0 && ($index + 1) < count($submission['responses']))
                    <!-- Page Break Indicator -->
                    <div class="text-center my-4">
                        <hr class="w-50 mx-auto">
                        <span class="badge bg-light text-dark px-3">{{ $index + 1 }} of {{ count($submission['responses']) }} responses shown</span>
                        <hr class="w-50 mx-auto">
                    </div>
                @endif
            @empty
                <div class="text-center py-5">
                    <i class="fa fa-inbox fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No responses found</h4>
                    <p class="text-muted">This submission doesn't contain any question responses yet.</p>
                </div>
            @endforelse
        </div>
    </div>

    
</div>
@endsection

@section('scripts')
<script>
// Add any interactive features if needed
$(document).ready(function() {
    // Optional: Add tooltips to badges or other elements
    $('[data-bs-toggle="tooltip"]').tooltip();
});
</script>
@endsection
