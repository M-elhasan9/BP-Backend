@php
    $firstColumnOfType = $crud->getFirstOfItsTypeInArray($column['type'],$crud->columns())
@endphp
    <table id="{{$column['key']}}" class="table table-striped table-bordered w-100">
        <thead class="">
        <tr>
            @foreach($column['titles'] as $item)
            <td>
                {{$item}}
            </td>
            @endforeach
        </tr>
        </thead >
    </table>


@if($firstColumnOfType && $firstColumnOfType['name'] == $column['name'])
    @push('after_styles')
        <link rel="stylesheet" type="text/css" href="{{ asset('packages/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('packages/datatables.net-fixedheader-bs4/css/fixedHeader.bootstrap4.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('packages/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}">
        <style>
            .dataTables_wrapper{
                padding: 8px;
            }
        </style>
    @endpush
    @push('after_scripts')
        <script type="text/javascript" src="{{ asset('packages/datatables.net/js/jquery.dataTables.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('packages/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('packages/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('packages/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('packages/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('packages/datatables.net-fixedheader-bs4/js/fixedHeader.bootstrap4.min.js') }}"></script>
    @endpush
@endif
@push('after_scripts')
    <script>
        $(document).ready(() => {
            Object.byString = function(o, s) {
                s = s.replace(/\[(\w+)\]/g, '.$1'); // convert indexes to properties
                s = s.replace(/^\./, '');           // strip a leading dot
                var a = s.split('.');
                for (var i = 0, n = a.length; i < n; ++i) {
                    var k = a[i];
                    if (k in o) {
                        o = o[k];
                    } else {
                        return;
                    }
                }
                return o;
            }
            let columns = {!! isset($column['columns']) ?  json_encode($column['columns'])  : json_encode([]) !!};
            let titles = {!! isset($column['titles']) ?  json_encode($column['titles'])  : json_encode([]) !!};
            if (columns.length > titles.length){
                new Noty({
                    text: 'يجب أن يكون طول مصفوفة العناوين مساويا لطول مصفوفة الخلايا ' + "{!! $column['name'] . " - " .  $column['key'] ?? '' !!}",
                    type: 'error'
                }).show()
                return false;
            }
            let options = {
                processing: true,
                serverSide: true,
                search: true,
                ajax: {
                    url: '{!! $column['source'] !!}',
                    error: er => {
                        if (er.status === 403){
                            new Noty({
                                text: 'خطأ في الصلاحيات  ' + "{!! $column['name'] . " - " .  $column['key'] ?? '' !!}",
                                type: 'error'
                            }).show()
                        }
                        else if(er.status === 404){
                            new Noty({
                                text: 'مصدر البيانات غير صالح  ' + "{!! $column['name'] . " - " .  $column['key'] ?? '' !!}",
                                type: 'error'
                            }).show()
                        }
                        else{
                            new Noty({
                                text: 'خطأ غير معروف  ' + "{!! $column['name'] . " - " .  $column['key'] ?? '' !!}",
                                type: 'error'
                            }).show()
                        }
                        return false
                    }
                },
            };
            if (columns.length > 0){
                options.columns = columns.map(el => {
                    let row = {data:el.name,name:el.name};
                    if (el.type && el.type === 'link'){
                        row.data = {};
                        row = {...row,render : (data) => {
                                return `<a href=${el.link.replace('?',Object.byString(data,el.key))}>${Object.byString(data,el.attribute)}</a>`;
                            }}
                    }
                    if (el.type && el.type === 'select'){
                        row = {...row,render : (data) => {
                                return el.options[data];
                            }}
                    }
                    return row;
                });

            }
            const {!! $column['key']  !!} =  $('#{!! $column['key'] !!}').DataTable(options);
        })
        $.fn.dataTable.ext.errMode = 'throw';

    </script>
@endpush
