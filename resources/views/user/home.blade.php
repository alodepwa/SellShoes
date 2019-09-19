@extends('layouts.user')

@section('header')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="/js/pageLoad.js"></script>
<style>
  #showProduct img{
    width: 253px;
    height: 190px;
  }
  .listCategory a:hover{
    color: red;
    text-decoration: none;
  }
  .promotions a:hover{
    text-decoration: none;
  }
  .promotions{
    height: 300px;
    width: 240px;
    overflow-y: auto;
    overflow-x: hidden;
  }
  ::-webkit-scrollbar{
      width: 0px;
  }
</style>

<!-- rate -->
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<link rel="stylesheet" href="/css/rateHome.css">


<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.12.4.js"></script>
<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
@endsection

@section('content')
<?php 
// Session()->flush();
  // echo "<pre>";
  // print_r (Session()->get('user'));
  // echo "</pre>";

 ?>
<div class="bg-light py-3">
      <div class="container">
        <div class="row">
          <div class="container d-flex justify-content-center">
             <form action="" style="width: 600px;">
                @csrf
                <input type="text" class="form-control" name="search" id="search" placeholder="Search...">
            </form>
          </div>
         
        </div>
      </div>
    </div>
                      
<div class="site-section">
      <div class="container">
        <div class="row mb-5" >
          <div class="col-md-9 order-2" >
            <div class="row mb-5" id="showProduct">
              @foreach($product as $value)
                <div class="col-sm-6 col-lg-4 mb-4" data-aos="fade-up">
                  <div class="block-4 text-center border">
                    <figure class="block-4-image">
                      @foreach($value->images as $val)
                        <?php $img = isset($val->path) ? $val->path:'1563269885_chukka.jpg' ?>
                        <a href='{{route("showDetail",$value->id)}}'><img src='{{asset("/upImage/$img")}}'alt="Image " class="img-fluid"></a>
                        @break
                       @endforeach
                    </figure>
                    <div class="block-4-text px-2">
                      <h3><a href='{{route("showDetail",$value->id)}}' class="text-dark">{{$value->name}}</a></h3>
                      @if(!$value->promotion)
                        <p class="text-primary font-weight-bold text-left text-danger mt-2"><?php echo number_format($value->price) ?> <u>đ</u></p>
                      @endif
                      @if($value->promotion)
                        <?php 
                            $end =  $value->promotion->end;
                            $start =  $value->promotion->start;
                            $today = date('Y-m-d');
                         ?>
                         @if(strtotime($today) >= strtotime($start) && strtotime($end)>=strtotime($today))
                          <p class="text-primary font-weight-bold text-left text-danger mt-3"><?php echo number_format($value->price-($value->price*$value->promotion->unit/100)) ?> <u>đ</u></p>
                          <p class="text-left"><strike>đ <?php echo number_format($value->price) ?> </strike>&nbsp;&nbsp;-{{$value->promotion->unit}}%</p>
                        @endif
                        @if(strtotime($today)< strtotime($start)  || strtotime($end)< strtotime($today))
                         <p class="text-primary font-weight-bold text-left text-danger mt-2"><?php echo number_format($value->price) ?> <u>đ</u></p>
                        @endif
                      @endif
                      <?php
                        $total=0;
                        foreach ($value->orders as $key => $val) {
                          if($value->id == $val->pivot->product_id){
                            $total+=$val->pivot->quantity;
                          }
                        }
                        if($value->comments->avg('rate')>=1 && $value->comments->avg('rate')<2 ){
                          echo '<div class="container">
                                  <div class="row">
                                  <div class="rating">
                                      <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
                                      <input type="radio" id="star4" name="rating" value="4" /><label for="star4">4 stars</label>
                                      <input type="radio" id="star3" name="rating" value="3" /><label for="star3">3 stars</label>
                                      <input type="radio" id="star2" name="rating" value="2" /><label for="star2">2 stars</label>
                                      <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                                    </div>
                                    <small style="padding-top: 08px;">('.$total.' đã bán)</small>
                                  </div>
                                </div>';
                        }
                        else if($value->comments->avg('rate')>=2 && $value->comments->avg('rate')<3 ){
                          echo '<div class="container">
                                  <div class="row">
                                  <div class="rating">
                                      <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
                                      <input type="radio" id="star4" name="rating" value="4" /><label for="star4">4 stars</label>
                                      <input type="radio" id="star3" name="rating" value="3" /><label for="star3">3 stars</label>
                                      <input type="radio" id="star2" name="rating" value="2" /><label for="star2" class="text-warning">2 stars</label>
                                      <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                                    </div>
                                    <small style="padding-top: 08px;">('.$total.' đã bán)</small>
                                  </div>
                                </div>';
                        }
                        else if($value->comments->avg('rate')>=3 && $value->comments->avg('rate')<4 ){
                          echo '<div class="container">
                                  <div class="row">
                                  <div class="rating">
                                      <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
                                      <input type="radio" id="star4" name="rating" value="4" /><label for="star4">4 stars</label>
                                      <input type="radio" id="star3" name="rating" value="3" /><label for="star3" class="text-warning">3 stars</label>
                                      <input type="radio" id="star2" name="rating" value="2" /><label for="star2" class="text-warning">2 stars</label>
                                      <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                                    </div>
                                    <small style="padding-top: 08px;">('.$total.' đã bán)</small>
                                  </div>
                                </div>';
                              }else if($value->comments->avg('rate')>=4 && $value->comments->avg('rate')<5 ){
                                echo '<div class="container">
                                  <div class="row">
                                  <div class="rating">
                                      <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
                                      <input type="radio" id="star4" name="rating" value="4" /><label for="star4" class="text-warning">4 stars</label>
                                      <input type="radio" id="star3" name="rating" value="3" /><label for="star3" class="text-warning">3 stars</label>
                                      <input type="radio" id="star2" name="rating" value="2" /><label for="star2" class="text-warning">2 stars</label>
                                      <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                                    </div>
                                    <small style="padding-top: 08px;">('.$total.' đã bán)</small>
                                  </div>
                                </div>';
                              }
                              else if($value->comments->avg('rate')==5){
                                echo '<div class="container">
                                  <div class="row">
                                  <div class="rating">
                                      <input type="radio" id="star5" name="rating" value="5" /><label for="star5" class="text-warning">5 stars</label>
                                      <input type="radio" id="star4" name="rating" value="4" /><label for="star4" class="text-warning">4 stars</label>
                                      <input type="radio" id="star3" name="rating" value="3" /><label for="star3" class="text-warning">3 stars</label>
                                      <input type="radio" id="star2" name="rating" value="2" /><label for="star2" class="text-warning">2 stars</label>
                                      <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                                    </div>
                                    <small style="padding-top: 08px;">('.$total.' đã bán)</small>
                                  </div>
                                </div>';
                              }else{
                                echo '<div class="container">
                                  <div class="row">
                                  <div class="rating">
                                      <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
                                      <input type="radio" id="star4" name="rating" value="4" /><label for="star4">4 stars</label>
                                      <input type="radio" id="star3" name="rating" value="3" /><label for="star3">3 stars</label>
                                      <input type="radio" id="star2" name="rating" value="2" /><label for="star2">2 stars</label>
                                      <input type="radio" id="star1" name="rating" value="1" /><label for="star1">1 star</label>
                                    </div>
                                    <small style="padding-top: 08px;">('.$total.' đã bán)</small>
                                  </div>
                                </div>';
                              }
                       ?>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
              <div class="row">
                    <div class="col-12 d-flex justify-content-center" id="pageAdd">
                      {{$product->links()}}
                    </div>
              </div> 
          </div>

          <div class="col-md-3 order-1 mb-5 mb-md-0">
            <div class="border p-4 rounded mb-4">
              <p class="mb-3 h6 text-uppercase text-danger d-block" style="font-weight: 700; font-size: 16px;">Categories</p>
              <ul class="list-unstyled mb-0">
                @foreach($category as $value)
                  <li class="mb-1 listCategory">
                    <!-- <button data-id="{{$value->id}}" class="d-flex category mt-2 rounded-circle">{{$value->name}}</button> -->
                    <a href="#" data-id="{{$value->id}}" class="d-flex category"><span>{{$value->name}}</span></a>
                  </li>
                @endforeach
              </ul>
            </div>

            <div class="border p-4 rounded mb-4">
              <div class="mb-4">
                <p class="mb-3 h6 text-uppercase text-danger d-block" style="font-weight: 700; font-size: 16px;">Filter By Price</p>
                <form action="">
                  <select name="" id="searchPrice">
                    <option value="" selected>Filter by Price</option>
                    <option value="1">Giá từ thấp đến cao</option>
                    <option value="2">Giá từ cao đến thấp</option>
                  </select>
                </form>
                <!-- <div id="slider-range" class="border-primary"></div> -->
               <!-- <div id="sliderRange" class="border-primary"></div> -->
               <!-- <label id="amount" class="mt-2"></label> -->
              
               <!-- <input type="hidden" name="startPrices" id="amountStart"> -->
               <!-- <input type="hidden" name="endPrices" id="amountEnd"> -->
              </div>

              <!-- <div class="mb-4">
                <h3 class="mb-3 h6 text-uppercase text-black d-block">Size</h3>
                <form action="">
                  @csrf
                   @foreach($size as $value)
                    <label  class="d-flex">
                        <input type="radio" id="s_sm" name="checkSize" data-id="{{$value->id}}" class="mr-2 mt-1 size"><span class="text-black">{{$value->name}}</span>
                      </label>
                   @endforeach
                </form>
              </div> -->

            </div>
          

          <!-- sản phẩm giảm giá -->
           <div class="border p-4 rounded mb-4 promotion">
              <div class="mb-4">
                <h3 class="mb-3 h6 text-center text-uppercase text-danger d-block pb-4" style="font-weight: 700;
                font-size: 18px;">Sản Phẩm Giảm Giá</h3>
               </div>
               <div class="promotions">
                @foreach($promotions as $value)
                 <?php 
                    $end =  isset($value->promotion->end)?$value->promotion->end:0;
                    $start =  isset($value->promotion->start)?$value->promotion->start:0;;
                    $today = date('Y-m-d');
                 ?>
                  @if(strtotime($today) >= strtotime($start) && strtotime($end)>=strtotime($today))
                  <div class="row ">
                    <div class="col-md-12">
                      <div class="row d-flex flex-row ml-1 mr-1 mt-1  border-top ">
                        <figure class="block-3-image">
                          @foreach($value->images as $val)
                            <a href='{{route("showDetail",$value->id)}}'><img src='{{asset("/upImage/$val->path")}}' style="width: 80px; height: 80px;" alt="Image placeholder" class="img-fluid"></a>
                            @break
                           @endforeach
                        </figure>
                        <div class="pl-3" style="margin-top: -20px;">
                          <h3><a href='{{route("showDetail",$value->id)}}' class="text-dark">{{$value->name}}</a></h3>
                            @if($value->promotion)
                                <p class="text-primary font-weight-bold text-left text-danger mt-3"><?php echo number_format($value->price-($value->price*$value->promotion->unit/100)) ?> <u>đ</u></p>
                                <p class="text-left" style="font-weight: bold;"><strike>đ <?php echo number_format($value->price) ?> </strike>&nbsp;&nbsp;-{{$value->promotion->unit}}%</p>
                            @endif
                        </div>
                      </div>
                    </div>
                  </div>
                @endif
              @endforeach
            </div>
            </div>

          
          <!-- sản phẩm bán chạy  -->
           <div class="border p-4 rounded mb-4 promotion">
              <div class="mb-4">
                <h3 class="mb-3 text-center h6 text-uppercase text-danger d-block pb-4" style="font-weight: 700;
                font-size: 18px;">Sản Phẩm Bán Chạy Gần Đây</h3>
               </div>
               <div class="promotions">
                 
                @foreach($products as $value)
                 <?php 
                    $end =  isset($value->promotion->end)?$value->promotion->end:0;
                    $start =  isset($value->promotion->start)?$value->promotion->start:0;;
                    $today = date('Y-m-d');
                 ?>
                  <?php 
                    $sold=0;
                    foreach ($value->orders as $key => $val) {
                      if($value->id == $val->pivot->product_id){
                        $sold+=$val->pivot->quantity;
                      }
                    }

                   ?>
                  @if($sold >100)
                  <div class="row ">
                    <div class="col-md-12">
                      <div class="row d-flex flex-row ml-1 mr-1 mt-1  border-top ">
                        <figure class="block-3-image">
                          @foreach($value->images as $val)
                            <a href='{{route("showDetail",$value->id)}}'><img src='{{asset("/upImage/$val->path")}}' style="width: 80px; height: 80px;" alt="Image placeholder" class="img-fluid"></a>
                            @break
                           @endforeach
                        </figure>
                        <div class="pl-3" style="margin-top: -20px;">
                          <h3><a href='{{route("showDetail",$value->id)}}' class="text-dark">{{$value->name}}</a></h3>
                           @if($value->promotion)
                            <?php 
                                $end =  isset($value->promotion->end)?$value->promotion->end:0;
                                $start =  isset($value->promotion->start)?$value->promotion->start:0;
                                $today = date('Y-m-d');
                             ?>
                             @if(strtotime($today) >= strtotime($start) && strtotime($end)>=strtotime($today))
                              <p class="text-primary font-weight-bold text-left text-danger mt-3"><?php echo number_format($value->price-($value->price*$value->promotion->unit/100)) ?> <u>đ</u></p>
                              <p class="text-left"><strike>đ <?php echo number_format($value->price) ?> </strike>&nbsp;&nbsp;-{{$value->promotion->unit}}%</p>
                            @endif
                            @if(strtotime($today)< strtotime($start)  || strtotime($end)< strtotime($today) || $end == 0 || $start == 0)
                             <p class="text-primary font-weight-bold text-left text-danger mt-2"><?php echo number_format($value->price) ?> <u>đ</u></p>
                            @endif
                          @endif
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row ml-2">
                    <?php 
                      if($value->comments->avg('rate')>=1 && $value->comments->avg('rate')<2 ){
                          echo '<div class="container">
                                  <div class="row">
                                  <div class="ratingLeft">
                                      <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
                                      <input type="radio" id="star4" name="rating" value="4" /><label for="star4">4 stars</label>
                                      <input type="radio" id="star3" name="rating" value="3" /><label for="star3">3 stars</label>
                                      <input type="radio" id="star2" name="rating" value="2" /><label for="star2">2 stars</label>
                                      <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                                    </div>
                                    <small style="padding-top: 12px;">('.$sold.' đã bán)</small>
                                  </div>
                                </div>';
                        }
                        else if($value->comments->avg('rate')>=2 && $value->comments->avg('rate')<3 ){
                          echo '<div class="container">
                                  <div class="row">
                                  <div class="ratingLeft">
                                      <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
                                      <input type="radio" id="star4" name="rating" value="4" /><label for="star4">4 stars</label>
                                      <input type="radio" id="star3" name="rating" value="3" /><label for="star3">3 stars</label>
                                      <input type="radio" id="star2" name="rating" value="2" /><label for="star2" class="text-warning">2 stars</label>
                                      <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                                    </div>
                                    <small style="padding-top: 12px;">('.$sold.' đã bán)</small>
                                  </div>
                                </div>';
                        }
                        else if($value->comments->avg('rate')>=3 && $value->comments->avg('rate')<4 ){
                          echo '<div class="container">
                                  <div class="row">
                                  <div class="ratingLeft">
                                      <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
                                      <input type="radio" id="star4" name="rating" value="4" /><label for="star4">4 stars</label>
                                      <input type="radio" id="star3" name="rating" value="3" /><label for="star3" class="text-warning">3 stars</label>
                                      <input type="radio" id="star2" name="rating" value="2" /><label for="star2" class="text-warning">2 stars</label>
                                      <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                                    </div>
                                    <small style="padding-top: 12px;">('.$sold.' đã bán)</small>
                                  </div>
                                </div>';
                              }else if($value->comments->avg('rate')>=4 && $value->comments->avg('rate')<5 ){
                                echo '<div class="container">
                                  <div class="row">
                                  <div class="ratingLeft">
                                      <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
                                      <input type="radio" id="star4" name="rating" value="4" /><label for="star4" class="text-warning">4 stars</label>
                                      <input type="radio" id="star3" name="rating" value="3" /><label for="star3" class="text-warning">3 stars</label>
                                      <input type="radio" id="star2" name="rating" value="2" /><label for="star2" class="text-warning">2 stars</label>
                                      <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                                    </div>
                                    <small style="padding-top: 12px;">('.$sold.' đã bán)</small>
                                  </div>
                                </div>';
                              }
                              else if($value->comments->avg('rate')==5){
                                echo '<div class="container">
                                  <div class="row">
                                  <div class="ratingLeft">
                                      <input type="radio" id="star5" name="rating" value="5" /><label for="star5" class="text-warning">5 stars</label>
                                      <input type="radio" id="star4" name="rating" value="4" /><label for="star4" class="text-warning">4 stars</label>
                                      <input type="radio" id="star3" name="rating" value="3" /><label for="star3" class="text-warning">3 stars</label>
                                      <input type="radio" id="star2" name="rating" value="2" /><label for="star2" class="text-warning">2 stars</label>
                                      <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                                    </div>
                                    <small style="padding-top: 12px;">('.$sold.' đã bán)</small>
                                  </div>
                                </div>';
                              }else{
                                echo '<div class="container">
                                  <div class="row">
                                  <div class="ratingLeft">
                                      <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
                                      <input type="radio" id="star4" name="rating" value="4" /><label for="star4">4 stars</label>
                                      <input type="radio" id="star3" name="rating" value="3" /><label for="star3">3 stars</label>
                                      <input type="radio" id="star2" name="rating" value="2" /><label for="star2">2 stars</label>
                                      <input type="radio" id="star1" name="rating" value="1" /><label for="star1">1 star</label>
                                    </div>
                                    <small style="padding-top: 12px;">('.$sold.' đã bán)</small>
                                  </div>
                                </div>';
                              }
                     ?>
                  </div>
                @endif
              @endforeach
            </div>
            </div>
          </div>
        </div>

      </div>
    </div>
@endsection