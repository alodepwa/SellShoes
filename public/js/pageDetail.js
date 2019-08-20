$(document).ready(function(){

	
	$.ajaxSetup({
		headers:{
			'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
		}

	});

	// load giá thay đổi theo sl
	$(document).on('click','button[type="button"]',function(e){
		e.preventDefault();
		var value = $('#increase').val();
		var id = $('#increase').attr('data-id');
		$.ajax({
			url:'/user/showPrice',
			type:'post',
			dataType:'json',
			data:{
				'id':id,
				'quantity': value
			},
			success:function(data){
				$('#price').html(data);
			}
		});
	});


	// Start add to cart

	$(document).on('click','#addToCart',function(e){
			e.preventDefault();
			$('.mess').html('');
			var id = $('#increase').attr('data-id');
			var quantity = $('input[type="text"]').val();
			var nameProduct = $('.nameProduct').text();
			var price = $('#price').text();
			var size = $('input[name="size"]:checked').val();
			if(size != null){
				$.ajax({
					url:'/user/cartShopping',
					type:'POST',
					dataType:'json',
					data:{
						'id':id,
						'quantity':quantity,
						'product':nameProduct,
						'size':size
					},
					success:function(data){
						console.log(data);
						$('.count').html(data);
						alert('Đã thêm vào giỏ hàng');
					},
					error:function(jqXHR){
						if(jqXHR.status==404){
							alert('bạn cần đăng nhập mới có thể mua hàng');
						}
					}
				});
			}else{
				$('.mess').html('! Bạn cần chọn size trước khi mua hàng');
			}
	});
});
