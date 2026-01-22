<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>{{ config('app.name') }}</title>
    <meta content="Anoor Cloud" name="author" />
    <link rel="shortcut icon" href="{{ asset('public/images/icon.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css"
        integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Table css -->
    {{-- <link href="../plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css" rel="stylesheet" type="text/css" media="screen"> --}}

    <link rel="shortcut icon" href="{{ env('APP_URL') }}/public/images/icon.png">
    <link href="{{ env('APP_URL') }}/public/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="{{ env('APP_URL') }}/public/assets/css/metismenu.min.css" rel="stylesheet" type="text/css">
    <link href="{{ env('APP_URL') }}/public/assets/css/icons.css" rel="stylesheet" type="text/css">
    <link href="{{ env('APP_URL') }}/public/assets/css/style.css" rel="stylesheet" type="text/css">
    <link href="{{ env('APP_URL') }}/public/assets/css/jquery-ui.min.css" rel="stylesheet" type="text/css">
    <style>
        .dt-buttons {
            float: left;
            width: auto;
        }

        .buttons-excel {
            display: block;
            width: 100px;
        }

        .buttons-excel span {
            display: block;
            background-repeat: no-repeat !important;
            background: url({{ asset('public/images/excel.svg') }});
        }

        .modal-content {
            margin-left: -250px;
            height: auto;
        }

        .border {
            border-bottom: 1px #ccc solid;
            padding: 10px 0px;
        }

        .m-r-10 {
            margin-right: 10px;
        }

        .modal-title {
            float: left;
            width: 100%;
        }

        div#DataTables_Table_0_length {
            margin-right: 20px;
        }

        .modal.fade {
            margin: 13%;
        }

        .modal-body table {
            width: 100%;
        }
    </style>
    <style>
        .show {
            display: block !important;
            opacity: 1 !important;
        }
    </style>
</head>

