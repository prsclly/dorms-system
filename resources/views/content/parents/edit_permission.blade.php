@extends('layouts/contentParentLayout')

@section('title', 'Edit Leave Request')

@section('content')
<div class="container mt-4">
    <div class="card p-4 shadow-sm">
        <h4 class="mb-4">Edit Leave Request</h4>
        <form action="{{ route('parent.permissions.update', $permission->id) }}" method="POST" enctype="multipart/form-data" id="leaveForm">
            @csrf
            @method('PUT')

            <table class="table table-borderless">
                <tbody>
                    {{-- Leave Type --}}
                    <tr>
                        <td><label for="type">Leave Type<span class="text-danger">*</span></label></td>
                        <td>
                            <select name="type" class="form-control" id="type" required>
                                <option value="pesiar" {{ old('type', $permission->type) == 'pesiar' ? 'selected' : '' }}>Pesiar</option>
                                <option value="ib" {{ old('type', $permission->type) == 'ib' ? 'selected' : '' }}>Izin Bermalam</option>
                            </select>
                            @error('type')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </td>
                    </tr>

                    {{-- Reason --}}
                    <tr>
                        <td><label for="reason">Reason<span class="text-danger">*</span></label></td>
                        <td>
                            <input type="text" name="reason" class="form-control" value="{{ old('reason', $permission->reason) }}" required>
                            @error('reason')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </td>
                    </tr>

                    {{-- Start Date --}}
                    <tr>
                        <td><label for="start_date">Start Date<span class="text-danger">*</span></label></td>
                        <td>
                            <input type="date" name="start_date" id="start_date" class="form-control"
                                value="{{ old('start_date', $permission->start_date) }}" required>
                            @error('start_date')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </td>
                    </tr>

                    {{-- End Date (optional for Pesiar) --}}
                    <tr id="endDateRow" style="{{ old('type', $permission->type) == 'pesiar' ? 'display: none;' : '' }}">
                        <td><label for="end_date">End Date<span class="text-danger" id="endDateAsterisk">*</span></label></td>
                        <td>
                            <input type="date" name="end_date" id="end_date" class="form-control"
                                value="{{ old('end_date', $permission->end_date) }}"
                                {{ old('type', $permission->type) == 'ib' ? 'required' : '' }}>
                            @error('end_date')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="text-end">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
    const typeSelect = document.querySelector('#type');
    const endDateRow = document.querySelector('#endDateRow');
    const endDateInput = document.querySelector('#end_date');
    const endDateAsterisk = document.querySelector('#endDateAsterisk');
    const startDateInput = document.querySelector('#start_date');

    function updateEndDateVisibility() {
        if (typeSelect.value === 'pesiar') {
            endDateRow.style.display = 'none';
            endDateInput.removeAttribute('required');
            endDateAsterisk.style.display = 'none';

            // auto set end_date = start_date
            if (startDateInput.value) {
                endDateInput.value = startDateInput.value;
            }
        } else {
            endDateRow.style.display = '';
            endDateInput.setAttribute('required', true);
            endDateAsterisk.style.display = 'inline';
        }
    }

    typeSelect.addEventListener('change', updateEndDateVisibility);
    startDateInput.addEventListener('change', () => {
        if (typeSelect.value === 'pesiar') {
            endDateInput.value = startDateInput.value;
        }
    });
    window.addEventListener('DOMContentLoaded', updateEndDateVisibility);
</script>
@endsection
