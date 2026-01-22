<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>{{ config('app.name') }}</title>
    <meta content="Anoor Cloud" name="author" />
    <link rel="shortcut icon" href="{{ asset('images/icon.png')}}">

    <link rel="shortcut icon" href="{{env('APP_URL')}}/public/images/icon.png">
        <link href="{{env('APP_URL')}}/public/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="{{env('APP_URL')}}/public/assets/css/metismenu.min.css" rel="stylesheet" type="text/css">
        <link href="{{env('APP_URL')}}/public/assets/css/icons.css" rel="stylesheet" type="text/css">
        <link href="{{env('APP_URL')}}/public/assets/css/style.css" rel="stylesheet" type="text/css">
</head>

<body>

    @extends('header')

    <div class="wrapper">
        <div class="container-fluid">
            <!-- Page-Title -->
            <div class="page-title-box">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h4 class="page-title">Dashboard</h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>
                </div>
                <!-- end row -->
            </div>

                                  

            <div class="row">
                <div class="col-xl-12">
                    <div class="card m-b-30">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="errormsg">
                                        <p style="width: 244px;float: left;margin-right:10px" class="minError">* Start date</p>
                                        <p style="width: 244px;float: left;margin-right:10px" class="maxError">* End date</p>
                                    </div>
                                </div>                        
                            </div>  
                            {{-- <div class="errormsg"></div> --}}
                            <form method="post" id="reportsdownload" name="reportsdownload" action="{{ url('dashboardDate') }}" onsubmit="return checkDateValid();">
                                @csrf
                                <input type="text" id="min" required style="width: 244px;float: left;margin-right:10px" name="min" class="form-control" autocomplete="off" placeholder="YYYY-MM-DD" value="{{ $min }}"/>
                                <input type="text" id="max" required name="max"  autocomplete="off" style="width: 244px;float: left;margin-right:10px" class="form-control" placeholder="YYYY-MM-DD" value="{{ $max }}"/>
                                <button type="submit" name="submit" class="btn btn-outline-success waves-effect waves-light"> Search Data</button>
                                <a href="{{url('dashboard')}}" class="btn btn-outline-dark">Clear</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
