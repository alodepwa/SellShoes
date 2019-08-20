@extends('layouts.user')

@section('header')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>

<script src="/js/pageLoad.js"></script>
<style>
  #showProduct img{
    width: 160px;
    height: 160px;
  }
  #sliderRange{

  }
</style>

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
        <div class="row mb-5">
          <div class="col-md-9 order-2" >
            <div class="row mb-5" id="showProduct">
              @foreach($product as $value)

                <div class="col-sm-6 col-lg-4 mb-4" data-aos="fade-up">
                  <div class="block-4 text-center border">
                    <figure class="block-4-image">
                      @foreach($value->images as $val)
                        <a href='{{route("showDetail",$value->id)}}'><img src='{{asset("/upImage/$val->path")}}' alt="Image placeholder" class="img-fluid"></a>
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
              <h3 class="mb-3 h6 text-uppercase text-black d-block">Categories</h3>
              <ul class="list-unstyled mb-0">
                @foreach($category as $value)
                  <li class="mb-1"><a href="#" data-id="{{$value->id}}" class="d-flex category"><span>{{$value->name}}</span></a></li>
                @endforeach
              </ul>
            </div>

            <div class="border p-4 rounded mb-4">
              <div class="mb-4">
                <h3 class="mb-3 h6 text-uppercase text-black d-block">Filter by Price</h3>
                <!-- <div id="slider-range" class="border-primary"></div> -->
               <div id="sliderRange" class="border-primary"></div>
               <label id="amount" class="mt-2"></label>
               <input type="hidden" name="startPrices" id="amountStart">
               <input type="hidden" name="endPrices" id="amountEnd">
              </div>

              <div class="mb-4">
                <h3 class="mb-3 h6 text-uppercase text-black d-block">Size</h3>
                <form action="">
                  @csrf
                   @foreach($size as $value)
                    <label  class="d-flex">
                        <input type="radio" id="s_sm" name="checkSize" data-id="{{$value->id}}" class="mr-2 mt-1 size"><span class="text-black">{{$value->name}}</span>
                      </label>
                   @endforeach
                </form>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
@endsection