@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    @include('dashboard.partials.kpi-widgets')

    <section class="grid grid-cols-12 gap-8">
        {{-- Left column --}}
        <div class="col-span-12 lg:col-span-8 flex flex-col gap-8">
            @include('dashboard.partials.active-alerts-bento')
            @include('dashboard.partials.budget-performance-card')
            @include('dashboard.partials.active-projects-summary')
            @include('dashboard.partials.safety-overview-card')
        </div>

        {{-- Right column --}}
        <div class="col-span-12 lg:col-span-4 flex flex-col gap-8">
            @include('dashboard.partials.pending-tasks-card')
            @include('dashboard.partials.team-workload-card')
            @include('dashboard.partials.recent-activity-card')
        </div>
    </section>

    <button
        class="fixed bottom-8 right-8 w-14 h-14 rounded-full bg-[#4d8eff] text-white shadow-2xl flex items-center justify-center hover:scale-110 active:scale-95 transition-transform z-50"
        onclick="toast('New project form coming soon','info')">
        <span class="material-symbols-outlined text-2xl" style="font-variation-settings:'FILL' 1;">add</span>
    </button>
@endsection

@section('scripts')
    @include('dashboard.partials.dashboard-scripts')
@endsection

