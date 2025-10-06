@extends('Partials.app', ['activeMenu' => 'responses'])

@section('title')
    {{ config('app.name') }}
@endsection

@section('page_title')
    Reports
@endsection

@section('styles')
    <style>
        .report-table input[type="number"] {
            width: 90px;
            text-align: center;
        }
        .report-table th, .report-table td {
            vertical-align: middle !important;
        }
        .report-actions button {
            margin-right: 4px;
        }
        .report-name {
            font-weight: 600;
            color: #2e3e4e;
        }
        .report-desc {
            font-size: 0.85rem;
            color: #6c757d;
        }
    </style>
@endsection

@section('content')
<div class="content">
    <div class="block block-rounded">
        {{-- Response message --}}
        @if (Session::has('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                <small class="mb-0">{{ Session::get('success') }}</small>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        {{-- End response message --}}

        <div class="block-header block-header-default">
            <h3 class="block-title">Reports</h3>
        </div>

        <div class="block-content fs-sm">
            <div class="table-responsive">
                <table class="table table-sm table-bordered table-striped table-vcenter report-table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 50px;">S/N</th>
                            <th>Reports</th>
                            <th class="text-center" style="width: 120px;">Year</th>
                            <th class="text-center" style="width: 200px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $reports = [
                                [
                                    'name' => 'Department EEI Report',
                                    'desc' => 'Shows average EEI score per department.'
                                ],
                                [
                                    'name' => 'Department Dimension Scores Report',
                                    'desc' => 'Displays average score per department per dimension.'
                                ],
                                [
                                    'name' => 'Employee Dimension Scores Report',
                                    'desc' => 'Displays EEI score per employee per dimension.'
                                ],
                            ];
                        @endphp

                        @foreach ($reports as $i => $report)
                            <tr>
                                <td class="text-center">{{ $i + 1 }}</td>
                                <td>
                                    <div class="report-name">{{ $report['name'] }}</div>
                                    <div class="report-desc">{{ $report['desc'] }}</div>
                                </td>
                                <td class="text-center">
                                    <input type="number" 
                                           class="form-control form-control-sm year-input" 
                                           value="{{ date('Y') }}" 
                                           min="2025" 
                                           max="{{ date('Y') + 1 }}">
                                </td>
                                <td class="text-center report-actions">
                                    <button class="btn btn-sm btn-secondary view-btn">
                                        <i class="fa fa-eye me-1"></i> View
                                    </button>
                                    <button class="btn btn-sm btn-success export-btn">
                                        <i class="fa fa-file-export me-1"></i> Export
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const baseUrl = "{{ url('/') }}"; // ✅ Laravel dynamically sets correct base URL (e.g., http://localhost/ees/public)

        document.querySelectorAll('.view-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const row = this.closest('tr');
                const year = row.querySelector('.year-input').value || new Date().getFullYear();
                const reportName = row.querySelector('.report-name').innerText.trim();

                // Construct full URL safely
                const url = `${baseUrl}/reports/view/${encodeURIComponent(reportName)}/${year}`;
                window.open(url, '_blank');
            });
        });

        document.querySelectorAll('.export-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const row = this.closest('tr');
                const year = row.querySelector('.year-input').value || new Date().getFullYear();
                const reportName = row.querySelector('.report-name').innerText.trim();

                // Construct full URL safely
                const url = `${baseUrl}/reports/export?name=${encodeURIComponent(reportName)}&year=${year}`;
                window.open(url, '_blank');
            });
        });
    });
</script>
@endsection

