@extends('layouts.app')
@section('title', 'Inspections')
@section('page-title', 'Inspections')
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Inspections</h3>
        </div>
        <div class="card-body">
            @if($inspections->count() > 0)
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Inspection Date</th>
                            <th>Inspector</th>
                            <th>Project</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($inspections as $inspection)
                            <tr>
                                <td>{{ $inspection->inspection_date?->format('M d, Y') ?? 'N/A' }}</td>
                                <td>{{ $inspection->inspector_name ?? 'N/A' }}</td>
                                <td>{{ $inspection->project_name ?? 'N/A' }}</td>
                                <td><span class="badge badge-success">{{ $inspection->status ?? 'Completed' }}</span></td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-info">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-muted">No inspections found.</p>
            @endif
        </div>
    </div>
@endsection