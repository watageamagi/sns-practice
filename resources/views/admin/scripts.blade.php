{{-- Global configuration object --}}
@php
$config = [
    'appName' => config('app.name'),
    'locale' => $locale = app()->getLocale(),
];
@endphp
<script>window.config = {!! json_encode($config); !!};</script>

{{-- Polyfill some features via polyfill.io --}}
@php
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
<script src="https://cdn.polyfill.io/v2/polyfill.min.js?features={{ implode(',', $polyfills) }}"></script>
<script src="https://cdn.rawgit.com/cretueusebiu/412715093d6e8980e7b176e9de663d97/raw/aea128d8d15d5f9f2d87892fb7d18b5f6953e952/objectToFormData.js"></script>

<script type="text/javascript">
    window.Laravel = {!! json_encode([
        'apiToken' => \Auth::user()->api_token ?? null
    ]) !!};

    // window.Laravel           = window.Laravel || {};
    {{--window.Laravel.appUrl = "{{env('APP_URL')}}"--}}
    window.Laravel.csrfToken = "{{ csrf_token() }}";
    window.Laravel.ttl = "{{ env('JWT_TTL', 10000) }}"

</script>

{{-- Load the application scripts --}}
@if (app()->isLocal())
  <script src="{{ mix('/admin.js', '/dist/js') }}"></script>
@else
  <script src="{{ mix('/admin.js', '/dist/js') }}"></script>
@endif

