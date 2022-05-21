@extends(backpack_view('blank'))


<link rel="stylesheet" href="{{asset('css/app.css')}}">
<script src="{{asset('js/app.js')}}"></script>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js"></script>

<script src="https://api.windy.com/assets/map-forecast/libBoot.js"></script>


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@drustack/leaflet.resetview/dist/L.Control.ResetView.min.css">
<script src="https://cdn.jsdelivr.net/npm/@drustack/leaflet.resetview/dist/L.Control.ResetView.min.js"></script>

<link rel="stylesheet" href="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.css"/>
<script src="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.min.js"></script>

<link rel="stylesheet" href="<?php echo url('css/fullscreen.css')?>"/>
<script src="<?php echo url('js/fullscreen.js')?>"></script>

<link rel="stylesheet" href="https://ppete2.github.io/Leaflet.PolylineMeasure/Leaflet.PolylineMeasure.css"/>
<script src="https://ppete2.github.io/Leaflet.PolylineMeasure/Leaflet.PolylineMeasure.js"></script>

<script src="<?php echo url('js/leaflet.browser.print.min.js')?>"></script>

<link rel="stylesheet" href="<?php echo url('css/Leaflet-Coordinates-Control.css')?>"/>
<script src="<?php echo url('js/Leaflet-Coordinates-Control.js')?>"></script>


<script src="<?php echo url('js/L.Realtime.js')?>"></script>

<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css"/>
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css"/>

<script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>

