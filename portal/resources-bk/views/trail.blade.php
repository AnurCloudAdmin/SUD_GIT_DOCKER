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
</head>

<body>

  @extends('header')
  <div class="wrapper">
    <div class="container-fluid">
      <!-- Page-Title -->
      <div class="page-title-box">
        <div class="row align-items-center">
          <div class="col-sm-6">  
            <h4 class="page-title">Trail Links</h4> 
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-right">
              <li class="breadcrumb-item"><a href="{{url('home')}}">Home</a></li>
              <li class="breadcrumb-item active">Trail Links</li>
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
                <span id="date-label-from" class="date-label">Completed On :</span><input class="date_range_filter date" type="text" id="min"  name="from" value="{{$lists['from']}}" placeholder="From"  /></span>
                <span id="date-label-to" class="date-label"> <input class="date_range_filter date" type="text" name="to" id="max"  placeholder="To"  value="{{$lists['to']}}" /></span>
                <span id="date-label-to" class="date-label"> <input class="date_range_filter date" type="text" name="filter" id="filter"  placeholder="Proposal Number"  value="{{$lists['filter']}}" /></span>
                <button type="submit" class="btn btn-success" id="submit" >Search </button>
              </p>
            </form>

            <form method="post" id="reportsdownload" name="reportsdownload" action="{{env('APP_URL')}}traillinksreportsdownload" style="display: inline-block;" > 
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
                        <th>App No</th>
                        <th>Link</th>
                        <th>Name</th> 
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Product Name</th>
                        <th>Link Sent On</th>
                        <th>Is Open</th>
                        <th>Is Open at</th>
                        <th>Link Status</th>
                        <th>Complete Status</th>
                        <th>Completed On</th>
                        <th>Personal Disagree</th>
                        <th>Policy Disagree</th>
                        <th>PDF</th>
                        <th>OS</th> 
                        <th>Version</th> 
                      </tr> 
                    </thead>
                    <tbody>
                      @foreach($lists['data'] as $key => $list)
                      @php 
                      $params = json_decode($list->params,true);
                      @endphp
                      <tr>
                        <th>{{$key+1}}</th>
                        <th>{{$list->proposal_no}}</th> 
                        <td>{{$list->short_link}}</td>
                        <td>{{$params['personal_name']}}</td> 
                        <td>{{$params['personal_email']}}</td>
                        <td>{{$params['personal_mobile']}}</td>
                        <td>{{$params['policy_prod_name']}}</td> 
                        <td>{{$list->created_at}}</td>
                        <th>{{($list->is_open==1)?'Open':'Not Open'}}</th>
                        <th>{{$list->is_open_at}}</th>
                        <th>{{($list->status==1 && $list->completed_status == 1)?'Expired':'Active'}}</th>
                        <th>{{($list->complete_status==1)?'Completed':'Incomplete'}}</th>
                        <th>{{$list->completed_on}}</th>
                        <th>{{$list->personal_disagree}}
                        </th>
                        <th>{{$list->policy_disagree}}
                        </th>
                        
                        <td>
                          @if($list->complete_status==1)
						
								<a href="{{url('/api/DownloadPdfArchive')}}/{{$list->proposal_no}}/{{$list->version}}" download class="btn btn-primary">PDF</a>
					
                          @else
                          PDF Not Generated
                          @endif
                        </td>
                        <td>
                          <?php
                          if(isset($list->device) && ($list->device !="") && (gettype($list->device) == "array")){
                            $device=$list->device['os'];
                            echo $device;
                          }else{
                            echo '';
                          }
                          ?></td> 
                          <th>{{$list->version}}</th>  
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
