@extends('layouts/contentResidentLayout')

@section('title', 'Resident Report History')

@section('content')

<style>
  .report-item {
    transition: all 0.2s ease;
  }

  .report-item:hover {
    background-color: #f8f9fa;
    transform: scale(1.01);
  }

  .cursor-pointer {
    cursor: pointer;
  }

  .text-truncate-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }
</style>

@if (session('success'))
<div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
  <div id="feedbackToast" class="toast align-items-center text-white bg-success border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body">
        {{ session('success') }}
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
</div>
@endif

<div class="row">
  <div class="col-md-4">
    <div class="card">
      <div class="card-header d-flex justify-content-between">
        <h5 class="card-title mb-0">History Report</h5>
        <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addReportModal">Add New Report</a>
      </div>
      <div class="card-body">
        <div class="row g-2">
          @foreach($reports as $report)
            <div class="col-12">
              <div class="card report-item cursor-pointer" data-id="{{ $report->id }}">
                <div class="card-body p-2 d-flex justify-content-between align-items-start">
                  <div class="me-2 flex-grow-1">
                    <h6 class="card-title mb-1">{{ $report->category }}</h6>
                    <p class="card-text text-truncate-2 mb-1">{{ $report->description }}</p>
                    <small class="text-muted">{{ $report->created_at->format('d M Y H:i') }}</small>
                  </div>
                  @if($report->photo)
                    <img src="{{ asset('storage/' . $report->photo) }}" alt="Report Photo" style="width: 80px; height: 80px; object-fit: cover;" class="rounded">
                  @endif
                </div>
              </div>
            </div>
          @endforeach
        </div>
        <div class="mt-3">
          {{ $reports->links('vendor.pagination.bootstrap-5') }}
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-8" id="report-detail">
    <div class="card">
      <div class="card-body">
        <p class="text-muted">Select a report to see the detail.</p>
      </div>
    </div>
  </div>
</div>

