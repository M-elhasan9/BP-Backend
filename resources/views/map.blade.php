@extends(backpack_view('blank'))


<link rel="stylesheet" href="{{asset('css/app.css')}}">

<script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js"></script>
<script src="https://api.windy.com/assets/map-forecast/libBoot.js"></script>

@section('content')
    <div id="windy"  style="width: 100%; height: 100%;"></div>



    <script>

        const options = {
            // Required: API key
            key: 'r16ylTSzt123Cjht7eKgaRvIn1gVLolu', // REPLACE WITH YOUR KEY !!!



            // Optional: Initial state of the map
            lat:39.24,
            lon:  35.19,
            zoom: 7,
        };



        // Initialize Windy API
        windyInit(options, windyAPI => {
            // windyAPI is ready, and contain 'map', 'store',
            // 'picker' and other usefull stuff

            const { map } = windyAPI;
            // .map is instance of Leaflet map

          //    L.popup()
            //    .setLatLng([50.4, 14.3])
              //  .setContent('Hello World')
             //   .openOn(map);

        });

       // var map = L.map('map').setView([39.24, 35.19], 7);

      //  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      //      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
     //   }).addTo(map);


    </script>
@endsection

