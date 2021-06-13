    @if(!empty($latest_category))
        <!--Image Banners-->
        <div class="section imgBanners">
            <div class="imgBnrOuter">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="inner">
                                <a href="{{route('main.product.category.get',['category' => $latest_category->url])}}">
                                    <img data-src="{{asset('public/'.$latest_category->img)}}" src="{{asset('public/'.$latest_category->img)}}" alt="" title="" class="blur-up lazyload" />
                                </a>
                            </div>
                            <div class="custom-text text-center">
                                <h4 class="h3"><a href="#"> {{$latest_category->name}}</a></h4>
                                <div class="rte-setting">
                                    <p>
                                        {!! $latest_category->description!!}
                                    </p>
                                </div>
                                <br />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--End Image Banners-->
    @endif
    
    @if(!empty($near_latest_category))
        <!--Featured Column-->
        <div class="section featured-column  section-2">
            <div class="container">
                <div class="row">
                    @foreach($near_latest_category as $key => $val)
                        <div class="col-12 col-sm-6 col-md-6 col-lg-6 text-center sc2-item">
                            <p>
                                <a href="{{route('main.product.category.get',['category' => $val->url])}}">
                                    <img
                                        data-src="{{asset('public/'.$val->img)}}"
                                        src="{{asset('public/'.$val->img)}}"
                                        alt="New in !"
                                        class="blur-up lazyload main-img-seaction"
                                    />
                                </a>
                            </p>
                            <h3 class="h4"><a href="{{route('main.product.category.get',['category' => $val->url])}}">{{$val->name}}</a></h3>
                            <div class="rte-setting"><p><a href="{{route('main.product.category.get',['category' => $val->url])}}">Discover</p></a></div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
    <!--End Featured Column-->

    @if(!empty($third_lasted_item))
        <!--Featured Column-->
        <div class="section featured-column  section-3">
            <div class="container">
                <div class="row">
                    @foreach($third_lasted_item as $key => $val)
                        <div class="col-12 col-sm-4 col-md-4 col-lg-4 text-center sc2-item">
                            <p>
                                <a href="{{route('main.product.detail.get', ['category'=> $val->category->url, 'item' => $val->slug])}}">
                                    <img
                                        data-src="{{asset('public/'.$val->img_thumb)}}"
                                        src="{{asset('public/'.$val->img_thumb)}}"
                                        alt="New in !"
                                        class="blur-up lazyload main-img-seaction"
                                    />
                                </a>
                            </p>
                            <h3 class="h4"><a href="{{route('main.product.detail.get', ['category'=> $val->category->url, 'item' => $val->slug])}}">{{$val->name}}</a></h3>
                            <div class="rte-setting"><p><a href="{{route('main.product.detail.get', ['category'=> $val->category->url, 'item' => $val->slug])}}">{{$val->sub_name}}</p></a></div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
    <!--End Featured Column-->

    <!--Image Banners-->
    @if(!empty($latest_album))
        <div class="section imgBanners section-banner">
            <div class="imgBnrOuter">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="inner">
                            @if(count($latest_album->categoryImages) > 0)
                                <a href="{{route('main.product.category.get',['category' => $latest_album->url])}}">
                                    <img data-src="{{asset('public/'.$latest_album->categoryImages[0]->url)}}" src="{{asset('public/'.$latest_album->categoryImages[0]->url)}}" alt="" title="" class="blur-up lazyload" />
                                </a>
                            @endif
                            </div>
                            <div class="custom-text text-center">
                                <h4 class="h3"><a href="{{route('main.product.category.get',['category' => $latest_album->url])}}"> {{$latest_album->name}}</a></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <!--End Image Banners-->

    <!--Feature Content-->
    <div class="section feature-content">
        <div class="container">
            <div class="row">
                <div class="feature-row">
                    <div class="col-12 col-sm-12 col-md-6 feature-row__item feature-row__text feature-row__text--left text-left">
                        <div class="row-text">
                            <h2 class="h2">Mushie Studio</h2>
                            <div class="rte-setting featured-row__subtext"><p>Vietnam fashion brand cultivated on the principles of confidence, femininity, and individuality</p></div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 feature-row__item">
                        <img src="{{asset('public/assets/app/page/user/images/184280271_467031527861915_2274740756541267454_n.jpg')}}" alt="Fast Fashion Only available at BElle" title="Fast Fashion Only available at BElle" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Feature Content-->

