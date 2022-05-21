@php

    @endphp
<span>
             <div class="mt-3 map" id="map"></div>
            <div class="close" style="z-index: 70000000;
            width: 40px;height: 40px;border-radius: 50px;background-color: black;padding: 5px;
            position:fixed;top: 10px;bottom: 0;right: 50%;left: 0;
            display: none;
            font-weight: bold;font-size: 16px;color: #fff;cursor: pointer"><i class="fa fa-close fa-2x"></i></div>
                    <div class="backdrop" style="background-color: rgba(0,0,0,0.26);position:fixed;top: 0;bottom: 0;right: 0;left: 0;z-index: 5000000;display: none"></div>
            <div class="w-100 text-center p-1">
               <button id="showMap" class="btn btn-secondary btn-light mx-auto">Full Screen</button>
            </div>

</span>
@if($entry->lat_lang)

    @push('after_scripts')
        @if( isset($entry->lat_lang) )
            <script>
                function initMap() {
                    // The location of Uluru
                    var uluru = {lat: <?php echo $entry->lat_lang->lat ;?>, lng: <?php echo $entry->lat_lang->lng ;?>};
                    // The map, centered at Uluru
                    var map = new google.maps.Map(
                        document.getElementById('map'), {zoom: 15, center: uluru});
                    // The marker, positioned at Uluru
                    var marker = new google.maps.Marker({position: uluru, map: map});
                }
                $('#showMap').click(function () {
                    $('.backdrop').show()
                    $('.close').show()
                    $('.map')[0].classList.toggle("full-map")
                })
                $('.close').click(() => {
                    $('.backdrop').hide()
                    $('.close').hide()
                    $('.map')[0].classList.toggle("full-map")

                })
            </script>
        @endif
        <script async defer
                src="https://maps.googleapis.com/maps/api/js?key={{config('services.google_places.key')}}&callback=initMap">
        </script>
    @endpush
@endif

@push('after_styles')
    <style>
        .map {
            height: 250px;  /* The height is 400 pixels */
            width: 100%;  /* The width is the width of the web page */
            z-index: 6000000;
        }
        .full-map{
            position: fixed !important;
            top: -20px !important;
            bottom: 0 !important;
            right: 0 !important;
            left: 0 !important;
            height: 100vh;
            z-index: 6000000;
        }
    </style>
@endpush
