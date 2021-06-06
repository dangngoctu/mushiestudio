<!--Footer-->
<footer id="footer" class="footer-2">
    <div class="site-footer">
        <div class="container">
            <!--Footer Links-->
            <div class="footer-top">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-4 col-lg-4 footer-links">
                        <h4 class="h4">Quick Shop</h4>
                        <ul>
                            @foreach($menu as $key => $val)
                                <li><a href="#">{{$val->name}}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="col-12 col-sm-12 col-md-4 col-lg-4 footer-links">
                        <h4 class="h4">Informations</h4>
                        <ul>
                            <li><a href="#">About us</a></li>
                        </ul>
                    </div>
                    <div class="col-12 col-sm-12 col-md-4 col-lg-4 contact-box">
                        <h4 class="h4">Contact Us</h4>
                        <ul class="addressFooter">
                            <li>
                                <i class="icon anm anm-map-marker-al"></i>
                                <p>
                                    {{$address->value}}
                                </p>
                            </li>
                            <li class="phone">
                                <i class="icon anm anm-phone-s"></i>
                                <p>{{$phone->value}}</p>
                            </li>
                            <li class="email">
                                <i class="icon anm anm-envelope-l"></i>
                                <p>{{$email->value}}</p>
                            </li>
                            <li class="facebook">
                                <i class="icon anm anm-facebook"></i>
                                <p>{{$facebook->value}}</p>
                            </li>
                            <li class="instagram">
                                <i class="icon anm anm-instagram"></i>
                                <p>{{$instagram->value}}</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!--End Footer Links-->
            <hr />
            <!-- <div class="footer-bottom"> -->
                <!-- <div class="row"> -->
                    <!--Footer Copyright-->
                    <!-- <div class="col-12 col-sm-12 col-md-6 col-lg-6 order-1 order-md-0 order-lg-0 order-sm-1 copyright text-sm-center text-md-left text-lg-left"><span></span> <a href="templateshub.net">Templates Hub</a></div> -->
                    <!--End Footer Copyright-->
                    <!--Footer Payment Icon-->
                    <!-- <div class="col-12 col-sm-12 col-md-6 col-lg-6 order-0 order-md-1 order-lg-1 order-sm-0 payment-icons text-right text-md-center"> -->
                        <!-- <ul class="payment-icons list--inline"> -->
                            <!-- <li><i class="icon fa fa-cc-visa" aria-hidden="true"></i></li> -->
                            <!-- <li><i class="icon fa fa-cc-mastercard" aria-hidden="true"></i></li> -->
                            <!-- <li><i class="icon fa fa-cc-discover" aria-hidden="true"></i></li> -->
                            <!-- <li><i class="icon fa fa-cc-paypal" aria-hidden="true"></i></li> -->
                            <!-- <li><i class="icon fa fa-cc-amex" aria-hidden="true"></i></li> -->
                            <!-- <li><i class="icon fa fa-credit-card" aria-hidden="true"></i></li> -->
                        <!-- </ul> -->
                    <!-- </div> -->
                    <!--End Footer Payment Icon-->
                <!-- </div> -->
            <!-- </div> -->
        </div>
    </div>
</footer>
<!--End Footer-->
<!--Scoll Top-->
<span id="site-scroll"><i class="icon anm anm-angle-up-r"></i></span>
<!--End Scoll Top-->

