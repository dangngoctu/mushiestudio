<!--MainContent-->
<div id="MainContent" class="main-content template-product" role="main">
    <div id="ProductSection-product-template" class="product-template__container prstyle1 container">
        <!--product-single-->
        <div class="product-single">
            <div class="row">
                <!--Featured Item-->
                <div id="lightgallery" class="col-12 col-sm-12 col-md-6 col-lg-6 sc2-item">
                    @foreach($item->itemImages as $key => $val)
                        <a href="{{asset('public/'.$val->url_view)}}">
                            <img
                                data-src="{{asset('public/'.$val->url)}}"
                                src="{{asset('public/'.$val->url)}}"
                                alt="New in !"
                                class="blur-up lazyload main-img-seaction"
                            />
                        </a>
                    @endforeach
                </div>
                <!--End Featured Item-->

         
                <div class="col-lg-6 col-md-6 col-sm-12 col-12 info-item">
                    <div class="product-single__meta sticky">
                        <h1 class="product-single__title">{{$item->name.'('.$item->sub_name.')'}}</h1>
                        <p class="product-single__price product-single__price-product-template">
                            <span class="product-price__price product-price__price-product-template product-price__sale product-price__sale--single">
                                <span id="ProductPrice-product-template"><span class="money"><span class="price">{{($item->price_setting == 1 )? $item->price :''}}</span></span></span>
                            </span>
                        </p>
                        <form method="post" action="http://annimexweb.com/cart/add" id="product_form_10508262282" accept-charset="UTF-8" class="product-form product-form-product-template hidedropdown" enctype="multipart/form-data">
                            <div class="swatch clearfix swatch-0 option1" data-option-index="0">
                                <div class="product-form__item">
                                    <!-- <label class="header">Color: <span class="slColor" id="slColor">Red</span></label> -->
                                    @foreach($color as $key => $val)
                                        <div data-value="{{$val->name}}" class="swatch-element color available">
                                            <input class="swatchInput" id="{{'swatch-'.$val->id.'-'.$val->name}}" type="radio" name="{{'option-'.$val->id}}" value="{{$val->name}}"><label class="swatchLbl color small rounded" for="swatch-0-black" style="background-color:{{$val->color_code}}" title="{{$val->name}}"></label>
                                        </div>
                                    @endforeach
                                   
                                </div>
                            </div>
                            <div class="swatch clearfix swatch-1 option2" data-option-index="1">
                                <div class="product-form__item">
                                    <!-- <label class="header">Size: <span class="slSize" id="slSize">XS</span></label> -->
                                    @foreach($size as $key => $val)
                                        <div data-value="{{$val->name}}" class="swatch-element available">
                                            <input class="swatchInput" id="{{'swatch-'.$val->id.'-'.$val->name}}" type="radio" name="{{'option-'.$val->id}}" value="{{$val->name}}" />
                                            <label class="swatchLbl medium rectangle" for="{{'swatch-'.$val->id.'-'.$val->name}}" title="{{$val->name}}">{{$val->name}}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <!-- End Product Action -->
                        </form>
                        <!--Product Tabs-->
                        <div class="tabs-listing">
                            <ul class="product-tabs">
                                <li rel="tab1"><a class="tablink">Description</a></li>
                                <li rel="tab2"><a class="tablink">Detail</a></li>
                                <li rel="tab3"><a class="tablink">FABRICS & CARE</a></li>
                            </ul>
                            <div class="tab-container">
                                <div id="tab1" class="tab-content">
                                     {!!$item->description!!}   
                                </div>
                                <div id="tab2" class="tab-content">
                                    {!!$item->detail!!}   
                                </div>
                                <div id="tab3" class="tab-content">
                                    {!!$item->farbrics!!}   
                                </div>
                            </div>
                        </div>
                        <!--End Product Tabs-->
                    </div>
                </div>
            </div>
            <!--End-product-single-->
            <!--Related Product Slider-->
          
            <div class="related-product grid-products">
            @if(count($related_item) > 0)
                <header class="section-header">
                    <h2 class="section-header__title h2"><span>Related Products</span></h2>
                </header>
                <!--Featured Column-->
                <div class="productList">
                    <div class="grid-products grid--view-items">
                        <div class="row">
                            @foreach($related_item as $key => $val)
                                <div class="col-6 col-sm-6 col-md-4 col-lg-4 item">
                                    <!-- start product image -->
                                    <div class="product-image">
                                        <!-- start product image -->
                                        <ul class="content-slider">
                                            @if(count($val->itemImages) > 0)
                                                @foreach($val->itemImages as $key1 => $val1)
                                                    <li>
                                                        <a href="{{route('main.product.detail.get',['category' => $val->category->url, 'item' => $val->slug])}}">
                                                            <img data-src="{{asset('public/'.$val1->url)}}" src="{{asset('public/'.$val1->url)}}" alt="New in !" class="blur-up main-img-seaction ls-is-cached lazyloaded">
                                                        </a>
                                                    </li>
                                                @endforeach
                                            @else
                                                <li>
                                                    <a href="{{route('main.product.detail.get',['category' => $val->category->url ,'item' => $val->slug])}}">
                                                        <img data-src="{{asset('public/'.$val->img_thumb)}}" src="{{asset('public/'.$val->img_thumb)}}" alt="New in !" class="blur-up main-img-seaction ls-is-cached lazyloaded">
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>
                                        <!-- end product image -->
                                    </div>
                                    <!-- end product image -->

                                    <!--start product details -->
                                    <div class="product-details">
                                        <!-- product name -->
                                        <h3 class="h4"><a href="{{route('main.product.detail.get',['category' => $val->category->url ,'item' => $val->slug])}}">{{$val->name}}</a></h3>
                                        <div class="product-name">
                                            <a href="{{route('main.product.detail.get',['category' => $val->category->url ,'item' => $val->slug])}}">{{$val->sub_name}}</a>
                                        </div>
                                        <!-- End product name -->
                                        <!-- product price -->
                                        <div class="product-price">
                                            <span class="price"><span class="price">{{($item->price_setting == 1)?($item->price):''}}</span></span>
                                        </div>
                                        <!-- End product price -->
                                    </div>
                                    <!-- End product details -->
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <!--End Featured Column-->
                @endif
            </div>
            <!--End Related Product Slider-->
        </div>
        <!--#ProductSection-product-template-->
    </div>
    <!--MainContent-->
</div>
<!--End Body Content-->
