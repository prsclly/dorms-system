@php $isNavbar = false; @endphp
@extends('layouts/contentResidentLayout')

@section('title', 'Resident Dashboard')

@section('content')
  <style>
    .bg-appreciation,
    .bg-violation {
    padding: 6px 12px;
    border-radius: 6px;
    display: inline-block;
    text-align: center;
    font-weight: 500;
    }

    .bg-appreciation {
    background-color: #d1e7dd !important;
    color: #0f5132;
    }

    .bg-violation {
    background-color: #f8d7da !important;
    color: #842029;
    }
  </style>

  <div class="container">
    <h2>Hi, {{ $student->name }}</h2>
    <p><strong>Total Points:</strong> {{ $student->total_point }}</p>

    <hr>
    <h4>Point History</h4>

    <div class="table-responsive text-nowrap">
    <table class="table table-bordered">
      <thead>
      <tr>
        <th>Date</th>
        <th>Category</th>
        <th class="text-center">Point Change</th>
        <th class="text-center">New Point</th>
        <th>Description</th>
      </tr>
      </thead>

      <tbody>
      @forelse ($pointlogs as $log)
      <tr>
        {{-- Tanggal --}}
        <td>{{ \Carbon\Carbon::parse($log->date)->format('d M Y H:i') }}</td>

        {{-- Kategori --}}
        <td class="text-center">
        @if ($log->category === 'Appreciation')
      <span class="bg-appreciation">{{ $log->category }}</span>
      @elseif ($log->category === 'Violation')
      <span class="bg-violation">{{ $log->category }}</span>
      @else
      <span>{{ $log->category ?? '-' }}</span>
      @endif
        </td>

        {{-- Selisih poin --}}
        <td class="text-center {{ $log->point_change >= 0 ? 'text-success' : 'text-danger' }}">
        {{ $log->point_change > 0 ? '+' : '' }}{{ $log->point_change }}
        </td>

        {{-- Poin baru --}}
        <td class="text-center">{{ $log->new_point }}</td>

        {{-- Deskripsi --}}
        <td>{{ $log->description }}</td>
      </tr>
    @empty
      <tr>
      <td colspan="5" class="text-center">No point history found.</td>
      </tr>
    @endforelse
      </tbody>
    </table>
    </div>
  </div>
@endsection