<!-- Modal Add New Report -->
<div class="modal fade" id="addReportModal" tabindex="-1" aria-labelledby="addReportModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form class="modal-content" action="{{ route('resident.report.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="_method" value="POST" id="methodField">
      <div class="modal-header">
        <h5 class="modal-title" id="addReportModalLabel">Add New Report</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body row g-3">
        <div class="col-md-6">
          <label class="form-label">Name</label>
          <input type="text" class="form-control" name="name" value="{{ Auth::guard('resident')->user()->name ?? '-' }}" readonly>
        </div>
        <div class="col-md-6">
          <label class="form-label">Room Number</label>
          <input type="text" class="form-control" name="room_number" value="{{ Auth::guard('resident')->user()->room_number ?? '-' }}" readonly>
        </div>
        <div class="col-md-6">
          <label class="form-label">Date</label>
          <input type="text" class="form-control" value="{{ now()->format('d M Y') }}" readonly>
        </div>
        <div class="col-md-6">
          <label class="form-label">Category</label>
          <select name="category" class="form-select" required>
            <option disabled selected>Choose category</option>
            <option value="Electrical">Electrical</option>
            <option value="Plumbing">Plumbing</option>
            <option value="Air Conditioning">Air Conditioning</option>
            <option value="Furniture">Furniture</option>
            <option value="Cleaning">Cleaning</option>
          </select>
        </div>
        <div class="col-md-12">
          <label class="form-label">Photo (optional)</label>
          <input type="file" name="photo" class="form-control" accept="image/*">
          <small id="photo-file-name" class="text-muted mt-1 d-block"></small>
        </div>
        <div class="col-md-12">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-control" rows="4" required></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Submit</button>
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Feedback -->
<div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" method="POST" action="{{ route('resident.feedback.submit') }}">
      @csrf
      <input type="hidden" name="report_id" id="feedbackReportId">
      <div class="modal-header">
        <h5 class="modal-title" id="feedbackModalLabel">Submit Feedback</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="comment" class="form-label">Your Feedback</label>
          <textarea name="comment" class="form-control" rows="4" required></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Submit Feedback</button>
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div>
</div>
@endsection

@section('page-script')
<script>
document.querySelectorAll('.report-item').forEach(item => {
  item.addEventListener('click', function() {
    const reportId = this.dataset.id;
    fetch(`/resident/report-history/detail/${reportId}`)
      .then(res => res.json())
      .then(data => {
        const container = document.getElementById('report-detail');

        const isPending = data.status === 'Pending';
        const badgeClass = {
          'Pending': 'bg-warning',
          'In Progress': 'bg-info',
          'Completed': 'bg-success',
          'Rejected': 'bg-danger'
        }[data.status] || 'bg-secondary';

        const actionButtons = isPending ? `
          <div class="d-flex gap-2">
            <button class="btn btn-sm btn-warning" onclick="editReport(${reportId})">
              <i class="bx bx-edit-alt fs-5"></i>
            </button>
            <form method="POST" action="/resident/report-history/delete/${reportId}" class="d-inline delete-form-${reportId}">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <input type="hidden" name="_method" value="DELETE">
              <button class="btn btn-sm btn-danger" type="submit" onclick="return confirm('Are you sure you want to delete this report?')">
                <i class="bx bx-trash"></i>
              </button>
            </form>
          </div>` : '';

        const hasFeedback = data.has_feedback;

        const feedbackButton = (data.status === 'Completed' && !hasFeedback)
          ? `<button class="btn btn-outline-primary mt-3" data-bs-toggle="modal" data-bs-target="#feedbackModal" onclick="setFeedbackReportId(${data.id})">
               Give Feedback
             </button>` : '';

        container.innerHTML = `
        <div class="card">
          <div class="card-body">
            <div class="d-flex align-items-start">
              ${data.image ? `
                <img src="/storage/${data.image}" alt="Report Photo" style="width: 200px; height: 200px; object-fit: cover;" class="me-3 rounded shadow-sm">
              ` : ''}

              <div class="ms-3 flex-grow-1">
                <h4 class="mb-2 fw-bold">${data.title}</h4>
                <p class="mb-2 fs-6">
                  <strong>Status:</strong>
                  <span class="badge ${badgeClass} text-white px-2 py-1 rounded-pill">${data.status}</span>
                </p>
                <p class="text-muted mb-3 fs-6">Submitted at ${data.created_at}</p>
                ${feedbackButton}
              </div>

              ${actionButtons}
            </div>

            <hr>
            <p class="mt-4 fs-5">${data.description}</p>
          </div>
        </div>`;
      });
  });
});

function setFeedbackReportId(id) {
  document.getElementById('feedbackReportId').value = id;
}

function editReport(id) {
  fetch(`/resident/report-history/detail/${id}`)
    .then(res => res.json())
    .then(data => {
      const modal = new bootstrap.Modal(document.getElementById('addReportModal'));

      const form = document.querySelector('#addReportModal form');
      form.action = `/resident/report-history/update/${id}`;
      document.getElementById('methodField').value = 'POST';

      // Set category & description
      document.querySelector('#addReportModal select[name="category"]').value = data.title.split(' ')[0];
      document.querySelector('#addReportModal textarea[name="description"]').value = data.description;

      // Set file name preview (jika ada foto)
      const fileNamePreview = document.getElementById('photo-file-name');
      if (data.image) {
        const fileName = data.image.split('/').pop();
        fileNamePreview.textContent = `Current photo: ${fileName}`;
      } else {
        fileNamePreview.textContent = '';
      }

      document.getElementById('addReportModalLabel').innerText = 'Edit Report';
      modal.show();
    });
}

document.getElementById('addReportModal').addEventListener('hidden.bs.modal', () => {
  const form = document.querySelector('#addReportModal form');
  form.action = "{{ route('resident.report.store') }}";
  document.getElementById('methodField').value = 'POST';
  document.getElementById('addReportModalLabel').innerText = 'Add New Report';
  document.querySelector('#addReportModal select[name="category"]').value = '';
  document.querySelector('#addReportModal textarea[name="description"]').value = '';
  document.querySelector('#addReportModal input[name="photo"]').value = '';
  document.getElementById('photo-file-name').textContent = '';
});
</script>

@if (session('success'))
<script>
  const toastEl = document.getElementById('feedbackToast');
  const toast = new bootstrap.Toast(toastEl, { delay: 3000 });
  toast.show();
</script>
@endif

@endsection
