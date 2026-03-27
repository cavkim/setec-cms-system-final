@extends('layouts.app')
@section('title', 'Audit Logs — BuildScape CMS')
@section('page-title', 'Audit Logs')

@section('content')

    {{-- Header --}}
    <div class="mb-10">
        <h1 class="text-4xl font-extrabold font-headline tracking-tight text-on-surface mb-2">Audit Log</h1>
        <p class="text-on-surface-variant font-medium">Complete activity history and system changes.</p>
    </div>

    {{-- Activity Table --}}
    <div class="bg-surface-container rounded-2xl overflow-hidden shadow-lg border border-white/5 flex flex-col">
        <div class="overflow-x-auto overflow-y-auto" style="max-height: calc(100vh - 500px);">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-high/50 border-b border-white/5">
                        @foreach(['User', 'Action', 'Description', 'Timestamp'] as $header)
                            <th
                                class="px-6 py-5 text-xs font-bold uppercase tracking-[0.1em] text-on-surface-variant whitespace-nowrap">
                                {{ $header }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($logs as $log)
                                <tr class="hover:bg-white/[0.04] transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <div
                                                class="w-8 h-8 rounded-full bg-primary/20 flex items-center justify-center text-xs font-bold text-primary flex-shrink-0">
                                                {{ strtoupper(substr($log['user'], 0, 1)) }}
                                            </div>
                                            <span class="text-sm text-on-surface font-medium">{{ $log['user'] }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 text-[10px] font-bold uppercase tracking-wider rounded-full
                                                                {{ in_array($log['action'], ['Created', 'Update']) ? 'bg-primary/20 text-primary' :
                        (in_array($log['action'], ['Delete', 'Deleted']) ? 'bg-error/20 text-error' :
                            'bg-surface-container-highest text-on-surface-variant') }}">
                                            {{ $log['action'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm text-on-surface-variant">{{ $log['description'] }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="text-xs text-on-surface-variant whitespace-nowrap">{{ $log['created_at']->format('M d, Y H:i') }}</span>
                                    </td>
                                </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <p class="text-on-surface-variant text-sm">No activity logs found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection