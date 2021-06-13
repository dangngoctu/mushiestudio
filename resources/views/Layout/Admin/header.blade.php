<div class="slim-header">
    <div class="container">
      <div class="slim-header-left">
        <h2 class="slim-logo"><a href="{{route('admin.index')}}"><img src="{{asset('public/img/web/logo.png')}}" width="135px" height="50px" alt="logo"><span></span></a></h2>
      </div><!-- slim-header-left -->
      <div class="slim-header-right">
        <div class="dropdown dropdown-c">
          <a href="#" class="logged-user" data-toggle="dropdown">
            <i class="fa fa-user-o"></i>
            @if(Auth::user())
              <span style="white-space: nowrap;max-width:150px; overflow: hidden;text-overflow: ellipsis; ">{{Auth::user()->name}}</span>
            @else
              Welcome
            @endif
            <i class="fa fa-angle-down"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-right">
            <nav class="nav">
              <a href="#" id="changePassWordModal" class="nav-link cursor-pointer"><i class="icon ion-compose"></i> Change Password</a>
              <a href="{{route('admin.logout')}}" class="nav-link cursor-pointer"><i class="icon ion-forward"></i> Logout</a>
            </nav>
          </div><!-- dropdown-menu -->
        </div><!-- dropdown -->
      </div><!-- header-right -->
    </div><!-- container -->
  </div><!-- slim-header -->