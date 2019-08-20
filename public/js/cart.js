$(document).ready(function(){
	$.ajaxSetup({
		headers:{
			'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
		}

	});
	// load giá thay đổi theo sl
	$(document).on('click','button[type="button"]',function(){
		var value = $(this).attr('data-click');
		var size = $(this).attr('data-size');
		var value2 = $('.'+value+'and'+size).val();
		$.ajax({
			url:'/user/showPrice',
			type:'post',
			dataType:'json',
			data:{
				'id':value,
				'quantity':value2
			},
			success:function(data){
				$('.'+value+'and'+size+'money').html(data);
				var total=0;
				$('#tableCart tr').each(function(){
					$(this).find('.total').each(function(){
						var price = $(this).text();
						if(price.length !=0){
							total+=parseFloat(price);
						}
					});
				});
				 $('#total').html(total+' VNĐ');
				var price=[];
				var quantity=[];
				$('#tableCart tbody tr').each(function(){
					$(this).find('input[type="text"]').each(function(){
						quantity+=$(this).val()+";";
					});
					$(this).find('.total').each(function(){
						price +=$(this).text()+";";
					});
				});
				var total = $('#total').text();
				$('form input[name="quantity"]').val(quantity);
				$('form input[name="total"]').val(total);
				$('form input[name="size"]').val(price);
			}
		});
		var price = $('.'+value+'price').text();
		var money= $('.'+value+'money').text();
		var total = price*value2;
	});

	//xóa sản phẩm khỏi giỏ hàng
	$(document).on('click','.delete',function(){
		var value = $(this).attr('data-id');
		if(confirm('Bạn có muốn xóa?')){
			$.ajax({
				url:'/user/cartDetail',
				type:'post',
				dataType:'json',
				data:{
					'id':value,
				},
				success:function(data){
					$('.count').html(data);
					$('table').load(' table');
					var total=0;
					$('#tableCart tr').each(function(){
						$(this).find('.total').each(function(){
							var price = $(this).text();
							if(price.length !=0){
								total+=parseFloat(price);
							}
						});
					});
					 $('#total').html(total+' VNĐ');
				}
			});
		}
		else{
			return false;
		}
	});


	//tính tổng total
		var total=0;
		$('#tableCart tr').each(function(){
			$(this).find('.total').each(function(){
				var price = $(this).text();
				if(price.length !=0){
					total+=parseFloat(price);
				}
			});
		});
		$('#total').html(total+' VNĐ');

	//checkout
	$('#checkout').on('click',function(){
		

		
		// $.ajax({
		// 	url:'/user/checkout',
		// 	type:'post',
		// 	data:{
		// 		'nameProduct':nameProduct,
		// 		'size':size,
		// 		'quantity':quantity
		// 	},success:function(data){
		// 		console.log(data);
		// 		location.reload();
		// 	}
		// });
	});


		var nameProduct=[];
		var price=[];
		var quantity=[];
		var size=[];
		var productID=[];
		$('#tableCart tbody tr').each(function(){
			$(this).find('.nameProduct').each(function(){
				nameProduct += $(this).text()+";";
			});
			$(this).find('.price').each(function(){
				price +=$(this).text()+";";
			});
			$(this).find('.size').each(function(){
				size +=$(this).text()+";";
			});
			$(this).find('.nameProduct').each(function(){
				productID +=$(this).attr("data-id")+";";
			});
			$(this).find('input[type="text"]').each(function(){
				quantity+=$(this).val()+";";
			});
			
		});
		var total = $('#total').text();
		$('form input[name="size"]').val(price);
		$('form input[name="nameProduct"]').val(nameProduct);
		$('form input[name="quantity"]').val(quantity);
		$('form input[name="total"]').val(total);
		$('form input[name="sizeAll"]').val(size);
		$('form input[name="productID"]').val(productID);

		
	// user order
	$('.err').hide();
	$(document).on('click','#order',function(){
		var check = $('input[type="checkbox"]:checked').val();
		if(check==1){
			var name = $('input[name="dname"]').val();
			var tel = $('input[name="dtel"]').val();
			var email = $('input[name="demail"]').val();
			var address = $('input[name="daddress"]').val();
		}else{
			var name = $('input[name="name"]').val();
			var tel = $('input[name="tel"]').val();
			var email = $('input[name="email"]').val();
			var address = $('input[name="address"]').val();
		}
		var size=[];
		var productID=[];
		var quantity=[];
		$('table tr').each(function(){
			$(this).find('.name').each(function(){
				size+=$(this).attr('data-size')+';';
			});
			$(this).find('.name').each(function(){
				productID+=$(this).attr('data-product')+';';
			});
			$(this).find('.name').each(function(){
				quantity+=$(this).attr('data-number')+';';
			});
		});
		$.ajax({
			url:'/user/order',
			dataType:'json',
			type:'POST',
			data:{
				'name':name,
				'tel':tel,
				'email':email,
				'address':address,
				'size':size,
				'productID':productID,
				'quantity':quantity
			},success:function(data){
				console.log(data);
				if(data != undefined && data.errors != undefined){
					$.each(data.errors, function(key,value){
						$('.err').show();
						$('.err').append(value+'<br>');
					});
				}
				if(data != undefined){
					$('.count').html('0');
					alert(data);
				}
			}
		});
	});

});