@section('content')

    <div id="windy" style="width: 100%; height: 100%;"></div>
    <style>
        #map-container {
            z-index: 0;
        }
    </style>

    <script>
        var windy;
        var store;
        var map;

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

        function MapWeatherTypeOnclick(r) {
            if (r.value == "Wind" || r.value == "Temp") {
                windy.style['display'] = "block"
                document.getElementById("bottom").style['display'] = "block"
                document.getElementsByClassName("basemap-layer")[0].style['display'] = "block"
                document.getElementsByClassName("labels-layer")[0].style['display'] = "block"
                //windy.style['opacity'] = "0.5"
                map.options.maxZoom = 11;
                map.setZoom(11)
                if (r.value == "Temp")
                    store.set("overlay", "temp")
                else
                    store.set("overlay", "wind")
            } else {
                map.options.maxZoom = 18;
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
            this.store = store
            this.map = map
            document.getElementsByClassName("leaflet-control-container")[0].style['display'] = "block"
            hideWindyElements();
            setInterval(function () {
                if (document.getElementsByClassName("overlay-layer") && windy == null) {
                    windy = document.getElementsByClassName("overlay-layer")[0];
                    windy.style['display'] = "none"
                }
            }, 10);
            map.options.maxZoom = 18;

            // .map is instance of Leaflet map

            // Wind Heat Map buttons
            var typeButton = L.Control.extend({
                options: {
                    position: 'topright'
                },
                onAdd: function (map) {
                    var div = L.DomUtil.create('div', "leaflet-control-layers leaflet-control-layers-expanded");
                    var b1 = L.DomUtil.create('input');
                    b1.type = "button";
                    b1.value = "Wind";
                    b1.onclick = function () {
                        MapWeatherTypeOnclick(this)
                    }
                    var b2 = L.DomUtil.create('input');
                    b2.type = "button";
                    b2.value = "Temp";
                    b2.onclick = function () {
                        MapWeatherTypeOnclick(this)
                    }
                    var b3 = L.DomUtil.create('input');
                    b3.type = "button";
                    b3.value = "Map";
                    b3.onclick = function () {
                        MapWeatherTypeOnclick(this)
                    }
                    div.append(b1)
                    div.append(b2)
                    div.append(b3)
                    return div;
                }
            })
            map.addControl(new typeButton());

            var TerrainLayer = L.tileLayer('https://{s}.tile.thunderforest.com/cycle/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);
            var DefaultLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            });


            var actualFireIcon = L.icon({
                iconUrl: 'https://server.yesilkalacak.com/images/fire.png',
                iconSize: [80, 80], // size of the icon
                iconAnchor: [35, 60], // point of the icon which will correspond to marker's location
                popupAnchor: [-10, -10] // point from which the popup should open relative to the iconAnchor
            })
            var actualFireIconSmall = L.icon({
                iconUrl: 'https://server.yesilkalacak.com/images/fire.png',
                iconSize: [40, 40], // size of the icon
                iconAnchor: [17, 30], // point of the icon which will correspond to marker's location
                popupAnchor: [-10, -10] // point from which the popup should open relative to the iconAnchor
            })
            var newFireIcon = L.icon({
                iconUrl: 'https://server.yesilkalacak.com/images/fire-black.png',
                iconSize: [60, 80], // size of the icon
                iconAnchor: [30, 60], // point of the icon which will correspond to marker's location
                popupAnchor: [-10, -10] // point from which the popup should open relative to the iconAnchor
            })
            var newFireIconSmall = L.icon({
                iconUrl: 'https://server.yesilkalacak.com/images/fire-black.png',
                iconSize: [30, 40], // size of the icon
                iconAnchor: [15, 30], // point of the icon which will correspond to marker's location
                popupAnchor: [-10, -10] // point from which the popup should open relative to the iconAnchor
            })
            var endFireIcon = L.icon({
                iconUrl: 'https://server.yesilkalacak.com/images/fire-green.png',
                iconSize: [60, 80], // size of the icon
                iconAnchor: [30, 60], // point of the icon which will correspond to marker's location
                popupAnchor: [-10, -10] // point from which the popup should open relative to the iconAnchor
            })
            var endFireIconSmall = L.icon({
                iconUrl: 'https://server.yesilkalacak.com/images/fire-green.png',
                iconSize: [30, 40], // size of the icon
                iconAnchor: [15, 30], // point of the icon which will correspond to marker's location
                popupAnchor: [-10, -10] // point from which the popup should open relative to the iconAnchor
            })
            var bigIcon = false
            map.on('zoomend', function () {
                var currentZoom = map.getZoom();
                newFireIcon.iconSize = [20, 20]

                if (currentZoom < 12 && bigIcon || currentZoom >= 12 && !bigIcon) {
                    bigIcon = !bigIcon;
                    newFiresLayer.eachLayer(function (layer) {
                        if (currentZoom >= 12)
                            return layer.setIcon(newFireIcon);
                        else
                            return layer.setIcon(newFireIconSmall);
                    });
                    actualFiresLayer.eachLayer(function (layer) {
                        if (currentZoom >= 12)
                            return layer.setIcon(actualFireIcon);
                        else
                            return layer.setIcon(actualFireIconSmall);
                    });
                    endFiresLayer.eachLayer(function (layer) {
                        if (currentZoom >= 12)
                            return layer.setIcon(endFireIcon);
                        else
                            return layer.setIcon(endFireIconSmall);
                    });
                }

            });

            var newFires = [];
            var actualFires = [];
            var reports = [];
            var endFires = [];
            @foreach( \App\Models\Fire::all() as $fire)
            var status = {{$fire->status}};
            if (status === 1)
                newFires.push(L.marker([{{$fire->lat_lang->lat}}, {{$fire->lat_lang->lng}}], {icon: newFireIconSmall}).bindPopup(
                    "Status :{{$fire->status}} - Degree: {{$fire->den_degree}} - Created at: {{$fire->created_at}} - <a href=\"{{url('/admin/fire/'.$fire->id.'/show')}}\"> Link <\a>"));
            else if (status === 2)
                actualFires.push(L.marker([{{$fire->lat_lang->lat}}, {{$fire->lat_lang->lng}}], {icon: actualFireIconSmall}).bindPopup('This is Crown Hill Park.'))
            else if (status === 3)
                endFires.push(L.marker([{{$fire->lat_lang->lat}}, {{$fire->lat_lang->lng}}], {icon: endFireIconSmall}).bindPopup('This is Crown Hill Park.'))
            @endforeach

            @php
                $Reports = \App\Models\Report::all()
            @endphp
            @foreach($Reports as $report)
            reports.push(L.marker([{{$report->lat_lang->lat}}, {{$report->lat_lang->lng}}]).bindPopup(
                "Reported From:{{$report->reporter_type}} - Degree: {{$report->den_degree}} - Reported at: {{$report->created_at}} - <a href=\"{{url('/admin/reports/'.$report->id.'/show')}}\"> Link <\a> <img style=\"width:200px\" src=\"https://server.yesilkalacak.com/storage/fires/f%20(45).jpgRES.jpg\"><\img>"))
            @endforeach

            var newFiresLayer = L.layerGroup(newFires)
            var actualFiresLayer = L.layerGroup(actualFires)
            var endFiresLayer = L.layerGroup(endFires)
            var reportsLayer = L.layerGroup(reports)

            var reportsMarkers = L.markerClusterGroup();
            for (var i = 0; i < reports.length; i++) {
                reportsMarkers.addLayer(reports[i]);
            }
            //map.addLayer(reportsMarkers);
            map.addLayer(newFiresLayer)
            map.addLayer(actualFiresLayer)
            var layerControl = L.control
                .layers(
                    {
                        'Terrain Map': TerrainLayer,
                        'Default Map': DefaultLayer,
                    },
                    {
                        'New fires': newFiresLayer,
                        'Actual fires': actualFiresLayer,
                        'End fires': endFiresLayer,
                        'Reports': reportsMarkers,
                    },
                    {
                        collapsed: false,
                    })
                .addTo(map);

            map.pm.addControls({
                position: 'topleft',
                drawCircle: true,
            });

            map.pm.Toolbar.createCustomControl({
                name: 'SaveButton',
                block: 'draw',
                className: 'control-icon leaflet-pm-icon-snapping',
                actions: [{
                    text: 'Save',
                    onClick: function () {
                        localStorage.setItem('markers', JSON.stringify(map.pm.getGeomanDrawLayers(true).toGeoJSON()));
                        alert("save done .")
                    }
                }]
            })

            var markers = localStorage.getItem("markers");
            if (markers) {
                markers = JSON.parse(markers);
                var layer = L.geoJson.css(markers);
                layer.pm._layers.forEach(function (e) {
                    e._drawnByGeoman = true
                })
                layer.addTo(map)
            }

            // create a fullscreen button and add it to the map
            L.control.fullscreen({
                position: 'topleft', // change the position of the button can be topleft, topright, bottomright or bottomleft, default topleft
                title: 'Show me the fullscreen !', // change the title of the button, default Full Screen
                titleCancel: 'Exit fullscreen mode', // change the title of the button when fullscreen is on, default Exit Full Screen
                content: null, // change the content of the button, can be HTML, default null
                forceSeparateButton: true, // force separate button to detach from zoom buttons, default false
                forcePseudoFullscreen: true, // force use of pseudo full screen even if full screen API is available, default false
                fullscreenElement: false // Dom element to render in full screen, false by default, fallback to map._container
            }).addTo(map);

            L.control.resetView({
                position: "topleft",
                title: "Reset view",
                latlng: L.latLng([39.24, 35.19]),
                zoom: 6,
            }).addTo(map);

            if (!map.restoreView()) {
                map.setView([39.24, 35.19], 6);
            }

            L.control.polylineMeasure({"showClearControl": true}).addTo(map);
            L.control.browserPrint({position: 'topleft', title: 'Print ...'}).addTo(map);

            var realtime = L.realtime({
                url: '{{url("api/mapPoints")}}',
                crossOrigin: true,
                type: 'json'
            }, {
                interval: 1000000 * 3
            }).addTo(map);

            realtime.on('update', function () {
                map.fitBounds(realtime.getBounds(), {maxZoom: 3});
            });


        });

        (function () {
            var RestoreViewMixin = {
                restoreView: function () {
                    if (!storageAvailable('localStorage')) {
                        return false;
                    }
                    var storage = window.localStorage;
                    if (!this.__initRestore) {
                        this.on('moveend', function (e) {
                            if (!this._loaded)
                                return;  // Never access map bounds if view is not set.

                            var view = {
                                lat: this.getCenter().lat,
                                lng: this.getCenter().lng,
                                zoom: this.getZoom()
                            };
                            storage['mapView'] = JSON.stringify(view);
                        }, this);
                        this.__initRestore = true;
                    }

                    var view = storage['mapView'];
                    try {
                        view = JSON.parse(view || '');
                        this.setView(L.latLng(view.lat, view.lng), view.zoom, true);
                        return true;
                    } catch (err) {
                        return false;
                    }
                }
            };

            function storageAvailable(type) {
                try {
                    var storage = window[type],
                        x = '__storage_test__';
                    storage.setItem(x, x);
                    storage.removeItem(x);
                    return true;
                } catch (e) {
                    console.warn("Your browser blocks access to " + type);
                    return false;
                }
            }

            L.Map.include(RestoreViewMixin);
        })();


        /// Load markers
        function loadMarkers() {
            var markers = localStorage.getItem("markers");
            if (!markers) return;
            markers = JSON.parse(markers);
            markers.features.forEach(function (entry) {
                latlng = JSON.parse(entry);
                var geojsonFeature = {
                    "type": "Feature",
                    "properties": {},
                    "geometry": {
                        "type": "Point",
                        "coordinates": [latlng.lat, latlng.lng]
                    }
                }

                var marker;

                L.geoJson(geojsonFeature, {

                    pointToLayer: function (feature) {

                        marker = L.marker(latlng, {

                            title: "Resource Location",
                            alt: "Resource Location",
                            riseOnHover: true,
                            draggable: true,
                            icon: redmarker


                        }).bindPopup("<<span>X: " + latlng.lng + ", Y: " + latlng.lat + "</span><br><a href='#' id='marker-delete-button'>Delete marker</a>");

                        marker.on("popupopen", onPopupOpen);

                        return marker;
                    }
                }).addTo(map);
            });
        }


    </script>
@endsection

