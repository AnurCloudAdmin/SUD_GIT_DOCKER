<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <title>{{ config('app.name') }}</title>
  <meta content="Anoor Cloud" name="author" />
  <link rel="shortcut icon" href="{{ asset('public/images/icon.png')}}">

  <!-- Table css -->
  {{-- <link href="../plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css" rel="stylesheet" type="text/css" media="screen"> --}}

  <link href="{{ asset('public/assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css">
  <link href="{{ asset('public/assets/css/metismenu.min.css')}}" rel="stylesheet" type="text/css">
  <link href="{{ asset('public/assets/css/icons.css')}}" rel="stylesheet" type="text/css">
  <link href="{{ asset('public/assets/css/style.css')}}" rel="stylesheet" type="text/css">
  <link href="{{ asset('public/assets/css/datatables.min.css')}}" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="{{ asset('public/assets/css/jquery-ui.min.css')}}" />
  <!-- <link rel="stylesheet" href="{{ asset('public/validatation/css/validationEngine.jquery.css')}}" /> -->

  <style>
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
      background: url({{asset('public/images/excel.svg')}});
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
    .infobox{
        padding: 10px;
background: #ffa84c; /* Old browsers */
background: -moz-linear-gradient(top,  #ffa84c 0%, #ff7b0d 100%); /* FF3.6-15 */
background: -webkit-linear-gradient(top,  #ffa84c 0%,#ff7b0d 100%); /* Chrome10-25,Safari5.1-6 */
background: linear-gradient(to bottom,  #ffa84c 0%,#ff7b0d 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffa84c', endColorstr='#ff7b0d',GradientType=0 ); /* IE6-9 */
min-height: 100px;
        color: #fff;
    }
    .infoclass{
        font-size: 20px;
    font-weight: bold;
    border-bottom: 1px solid #e80c01;
    padding: 10px 0;
    text-align: center;
    }
    .infocount{
        float: right;
    padding: 7px;
    font-size: 18px;
    }
    .errormsg{
      background: red;
    padding: 7px;
    margin: 14px;
    color: #fff;
    border-radius: 5px;
    width: 238px;
    display:none;
    }
    .errormsg p{line-height:1;margin-bottom:5px}
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
            <h4 class="page-title">All Data Report Download</h4>
          </div>
          <div class="col-sm-6"> 
            <ol class="breadcrumb float-right">
              <li class="breadcrumb-item"><a href="{{url('home')}}">Home</a></li>
              <li class="breadcrumb-item active">All Data Report Download</li>
            </ol>
          </div>
        </div>
        <!-- end row -->
      </div>
      <div class="row">
        <div class="col-md-12 col-xs-12">
          <div class="card m-b-30">
            <div class="card-body  ">
              <div class="row"> 
                <div class="col-md-12 col-xs-12">
                <form method="post" id="reportsdownload" name="reportsdownload" action="{{url('linksreportsdownload')}}" onsubmit="return checkDateValid();">
                @csrf
                  <input type="text" id="min" style="width: 244px;float: left;margin-right:10px" name="min" class="form-control" autocomplete="off" placeholder="YY-MM-DD"/>
                  <input type="text" id="max" name="max"  autocomplete="off" style="width: 244px;float: left;margin-right:10px" class="form-control" placeholder="YY-MM-DD"/>
                  <button type="submit" name="submit" class="btn btn-success"><i class="fa fa-file-excel"></i> Export Excel</button>
                </form>
                </div>
                <div class="errormsg"></div>
              </div>
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
    © <?php echo date('Y');?> Anoor Cloud Technologies.
  </footer>

  <!-- End Footer -->

  <!-- jQuery  -->
  <script src="{{ asset('public/assets/js/jquery.min.js')}}"></script>
  <script src="{{ asset('public/assets/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{ asset('public/assets/js/jquery.slimscroll.js')}}"></script>
  <script src="{{ asset('public/assets/js/waves.min.js')}}"></script>

  <script src="{{ asset('public/assets/js/datatables.min.js')}}"></script>
  <script src="{{ asset('public/assets/js/dataTables.buttons.min.js')}}"></script>
  <script src="{{ asset('public/assets/js/jszip.min.js')}}"></script>
  <script src="{{ asset('public/assets/js/pdfmake.min.js')}}"></script>
  <script src="{{ asset('public/assets/js/vfs_fonts.js')}}"></script>
  <script src="{{ asset('public/assets/js/buttons.html5.min.js')}}"></script>
  <script src="{{ asset('public/assets/js/jquery-ui.js')}}"></script>


  <!-- App js -->
  <script src="{{ asset('public/assets/js/app.js')}}"></script>
  <script>
  function checkDateValid(){
    var flag=true;
    var errorMsg  = '';
    if($('#min').val()==''){
      errorMsg  +='<p>Choose Min Date</p>';
      flag=false;
    }
    if($('#max').val()==''){
      errorMsg  +='<p>Choose Max Date</p>';
      flag=false;
    }
    if(flag==false){
      $('.errormsg').fadeIn();
      $('.errormsg').html(errorMsg);
    }

    return flag;
  }
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

    $('#min').datepicker({
      onSelect: function (selected) {
        var dt = new Date(selected);
        dt.setDate(dt.getDate() + 1);
        $("#max").datepicker("option", "minDate", dt);
      },
      changeMonth: true, changeYear: true,dateFormat:'yy-mm-dd'
    });
    $('#max').datepicker({
      onSelect: function (selected) {
        var dt = new Date(selected);
        dt.setDate(dt.getDate() + 1);
        $("#min").datepicker("option", "maxDate", dt);
      },
      changeMonth: true, changeYear: true,dateFormat:'yy-mm-dd' });
    /*var table = $('.table').DataTable({
        "pageLength": 50,
        dom: 'lBfrtip',
        buttons: [
          {
            extend: 'excelHtml5',
            text: 'Report',
            exportOptions: {
                columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 10 ]
            }
          },
        ]
      });
      */

    });


  </script>

</body>

</html>
