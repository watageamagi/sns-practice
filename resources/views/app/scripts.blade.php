@php
    $config = [
        'appName' => config('app.name'),
        'locale' => $locale = app()->getLocale(),
        'locales' => config('app.locales'),
        'password_secret' => config('auth.oauth.client_secret')
    ];

    $polyfills = [
        'Promise',
        'Object.assign',
        'Object.values',
        'Array.prototype.find',
        'Array.prototype.findIndex',
        'Array.prototype.includes',
        'String.prototype.includes',
        'String.prototype.startsWith',
        'String.prototype.endsWith',
    ];
@endphp

{{-- Global configuration object --}}
<script>window.config = @json($config);</script>

{{-- Polyfill JS features via polyfill.io --}}
<script src="https://cdn.polyfill.io/v2/polyfill.min.js?features={{ implode(',', $polyfills) }}"></script>

<script type="text/javascript">
    window.Laravel = {!! json_encode([
        'apiToken' => \Auth::user()->api_token ?? null
    ]) !!};
    window.Laravel.csrfToken = "{{ csrf_token() }}";
    window.Laravel.ttl = "{{ env('JWT_TTL', 10000) }}"

</script>
<script src="{{ mix('/app.js', '/dist/js') }}"></script>
