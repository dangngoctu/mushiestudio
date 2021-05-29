@php
    use Illuminate\Support\Facades\URL;
    $route = \Request::route()->getName();
    $current_url = URL::current();
    $base_url = URL::to('/');
@endphp
<div class="slim-navbar">
    <div class="container">
      <ul class="nav">
        <li class="nav-item with-sub {{ (strpos($route, 'admin.menu') !== false) ? 'active' : '' }}">
          <a class="nav-link cursor-pointer" href="#">
            <i class="icon ion-clipboard"></i>
            <span>Menu</span>
          </a>
          <div class="sub-item">
            <ul>
              <li><a href="{{route('admin.menu')}}">Menu</a></li>
            </ul>
          </div><!-- dropdown-menu -->
        </li>
        <li class="nav-item with-sub {{ (strpos($route, 'admin.size') !== false || strcmp($route, 'admin.color') == 0 || strcmp($route, 'admin.material') == 0) ? 'active' : '' }}">
          <a class="nav-link cursor-pointer" href="#">
            <i class="icon ion-clipboard"></i>
            <span>Addon</span>
          </a>
          <div class="sub-item">
            <ul>
              <li><a href="{{route('admin.size')}}">Size</a></li>
              <li><a href="{{route('admin.color')}}">Color</a></li>
              <li><a href="{{route('admin.material')}}">Material</a></li>
            </ul>
          </div><!-- dropdown-menu -->
        </li>
        <li class="nav-item {{ (strpos($route, 'admin.user') !== false)  ? 'active' : '' }}">
          <a class="nav-link cursor-pointer" href="{{route('admin.user')}}">
              <i class="icon ion-ios-gear-outline"> </i>
              <span>User</span>
          </a>
        </li>
        <li class="nav-item {{ (strpos($route, 'admin.setting') !== false || strcmp($route, 'admin.index') == 0 || strcmp($route, 'admin.login.view') == 0) ? 'active' : '' }}">
          <a class="nav-link cursor-pointer" href="{{route('admin.setting')}}">
              <i class="icon ion-ios-gear-outline"> </i>
              <span>Setting</span>
          </a>
        </li>
      </ul>
    </div><!-- container -->
  </div><!-- slim-navbar -->