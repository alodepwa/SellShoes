@component('mail::message')
# Đơn Đặt Hàng
<div>
	{{$user}} thân mến!
</div>
<div>
	Đơn hàng của bạn đã được đặt theo yêu cầu của bạn. Chi tiết sản phẩm được liệt kê bên dưới.
</div>
<div>
	Chi tiết đơn hàng
	<br>
	@foreach($product as $val)
	{{$val->name}}
	@endforeach
</div>
{{$path}}
<img src='{{asset("upImage/$path")}}' alt="">
{{asset("upImage/$path")}}
Thanks<br>
@endcomponent
