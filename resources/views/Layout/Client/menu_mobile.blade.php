<div class="mobile-nav-wrapper" role="navigation">
    <div class="closemobileMenu"><i class="icon anm anm-times-l pull-right"></i> Close Menu</div>
    <ul id="MobileNav" class="mobile-nav">
        <li class="lvl1 parent megamenu">
            <a href="{{route('main.home.get')}}">Home</a>
        </li>
        @if(env('APP_DEBUG'))
            @foreach($menu as $key => $val)
                <li class="lvl1 parent megamenu">
                    <a href="#">{{$val->name}} <i class="anm anm-plus-l"></i></a>
                    <ul>
                        @foreach($val->categorys as $key1 => $val1)
                            <li><a href="{{route('main.product.category.get',['category' => $val1->url])}}" class="site-nav">{{$val1->name}}</a></li>
                        @endforeach
 
                    </ul>
                </li>
            @endforeach
        @endif
        <li class="lvl1 parent megamenu">
            <a href="{{route('main.about_us.get')}}">About Us </a>
        </li>
    </ul>
</div>
