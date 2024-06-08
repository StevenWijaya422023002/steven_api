@extends('layouts.app-public')
@section('title', 'Home')
@section('content')
<div class="site-wrapper-reveal">
    <div class="hero-box-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Hero Slider Area Start -->
                    <div class="hero-area" id="product-preview"></div>
                    <!-- Hero Slider Area End -->
                </div>
            </div>
        </div>
    </div>

    <div class="about-us-area section-space--ptb_120">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="about-us-content_6 text-center">
                        <h2>Medical Device;&nbsp;Store</h2>
                        <p>
                            <small>
                            "Welcome to our medical device emporium, where innovation meets care.
                             Whether you're seeking cutting-edge technology, reliable essentials, or 
                             specialized solutions, our handpicked selection caters to all. Our 
                             dedicated team is here to guide you every step of the way, ensuring 
                             you find the perfect fit for your needs. Step into our world of health
                              and wellness today and discover the power of precision, compassion, 
                              and innovation. Let's embark on this journey together towards better
                               health and brighter tomorrows! 🌟" &#10084; 
                            </small>
                        </p>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Banner Video Area Start -->
    <div class="banner-video-area overflow-hidden">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="banner-video-box">
                        <img src="https://thehospitallocation.co.uk/wp-content/uploads/2022/10/005-OR-1365x1024.jpg" alt="">
                        <div class="video-icon">
                            <a href="https://youtu.be/VFG0OmbPZSs?si=1_iQVdYVti7IEqsf" class="popup-youtube">
                                <i class="linear-ic-play"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="our-brand-area section-space--pb_90">
        <div class="container">
            <div class="brand-slider-active">
                @php
                    $partner_count = 8;
                @endphp 
                @for($i = 1; $i <= $partner_count; $i++)
                    <div class="col-lg-12">
                        <div class="single-brand-item">
                            <a href="#"><img src="{{ asset('assets/images/brand/partnerb' . $i . '.jpg') }}" class="img-fluid" alt="Partner Images"></a>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>

    <div class="our-member-area section-space--pb_120">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="member--box">
                        <div class="row align-items-center">
                            <div class="col-lg-5 col-md-4">
                                <div class="section-title small-mb__40 tablet-mb__40">
                                    <h4 class="section-title">Join the community!</h4>
                                    <p>Become one of the member and get discount 50% off</p>
                                </div>
                            </div>
                            <div class="col-lg-7 col-md-8">
                                <div class="member-wrap">
                                    <form action="#" class="member--two">
                                        <input class="input-box" type="text" placeholder="Your email address">
                                        <button class="submit-btn"><i class="icon-arrow-right"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('addition-css')
@endsection
@section('addition_script')
<script src="{{ asset('pages/js/home.js') }}"></script>
@endsection
