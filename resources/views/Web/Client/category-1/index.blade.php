<div class="section product-single product-template__container category_1">
    <div class="container">
        <div class="product-single-wrap">
            <div class="row display-table">
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 display-table-cell">
                    <img src="{{asset($category->img)}}" alt="" class="product-featured-img">
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 display-table-cell">
                    <div class="product-single__meta">
                        <h2 class="grid_item-title h2"><label>{{$category->name}}</label></h2>
                        <div class="product-single__description rte">{!! $category->description !!}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--Featured Column-->
<div class="section featured-column section-2">
    <div class="container">
        <div class="filters-toolbar-wrapper">
            <div class="row">
                <div class="col-3 col-md-3 col-lg-3 filters-toolbar__item collection-view-as d-flex justify-content-start align-items-center">
                        <div class="view-option">View <a href="#" class="view-small">  small  </a>/<a href="#" class="view-large">  large</a></div>
                </div>
                <div class="col-6 col-md-6 col-lg-6 text-center filters-toolbar__item filters-toolbar__item--count d-flex justify-content-center align-items-center">
                    <div class="filters-toolbar__item">
                        <label for="SortBy" class="hidden">Sort</label>
                        <select style="width:100px" name="sort_material" id="sort_material" class="filters-toolbar__input filters-toolbar__input--sort">
                            <option value="title-ascending" selected="selected">Material</option>
                            @foreach($material as $key => $val)
                                <option value="{{$val->id}}">{{$val->name}}</option>
                            @endforeach
                        </select>
                        <input class="collection-header__default-sort" type="hidden" value="manual">
                    </div>

                    <div class="filters-toolbar__item">
                        <label for="SortBy" class="hidden">Sort</label>
                        <select style="width:100px" name="sort_size" id="sort_size" class="filters-toolbar__input filters-toolbar__input--sort">
                            <option value="title-ascending" selected="selected">Size</option>
                            @foreach($size as $key => $val)
                                <option value="{{$val->id}}">{{$val->name}}</option>
                            @endforeach
                        </select>
                        <input class="collection-header__default-sort" type="hidden" value="manual">
                    </div>
                </div>
                <div class="col-3 col-md-3 col-lg-3 text-right">
                <div class="filters-toolbar__item">
                        <label for="SortBy" class="hidden">Sort</label>
                        <select style="width:100px" name="sort" id="sort" class="filters-toolbar__input filters-toolbar__input--sort">
                            <option value="title-ascending" selected="selected">Sort by</option>
                            <option value="1">SORT BY RECENTLY ADDED</option>
                            <option value="2">SORT BY PRICE(ASCENDING)</option>
                            <option value="3">SORT BY PRICE(DESCENDING)</option>
                        </select>
                        <input class="collection-header__default-sort" type="hidden" value="manual">
                    </div>
                </div>
            </div>
            <!--Featured Item-->
            @if(count($category->items) > 0)
                <div class="row product-list">
                    @foreach($category->items as $key => $val)
                        <!--Featured Item-->
                        <div class="col-6 col-sm-6 col-md-4 col-lg-4 category-item col-large category-item">
                            <p>
                                <ul class="content-slider">
                                    @if(count($val->itemImages) > 0)
                                        @foreach($val->itemImages as $key1 => $val1)
                                            <li>
                                                <a href="{{route('main.product.detail.get')}}">
                                                    <img data-src="{{asset($val1->url)}}" src="{{asset($val1->url)}}" alt="{{$val->name}}" class="blur-up main-img-seaction ls-is-cached lazyloaded">
                                                </a>
                                            </li>
                                        @endforeach
                                    @else 
                                        <li>
                                            <a href="{{route('main.product.detail.get')}}">
                                                <img data-src="{{asset($val->img_thumb)}}" src="{{asset($val->img_thumb)}}" alt="{{$val->name}}" class="blur-up main-img-seaction ls-is-cached lazyloaded">
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </p>
                            <h3 class="h4"><a href="{{route('main.product.detail.get')}}">{{$val->name}}</a></h3>
                            <div class="rte-setting"><p><a href="{{route('main.product.detail.get')}}">{{$val->sub_name}}</a></p></div>
                            <div class="product-price">
                                <span class="price">{{$val->price}}</span>
                            </div>
                        </div>
                        <!--End Featured Item-->
                    
                    @endforeach
                </div>
            @endif
            
        </div>
    </div>
</div>

<div class="section featured-column  section-3">
    <div class="container">
        
    </div>
</div>

