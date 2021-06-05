<div class="row" style="position:unset">
    <nav class="grid__item" id="AccessibleNav" style="position:unset">
        <ul id="siteNav" class="site-nav medium center hidearrow" style="position:unset">
            <li class="lvl1 parent megamenu">
                <a href="{{route('main.home.get')}}">Home <i class="anm anm-angle-down-l"></i></a>
            </li>
            <li class="lvl1 parent megamenu" >
                <a href="#">Collection <i class="anm anm-angle-down-l"></i></a>
                <div class="megamenu style4">
                    <ul class="grid grid--uniform mmWrapper">
                        <li class="grid__item lvl-1 col-md-3 col-lg-3">
                            <a href="#" class="site-nav lvl-1">Category</a>
                            <ul class="subLinks">
                                <li class="lvl-2"><a href="{{route('main.product.category.get')}}" class="site-nav lvl-2">Category Layout 1</a></li>
                                <li class="lvl-2"><a href="{{route('main.product.category2.get')}}" class="site-nav lvl-2">Category Layout 2</a></li>
                            </ul>
                        </li>
                        <li class="grid__item lvl-1 col-md-6 col-lg-6">
                            <a href="#"><img src="{{asset('assets/app/page/user/images/megamenu-bg1.jpg')}}" alt="" title="" /></a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="lvl1 parent megamenu">
                <a href="#">Product <i class="anm anm-angle-down-l"></i></a>
                <div class="megamenu style2">
                    <ul class="grid mmWrapper">
                        <li class="grid__item one-whole">
                            <ul class="grid">
                                <li class="grid__item lvl-1 col-md-3 col-lg-3">
                                    <a href="#" class="site-nav lvl-1">Product Page</a>
                                    <ul class="subLinks">
                                        <li class="lvl-2"><a href="{{route('main.product.detail.get')}}" class="site-nav lvl-2">Product Layout</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="lvl1 parent dropdown">
                <a href="#">About Us <i class="anm anm-angle-down-l"></i></a>
            </li>
        </ul>
    </nav>
</div>