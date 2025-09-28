@extends('Partials.app', ['activeMenu' => $activeMenu])

@section('title')
    {{ config('app.name') }} 
@endsection

@section('page_title')
    Announce Survey
@endsection

@section('content')
<div class="content">
    <div class="block block-rounded">
        {{-- Response message --}}
        @if (Session::has('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <small class="mb-0">
                {{ Session::get('success') }}
            </small>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            <small class="mb-0">{{ session('error') }}</small>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        {{-- End response message --}}
        <div class="block-header block-header-default">
            <h3 class="block-title">Create announcement</h3>
        </div>
        <div class="block-content fs-sm">
            <form method="POST" action="{{ route('announce.store') }}">
                @csrf
                
                <!-- Year -->
                <div class="mb-4">
                    <label class="form-label">Year <span class="text-danger">*</span></label>
                    <input type="number" 
                           name="year" 
                           class="form-control" 
                           value="{{ old('year', date('Y')) }}" 
                           min="2025" max="2100" step="1" required>
                </div>

                <!-- Department -->
                <div class="mb-4">
                    <label class="form-label">Department <span class="text-danger">*</span></label>
                    <input type="text" 
                           name="department" 
                           class="form-control" 
                           value="" 
                           placeholder="Enter department name" 
                           required>
                </div>

                <!-- Group -->
                <div class="mb-4">
                    <label class="form-label">Group/Role</label>
                    <select name="is_tech" class="form-select">
                        <option value="">-- Select Group --</option>
                        <option value="Tech">Tech</option>
                        <option value="Non-Tech">Non-Tech</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <!-- Submit button -->
                <div class="mb-4">
                    <button type="submit" class="btn btn-primary">Announce</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
