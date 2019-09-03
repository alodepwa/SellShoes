@extends('layouts.user')

@section('header')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="/css/detail.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
<link rel="stylesheet" href="/css/comment.css">
<style>
	img{
		width: 200px;
		height: 200px;
	}
</style>
@endsection

@section('content')
<div class="container my-5">
	<div class="row">
		<div class="col-sm-5">
			<form method="post" action="{{route('commentPost')}}" id ="addProduct">
				@csrf
				<input type="hidden" name="productID" value="{{$product->id}}">
				<fieldset class="form-group">
					<div class="container">
                      	<div class="row">
							<label class="label">1. Đánh giá của bạn về sản phẩm này:</label>
	                      	<div class="rating">
	                          <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
	                          <input type="radio" id="star4" name="rating" value="4" /><label for="star4">4 stars</label>
	                          <input type="radio" id="star3" name="rating" value="3" /><label for="star3">3 stars</label>
	                          <input type="radio" id="star2" name="rating" value="2" /><label for="star2" >2 stars</label>
	                          <input type="radio" id="star1" name="rating" value="1" /><label for="star1">1 star</label>
	                        </div>
	                      </div>
                    </div>
				</fieldset>
				<fieldset class="form-group">
					<label class="label mb-4" for="descripton">2. Viết nhận xét của bạn vào bên dưới:</label>
					<textarea name="description" id="descripton" class="form-control" placeholder="Nhận xét của bạn về sản phẩm này" required></textarea>
				</fieldset>
				<fieldset class="form-group">
					<input type="submit" class="form-control btn btn-warning mt-4" value="Gửi Nhận Xét">
				</fieldset>
			</form>
		</div>
		<div class="col-sm-2"></div>
		<div class="col-sm-5" >
			<?php 
				foreach ($product->images as $key => $value) {
					$img = $value->path;
					break;
				}
			 ?>
			<img src='{{asset("upImage/$img")}}' alt="">
			<?php 
				$rate=$product->comments->avg('rate');
				$number= $product->comments->count('rate');
			 ?>
			<div class="container mt-3">
                      	<div class="row">
	                      	<div class="ratingTop">
	                      		@if($rate>1 && $rate<2)
		                          <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
		                          <input type="radio" id="star4" name="rating" value="4" /><label for="star4">4 stars</label>
		                          <input type="radio" id="star3" name="rating" value="3" /><label for="star3">3 stars</label>
		                          <input type="radio" id="star2" name="rating" value="2" /><label for="star2" >2 stars</label>
		                          <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
	                          	@endif
	                          	@if($rate>2 && $rate<3)
		                          <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
		                          <input type="radio" id="star4" name="rating" value="4" /><label for="star4">4 stars</label>
		                          <input type="radio" id="star3" name="rating" value="3" /><label for="star3">3 stars</label>
		                          <input type="radio" id="star2" name="rating" value="2" /><label for="star2" class="text-warning">2 stars</label>
		                          <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
	                          	@endif
	                          	@if($rate>3 && $rate<4)
		                          <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
		                          <input type="radio" id="star4" name="rating" value="4" /><label for="star4">4 stars</label>
		                          <input type="radio" id="star3" name="rating" value="3" /><label for="star3" class="text-warning">3 stars</label>
		                          <input type="radio" id="star2" name="rating" value="2" /><label for="star2" class="text-warning" >2 stars</label>
		                          <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
	                          	@endif
	                          	@if($rate>4 && $rate<5)
		                          <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
		                          <input type="radio" id="star4" name="rating" value="4" /><label for="star4" class="text-warning">4 stars</label>
		                          <input type="radio" id="star3" name="rating" value="3" /><label for="star3" class="text-warning">3 stars</label>
		                          <input type="radio" id="star2" name="rating" value="2" /><label for="star2" class="text-warning" >2 stars</label>
		                          <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
	                          	@endif
	                          	@if($rate==5)
		                          <input type="radio" id="star5" name="rating" value="5" /><label for="star5" class="text-warning">5 stars</label>
		                          <input type="radio" id="star4" name="rating" value="4" /><label for="star4" class="text-warning">4 stars</label>
		                          <input type="radio" id="star3" name="rating" value="3" /><label for="star3" class="text-warning">3 stars</label>
		                          <input type="radio" id="star2" name="rating" value="2" /><label for="star2" class="text-warning" >2 stars</label>
		                          <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="text-warning">1 star</label>
	                          	@endif
	                          	@if($rate<1)
		                          <input type="radio" id="star5" name="rating" value="5" /><label for="star5">5 stars</label>
		                          <input type="radio" id="star4" name="rating" value="4" /><label for="star4">4 stars</label>
		                          <input type="radio" id="star3" name="rating" value="3" /><label for="star3">3 stars</label>
		                          <input type="radio" id="star2" name="rating" value="2" /><label for="star2" >2 stars</label>
		                          <input type="radio" id="star1" name="rating" value="1" /><label for="star1">1 star</label>
	                          	@endif
	                        </div>
	                       <div class="mt-4">({{$number}} đánh giá)</div>
	                      </div>
                    </div>
			<h4 class="mb-3 text-body">{{$product->name}}</h4>
			
			<h4 class="text-body">{{$size[0]->name}}</h4>
		</div>
	</div>
</div>
@endsection