@extends('layouts/contentNavbarLayout')

@section('title', 'Add New Point Log')

@section('content')
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
    </style>

    <div class="row">
        <div class="col-xl-8 mx-auto">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Add New Point Log - {{ $student->name }}</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('store_log', ['id' => $student->id]) }}">
                        @csrf
                        <input type="hidden" name="nim" value="{{ $student->nim }}">

                        <div class="mb-3">
                            <label for="date" class="form-label">Change Date</label>
                            <input type="datetime-local" class="form-control" name="date" required>
                        </div>

                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-control bg-placeholder text-dark" name="category" id="category" required>
                                <option value="" disabled selected>-- Select Category --</option>
                                <option value="Appreciation" class="bg-appreciation text-dark">Appreciation (e.g.,
                                    achievement, good deeds)</option>
                                <option value="Violation" class="bg-violation text-dark">Violation (e.g., misconduct,
                                    lateness)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="2" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="point_change" class="form-label">Points Changed</label>
                            <input type="number" class="form-control" name="point_change" id="point_change" required>
                        </div>

                        <div class="mb-3">
                            <label for="previous_point" class="form-label">Previous Point</label>
                            <input type="number" class="form-control" name="previous_point" id="previous_point"
                                value="{{ $student->pointLogs->last()?->new_point ?? $student->total_point }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="new_point" class="form-label">New Point</label>
                            <input type="number" class="form-control" name="new_point" id="new_point" readonly>
                        </div>

                        <!-- Tombol sejajar: Submit kiri, Cancel kanan -->
                        <div class="d-flex justify-content-between mt-4">
                            <button type="submit" class="btn btn-success">Submit</button>
                            <a href="{{ route('edit_student_point', ['id' => $student->id]) }}"
                                class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const pointChangeInput = document.getElementById("point_change");
            const previousPointInput = document.getElementById("previous_point");
            const newPointInput = document.getElementById("new_point");
            const categorySelect = document.getElementById("category");

            function updateNewPoint() {
                const change = parseInt(pointChangeInput.value) || 0;
                const previous = parseInt(previousPointInput.value) || 0;
                newPointInput.value = previous + change;
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

            pointChangeInput.addEventListener("input", updateNewPoint);
            categorySelect.addEventListener("change", updateCategoryColor);

            updateNewPoint();       // inisialisasi
            updateCategoryColor();  // inisialisasi
        });
    </script>
@endsection