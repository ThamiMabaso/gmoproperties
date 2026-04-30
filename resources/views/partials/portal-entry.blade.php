{{--
    Portal entry for public layout: guests → login; authenticated users → their dashboard.
    $variant: 'desktop' | 'mobile'
--}}
@php
    $variant = $variant ?? 'desktop';
@endphp
@auth
    @php
        $portalHref = auth()->user()->portalDashboardUrl();
        $portalLabel = 'My portal';
    @endphp
    @if($variant === 'desktop')
        <a href="{{ $portalHref }}" class="inline-flex items-center justify-center px-5 py-2 bg-gmo-gold text-black rounded-full hover:bg-opacity-90 transition-colors font-semibold text-sm relative z-10">
            {{ $portalLabel }}
        </a>
    @else
        <a href="{{ $portalHref }}" class="block px-3 py-2 bg-gmo-gold text-black rounded-full hover:bg-opacity-90 font-semibold text-center mt-2 relative z-10">
            {{ $portalLabel }}
        </a>
    @endif
@else
    @if($variant === 'desktop')
        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-5 py-2 bg-gmo-gold text-black rounded-full hover:bg-opacity-90 transition-colors font-semibold text-sm relative z-10">
            Portal Login
        </a>
    @else
        <a href="{{ route('login') }}" class="block px-3 py-2 bg-gmo-gold text-black rounded-full hover:bg-opacity-90 font-semibold text-center mt-2 relative z-10">
            Portal Login
        </a>
    @endif
@endauth
