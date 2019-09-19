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
		h2{
			color: red;
			font-weight: bold;
		}
	</style>
</head>
<body>
	<h2>THÔNG BÁO ĐẶT HÀNG THÀNH CÔNG</h2>
	<div>
		{{$user}} thân mến!
	</div>
	<div>
		Đơn hàng của bạn đã được đặt theo yêu cầu của bạn.
	</div>
	<div class="chitiet">
		Chi tiết sản phẩm được liệt kê bên dưới.
	</div>
	<div>
		<?php 
			$count = count($product);

		 ?>
		@for($i=0;$i<$count;$i++)
		<?php 
			$pathImg= $path[$i][0]->path
		 ?>
		<div class="anh">
			<img src='{{$message->embed("upImage/$pathImg")}}' alt="">
			<div class="chu">
				<div class="chunho">Tên SP: {{$product[$i]->name}}</div>
				<div class="chunho">Size: {{$size[$i][0]->name}}</div>
				<div class="chunho">Giá tiền: {{$price[$i]*$soluong[$i]}} đ</div>
				<div class="chunho">Số lượng: {{$soluong[$i]}}</div>
			</div>
		</div>
		@endfor
	</div>
	
</body>
</html>