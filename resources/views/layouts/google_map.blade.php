@if(isset($type) && strcmp($type, 'autocomplete') == 0)
<script src="{{ secure_asset('https://maps.googleapis.com/maps/api/js?key=AIzaSyCF_XFxr870AjwID4rxlXqs6TuYYQmNAyg&callback=initAutocomplete&libraries=places&v=weekly') }}" defer></script>
@endif
@if(isset($type) && strcmp($type, 'map') == 0)
<script src="{{ secure_asset('https://maps.googleapis.com/maps/api/js?key=AIzaSyCF_XFxr870AjwID4rxlXqs6TuYYQmNAyg&callback=initMap&libraries=places&v=weekly') }}" defer></script>
@endif
@if(isset($type) && strcmp($type, 'mix') == 0)
<script src="{{ secure_asset('https://maps.googleapis.com/maps/api/js?key=AIzaSyCF_XFxr870AjwID4rxlXqs6TuYYQmNAyg&callback=initMix&libraries=places&v=weekly') }}" defer></script>
@endif

<script src="{{ secure_asset('js/google_map.js') }}"></script>
