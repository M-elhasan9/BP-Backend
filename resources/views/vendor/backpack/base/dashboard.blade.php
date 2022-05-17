@extends(backpack_view('blank'))

@section('content')
    <div class="card">
        <div class="card-header">General Statistics</div>
        <div class="card-body">
            <div class="row">
                <!-- /.col-->
                <div class="col-sm-12 col-md-3 col-lg-2">
                    <div class="callout callout-danger"><small class="text-muted">Fires Count</small><br><strong
                            class="h4">{{\App\Models\Fire::count()}}</strong>

                    </div>
                </div>
                <!-- /.col-->
                <div class="col-sm-12 col-md-3 col-lg-2">
                    <div class="callout callout-warning"><small class="text-muted">Reports Count</small><br><strong
                            class="h4">{{\App\Models\Report::count()}}</strong>

                    </div>
                </div>
                <!-- /.col-->
                <div class="col-sm-12 col-md-3 col-lg-2">
                    <div class="callout callout-dark"><small class="text-muted">Users Count</small><br><strong
                            class="h4">{{\App\Models\User::count()}}</strong>

                    </div>
                </div>

                <div class="col-sm-12 col-md-3 col-lg-2">
                    <div class="callout callout-info"><small class="text-muted">Subscribes Count</small><br><strong
                            class="h4">{{\App\Models\Subscribe::count()}}</strong>

                    </div>
                </div>
                <!-- /.col-->
                <div class="col-sm-12 col-md-3 col-lg-2">
                    <div class="callout callout-success"><small class="text-muted">Cameras Count</small><br><strong
                            class="h4">{{\App\Models\Camera::count()}}</strong>

                    </div>
                </div>
            </div>
            <!-- /.row-->

            <br>
            <script class="p-4">
                window.onload = function () {
                    var chart = new CanvasJS.Chart("chartContainer", {
                        theme: "light2", // "light1", "light2", "dark1", "dark2"
                        exportEnabled: true,
                        animationEnabled: true,
                        title: {
                            text: "A graph showing the percentage of fires by state until {{ date('Y-m-d') }}"
                        },
                        data: [{
                            type: "pie",
                            startAngle: 25,
                            toolTipContent: "<b>{label}</b>: {y}%",
                            showInLegend: "true",
                            legendText: "{label}",
                            indexLabelFontSize: 16,
                            indexLabel: "{label} - %{y}",
                            dataPoints: [
                                {y: {{number_format(\App\Models\Fire::query()->where('status','=',1)->count(),2)}}, label: "New"},
                                {y: {{number_format(\App\Models\Fire::query()->where('status','=',3)->count(),2)}}, label: "End"},
                                {y: {{number_format(\App\Models\Fire::query()->where('status','=',2)->count(),2)}}, label: "Confirmed"},
                            ]
                        }]
                    });
                    chart.render();
                }
            </script>


            <body>
            <div class="" id="chartContainer" style="height: 500px; width: 100%;"></div>
            <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
            </body>


        </div>
    </div>



@endsection

