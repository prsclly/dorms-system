@extends('layouts/contentNavbarLayout')

@section('title', 'Add Student Point')

@section('content')
    <div class="row">
        <div class="col-xl-8 mx-auto">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Add Student Point</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('add_student_point') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="student_name">Student Name</label>
                            <input type="text" class="form-control" id="student_name" name="student_name"
                                placeholder="John Doe" required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="nim">Student NIM</label>
                            <input type="text" class="form-control" id="nim" name="nim" placeholder="2023123001" required />

                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="total_point">Total Point</label>
                            <input type="number" class="form-control" id="total_point" name="total_point" value="300"
                                readonly />
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="{{ route('add_student_point') }}" class="btn btn-secondary ms-2">Cancel</a>
                        <button type="button" class="btn btn-warning ms-auto position-absolute"
                            style="right: 10px; top: 10px;" onclick="window.history.back()">Back</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
