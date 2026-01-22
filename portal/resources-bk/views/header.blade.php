<div class="header-bg">
    <!-- Navigation Bar-->
    <header id="topnav">
        <div class="topbar-main">
            <div class="container-fluid">

                <!-- Logo-->
                <div>
                    <a href="{{url('home')}}" class="logo">
                        <span class="logo-light">
                            <img src="{{asset('public/images/logo.png')}}" alt="" width="80">
                        </span>
                    </a>
                </div>
                <!-- End Logo-->

                <div class="menu-extras topbar-custom navbar p-0">
                    <!-- <ul class="list-inline d-none d-lg-block mb-0">
                        <li class="hide-phone app-search float-left">
                            <form role="search" class="app-search">
                                <div class="form-group mb-0">
                                    <input type="text" class="form-control" placeholder="Search..">
                                    <button type="submit"><i class="fa fa-search"></i></button>
                                </div>
                            </form>
                        </li>
                    </ul> -->

                    <ul class="navbar-right ml-auto list-inline float-right mb-0">
                        <!-- language-->


                        <!-- full screen -->
                        <!-- <li class="dropdown notification-list list-inline-item d-none d-md-inline-block">
                            <a class="nav-link waves-effect" href="#" id="btn-fullscreen">
                                <i class="mdi mdi-arrow-expand-all noti-icon"></i>
                            </a>
                        </li> -->

                        <!-- notification -->

                        <li class="dropdown notification-list list-inline-item">
                            <div class="dropdown notification-list nav-pro-img">
                                <a class="dropdown-toggle nav-link arrow-none nav-user" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                    Hi&nbsp;&nbsp; {{Auth::user()->name}}
                                </a>
                                <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                                    <!-- item-->
                                    <a class="dropdown-item " href="{{ route('myprofile') }}"><i class="mdi mdi-power text-danger"></i> My Profile</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item text-danger" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                  document.getElementById('logout-form').submit();"><i class="mdi mdi-power text-danger"></i> Logout</a>
                                    
                                </div>
                            </div>
                        </li>
                        <form id="logout-form" action="{{url('logout')}}" method="POST"  >
                                        @csrf
                                    </form> 
                    </ul>

                </div>
                <!-- end menu-extras -->

                <div class="clearfix"></div>

            </div>
            <!-- end container -->
        </div>
        <!-- end topbar-main -->
        <div class="clearfix"></div>
        <!-- MENU Start -->
        
<style>
.bg-light{
    background-color: #fff!important;
}
@media (max-width: 420px) {
    .page-title-box{
    margin-top: 39px;
}
} .table-list{
       height:auto;width:1200px !important;display:block;overflow-x:scroll
     }

     @media (max-width: 991px){
body {
    overflow-x: auto !important;
}
}
</style>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#"></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      @if(isset(Auth::user()->name) && Auth::user()->role!='User')
      <li class="nav-item active">
        <a href="{{url('home')}}" class="nav-link"><i class="fa fa-home"></i> Home </a> 
      </li>
      <li class="nav-item active">
        <a href="{{url('list')}}" class="nav-link"><i class="icon-pencil-ruler"></i></i> Links </a> 
      </li>  
      <li class="nav-item active">  
        <a href="{{url('trailLinks')}}" class="nav-link"><i class="icon-pencil-ruler"></i></i> Trail Links </a> 
      </li>  
      <li class="nav-item active">
        <a href="{{url('resend')}}" class="nav-link"><i class="icon-pencil-ruler"></i></i> Resend </a> 
      </li>  
      @endif
      <li class="nav-item active">
        <a href="{{url('retrigger')}}" class="nav-link"><i class="icon-pencil-ruler"></i></i> Reactivate </a> 
      </li>  

      
   
    </ul>
  </div>
</nav>

 
    </header>
    <!-- End Navigation Bar-->

</div>

<div class="modal fade change_password" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Change Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
              <form class="m-b-30" method="post" action="{{url('password')}}">
                @csrf
                <div class="form-group row">
                  <label for="email" class="col-md-5 text-right">Old Password</label>
                  <div class="col-md-5">
                    <input type="password" class="form-control" required name="old_pass">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="email" class="col-md-5 text-right">New Password</label>
                  <div class="col-md-5">
                    <input type="password" class="form-control" required name="new_pass">
                  </div>
                </div>
                <div class="form-group row col-md-6" style="float:right">
                  <button type="submit" class="btn btn-primary m-r-10">Save</button>
                  <a href="{{url('add_user')}}" class="btn btn-default">Clear</a>
                </div>
              </form>
            </div>
        </div>
    </div>
</div>
