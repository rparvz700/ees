@extends('Partials.app', ['activeMenu' => 'reports'])

@section('title')
    {{ $reportName }} | {{ config('app.name') }}
@endsection

@section('page_title')
    {{ $reportName }}
@endsection

@section('styles')
    <style>
        .table thead th {
            background-color: #f8f9fa;
            font-weight: 600;
            text-transform: capitalize;
        }
        .report-header {
            margin-bottom: 1rem;
        }
        .report-header h4 {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .report-header small {
            color: #6c757d;
        }
        .no-data {
            text-align: center;
            color: #777;
            padding: 30px 0;
            font-size: 0.95rem;
        }
    </style>
@endsection

@section('content')
<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default report-header">
            <div>
                <h4 class="mb-0">{{ $reportName }}</h4>
                @if ($year)
                    <small>Report Year: <strong>{{ $year }}</strong></small>
                @endif
            </div>
            <div class="block-options">
                <a href="{{ route('reports.index') }}" class="btn btn-sm btn-secondary">
                    <i class="fa fa-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>

        <div class="block-content fs-sm">
            @if (empty($data) || count($data) === 0)
                <div class="no-data">
                    <i class="fa fa-info-circle me-1"></i> No data available for this report.
                </div>
            @else
                @php
                    // Normalize data to always be a numerically indexed array
                    $isAssoc = array_keys($data) !== range(0, count($data) - 1);
                    $rows = $isAssoc ? array_values($data) : $data;

                    // Get first row to extract column headers
                    $firstRow = reset($rows);
                    $columns = array_keys((array) $firstRow);
                @endphp

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-sm align-middle">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;">#</th>
                                @foreach($columns as $col)
                                    <th>{{ ucwords(str_replace('_', ' ', $col)) }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rows as $index => $row)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    @foreach($columns as $col)
                                        <td>
                                            @php $value = $row[$col] ?? '-'; @endphp
                                            @if(is_array($value))
                                                {{ json_encode($value) }}
                                            @else
                                                {{ $value }}
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