<?php 
if($min=="" && $max==""){
   $min = date('Y-m-d');
   $max = date('Y-m-d');
}
?>
            <div class="row">
                <div class="col-sm-6 col-xl-6">
                    <div class="row">
                        <div class="col-sm-6 col-xl-6">
                            <div class="card">
                                <div class="card-heading p-4">
                                    <div class="mini-stat-icon float-right">
                                        <!--<i class="mdi mdi-notification-clear-all bg-primary text-white"></i>-->
                                        <a href="{{url('excelExport')}}/{{ $min }}/{{ $max }}/Total_links" >
                                            <i class="mdi mdi-file-excel bg-primary text-white"></i>
                                        </a>
                                    </div>
                                    <div>
                                        <h5 class="font-16">Total links</h5>
                                    </div>
                                    <h3 class="mt-4">{{ $total }}</h3>
                                    <div class="progress mt-4" style="height: 4px;">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $total_progress }}%" aria-valuenow="{{ $total_progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <p class="text-muted mt-2 mb-0">Total links<span class="float-right">{{--{{ $total_progress }}%--}}</span></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-xl-6">
                            <div class="card">
                                <div class="card-heading p-4">
                                    <div class="mini-stat-icon float-right">
                                        <!--<i class="mdi mdi-email-open-outline bg-warning text-white"></i>-->
                                        <a href="{{url('excelExport')}}/{{ $min }}/{{ $max }}/Opened_Pending" >
                                            <i class="mdi mdi-file-excel bg-warning text-white"></i>
                                        </a>
                                    </div>
                                    <div>
                                        <h5 class="font-16">Opened (Pending)</h5>
                                    </div>
                                    <h3 class="mt-4">{{ $opened }}</h3>
                                    <div class="progress mt-4" style="height: 4px;">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $opened_progress }}%" aria-valuenow="{{ $opened_progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <p class="text-muted mt-2 mb-0">Opened Pending PIVC<span class="float-right">{{ $opened_progress }}%</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">  
                        <div class="col-sm-6 col-xl-6">
                            <div class="card">
                                <div class="card-heading p-4">
                                    <div class="mini-stat-icon float-right">
                                        <!--<i class="mdi mdi-emoticon-happy bg-success text-white"></i>-->
                                        <a href="{{url('excelExport')}}/{{ $min }}/{{ $max }}/Completed" >
                                            <i class="mdi mdi-file-excel bg-success text-white"></i>
                                        </a>
                                    </div>
                                    <div>
                                        <h5 class="font-16">Completed</h5>
                                    </div>
                                    <h3 class="mt-4">{{ $completed }}</h3>
                                    <div class="progress mt-4" style="height: 4px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $completed_progress }}%" aria-valuenow="{{ $completed_progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <p class="text-muted mt-2 mb-0">PIVC Completed<span class="float-right">{{ $completed_progress }}%</span></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-xl-6">
                            <div class="card">
                                <div class="card-heading p-4">
                                    <div class="mini-stat-icon float-right">
                                        <!--<i class="mdi mdi-email-outline bg-danger text-white"></i>-->
                                        <a href="{{url('excelExport')}}/{{ $min }}/{{ $max }}/Not_Opened" >
                                            <i class="mdi mdi-file-excel bg-danger text-white"></i>
                                        </a>
                                    </div>
                                    <div>
                                        <h5 class="font-16">Not Opened</h5>
                                    </div>
                                    <h3 class="mt-4">{{ $pending }}</h3>
                                    <div class="progress mt-4" style="height: 4px;">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $pending_progress }}%" aria-valuenow="{{ $pending_progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <p class="text-muted mt-2 mb-0">Not Opened PIVC<span class="float-right">{{ $pending_progress }}%</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-6">
                    <div id="pie_chart" >
                </div>
            </div>
        </div><?php ?>
    
        </div>
        <!-- end container-fluid -->
    </div>
    <!-- end wrapper -->

    <!-- Footer -->
    <footer class="footer">
        © <?php echo date('Y');?> <a href="https://anurcloud.com/" target="_blank">Anur Cloud Technologies</a>.
    </footer>

    <!-- End Footer -->
 
    <script src="{{env('APP_URL')}}/assets/js/jquery.min.js"></script>
    <script src="{{env('APP_URL')}}/assets/js/bootstrap.bundle.min.js"></script>
    <script src="{{env('APP_URL')}}/assets/js/jquery.slimscroll.js"></script>
    <script src="{{env('APP_URL')}}/assets/js/waves.min.js"></script>

    <!-- App js -->
    <script src="{{env('APP_URL')}}/assets/js/app.js"></script>
    <script src="{{env('APP_URL')}}/assets/js/jquery-ui.js"></script>

    <script>
        
    function checkDateValid(){
        var flag=true;
        var errorMsg  = '';
        if($('#min').val()==''){
        errorMsg  +='<p>Choose Min Date</p>';
        $('.errormsg .minError').html(errorMsg);
        $('.errormsg .minError').css("color","red");
        flag=false;
        }
        else {
            $('.errormsg .minError').css("color","");
        }
        if($('#max').val()==''){
        errorMsg  +='<p>Choose Max Date</p>';
        $('.errormsg .maxError').css("color","red");
        flag=false;
        }
        if(flag==false){
        $('.errormsg').fadeIn();
        
        }
        return flag;
    }

    $(function() {
        $('#min').datepicker({
        onSelect: function (selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate() + 1);
            $("#max").datepicker("option", "minDate", dt);
        },
        changeMonth: true, changeYear: true,dateFormat:'yy-mm-dd',maxDate: new Date()
        });
        $('#max').datepicker({
        onSelect: function (selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate() + 1);
            $("#min").datepicker("option", "maxDate", dt);
        },
        changeMonth: true, changeYear: true,dateFormat:'yy-mm-dd',maxDate: new Date() });
    });


      $("form").on("click", function(){
        $('#excelfile').click();
      });
    </script>

    <script src="{{env('APP_URL')}}/assets/js/highcharts.js" ></script>
    <script src="{{env('APP_URL')}}/assets/js/exporting.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var pieData =  <?php echo json_encode($pieData); ?>;
            var options = {
                chart: {
                    renderTo: 'pie_chart',
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false
                },
                title: {
                    text: 'PIVC Information Chart'
                },
                tooltip: {
                    //pointFormat: '{series.name}: <b>{point.percentage}%</b>',
                    pointFormat: '{series.name}',
                    percentageDecimals: 1
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                            dataLabels: {
                            enabled: true,
                            color: '#000000',
                            connectorColor: '#000000',
                            formatter: function() {
                                return '<b>'+ this.point.name +'</b>: '+ Math.round(this.percentage) +' %';
                            }
                        }
                    }
                },
                series: [{
                    type:'pie',
                    name:'PIVC'
                }],
                // dc3545 => red, ffc107 => yellow
                colors: ['#dc3545', '#02c58d', '#ffc107'],
                exporting: {
                    enabled: false
                }
            }
            myarray = [];
            $.each(pieData, function(index, val) {
                myarray[index] = [val.label, val.value];
            });
            options.series[0].data = myarray;
            chart = new Highcharts.Chart(options);
            

        });

        
        $(function() {
            window.onload = function(){
                @if($errors->any())
                    $('html, body').animate({
                        scrollTop: $("#upload-section").offset().top
                    }, 2000);
                @endif
            }
         });
    </script>

</body>

</html>
