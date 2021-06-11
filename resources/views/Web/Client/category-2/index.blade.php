<div class="section video-section">
    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                <div class="video-wrapper">
                <iframe width="560" height="315" src="{{$category->video.'?autoplay=1'}}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</div>
 <!--Featured Column-->
 <div class="section featured-column category_2">
    <div class="container">
        <div class="row">
            <!--Featured Item-->
            @if(count($category->categoryImages) > 0)
                @foreach($category->categoryImages as $key => $val)
                    <div class="col-6 col-sm-6 col-md-6 col-lg-4 category_2_item">
                        <p>
                            <a href="{{route('main.product.detail.get',['category' => $val->category_id, 'item' => $val->id])}}">
                                <img
                                    data-src="{{asset($val->url)}}"
                                    src="{{asset($val->url)}}"
                                    alt="{{$category->name}}"
                                    class="blur-up lazyload main-img-seaction"
                                />
                            </a>
                        </p>
                        <h3 class="h4 title_sm"><a href="{{route('main.product.detail.get',['category' => 1, 'item' => 2])}}">{{$category->name}}</a></h3>
                    </div>
                @endforeach
            @endif
            <!--End Featured Item-->
            
        </div>
    </div>
</div>
<!--End Featured Column-->