<body>

    @extends('header')
    <div class="wrapper">
        <div class="container-fluid">
            <!-- Page-Title -->
            <div class="page-title-box">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h4 class="page-title">Logs details</h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item"><a href="{{ url('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Logs details</li>
                        </ol>
                    </div>
                </div>
                <!-- end row -->
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card m-b-30">
                        <div class="card-body">
                            <form method="post" id="filterhome" action="" style="display: inline-block;">
                                @csrf
                                <p id="date_filter" class="d-flex align-items-center">

                                    <input class="form-control mr-2" style="width: 300px;" placeholder="Application No"
                                        name="application_no" value="{{ @$application_no }}" id="application_no"
                                        autocomplete="off" />

                                    <button type="submit" class="btn btn-success mr-2" id="submit">Search</button>

                                    <a href="{{env('APP_URL')}}logsreportdownload/{{@$application_no}}" class="btn btn-success">
                                        <i class="fa fa-file-excel"></i> Export Excel
                                    </a>
                                </p>

                               
                                <label for="">No Of Attempts      {{@$no_of_attempts}}</label>
                                <br>
                            </form>
                            @if (!empty($logs))
                                <div class="table-rep-plugin">
                                    <div class="table-responsive b-0" data-pattern="priority-columns" style="">
                                        <table class="table  table-bordered" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Sl.No</th>
                                                    <th>Application No</th>
                                                    <th>created at</th>
                                                    <th>Module</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($logs as $key => $log)
                                                    <tr>
                                                        <th>{{ ++$key }}</th>
                                                        <th>{{ $log->app_no }}</th>
                                                        <th>{{ $log->created_at }}</th>
                                                        <th>{{ $log->module }}</th>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <td colspan="13">
                                                        <nav aria-label="Page navigation" style="float:right">
                                                            <ul class="pagination">

                                                            </ul>
                                                        </nav>
                                                    </td>
                                                </tr>

                                            </tbody>

                                        </table>

                                    </div>

                                </div>
                            @endif

                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->

        </div>
        <!-- end container-fluid -->
    </div>
    <!-- end wrapper -->
    <div class="modal fade" id="userModal" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="border-bottom:none;">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <code></code>
                </div>
                <div class="modal-footer" style="border-top:none;">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <footer class="footer">
        © <?php echo date('Y'); ?> Anoor Cloud Technologies.
    </footer>

    <!-- End Footer -->

    <!-- jQuery  -->
    <script src="{{ env('APP_URL') }}/public/assets/js/jquery.min.js"></script>
    <script src="{{ env('APP_URL') }}/public/assets/js/bootstrap.bundle.min.js"></script>
    <script src="{{ env('APP_URL') }}/public/assets/js/jquery.slimscroll.js"></script>
    <script src="{{ env('APP_URL') }}/public/assets/js/waves.min.js"></script>

    <script src="{{ env('APP_URL') }}/public/assets/js/datatables.min.js"></script>
    <script src="{{ env('APP_URL') }}/public/assets/js/dataTables.buttons.min.js"></script>
    <script src="{{ env('APP_URL') }}/public/assets/js/jszip.min.js"></script>
    <script src="{{ env('APP_URL') }}/public/assets/js/pdfmake.min.js"></script>
    <script src="{{ env('APP_URL') }}/public/assets/js/vfs_fonts.js"></script>
    <script src="{{ env('APP_URL') }}/public/assets/js/buttons.html5.min.js"></script>
    <script src="{{ env('APP_URL') }}/public/assets/js/jquery-ui.js"></script>
    <!-- Responsive-table-->


    <!-- App js -->
    <script src="{{ env('APP_URL') }}/public/assets/js/app.js"></script>
    <script>
        /*$(function() {
          $('.table').DataTable({dom: 'Bfrtip',
            buttons: [
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ]
          });
        });*/
        /*$('#userModal').on('hidden.bs.modal', function (e) {

        })
        $('#userModal').on('show.bs.modal', function (e) {
          var button = $(e.relatedTarget);
          $(this).find('.modal-title').html($(this).data('name')+" "+$(this).data('proposal'))
        })*/
        $(function() {
            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {
                    var min = $('#min').datepicker('getDate');
                    var max = $('#max').datepicker('getDate');
                    if (max !== null) {
                        max.setDate(max.getDate() + 1);
                        max = new Date(max);
                    }
                    /*if(max != ""){
                      max.setDate(max.getDate()+1);
                      max= new Date(max);
                    }*/
                    var startDate = new Date(data[3]);
                    if (min == null && max == null) return true;
                    if (min == null && startDate <= max) return true;
                    if (max == null && startDate >= min) return true;
                    if (startDate <= max && startDate >= min) return true;
                    return false;
                }
            );


            var table = $('.table').DataTable({
                // "iDisplayLength": -1,
                // "sPaginationType": "full_numbers",
                "pageLength": 50,
                dom: 'lBfrtip',
                order: [
                    [1, 'asc'],
                    [11, 'dasc']
                ],
                buttons: [{
                        extend: 'excelHtml5',
                        text: 'Report',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 10]
                        }
                    },
                    // 'csvHtml5',
                    // 'pdfHtml5'
                ]
            });

            // Event listener to the two range filtering inputs to redraw on input
            $('#min, #max').change(function() {
                table.draw();
            });
            /*
                  var oTable = $('.table').DataTable({
                    "iDisplayLength": -1,
                    "sPaginationType": "full_numbers",
                    dom: 'Bfrtip',
                    buttons: [
                        'excelHtml5',
                        'csvHtml5',
                        'pdfHtml5'
                    ]
                  });

                  $("#datepicker_from").datepicker({
                    showOn: "button",
                    buttonImageOnly: false,
                    "onSelect": function(date) {
                      minDateFilter = new Date(date).getTime();
                      oTable.fnDraw();
                    }
                  }).keyup(function() {
                    minDateFilter = new Date(this.value).getTime();
                    oTable.fnDraw();
                  });

                  $("#datepicker_to").datepicker({
                    showOn: "button",
                    buttonImageOnly: false,
                    "onSelect": function(date) {
                      maxDateFilter = new Date(date).getTime();
                      oTable.fnDraw();
                    }
                  }).keyup(function() {
                    maxDateFilter = new Date(this.value).getTime();
                    oTable.fnDraw();
                  });*/

        });
    </script>

</body>

</html>
