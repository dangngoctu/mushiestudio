<!--Search Form Drawer-->
<div class="search">
    <div class="search__form">
        <form class="search-bar__form" action="#">
            <button class="go-btn search__button" type="submit"><i class="icon anm anm-search-l"></i></button>
            <input class="search__input" type="search" name="q" value="" placeholder="Search entire store..." aria-label="Search" autocomplete="off" />
        </form>
        <button type="button" class="search-trigger close-btn"><i class="anm anm-times-l"></i></button>
    </div>
</div>
<!--End Search Form Drawer-->
<!--Header-->
<div class="header-wrap animated d-flex">
    <div class="container" style="position:relative">        
        <div class="row align-items-center" style="position:unset">
            <!--Desktop Logo-->
            <div class="logo col-md-2 col-lg-2 d-none d-lg-block">
                <a href="{{route('main.home.get')}}">
                    <img src="{{asset('assets/images/logo_mushie.png')}}" alt="Belle Multipurpose Html Template" title="Belle Multipurpose Html Template">
                </a>
            </div>
            <!--End Desktop Logo-->
            <div class="col-2 col-sm-3 col-md-3 col-lg-8" style="position:unset">
                <div class="d-block d-lg-none">
                    <button type="button" class="btn--link site-header__menu js-mobile-nav-toggle mobile-nav--open">
                        <i class="icon anm anm-times-l"></i>
                        <i class="anm anm-bars-r"></i>
                    </button>
                </div>
                <!--Desktop Menu-->
                @include('Layout.Client.menu')
                <!--End Desktop Menu-->
            </div>
            <!--Mobile Logo-->
            <div class="col-6 col-sm-6 col-md-6 col-lg-2 d-block d-lg-none mobile-logo">
                <div class="logo">
                    <a href="{{route('main.home.get')}}">
                        <img src="{{asset('assets/images/logo_mushie.png')}}" alt="Belle Multipurpose Html Template" title="Belle Multipurpose Html Template">
                    </a>
                </div>
            </div>
            <!--Mobile Logo-->
            <div class="col-4 col-sm-3 col-md-3 col-lg-2">
                <div class="site-header__search">
                    <button type="button" class="search-trigger"><i class="icon anm anm-search-l"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>

<!--End Header-->
<!--Mobile Menu-->
@include('Layout.Client.menu_mobile')
<!--End Mobile Menu-->
