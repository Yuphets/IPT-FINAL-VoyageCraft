@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'rounded-2xl border border-teal-500/20 bg-teal-500/10 px-4 py-3 text-sm font-medium text-teal-700']) }}>
        {{ $status }}
    </div>
@endif
