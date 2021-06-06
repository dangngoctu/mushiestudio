<div class="row" style="position:unset">
    <nav class="grid__item" id="AccessibleNav" style="position:unset">
        <ul id="siteNav" class="site-nav medium center hidearrow" style="position:unset">
            <li class="lvl1 parent megamenu">
                <a href="{{route('main.home.get')}}">Home <i class="anm anm-angle-down-l"></i></a>
            </li>
            @foreach($menu as $key => $val)
                <li class="lvl1 parent megamenu" >
                    <a href="#">{{$val->name}}<i class="anm anm-angle-down-l"></i></a>
                    
                        <div class="megamenu style4">
                            <ul class="grid grid--uniform mmWrapper">
                                <li class="grid__item lvl-1 col-md-3 col-lg-3">
                                    <ul class="subLinks">
                                    @if(count($val->categorys) > 0)
                                        @foreach($val->categorys as $key1 => $val1)
                                            <li class="lvl-2"><a href="{{route('main.product.category.get')}}" class="site-nav lvl-2">{{$val1->name}}</a></li>
                                            
                                        @endforeach
                                    @endif
                                    </ul>
                                </li>
                                @if(!empty($val->url_img))
                                    <li class="grid__item lvl-1 col-md-6 col-lg-6">
                                        <a href="#"><img src="{{asset($val->url_img)}}" alt="" title="" /></a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    
                </li>
            @endforeach
            <li class="lvl1 parent dropdown">
                <a href="#">About Us <i class="anm anm-angle-down-l"></i></a>
            </li>
        </ul>
    </nav>
</div>