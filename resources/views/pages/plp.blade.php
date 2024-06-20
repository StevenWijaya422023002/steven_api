@extends('layouts.app-public')
@section('title', 'Shop')
@section('content')
<div class="site-wrapper-reveal">
  <!-- Product Area Start -->
  <div class="product-wrapper section-space--ptb_90 border-bottom pb-5 mb-5">
    <div class="container">
      <div class="row">
        <div class="col-lg-3 col-md-3 order-md-1 order-2 small-mt__40">
          <!-- Publisher Filter -->
          <div class="shop-widget widget-shop-publishers mt-3">
            <div class="product-filter">
              <h6 class="mb-20">Brand</h6> 
              <select class="_filter form-select form-select-sm" name="_brand" onchange="getData()">
                <option value="" selected>All</option>
                <option value="Omron">Omron</option>
                <option value="Philips">Philips</option>
                <option value="Terumo">Terumo</option>
                <option value="Abs">Abs</option>
                <option value="Onemed">Onemed</option>
                <option value="WELL LEAD">WELL LEAD</option>
                <option value="GEA Medical">GEA Medical</option>
                <option value="GEA">GEA</option>
                <option value="General Care">General Care</option>
                <option value="MedGroup">MedGroup</option>
                <option value="Accu-Chek">Accu-Chek</option>
                <option value="Mindray">Mindray</option>
                <option value="Krisbow">Krisbow</option>
                <option value="no brand">no brand</option>
              </select>
            </div>
          </div>
          <!-- Color Filter -->
          <div class="shop-widget widget-color">
            <div class="product-filter">
              <h6 class="mb-20">Color</h6>
              <ul class="widget-nav-list">
                <li><a href="#"><span class="swatch-color black"></span></a></li>
                <li><a href="#"><span class="swatch-color green"></span></a></li>
                <li><a href="#"><span class="swatch-color grey"></span></a></li>
                <li><a href="#"><span class="swatch-color red"></span></a></li>
                <li><a href="#"><span class="swatch-color white"></span></a></li>
                <li><a href="#"><span class="swatch-color yellow"></span></a></li>
              </ul>
            </div>
          </div>
          <!-- Price Filter -->
          <div class="shop-widget">
            <div class="product-filter widget-price">
              <h6 class="mb-20">Price</h6>
              <ul class="widget-nav-list">
                <li><a href="#">Under IDR 100k</a></li>
                <li><a href="#">IDR 100-500k</a></li>
                <li><a href="#">IDR 501-1000K</a></li>
                <li><a href="#">Above IDR 1000k</a></li>
              </ul>
            </div>
          </div>
          <!-- Tags Filter -->
          <div class="shop-widget">
            <div class="product-filter">
              <h6 class="mb-20">Tags</h6>
              <div class="blog-tagcloud">
                <a href="#" class="selected">Medical Device</a>
                <a href="#">Health</a>
                <a href="#">Best Seller</a>
                <a href="#">Innovative</a>
                <a href="#">High Quality</a>
                <a href="#">Reliable</a>
                <a href="#">Accurate</a>
                <a href="#">User-Friendly</a>  
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-9 col-md-9 order-md-2 order-1">
          <div class="row mb-5">
            <div class="col-lg-6 col-md-8">
              <div class="shop-toolbar__items shop-toolbar__item--left">
                <div class="shop-toolbar__item shop-toolbar__item--result">
                  <p class="result-count"> 
                    Showing <span id="products_count_start"></span>-<span id="products_count_end"></span>
                    of <span id="products_count_total"></span>
                  </p>
                </div>
                <div class="shop-toolbar__item">
                  <select class="_filter form-select form-select-sm" name="_sort_by" onchange="getData()">
                    <option value="name_asc">Sort by A-Z</option>
                    <option value="name_desc">Sort by Z-A</option>
                    <option value="latest_publication">Sort by latest</option>
                    <option value="latest_added">Sort by time added</option>
                    <option value="price_asc">Sort by price: low to high</option>
                    <option value="price_desc">Sort by price: high to low</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="col-lg-6 col-md-4">
              <div class="header-right-search">
                <div class="header-search-box">
                  <input class="_filter search-field" name="_search" type="text"
                         onkeypress="getDataOnEnter(event)" placeholder="Search by equipment or Brand...">
                  <button class="search-icon"><i class="icon-magnifier"></i></button>
                </div>
              </div>
            </div>
          </div>
          <div>
            <div class="row" id="product-list"></div>
            <div class="row">
              <div class="col-12">
                <ul class="page-pagination text-center mt-40" id="product-list-pagination"></ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('addition_css')
@endsection
@section('addition_script')
<script src="{{ asset('pages/js/plp.js') }}"></script>
@endsection
