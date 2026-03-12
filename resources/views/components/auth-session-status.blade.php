@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'notice-banner']) }}>
        {{ $status }}
    </div>
@endif
