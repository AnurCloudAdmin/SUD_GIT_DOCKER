<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <title>{{ config('app.name') }}</title>
  <meta content="Anoor Cloud" name="author" />
  <link rel="shortcut icon" href="{{ asset('images/icon.png')}}">

  <!-- Table css -->
  {{-- <link href="../plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css" rel="stylesheet" type="text/css" media="screen"> --}}

  <link href="{{ asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css">
  <link href="{{ asset('assets/css/metismenu.min.css')}}" rel="stylesheet" type="text/css">
  <link href="{{ asset('assets/css/icons.css')}}" rel="stylesheet" type="text/css">
  <link href="{{ asset('assets/css/style.css')}}" rel="stylesheet" type="text/css">
  <link href="{{ asset('assets/css/datatables.min.css')}}" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.min.css')}}" />
  <link rel="stylesheet" href="{{ asset('validatation/css/validationEngine.jquery.css')}}" />

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
      background: url({{asset('images/excel.svg')}});
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
            <h4 class="page-title">Reports</h4>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-right">
              <li class="breadcrumb-item"><a href="{{url('home')}}">Home</a></li>
              <li class="breadcrumb-item active">Reports</li>
            </ol>
          </div>
        </div>
        <!-- end row -->
      </div>
      <style>
      #search td{border-top:medium none;}
      </style>
      <div class="row">
        <div class="">
          <div class="card m-b-30">
            <div class="card-body">  
            @if($show=='Yes')
            <form method="post" action="{{url('generateReport')}}" id="exportreport" name="exportreport">
            @csrf
            <input type="hidden" name="date"  value="{{$date}}"/>
            <input type="hidden" name="DIET_TYPE"  value="{{$DIET_TYPE}}"/>
            <input type="hidden" name="FOOD_CATEGORY"  value="{{$FOOD_CATEGORY}}"/>
            <input type="hidden" name="FOOD_INTERVAL"  value="{{$FOOD_INTERVAL}}"/>
            <button type="submit" class="btn btn-success" style="float:right" > <i class="fa fa-file-excel"></i> Excel Download</button>
            </form>
            @endif 
            <form method="get" action="" id="dietfilterreportform" name="dietfilterreportform">
            <div class="row">
              <div class="col-sm-2">
              <input type="text" id="date" placeholder="Choose Date" name="date" class="form-control form-control-sm" data-validation-engine="validate[required]" value="{{$date}}" autocomplete="off" data-errormessage-value-missing="Please Choose Date" /> 
              </div>
              <div class="col-sm-3">
              <select name="DIET_TYPE" class="form-control form-control-sm"  data-validation-engine="validate[required]"  data-errormessage-value-missing="Please Choose Diet Type">
                    <option value="">Diet Type</option>
                    @foreach ($dietType as $key=>$dietTypeSingle)
                      <option value="{{$dietTypeSingle->TYPE_VALUE}}"  @if ($DIET_TYPE ==$dietTypeSingle->TYPE_VALUE) selected @endif>{{$dietTypeSingle->TYPE_VALUE}}</option>
                      @endforeach
                    </select>
              </div>
              <div class="col-sm-3">
              <select name="FOOD_CATEGORY" class="form-control form-control-sm" data-validation-engine="validate[required]"  data-errormessage-value-missing="Please Choose Food Category">
                      <option value="">Food Category</option>
                      @foreach ($foodCategory as $key=>$foodCategorySingle)
                      <option value="{{$foodCategorySingle->TYPE_VALUE}}" @if ($FOOD_CATEGORY ==$foodCategorySingle->TYPE_VALUE) selected @endif>{{$foodCategorySingle->TYPE_VALUE}}</option>
                      @endforeach
                    </select>
              </div>
              <div class="col-sm-2">
              <select name="FOOD_INTERVAL" class="form-control form-control-sm" data-validation-engine="validate[required]"  data-errormessage-value-missing="Please Choose Food Interval">
                    <option value="">Food Interval</option>
                    @foreach ($foodInterval as $key=>$foodIntervalSingle)
                      <option value="{{$foodIntervalSingle}}"  @if ($FOOD_INTERVAL ==$foodIntervalSingle) selected @endif>{{$foodIntervalSingle}}</option>
                      @endforeach
                    </select>
              </div>
              <div class="col-sm-2">
              <button   type="submit" class="btn btn-success"> Submit</button>
              </div>
            </div> 
              </form> 
            <br/>
            <br/>
            
            @if($show=='Yes')
              <div class="table-rep-plugin"> 
                          <div style="text-align:center"><h5>{{$foodCategoryTitle}}</h5></div>
                      </div>
                      <div>
                          <div  style="text-align:center"><h5>{{$daywithHeader}}</h5>	</div>
                      </div>
                <div class="table-responsive b-0" data-pattern="priority-columns">
                  <table class="table table-list table-bordered" width="100%">
                    <thead> 
                      <tr>
                        <th>WARDS</th>
                         @foreach ($floor as $key=>$floorSingle)
                         <th>{{$floorSingle}}</th>
                        @endforeach 
                        <th>TOTAL</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($reports as $key=>$reportsSingle)
                      <tr>
                        <td>{{$key}}</td>
                        @php
                          $CountSet = 0
                          @endphp
                        @foreach ($floor as $key=>$floorSingle)
                         <td> 
                         @if(isset($reportsSingle[$floorSingle]))
                         @php
                          $CountSet += $reportsSingle[$floorSingle]
                          @endphp
                         {{$reportsSingle[$floorSingle]}}
                         @else
                          -
                         @endif
                         </td>
                        @endforeach 
                        <td>{{$CountSet}}</td>
                        </tr> 
                      @endforeach 
                    </tbody>
                  </table>

                </div>
                @else
                <p>No Record Found</p>
                @endif

              </div>

            </div>
          </div>
        </div> <!-- end col -->
      </div> <!-- end row -->

    </div>
    <!-- end container-fluid -->
  </div>
  <style>
  
     </style>
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

  
  <script src="{{ asset('assets/js/jquery.min.js')}}"></script>
  <script src="{{ asset('assets/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{ asset('assets/js/jquery.slimscroll.js')}}"></script>
  <script src="{{ asset('assets/js/waves.min.js')}}"></script>

  <script src="{{ asset('assets/js/datatables.min.js')}}"></script>
  <script src="{{ asset('assets/js/dataTables.buttons.min.js')}}"></script>
  <script src="{{ asset('assets/js/jszip.min.js')}}"></script>
  <script src="{{ asset('assets/js/pdfmake.min.js')}}"></script>
  <script src="{{ asset('assets/js/vfs_fonts.js')}}"></script>
  <script src="{{ asset('assets/js/buttons.html5.min.js')}}"></script>
  <script src="{{ asset('assets/js/jquery-ui.js')}}"></script>

  <script src="{{ asset('validatation/js/languages/jquery.validationEngine-en.js')}}"></script>
  <script src="{{ asset('validatation/js/jquery.validationEngine.js')}}"></script>
  <!-- Responsive-table-->
  {{-- <script src="../plugins/RWD-Table-Patterns/dist/js/rwd-table.min.js')}}"></script> --}}

  <!-- App js -->
  <script src="{{ asset('assets/js/app.js')}}"></script>
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
      //$("#dietfilterreportform").validationEngine();
      $("#dietfilterreportform").validationEngine('attach', {promptPosition : "topLeft"});
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

    $('#date').datepicker({
      changeMonth: true, changeYear: true,dateFormat:'yy-mm-dd'
    }); 
    });


  </script>
<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
<script>
  $(function() {
    $('#toggle-two').bootstrapToggle({
      on: 'Enabled',
      off: 'Disabled'
    });
  })
</script>
</body>

</html>
