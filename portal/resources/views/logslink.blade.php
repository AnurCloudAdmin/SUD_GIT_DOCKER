<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <title>{{ config('app.name') }}</title>
  <meta content="Anoor Cloud" name="author" />
  <link rel="shortcut icon" href="{{ asset('public/images/icon.png')}}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

  <!-- Table css -->
  {{-- <link href="../plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css" rel="stylesheet" type="text/css" media="screen"> --}}

  <link rel="shortcut icon" href="{{env('APP_URL')}}/public/images/icon.png">
        <link href="{{env('APP_URL')}}/public/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="{{env('APP_URL')}}/public/assets/css/metismenu.min.css" rel="stylesheet" type="text/css">
        <link href="{{env('APP_URL')}}/public/assets/css/icons.css" rel="stylesheet" type="text/css">
        <link href="{{env('APP_URL')}}/public/assets/css/style.css" rel="stylesheet" type="text/css">
        <link href="{{env('APP_URL')}}/public/assets/css/jquery-ui.min.css" rel="stylesheet" type="text/css">
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
    .modal-title{float: left;
    width: 100%;}
    div#DataTables_Table_0_length {margin-right: 20px;}
	.modal.fade{margin: 13%;}
	.modal-body table{width: 100%;}
  </style>
  <style>.show {
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
            <h4 class="page-title">Logs</h4> 
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-right">
              <li class="breadcrumb-item"><a href="{{url('home')}}">Home</a></li>
              <li class="breadcrumb-item active">Logs</li>
            </ol>
          </div>
        </div>
        <!-- end row -->
      </div>
      <div class="row">
        <div class="col-12">
          <div class="card m-b-30">
            <div class="card-body"> 
              <form method="get" id="filterhome" action="" style="display: inline-block;"> 
              <input type="hidden" name="orderName" class="orderName" value="{{$lists['orderName']}}"/>
              <input type="hidden" name="orderBy" class="orderBy" value="{{$lists['orderBy']}}"/>
              <input type="hidden" name="pageNumber" class="pageNumber" value="{{$lists['pageNumber']}}"/>
              <input type="hidden" name="perpage" class="perpage" value="{{$lists['perPage1']}}"/>
              <input type="hidden" name="resetfilter" class="resetfilter" value="0"/>
              
              <p id="date_filter">
                <input class="date_range_filter date" type="text" id="min"  name="from" value="{{$lists['from']}}" placeholder="From"  /></span>
                <span id="date-label-to" class="date-label"> <input class="date_range_filter date" type="text" name="to" id="max"  placeholder="To"  value="{{$lists['to']}}" /></span>
                
                <button type="submit" class="btn btn-success" id="submit" >Search </button>
              </p>
            </form>

            <form method="post" id="reportsdownload" name="reportsdownload" action="{{env('APP_URL')}}logsreportdownload" style="display: inline-block;" > 
            @csrf
            <button type="submit" name="submit" class="btn btn-success"><i class="fa fa-file-excel"></i> Export Excel</button>
            <input class="date_range_filter date" type="hidden" id="min"  name="from" value="{{$lists['from']}}" placeholder="From"  />
                <span id="date-label-to" class="date-label"> <input class="date_range_filter date" type="hidden" name="to" id="max"  placeholder="To"  value="{{$lists['to']}}" ></span>
                <span id="date-label-to" class="date-label"> <input class="date_range_filter date" type="hidden" name="filter" id="filter"  placeholder="Proposal Number"  value="{{$lists['filter']}}" ></span>
            </form>

              <div class="table-rep-plugin">
                <div class="table-responsive b-0" data-pattern="priority-columns" style="">
                  <table class="table  table-bordered" width="100%">
                    <thead>
                      <tr>
                        <th>Sl.No</th>
                        <th>Application No</th>
                        <th>Mobile No</th>
                        <th>Link Open</th>
                        <th>Link Open At</th>
                        <th>Stage</th>
                        <th>Submitted</th>
                        <th>Submitted At</th>
                        <th>Error Log</th>
                        <th>No Of Attempts</th>
                        <th>Date</th>
                      </tr> 
                    </thead>
                    <tbody>
                      @foreach($lists['data'] as $key => $list)
                      @php 

// {"proposal_no":"PREM50505","personal_name":"Kumar Gowtham","personal_dob":"1989-04-10","personal_gender":"Male","personal_occupation":"Business","personal_email":"saravanak@anurcloud.com","personal_mobile":"8807831686","personal_address":"30 Thirumalapuram Nadarstreet, Srivilliputtur","policy_prod_name":"pramerica life signature wealth","policy_plan":"regular income option","policy_sum_assured":"1500000","policy_rider_name":"Critical Illness","policy_rider_sum_assured":"100000","policy_preimum_amount":"100000","policy_payment_type":"Credit Card","policy_Frequency":"Annually","photo":null,"premium_payment_term":"10 Years","policy_term":"10 Years","policy_plan_option":"NON-ULIP"}

                      $_params = json_decode(@$list->getLink->params,true); 
                      
                        $is_open = " ";
                         if ($list->module == 'Not Attempted' ){
                          $is_open = "No";
                         }else{
                          $is_open = "Yes";
                         } 

                         $responsesuccess = "";
                         $responsefailure = "";

                         if ($list->module == 'Product Unavailable' ){
                            $responsefailure =$list->response;
                         }
                         
                         $submitted = "No";
                         $submitted_at = "";
                         if (@$list->getLink->docpush == 1 ){
                          $submitted = "Yes";
                          $submitted_at = @$list->getLink->docpush_date;
                         }
                        
                         if (Str::contains($list->module, 'Failed')){
                              $responsesuccess = "-";
                              $responsefailure =$list->response;
                         }else{
                              $responsesuccess = "Yes";
                            //  $responsefailure = " ";
                         }
                      @endphp
                   
                      <tr>
                        <th>{{$key+1}}</th>
                        <th>{{$list->app_no}}</th>
                        <th>{{@$_params['personal_mobile']}}</th>  
                        <td>{{$is_open}}</td>
                        <td>{{@$list->getLink->is_open_at}}</td>
                        <td>{{$list->module}}</td> 
                        <td>{{$submitted}}</td>
                        <td>{{$submitted_at}}</td>
                         
                        <td>{{$responsefailure}}</td> 
                        <td>{{@$list->getLink->link_attempt_count}}</td>
                        <td>{{$list->created_at}}</td>
                      </tr>
                      @endforeach
                      <tr>
                        <td colspan="13">
                        <nav aria-label="Page navigation" style="float:right">
                          <ul class="pagination">
                           {!! $pagination !!}
                          </ul>
                        </nav>
                        </td>
                      </tr>

                    </tbody>
                  </table>

                </div>

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
 

  <!-- App js -->
  <script src="{{env('APP_URL')}}/public/assets/js/app.js"></script>
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
        function (settings, data, dataIndex) {
          var min = $('#min').datepicker('getDate');
          var max = $('#max').datepicker('getDate');
          if (max !== null) {
            max.setDate(max.getDate()+1);
            max= new Date(max);
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

    $('#min').datepicker({
      onSelect: function (selected) {
        var dt = new Date(selected);
        dt.setDate(dt.getDate() + 1);
        $("#max").datepicker("option", "minDate", dt); 
      },
     dateFormat:'yy-mm-dd', changeMonth: true, changeYear: true
    });
    $('#max').datepicker({
      onSelect: function (selected) {
        var dt = new Date(selected);
        dt.setDate(dt.getDate() + 1);
        $("#min").datepicker("option", "maxDate", dt); 
      },dateFormat:'yy-mm-dd',
      changeMonth: true, changeYear: true });
    var table = $('.table').DataTable({
        // "iDisplayLength": -1,
        // "sPaginationType": "full_numbers",
        "pageLength": 50,
        dom: 'lBfrtip',
        order: [[1, 'asc'], [11, 'dasc']],
        buttons: [
          {
            extend: 'excelHtml5',
            text: 'Report',
            exportOptions: {
                columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 10 ]
            }
          },
            // 'csvHtml5',
            // 'pdfHtml5'
        ]
      });

    // Event listener to the two range filtering inputs to redraw on input
    $('#min, #max').change(function () {
        table.draw();
    });/*
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
