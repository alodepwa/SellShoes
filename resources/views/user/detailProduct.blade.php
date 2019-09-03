@extends('layouts.user')

@section('header')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="/css/detail.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
<script src="/js/pageDetail.js"></script>

<!-- đánh giá sp -->
<link rel="stylesheet" href="/css/rateHome.css">
<link rel="stylesheet" href="/css/rateDetail.css">
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="assets/js/vendor/jquery.min.js"><\/script>')</script>
<link rel="stylesheet" href="/css/rate.css">
<style>
  .bottom img{
    width: 160px;
    height: 160px;
    }}
</style>
@endsection

@section('content')
<?php 
 ?>
<div class="bg-light py-3">
      <div class="container">
        <div class="row">
          <div class="col-md-12 mb-0"><a href="{{route('page.index')}}">Home</a> <span class="mx-2 mb-0">/</span> <strong class="text-black">Products Detail</strong></div>
        </div>
      </div>
    </div>  
    
    <div class="site-section">
      <div class="container">
        <div class="row">
		<div class="col-sm-6 details">
					<div class="row imgTop">
						<div class="container imgBox">
								@foreach($product->images as $val)
									<img src='{{asset("/upImage/$val->path")}}' alt="Image" class="img-fluid">
									@break
								@endforeach
						</div>
					</div>
					<div class="container mt-2">
						<div class="row imgBottom">
							@foreach($product->images as $val)
									<div class="card mr-1">
										<a href='{{asset("/upImage/$val->path")}}' target="imgBox"><img class="card-img-top img-fluid" src='{{asset("/upImage/$val->path")}}' alt="Card image cap"></a>
									</div>
								@endforeach
						</div>
					</div>
			</div>


          <div class="col-md-6">
            <h2 class="text-black nameProduct">{{$product['name']}}</h2>
            <div class="my-4">
              <?php
                  $sold=0;
                  foreach ($product->orders as $key => $value) {
                    $sold+=$value->pivot->quantity;
                  }
              if($comments->avg('rate')>=1 && $comments->avg('rate')<2 ){
                          echo '<div class="container">
                                  <div class="row">
                                  <div class="rating">
                                      <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
                                      <input type="radio" id="star4" name="rating" value="4" /><label for="star4">4 stars</label>
                                      <input type="radio" id="star3" name="rating" value="3" /><label for="star3">3 stars</label>
                                      <input type="radio" id="star2" name="rating" value="2" /><label for="star2">2 stars</label>
                                      <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                                    </div>
                                    <small style="padding-top: 08px;">('.$sold.' đã bán)</small>
                                  </div>
                                </div>';
                        }
                        else if($comments->avg('rate')>=2 && $comments->avg('rate')<3 ){
                          echo '<div class="container">
                                  <div class="row">
                                  <div class="rating">
                                      <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
                                      <input type="radio" id="star4" name="rating" value="4" /><label for="star4">4 stars</label>
                                      <input type="radio" id="star3" name="rating" value="3" /><label for="star3">3 stars</label>
                                      <input type="radio" id="star2" name="rating" value="2" /><label for="star2" class="text-warning">2 stars</label>
                                      <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                                    </div>
                                    <small style="padding-top: 08px;">('.$sold.' đã bán)</small>
                                  </div>
                                </div>';
                        }
                        else if($comments->avg('rate')>=3 && $comments->avg('rate')<4 ){
                          echo '<div class="container">
                                  <div class="row">
                                  <div class="rating">
                                      <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
                                      <input type="radio" id="star4" name="rating" value="4" /><label for="star4">4 stars</label>
                                      <input type="radio" id="star3" name="rating" value="3" /><label for="star3" class="text-warning">3 stars</label>
                                      <input type="radio" id="star2" name="rating" value="2" /><label for="star2" class="text-warning">2 stars</label>
                                      <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                                    </div>
                                    <small style="padding-top: 08px;">('.$sold.' đã bán)</small>
                                  </div>
                                </div>';
                              }else if($comments->avg('rate')>=4 && $comments->avg('rate')<5 ){
                                echo '<div class="container">
                                  <div class="row">
                                  <div class="rating">
                                      <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
                                      <input type="radio" id="star4" name="rating" value="4" /><label for="star4" class="text-warning">4 stars</label>
                                      <input type="radio" id="star3" name="rating" value="3" /><label for="star3" class="text-warning">3 stars</label>
                                      <input type="radio" id="star2" name="rating" value="2" /><label for="star2" class="text-warning">2 stars</label>
                                      <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                                    </div>
                                    <small style="padding-top: 08px;">('.$sold.' đã bán)</small>
                                  </div>
                                </div>';
                              }
                              else if($comments->avg('rate')==5){
                                echo '<div class="container">
                                  <div class="row">
                                  <div class="rating">
                                      <input type="radio" id="star5" name="rating" value="5" /><label for="star5" class="text-warning">5 stars</label>
                                      <input type="radio" id="star4" name="rating" value="4" /><label for="star4" class="text-warning">4 stars</label>
                                      <input type="radio" id="star3" name="rating" value="3" /><label for="star3" class="text-warning">3 stars</label>
                                      <input type="radio" id="star2" name="rating" value="2" /><label for="star2" class="text-warning">2 stars</label>
                                      <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                                    </div>
                                    <small style="padding-top: 08px;">('.$sold.' đã bán)</small>
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
                                    <small style="padding-top: 08px;">('.$sold.' đã bán)</small>
                                  </div>
                                </div>';
                              } 
           ?>

            </div>
            <p>{{$product->description}}</p>
            <p class="text-danger"><strong class="text-primary h4 text-danger" id="price">
              <?php 
                $end =  $product->promotion->end;
                $start =  $product->promotion->start;
                $today = date('Y-m-d');
                if(strtotime($today) >= strtotime($start) && strtotime($end)>=strtotime($today)){
                  echo number_format($product->price-($product->price*$product->promotion->unit/100)); 
                }
                if(strtotime($today)< strtotime($start)  || strtotime($end)< strtotime($today)){
                  echo number_format($product->price); 
                }
              ?>
              </strong> đ
            </p>
            @if($product->promotion)
              @if(strtotime($today) >= strtotime($start) && strtotime($end)>=strtotime($today))
                 <p><strong class="text-dark"><strike><?php echo number_format($product->price) ?> đ</strike></strong>&nbsp;&nbsp;-<?php 
                  echo $product->promotion->unit;
                  ?>%
                </p>
              @endif
            @endif

            <div class="mb-1 d-flex flex-column">
            	@foreach($product->sizes as $value)
              <label  class="d-flex mr-3 mb-3 hethang">
                <span class="mr-2" style="top:-2px; position: relative;"><input type="radio" name="size" value="{{$value->id}}" data-name="{{$value->name}}"></span><span class="d-inline-block text-black">{{$value->name}}</span>
                  <?php
                  
                    $allTotal = $value->pivot->quantity;
                    if ($allTotal>0) {
                      echo "<p class='ml-3'>còn $allTotal sp</p>";
                    }
                    else {
                      echo '<p class="ml-3 hethang1" data-id="'.$value->id.'">Hết hàng</p>';
                    }
                 ?>
              </label>
             	@endforeach
            </div>
            <div class="mb-5">
              <div class="input-group mb-3" style="max-width: 120px;">
             <!--  <div class="input-group-prepend">
                <button class="btn btn-outline-primary js-btn-minus nut" type="button">&minus;</button>
              </div> -->
              <input type="text" class="form-control text-center" value="1" disabled aria-label="Example text with button addon" data-id="{{$product->id}}" id="increase" aria-describedby="button-addon1">
              <!-- <div class="input-group-append">
                <button class="btn btn-outline-primary js-btn-plus nut" type="button">&plus;</button>
              </div> -->
            </div>
              <!-- <div class="alert alert-danger notification"> -->
                <p class="mess text-danger mt-3"></p>
              <!-- </div> -->
            </div>
            <!-- <p><button class="btn-success"  id="addToCart">Add To Cart</button></p> -->
            <p><a href="" id="addToCart" class="btn btn-success">Add To Cart</a></p>

          </div>
        </div>
      </div>
    </div>
  


    <!-- đáng giá sản phẩm -->
  
    @if($comment->avg('rate'))
    <div class="container comment">
      <div class="container">
    <div class="row">
      <div class="col-sm-4">
        <div class="rating-block">
          <h4>Đánh Giá Sản Phẩm</h4>
          <h2 class="bold" style="margin-bottom: -3px;"><?php echo round($comments->avg('rate'),2); ?><small style="font-size: 28px; font-weight: bold; color: black;">/ 5</small></h2>
          <?php 
            if($comments->avg('rate')>=1 && $comments->avg('rate')<2 ){
              echo '<div class="container">
                      <div class="row">
                      <div class="ratingTop">
                          <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
                          <input type="radio" id="star4" name="rating" value="4" /><label for="star4">4 stars</label>
                          <input type="radio" id="star3" name="rating" value="3" /><label for="star3">3 stars</label>
                          <input type="radio" id="star2" name="rating" value="2" /><label for="star2">2 stars</label>
                          <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                        </div>
                      </div>
                    </div>';
            }
            else if($comments->avg('rate')>=2 && $comments->avg('rate')<3 ){
              echo '<div class="container">
                      <div class="row">
                      <div class="ratingTop">
                          <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
                          <input type="radio" id="star4" name="rating" value="4" /><label for="star4">4 stars</label>
                          <input type="radio" id="star3" name="rating" value="3" /><label for="star3">3 stars</label>
                          <input type="radio" id="star2" name="rating" value="2" /><label for="star2" class="text-warning">2 stars</label>
                          <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                        </div>
                      </div>
                    </div>';
            }
            else if($comments->avg('rate')>=3 && $comments->avg('rate')<4 ){
              echo '<div class="container">
                      <div class="row">
                      <div class="ratingTop">
                          <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
                          <input type="radio" id="star4" name="rating" value="4" /><label for="star4">4 stars</label>
                          <input type="radio" id="star3" name="rating" value="3" /><label for="star3" class="text-warning">3 stars</label>
                          <input type="radio" id="star2" name="rating" value="2" /><label for="star2" class="text-warning">2 stars</label>
                          <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                        </div>
                      </div>
                    </div>';
                  }else if($comments->avg('rate')>=4 && $comments->avg('rate')<5 ){
                    echo '<div class="container">
                      <div class="row">
                      <div class="ratingTop">
                          <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
                          <input type="radio" id="star4" name="rating" value="4" /><label for="star4" class="text-warning">4 stars</label>
                          <input type="radio" id="star3" name="rating" value="3" /><label for="star3" class="text-warning">3 stars</label>
                          <input type="radio" id="star2" name="rating" value="2" /><label for="star2" class="text-warning">2 stars</label>
                          <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                        </div>
                      </div>
                    </div>';
                  }
                  else if($comments->avg('rate')==5){
                    echo '<div class="container">
                      <div class="row">
                      <div class="ratingTop">
                          <input type="radio" id="star5" name="rating" value="5" /><label for="star5" class="text-warning">5 stars</label>
                          <input type="radio" id="star4" name="rating" value="4" /><label for="star4" class="text-warning">4 stars</label>
                          <input type="radio" id="star3" name="rating" value="3" /><label for="star3" class="text-warning">3 stars</label>
                          <input type="radio" id="star2" name="rating" value="2" /><label for="star2" class="text-warning">2 stars</label>
                          <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                        </div>
                      </div>
                    </div>';
                  }else{
                    echo '<div class="container">
                      <div class="row">
                      <div class="ratingTop">
                          <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
                          <input type="radio" id="star4" name="rating" value="4" /><label for="star4">4 stars</label>
                          <input type="radio" id="star3" name="rating" value="3" /><label for="star3">3 stars</label>
                          <input type="radio" id="star2" name="rating" value="2" /><label for="star2">2 stars</label>
                          <input type="radio" id="star1" name="rating" value="1" /><label for="star1">1 star</label>
                        </div>
                      </div>
                    </div>';
                  } 
              ?>
        </div>
      </div>
      <div class="col-sm-3 mt-5">
        <div class="pull-left">
          <div class="pull-left" style="width:35px; line-height:1;">
            <div style="height:9px; margin:5px 0;">
              5
              <span class="glyphicon glyphicon-star"></span>
            </div>
          </div>
          <div class="pull-left" style="width:180px;">
            <div class="progress" style="height:9px; margin:8px 0;">
              <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="5" aria-valuemin="0" aria-valuemax="5" style="width: 1000%">
              <span class="sr-only">80% Complete (danger)</span>
              </div>
            </div>
          </div>
          <div class="pull-right" style="margin-left:10px;">
            <?php 
                  // bao nhiêu người 5 sao
                  $total=0;
                  foreach ($comments as $key => $value) {
                    if($value->rate == 5){
                      $total = $total+1; 
                    }
                  }
                  echo $total;              
               ?>
          </div>
        </div>
        <div class="pull-left">
          <div class="pull-left" style="width:35px; line-height:1;">
            <div style="height:9px; margin:5px 0;">4 <span class="glyphicon glyphicon-star"></span></div>
          </div>
          <div class="pull-left" style="width:180px;">
            <div class="progress" style="height:9px; margin:8px 0;">
              <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="4" aria-valuemin="0" aria-valuemax="5" style="width: 80%">
              <span class="sr-only">80% Complete (danger)</span>
              </div>
            </div>
          </div>
          <div class="pull-right" style="margin-left:10px;">
            <?php 
                  // bao nhiêu người 5 sao
                  $total=0;
                  foreach ($comments as $key => $value) {
                    if($value->rate == 4){
                      $total = $total+1; 
                    }
                  }
                  echo $total;              
               ?>
          </div>
        </div>
        <div class="pull-left">
          <div class="pull-left" style="width:35px; line-height:1;">
            <div style="height:9px; margin:5px 0;">3 <span class="glyphicon glyphicon-star"></span></div>
          </div>
          <div class="pull-left" style="width:180px;">
            <div class="progress" style="height:9px; margin:8px 0;">
              <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="3" aria-valuemin="0" aria-valuemax="5" style="width: 60%">
              <span class="sr-only">80% Complete (danger)</span>
              </div>
            </div>
          </div>
          <div class="pull-right" style="margin-left:10px;">
            <?php 
                  // bao nhiêu người 5 sao
                  $total=0;
                  foreach ($comments as $key => $value) {
                    if($value->rate == 3){
                      $total = $total+1; 
                    }
                  }
                  echo $total;              
               ?>
          </div>
        </div>
        <div class="pull-left">
          <div class="pull-left" style="width:35px; line-height:1;">
            <div style="height:9px; margin:5px 0;">2 <span class="glyphicon glyphicon-star"></span></div>
          </div>
          <div class="pull-left" style="width:180px;">
            <div class="progress" style="height:9px; margin:8px 0;">
              <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="5" style="width: 40%">
              <span class="sr-only">80% Complete (danger)</span>
              </div>
            </div>
          </div>
          <div class="pull-right" style="margin-left:10px;">
            <?php 
                  // bao nhiêu người 5 sao
                  $total=0;
                  foreach ($comments as $key => $value) {
                    if($value->rate == 2){
                      $total = $total+1; 
                    }
                  }
                  echo $total;              
               ?>
          </div>
        </div>
        <div class="pull-left">
          <div class="pull-left" style="width:35px; line-height:1;">
            <div style="height:9px; margin:5px 0;">1 <span class="glyphicon glyphicon-star"></span></div>
          </div>
          <div class="pull-left" style="width:180px;">
            <div class="progress" style="height:9px; margin:8px 0;">
              <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="1" aria-valuemin="0" aria-valuemax="5" style="width: 20%">
              <span class="sr-only">80% Complete (danger)</span>
              </div>
            </div>
          </div>
          <div class="pull-right" style="margin-left:10px;">
            <?php 
                  // bao nhiêu người 5 sao
                  $total=0;
                  foreach ($comments as $key => $value) {
                    if($value->rate == 1){
                      $total = $total+1; 
                    }
                  }
                  echo $total;              
               ?>
          </div>
        </div>
      </div>      
    </div>      
    
    <div class="row">
      <div class="col-sm-7">
        <hr/>
        <div class="review-block">
          @foreach($comment as $value)
          <div class="row">
            <div class="col-sm-3">
              <div class="review-block-name"><a href="#">{{$value->user->name}}</a></div>
              <div class="review-block-date"><?php echo date_format($value->created_at,'d-m-Y');echo"</br>"; echo date_format($value->created_at,'H:i:s'); ?></div>
            </div>
            <div class="col-sm-9">
              <div class="review-block-rate">
                <?php 
                  switch ($value->rate) {
                    case 1:
                      echo '<div class="container">
                            <div class="row">
                            <div class="rating">
                                <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
                                <input type="radio" id="star4" name="rating" value="4" /><label for="star4">4 stars</label>
                                <input type="radio" id="star3" name="rating" value="3" /><label for="star3">3 stars</label>
                                <input type="radio" id="star2" name="rating" value="2" /><label for="star2">2 stars</label>
                                <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                              </div>
                            </div>
                          </div>';
                      break;

                    case 2:
                      echo '<div class="container">
                            <div class="row">
                            <div class="rating">
                                <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
                                <input type="radio" id="star4" name="rating" value="4" /><label for="star4">4 stars</label>
                                <input type="radio" id="star3" name="rating" value="3" /><label for="star3">3 stars</label>
                                <input type="radio" id="star2" name="rating" value="2" /><label for="star2" class="text-warning">2 stars</label>
                                <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                              </div>
                            </div>
                          </div>';
                      break;

                    case 3:
                      echo '<div class="container">
                            <div class="row">
                            <div class="rating">
                                <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
                                <input type="radio" id="star4" name="rating" value="4" /><label for="star4">4 stars</label>
                                <input type="radio" id="star3" name="rating" value="3" /><label for="star3" class="text-warning">3 stars</label>
                                <input type="radio" id="star2" name="rating" value="2" /><label for="star2" class="text-warning">2 stars</label>
                                <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                              </div>
                            </div>
                          </div>';
                      break;
                    case 4:
                      echo '<div class="container">
                            <div class="row">
                            <div class="rating">
                                <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
                                <input type="radio" id="star4" name="rating" value="4" /><label for="star4" class="text-warning">4 stars</label>
                                <input type="radio" id="star3" name="rating" value="3" /><label for="star3" class="text-warning">3 stars</label>
                                <input type="radio" id="star2" name="rating" value="2" /><label for="star2" class="text-warning">2 stars</label>
                                <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                              </div>
                            </div>
                          </div>';
                      break;

                    case 5:
                      echo '<div class="container">
                            <div class="row">
                            <div class="rating">
                                <input type="radio" id="star5" name="rating" value="5" /><label for="star5" class="text-warning">5 stars</label>
                                <input type="radio" id="star4" name="rating" value="4" /><label for="star4" class="text-warning">4 stars</label>
                                <input type="radio" id="star3" name="rating" value="3" /><label for="star3" class="text-warning">3 stars</label>
                                <input type="radio" id="star2" name="rating" value="2" /><label for="star2" class="text-warning">2 stars</label>
                                <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
                              </div>
                            </div>
                          </div>';
                      break;
                  }

                 ?>
              </div>
              <div class="review-block-description">{{$value->content}}</div>
            </div>
          </div>
          <hr/>
          @endforeach
        </div>
        <!-- phân trang comment -->
        <div class="text-center">
          {{$comment->links()}}
        </div>
      </div>
    </div>
    </div> <!-- /container -->
    </div>
    @endif
    @if(!$comment->avg('rate'))
    <div class="container">
      <div>Chưa có đánh giá nào.</div>
    </div>
    @endif
      


    <div class="site-section block-3 site-blocks-2 bg-light mt-5">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-7 site-section-heading text-center pt-4">
            <h2>Featured Products</h2>
          </div>
        </div>
        <div class="row">

          <div class="col-md-12">
            <div class="nonloop-block-3 owl-carousel">
                @foreach($categoryAll as $value)
                
                  <div class="block-4 text-center bottom">
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
                        
              @endforeach
            </div>
          </div>

        </div>
      </div>
    </div>

    <script>
		$(document).ready(function(){
			$('.imgBottom a').click(function(e){
				e.preventDefault();
				console.log('alo');
				$('.imgBox img').attr("src",$(this).attr("href"));
			});
		});
	</script>
@endsection