<div class="row" style="position:unset">
    <nav class="grid__item" id="AccessibleNav" style="position:unset">
        <ul id="siteNav" class="site-nav medium center hidearrow" style="position:unset">
            <li class="lvl1 parent megamenu">
                <a href="{{route('main.home.get')}}">Home <i class="anm anm-angle-down-l"></i></a>
            </li>
            @if(env('APP_DEBUG'))
            @foreach($menu as $key => $val)
                <li class="lvl1 parent megamenu" >
                    <a href="#">{{$val->name}}<i class="anm anm-angle-down-l"></i></a>
                    
                        <div class="megamenu style4">
                            <ul class="grid grid--uniform mmWrapper">
                                @if(count($val->categorys) > 0)
                                    @if(floor(count($val->categorys)/4) < 2)
                                        <li class="grid__item lvl-1 col-md-8 col-lg-8">
                                            <ul class="subLinks">
                                                @foreach($val->categorys as $key1 => $val1)
                                                    <li class="lvl-2"><a href="{{route('main.product.category.get',['category' => $val1->url])}}" class="site-nav lvl-2"><label>{{$val1->name}}</label></a></li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    @else
                                        @php
                                            $array_menu_left = [];
                                            $array_menu_right = [];
                                            foreach($val->categorys as $key1 => $val1){
                                                if($key%2 == 0){
                                                    array_push($array_menu_left, $val);
                                                } else {
                                                    array_push($array_menu_right, $val);
                                                }
                                            }
                                        @endphp
                                        <li class="grid__item lvl-1 col-md-8 col-lg-8">
                                            <ul class="subLinks">
                                                @foreach($array_menu_left as $key1 => $val1)
                                                    <li class="lvl-2"><a href="{{route('main.product.category.get',['category' => $val1->url])}}" class="site-nav lvl-2">{{$val1->name}}</a></li>
                                                @endforeach
                                            </ul>
                                        </li>
                                        <li class="grid__item lvl-1 col-md-8 col-lg-8">
                                            <ul class="subLinks">
                                                @foreach($array_menu_right as $key1 => $val1)
                                                    <li class="lvl-2"><a href="{{route('main.product.category.get',['category' => $val1->url])}}" class="site-nav lvl-2">{{$val1->name}}</a></li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    @endif
                                @endif
                                @if(!empty($val->url_img))
                                    <li class="grid__item lvl-1 col-md-4 col-lg-4">
                                        <a href="#"><img src="{{asset($val->url_img)}}" alt="" title="" /></a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    
                </li>
            @endforeach
            @endif
            <li class="lvl1 parent dropdown">
                <a href="{{route('main.about_us.get')}}">About Us <i class="anm anm-angle-down-l"></i></a>
            </li>
        </ul>
    </nav>
</div>