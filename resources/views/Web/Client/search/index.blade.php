@if(count($categorys) > 0)
<!--Featured Column-->
 <div class="section featured-column category_2">
    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                <div class="custom-text">
                    <h4 class="h3" style="padding: 5px 0 25px 0;"><label>CATEGORYS</label></h4>
                </div>
            </div>
         
            @foreach($categorys as $key => $val)
                <div class="col-6 col-sm-6 col-md-6 col-lg-4 category_2_item">
                        <div class="light_item"  id="lightItemGallery{{$key}}">
                            <a href="{{route('main.product.category.get', ['category' => $val->url ])}}">
                                <img
                                    data-src="{{asset('public/'.$val->img)}}"
                                    src="{{asset('public/'.$val->img)}}"
                                    alt="{{$val->name}}"
                                    class="blur-up lazyload main-img-seaction"
                                />
                            </a>
                        </div>    
                    <h3 class="h4 title_sm" style="margin-top:10px"><a href="#">{{$val->name}}</a></h3>
                </div>
            @endforeach
            <!--End Featured Item-->
            
        </div>
    </div>
</div>
<!--End Featured Column-->
@endif

@if(count($items) > 0)
<!--Featured Column-->
<div class="section featured-column section-2">
    <div class="container">
        <div class="filters-toolbar-wrapper">
            <!--Featured Item-->
            <div class="row product-list">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="custom-text">
                        <h4 class="h3" style="padding: 5px 0 25px 0;"><label>PRODUCTS</label></h4>
                    </div>
                </div>
                @foreach($items as $key => $val)
                    <!--Featured Item-->
                    <div class="col-6 col-sm-6 col-md-4 col-lg-4 category-item col-large category-item">
                        <p>
                            <ul class="content-slider">
                                @if(count($val->itemImages) > 0)
                                    @foreach($val->itemImages as $key1 => $val1)
                                        <li>
                                            <a href="{{route('main.product.detail.get', ['category' => $val->category->url, 'item' => $val->slug])}}">
                                                <img data-src="{{asset('public/'.$val1->url)}}" src="{{asset('public/'.$val1->url)}}" alt="{{$val->name}}" class="blur-up main-img-seaction ls-is-cached lazyloaded">
                                            </a>
                                        </li>
                                    @endforeach
                                @else 
                                    <li>
                                        <a href="{{route('main.product.detail.get', ['category' => $val->category->url, 'item' => $val->slug])}}">
                                            <img data-src="{{asset('public/'.$val->img_thumb)}}" src="{{asset('public/'.$val->img_thumb)}}" alt="{{$val->name}}" class="blur-up main-img-seaction ls-is-cached lazyloaded">
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </p>
                        <h3 class="h4"><a href="{{route('main.product.detail.get', ['category' => $val->category->url, 'item' => $val->slug])}}">{{$val->name}}</a></h3>
                        <div class="rte-setting"><p><a href="{{route('main.product.detail.get', ['category' => $val->category->url, 'item' => $val->slug])}}">{{$val->sub_name}}</a></p></div>
                        <div class="product-price">
                            <span class="price">{{($val->price_setting == 1)?($val->price):''}}</span>
                        </div>
                    </div>
                    <!--End Featured Item-->
                
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif
@if(count($categorys) == 0 && count($items) == 0)
<div class="container">
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12">	
            <div class="empty-page-content text-center">
                <h1>No products were found</h1>
                <p><a href="/" class="btn btn--has-icon-after">Continue shopping <i class="fa fa-caret-right" aria-hidden="true"></i></a></p>
                </div>
        </div>
    </div>
</div>
@endif