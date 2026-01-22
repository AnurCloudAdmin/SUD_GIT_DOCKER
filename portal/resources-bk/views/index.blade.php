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
        <link href="{{env('APP_URL')}}/public/assets/css/jquery-ui.min.css" rel="stylesheet" type="text/css">
    <style>
    #topnav .topbar-main {
      background: #0061A0;
    }
    .small-box {
      border-radius: 2px;
      position: relative;
      display: block;
      margin-bottom: 20px;
      box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
      background: #0090DA;
      color: #fff;
    }
    .small-box:hover {
      text-decoration: none;
      color: #fff;
    }
    .small-box>.inner {
      padding: 10px;
    }
    .small-box h3 {
      font-size: 38px;
      font-weight: bold;
      margin: 0 0 10px 0;
      white-space: nowrap;
      padding: 0;
    }
    .small-box h3,
    .small-box p {
      z-index: 5;
    }
    .small-box p {
      font-size: 15px;
    }
    .small-box .icon {
      -webkit-transition: all .3s linear;
      -o-transition: all .3s linear;
      transition: all .3s linear;
      position: absolute;
      top: -10px;
      right: 10px;
      z-index: 0;
      font-size: 90px;
      color: rgba(0, 0, 0, 0.15);
    }
    .small-box:hover .icon {
      font-size: 95px;
    }
    .dt-buttons {
      float: left;
      width: auto;
    }
    .buttons-excel {
      display: block;
      width: 100px;
    }
    .buttons-excel span{
      display: block;
      background-repeat: no-repeat !important;
      background: url(https://uat-online.pnbmetlife.com/pivc/portal/public/images/excel.svg);
    }
    .modal-content{
      width: 1300px;
      margin-left: -250px;
      height: auto;
    }
    .border{
      border-bottom: 1px #ccc solid;
      padding: 10px 0px;
    }
    .m-r-10{
      margin-right: 10px;
    }
    div#DataTables_Table_0_length {margin-right: 20px;}
	div.dataTables_wrapper {
        max-width: 1280px;
        margin: 0 auto;
    }
  </style>
</head>

<body>

    @extends('header')
    <div class="wrapper">
    <div class="container-fluid" style="max-width: inherit;">
      <!-- Page-Title -->
      <div class="page-title-box">
        <div class="row align-items-center">
          <div class="col-sm-6">
            <h4 class="page-title">Dashboard</h4>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-right">
              <li class="breadcrumb-item"><a href="{{ config('app.url').'/home'}}">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div>
        </div>
        <!-- end row -->
      </div>
      <div class="row">
        <div class="col-lg-12">
          @php
            $from = date('m-d-Y', strtotime('-30 days'));
            $to = date('m-d-Y', strtotime('now'));
          @endphp
          <div class="card m-b-30">
            <div class="card-body">
              <form name="reports" id="reports" style="margin-bottom: 0;">
                @csrf
                <div class="d-flex col-md-12">
                  <div class="form-group col-md-12">
                    <p id="date_filter">
                      <span id="date-label-from" class="date-label">From: </span><input class="date_range_filter date" type="text" id="min" autocomplete="off" readonly="" />
                      <span id="date-label-to" class="date-label">To: <input class="date_range_filter date" type="text" id="max" autocomplete="off" readonly="" />
                    </p>
                  </div>
                </div>
              </form>
              <div class="col-lg-12">
                <h5>Overall</h5>
              </div>
              <div class="col-lg-3 col-xs-6" style="float: left;">
                <div class="small-box bg-aqua">
                  <div class="inner">
                    <h3 id="link_count">{{$prmonth['count']}}</h3>
                    <p>Total Links generated</p>
                  </div>
                  <div class="icon">
                    <a id="link_count_url" href="{{ config('app.url')}}/reports-by-date/total/{{ $from }}/{{ $to }}">
                      <!-- <i class="fa fa-file-excel" style="font-size: 50px;color: white;margin-top: 32px;"></i> -->
                    </a>
                  </div>
                </div>
              </div>
              <div class="col-lg-3 col-xs-6" style="float: left;">
                <div class="small-box bg-green">
                  <div class="inner">
                    <h3 id="link_completed">{{$prmonth['completed']}}</h3>
                    <p>PIVV journey completed</p>
                  </div>
                  <div class="icon">
                    <a id="link_completed_url" href="{{ config('app.url')}}/reports-by-date/completed/{{ $from }}/{{ $to }}">
                      <!-- <i class="fa fa-file-excel" style="font-size: 50px;color: white;margin-top: 32px;"></i> -->
                    </a>
                  </div>
                </div>
              </div>
              <div class="col-lg-3 col-xs-6" style="float: left;">
                <div class="small-box bg-aqua">
                  <div class="inner">
                    <h3 id="link_opened">{{$prmonth['notopen']}}</h3>
                    <p>Link not opened</p>
                  </div>
                  <div class="icon">
                    <a id="link_opened_url" href="{{ config('app.url')}}/reports-by-date/notopen/{{ $from }}/{{ $to }}">
                      <!-- <i class="fa fa-file-excel" style="font-size: 50px;color: white;margin-top: 32px;"></i> -->
                    </a>
                  </div>
                </div>
              </div>
              <div class="col-lg-3 col-xs-6" style="float: left;">
                <div class="small-box bg-green">
                  <div class="inner">
                    <h3 id="link_incomplete">{{$prmonth['incomplete']}}</h3>
                    <p>Incomplete Journey</p>
                  </div>
                  <div class="icon">
                    <a id="link_incomplete_url" href="{{ config('app.url')}}/reports-by-date/incomplete/{{ $from }}/{{ $to }}">
                      <!-- <i class="fa fa-file-excel" style="font-size: 50px;color: white;margin-top: 32px;"></i> -->
                    </a>
                  </div>
                </div>
              </div> 
            </div>
          </div>
        </div>
      </div>

    </div>
    <!-- end container-fluid -->
  </div>

    <!-- Footer -->
    <footer class="footer">
        © <?php echo date('Y');?> <a href="https://anurcloud.com/" target="_blank">Anur Cloud Technologies</a>.
    </footer>

    <!-- End Footer -->

    <!-- jQuery  -->
    <script src="{{env('APP_URL')}}/public/assets/js/jquery.min.js"></script>
  <script src="{{env('APP_URL')}}/public/assets/js/bootstrap.bundle.min.js"></script>
  <script src="{{env('APP_URL')}}/public/assets/js/jquery.slimscroll.js"></script>
  <script src="{{env('APP_URL')}}/public/assets/js/waves.min.js"></script>

  <script src="{{env('APP_URL')}}/public/assets/js/datatables.min.js"></script>
  <script src="{{env('APP_URL')}}/public/assets/js/dataTables.buttons.min.js"></script>
  <script src="{{env('APP_URL')}}/public/assets/js/jszip.min.js"></script>
  <script src="{{env('APP_URL')}}/public/assets/js/pdfmake.min.js"></script>
  <script src="{{env('APP_URL')}}/public/assets/js/vfs_fonts.js"></script>
  <script src="{{env('APP_URL')}}/public/assets/js/buttons.html5.min.js"></script>
  <script src="{{env('APP_URL')}}/public/assets/js/jquery-ui.js"></script>
  <!-- Responsive-table-->
  <!-- {{-- <script src="../plugins/RWD-Table-Patterns/dist/js/rwd-table.min.js')}}"></script> --}} -->

  <!-- App js -->
  <script src="{{env('APP_URL')}}/public/assets/js/app.js"></script>
     
  <script>
    $('#min').datepicker({
      //dateFormat: 'dd-mm-yy',
      beforeShow: function(input, inst) {
        $("#downloadReport,.error-message").hide();
      },
      onSelect: function (selected) {
        var dt = new Date(selected);
        dt.setDate(dt.getDate() + 1);
        $("#max").datepicker("option", "minDate", dt);
        getData();
      },
      changeMonth: true,
      changeYear: true
    });
    $('#max').datepicker({
      //dateFormat: 'dd-mm-yy',
      maxDate: 0,
      beforeShow: function(input, inst) {
        $("#downloadReport,.error-message").hide();
      },
      onSelect: function (selected) {
        var dt = new Date(selected);
        dt.setDate(dt.getDate() + 1);
        $("#min").datepicker("option", "maxDate", dt);
        getData();
      },
      changeMonth: true,
      changeYear: true
    });
    var d = new Date()
    d.setDate(d.getDate() - 30);
    $('#min').datepicker('setDate', d)
    $('#max').datepicker('setDate', new Date())
    $(".date_range_filter").attr("autocomplete", "off");

    function getData() {
      let formdata = new FormData($("#reports")[0])
      formdata.append("from", $('#min').val())
      formdata.append("to", $('#max').val())
      if($('#min').val() != ""){
        let from = formdata.get("from").replaceAll("/",'-')
        let to = formdata.get("to").replaceAll("/",'-')
        $.ajax({
          url:"{{ config('app.url').'/get-home-data'}}",
          type: 'post',
          dataType: "json",
          data: formdata,
          cache : false,
          processData: false,
          contentType: false,
          success: function (response) {
            $("#link_count").text(response.count);
            $("#link_completed").text(response.completed);
            $("#link_opened").text(response.notopen);
            $("#link_incomplete").text(response.incomplete); 
            $("#link_count_url").attr("href", "{{config('app.url')}}/reports-by-date/total/"+from+"/"+to);
            $("#link_completed_url").attr("href", "{{config('app.url')}}/reports-by-date/completed/"+from+"/"+to);
            $("#link_opened_url").attr("href", "{{config('app.url')}}/reports-by-date/notopen/"+from+"/"+to);
            $("#link_incomplete_url").attr("href", "{{config('app.url')}}/reports-by-date/incomplete/"+from+"/"+to); 
          }
        })
      }

    }

  </script>

</body>

</html>
