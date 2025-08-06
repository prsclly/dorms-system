@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Point Log')

@section('content')

@php
    use Carbon\Carbon;
    $expired = Carbon::parse($log->created_at)->diffInMinutes(now()) > 60;
@endphp

<style>
    .bg-appreciation {
        background-color: #d1e7dd !important;
        color: #0f5132;
    }

    .bg-violation {
        background-color: #f8d7da !important;
        color: #842029;
    }

    .bg-placeholder {
        background-color: #f8f9fa !important;
        color: #6c757d;
    }

    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type=number] {
        -moz-appearance: textfield;
    }
</style>

<div class="row">
    <div class="col-xl-8 mx-auto">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Edit Point Log - {{ $student->name }}</h5>
            </div>
            <div class="card-body">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>There were some problems with your input:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if ($expired)
                    <div class="alert alert-warning">
                        This log cannot be edited anymore. The time limit of 1 hour has passed.
                    </div>
                @endif

                <form method="POST"
                    action="{{ route('update_log', ['student_id' => $student->id, 'log_id' => $log->id]) }}"
                    @if ($expired) onsubmit="return false;" @endif>
                    @csrf
                    @method('PUT')

                    <fieldset @if($expired) disabled @endif>
                        <div class="mb-3">
                            <label for="date" class="form-label">Change Date</label>
                            <input type="datetime-local" class="form-control @error('date') is-invalid @enderror"
                                name="date" value="{{ old('date', \Carbon\Carbon::parse($log->date)->format('Y-m-d\TH:i')) }}" required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-control bg-placeholder text-dark @error('category') is-invalid @enderror"
                                name="category" id="category" required>
                                <option value="" disabled>-- Select Category --</option>
                                <option value="Appreciation" class="bg-appreciation"
                                    {{ old('category', $log->category) === 'Appreciation' ? 'selected' : '' }}>
                                    Appreciation (e.g., achievement, good deeds)
                                </option>
                                <option value="Violation" class="bg-violation"
                                    {{ old('category', $log->category) === 'Violation' ? 'selected' : '' }}>
                                    Violation (e.g., misconduct, lateness)
                                </option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="2" required>{{ old('description', $log->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="point_change" class="form-label">Points Changed</label>
                            <input type="number" class="form-control @error('point_change') is-invalid @enderror"
                                name="point_change" id="point_change" value="{{ old('point_change', $log->point_change) }}"
                                required>
                            @error('point_change')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </fieldset>

                    <div class="d-flex justify-content-between mt-4">
                        @if (!$expired)
                            <button type="submit" class="btn btn-success">Update</button>
                        @endif
                        <a href="{{ route('edit_student_point', ['id' => $student->id]) }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const pointChangeInput = document.getElementById("point_change");
        const categorySelect = document.getElementById("category");

        function enforceSignRule() {
            const category = categorySelect.value;
            const currentValue = parseInt(pointChangeInput.value) || 0;

            if (category === 'Appreciation') {
                if (currentValue < 0) pointChangeInput.value = Math.abs(currentValue);
                pointChangeInput.setAttribute("min", "1");
                pointChangeInput.removeAttribute("max");
            } else if (category === 'Violation') {
                if (currentValue > 0) pointChangeInput.value = -Math.abs(currentValue);
                pointChangeInput.setAttribute("max", "-1");
                pointChangeInput.removeAttribute("min");
            } else {
                pointChangeInput.removeAttribute("min");
                pointChangeInput.removeAttribute("max");
            }
        }

        function updateCategoryColor() {
            categorySelect.classList.remove('bg-appreciation', 'bg-violation', 'bg-placeholder');
            if (categorySelect.value === 'Appreciation') {
                categorySelect.classList.add('bg-appreciation');
            } else if (categorySelect.value === 'Violation') {
                categorySelect.classList.add('bg-violation');
            } else {
                categorySelect.classList.add('bg-placeholder');
            }
        }

        if (pointChangeInput && categorySelect) {
            pointChangeInput.addEventListener("input", enforceSignRule);
            categorySelect.addEventListener("change", function () {
                enforceSignRule();
                updateCategoryColor();
            });

            enforceSignRule();
            updateCategoryColor();
        }
    });
</script>
@endsection
