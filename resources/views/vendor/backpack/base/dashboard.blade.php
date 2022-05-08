@extends(backpack_view('blank'))

@section('content')
    <div class="card">
        <div class="card-header">General Statistics</div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12 col-md-3 col-lg-2">
                    <div class="callout callout-warning"><small class="text-muted">Users Count</small><br><strong
                            class="h4">{{\App\Models\User::count()}}</strong>

                    </div>
                </div>
                <!-- /.col-->
                <div class="col-sm-12 col-md-3 col-lg-2">
                    <div class="callout callout-success"><small class="text-muted">Reports Count</small><br><strong
                            class="h4">{{\App\Models\Report::count()}}</strong>

                    </div>
                </div>
                <div class="col-sm-12 col-md-3 col-lg-2">
                    <div class="callout callout-warning"><small class="text-muted">Subscribes Count</small><br><strong
                            class="h4">{{\App\Models\Subscribe::count()}}</strong>

                    </div>
                </div>
                <!-- /.col-->
                <div class="col-sm-12 col-md-3 col-lg-2">
                    <div class="callout callout-success"><small class="text-muted">Cameras Count</small><br><strong
                            class="h4">{{\App\Models\Camera::count()}}</strong>

                    </div>
                </div>


                <!-- /.col-->
            </div>
            <!-- /.row-->
        </div>
    </div>



@endsection

