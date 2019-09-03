<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<link rel="stylesheet" href="/css/orderMail.css">
	<style>
		*{
			margin: 0px;
			padding: 0px;
		}
		.anh{
			display: flex;
			margin-bottom: 40px;
		}
		.chu{
			width: 300px;
			margin-left: 20px;
		}
		img{
			width: 150px;
			height: 150px;
		}
		.chunho{
			width: 100%;
			margin-bottom: 10px;
		}
		.chitiet{
			margin: 20px;
		}
	</style>
</head>
<body>
	<div>
		{{$order->name}} thân mến!
	</div>
	<div>
		Đơn hàng của bạn đã hủy.
	</div>
	<div class="chitiet">
		Chi tiết sản phẩm được liệt kê bên dưới.
	</div>
	<div>
		<?php 
			$product = $order->products;
			$pivot = $product[0]->pivot;
			$nameProduct = $product[0]->name;
			$price = $pivot->price;
			$quantity = $pivot->quantity;
		 ?>
		<div class="anh">
			<img src='{{$message->embed("upImage/$path")}}' alt="">
			<div class="chu">
				<div class="chunho">Tên SP: {{$nameProduct}}</div>
				<div class="chunho">Giá tiền:{{$price*$quantity}} đ</div>
				<div class="chunho">Số lượng: {{$quantity}}</div>
			</div>
		</div>
	</div>
	
</body>
</html>