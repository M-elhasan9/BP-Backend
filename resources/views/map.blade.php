@extends(backpack_view('blank'))


<link rel="stylesheet" href="{{asset('css/app.css')}}">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js"></script>

<script src="https://api.windy.com/assets/map-forecast/libBoot.js"></script>

@section('content')
    <div class="row">
        <input onclick="windyOnclick(this)" type="radio" name="windyRadio" value="without">
        <label for="html">بدون طقس</label><br>
        <input onclick="windyOnclick(this)" type="radio" name="windyRadio" value="wind">
        <label for="css">حركة الرياح</label><br>
        <input onclick="windyOnclick(this)" type="radio" name="windyRadio" value="temp">
        <label for="javascript">الحرارة</label>
    </div>



    <div id="windy" style="width: 100%; height: 90%;"></div>

    <style>
        #map-container {
            z-index: 0;
        }
    </style>

    <script>
        var windy;
        var store;

        function hideWindyElements() {
            document.getElementById("bottom").style['display'] = "none"
            document.getElementsByClassName("basemap-layer")[0].style['display'] = "none"
            document.getElementsByClassName("labels-layer")[0].style['display'] = "none"

            document.getElementById("logo-wrapper").style['display'] = "none"
            document.getElementById("embed-zoom").style['display'] = "none"
            document.getElementById("mobile-ovr-select").style['display'] = "none"
            if (windy)
                windy.style['display'] = "none"
        }

        function windyOnclick(r) {
            if (r.value == "wind" || r.value == "temp") {
                windy.style['display'] = "block"
                document.getElementById("bottom").style['display'] = "block"
                document.getElementsByClassName("basemap-layer")[0].style['display'] = "block"
                document.getElementsByClassName("labels-layer")[0].style['display'] = "block"
                //windy.style['opacity'] = "0.5"
                if (r.value == "temp")
                    store.set("overlay", "temp")
                else
                    store.set("overlay", "wind")
            } else {
                hideWindyElements();
            }
        }

        /**
         * Leaflet.geojsonCSS
         * @author Alexander Burtsev, http://burtsev.me, 2014
         * @license MIT
         */
        !function (a) {
            a.L && L.GeoJSON && (L.GeoJSON.CSS = L.GeoJSON.extend({
                initialize: function (a, b) {
                    var c = L.extend({}, b, {
                        onEachFeature: function (a, c) {
                            b && b.onEachFeature && b.onEachFeature(a, c);
                            var d = a.style, e = a.popupTemplate;
                            d && (c instanceof L.Marker ? d.icon && c.setIcon(L.icon(d.icon)) : c.setStyle(d)), e && a.properties && c.bindPopup(L.Util.template(e, a.properties))
                        }
                    });
                    L.setOptions(this, c), this._layers = {}, a && this.addData(a)
                }
            }), L.geoJson.css = function (a, b) {
                return new L.GeoJSON.CSS(a, b)
            })
        }(window, document);
        //
        const options = {
            // Required: API key
            key: 'r16ylTSzt123Cjht7eKgaRvIn1gVLolu', // REPLACE WITH YOUR KEY !!!

            // Optional: Initial state of the map
            lat: 39.24,
            lon: 35.19,
            zoom: 7,
        };


        // Initialize Windy API
        windyInit(options, windyAPI => {
            // windyAPI is ready, and contain 'map', 'store',
            // 'picker' and other usefull stuff

            const {map, store} = windyAPI;
            // .map is instance of Leaflet map
            this.store = store
            var littleton = L.marker([38.0305851, 33.6290171]).bindPopup('This is Littleton, CO.'),
                denver = L.marker([38.0205851, 33.6390171]).bindPopup('This is Denver, CO.'),
                aurora = L.marker([38.0405851, 33.6490171]).bindPopup('This is Aurora, CO.'),
                golden = L.marker([38.0505851, 33.6590171]).bindPopup('This is Golden, CO.');

            var cities = L.layerGroup([littleton, denver, aurora, golden]);

            var crownHill = L.marker([38.1205851, 33.6390171]).bindPopup('This is Crown Hill Park.'),
                rubyHill = L.marker([38.1305851, 33.6390171]).bindPopup('This is Ruby Hill Park.');

            var parks = L.layerGroup([crownHill, rubyHill]);

            var myLayer1 = L.tileLayer('https://{s}.tile.thunderforest.com/cycle/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);
            var myLayer2 = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            });

            const Map_BaseLayer = {'خريطة التضاريس': myLayer1, 'الخريطة الافتراضية': myLayer2};

            const Map_AddLayer = {
                'الحرائق الجديدة': parks,
                'الحرائق الفعلية': parks,
                'الحرائق المنتهية': parks,
                'تقارير الأشخص': cities,
            };

            L.control
                .layers(Map_BaseLayer, Map_AddLayer, {
                    collapsed: false,
                })
                .addTo(map);

            var marker = L.marker([38.0105851, 33.6190171]).addTo(map);

            L.marker([38.0105851, 33.6190171], {
                icon: L.icon({
                    iconUrl: 'https://server.yesilkalacak.com/images/fire.png',
                    iconSize: [30, 30], // size of the icon
                    iconAnchor: [10, 10], // point of the icon which will correspond to marker's location
                    popupAnchor: [-3, -76] // point from which the popup should open relative to the iconAnchor
                })
            }).addTo(map).bindPopup("I am a green leaf.");

            var circle = L.circle([38.0105851, 30.6190171], {
                color: 'red',
                fillColor: '#f03',
                fillOpacity: 0.5,
                radius: 500
            }).addTo(map);

            var polygon = L.polygon([
                [37.21489152908325, 28.05506145183324,],
                [36.41577129364014, 29.352586015356876],
                [37.016543769836426, 29.5539867694403]
            ]).addTo(map);

            json = {
                "type": "FeatureCollection",
                "features": [
                    {
                        "type": "Feature",
                        "geometry": {
                            "type": "Polygon",
                            "coordinates": [[
                                [28.05506145183324, 37.21489152908325,],
                                [29.352586015356876, 36.41577129364014],
                                [29.5539867694403, 37.016543769836426]
                            ]]
                        },
                        "style": {
                            "color": "#CC0000",
                            "weight": 2,
                            "fill-opacity": 0.6,
                            "opacity": 1,
                            "dashArray": "3, 5"
                        }
                    },
                    {
                        "type": "Feature",
                        "geometry": {
                            "type": "MultiLineString",
                            "coordinates": [[[37.611907, 55.747355], [37.612639, 55.747611], [37.613671, 55.747839], [37.614446, 55.748040], [37.616002, 55.748446], [37.616364, 55.748537], [37.616573, 55.748585], [37.616779, 55.748627], [37.617038, 55.748677], [37.618375, 55.748887], [37.620201, 55.749173], [37.620494, 55.749215], [37.620723, 55.749246], [37.621209, 55.749296], [37.622037, 55.749388], [37.622402, 55.749421], [37.622745, 55.749461], [37.622990, 55.749484], [37.623206, 55.749507], [37.623680, 55.749562], [37.624266, 55.749640]], [[37.624245, 55.749770], [37.623771, 55.749678], [37.623476, 55.749623], [37.623147, 55.749577], [37.621484, 55.749414], [37.620021, 55.749222], [37.618740, 55.749021], [37.617359, 55.748819], [37.616927, 55.748750], [37.616755, 55.748721], [37.616592, 55.748691], [37.616413, 55.748652], [37.616225, 55.748608], [37.614894, 55.748251], [37.614139, 55.748055], [37.613795, 55.747971], [37.613487, 55.747906], [37.612526, 55.747741], [37.612248, 55.747656], [37.611791, 55.747497]]]
                        },
                        "style": {
                            "color": "#CC0000",
                            "opacity": 1,
                            "weight": 4
                        }
                    },
                    {
                        "type": "Feature",
                        "geometry": {
                            "type": "Point",
                            "coordinates": [28.3555422, 37.2101562]
                        },
                        "style": {
                            "icon": {
                                "iconUrl": "http://server.yesilkalacak.com/images/fire.png",
                                "iconSize": [50, 50],
                                "iconAnchor": [10, 10]
                            }
                        }
                    },

                ]
            }

            L.geoJson.css(json).addTo(map);

            var popup = L.popup();

            function onMapClick(e) {
                popup
                    .setLatLng(e.latlng)
                    .setContent("You clicked the map at " + e.latlng.toString())
                    .openOn(map);
            }

            map.on('click', onMapClick);


            var imageUrl = 'https://server.yesilkalacak.com/storage/fires/f%20(45).jpgRES.jpg';
            var altText = 'detect by AI';
            var latLngBounds = L.latLngBounds([[37.0105851, 33.6190171], [37.1105851, 33.7190171]]);

            var imageOverlay = L.imageOverlay(imageUrl, latLngBounds, {
                opacity: 0.8,
                alt: altText,
                interactive: true
            }).addTo(map);

            document.getElementsByClassName("leaflet-control-container")[0].style['display'] = "block"

            hideWindyElements();
            var t = setInterval(function () {
                if (document.getElementsByClassName("overlay-layer") && windy == null) {
                    windy = document.getElementsByClassName("overlay-layer")[0];
                    windy.style['display'] = "none"
                }
            }, 10);

        });


    </script>
@endsection

