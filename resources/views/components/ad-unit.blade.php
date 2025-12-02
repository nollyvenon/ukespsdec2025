@props(['position' => 'top', 'page' => null])

@php
    $ads = \App\Models\Ad::where('is_active', true)
                   ->where('position', $position);
    
    if($page) {
        $ads = $ads->whereJsonContains('target_pages', $page);
    }
    
    $ads = $ads->orderBy('priority', 'desc')->get();
@endphp

@if($ads->count() > 0)
    <div class="ad-container ad-position-{{ $position }}">
        @foreach($ads as $ad)
            <div class="ad-unit" data-ad-id="{{ $ad->id }}">
                @if($ad->platform === 'custom')
                    <div class="ad-content">
                        {!! $ad->custom_code !!}
                    </div>
                @else
                    <!-- Ad from platform: {{ ucfirst(str_replace('_', ' ', $ad->platform)) }} -->
                    <div class="ad-placeholder bg-gray-100 border border-gray-300 rounded p-4 text-center">
                        <p class="text-gray-500">Ad: {{ $ad->name }}</p>
                        <p class="text-xs text-gray-400 mt-2">Powered by {{ ucfirst(str_replace('_', ' ', $ad->platform)) }}</p>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
@else
    <!-- No ads available for position: {{ $position }} -->
@endif

<style>
.ad-container {
    margin: 15px 0;
}
.ad-unit {
    margin-bottom: 15px;
}
.ad-placeholder {
    min-height: 100px;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>