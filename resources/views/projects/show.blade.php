@extends('layouts.app')
@section('title', 'Project Details')
@section('page-title', 'Project Details')
@section('content')
<div class="card p-6">
    <h2 class="text-lg font-semibold">{{ $project->project_name ?? 'Project' }}</h2>
    <p class="text-sm text-slate-400 mt-2">Project detail placeholder view.</p>
</div>
@endsection
