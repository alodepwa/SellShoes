$(document).ready(function(){
	$.ajaxSetup({
		headers:{
			'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
		}

	});
	
	function numberFormat(number){
		var dem=1;
		var newNumber=0;
		for (var i = number.length; i < number.length; i-3) {
			// dem++;
			// if(dem==3){
			// 	newNumber = ','+number[i];
			// }
			newNumber = ',' + number[i];
		}
		console.log(newNumber);
	}

	numberFormat('12003');
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
							console.log(price.valueOf());

							total+=parseFloat(price.valueOf());
							// total+=Number(price.valueOf());
						}
					});
					return total;
				});
				// $('#total').html(total+' VNĐ');
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
		console.log(value);
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
				// console.log(price);
				if(price.length !=0){
					total+=parseFloat(price,10);
				}
			});
		});
		// $('#total').html(total+' VNĐ');




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
		$('.1').html('');
		$('.2').html('');
		$('.3').html('');
		$('.4').html('');
		$('.11').html('');
		$('.22').html('');
		$('.33').html('');
		$('.44').html('');
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
						if(check==1){
							switch(value.charAt(0)){
							case '1':$('.11').text(value.slice(2));
								break;
							case '2':$('.22').text(value.slice(2));
								break;
							case '3':$('.33').text(value.slice(2));
								break;
							case '4':$('.44').text(value.slice(2));
								break;
							}
						}else{
							switch(value.charAt(0)){
							case '1':$('.1').text(value.slice(2));
								break;
							case '2':$('.2').text(value.slice(2));
								break;
							case '3':$('.3').text(value.slice(2));
								break;
							case '4':$('.4').text(value.slice(2));
								break;
							}
						}
					});
				}
				else{
					$('#order').attr('disabled','disabled');
					$('#order').text('Bạn đã đặt hàng thành công!');
					$('.count').html('0');
				}
			}
		});
	});


	// cancle order user
	$(document).on('click','.cancleOrder',function(){
		if(confirm('Bạn có muốn hủy đơn hàng này')){
			var id = $(this).attr('data-id');
			$.ajax({
				url:'/user/cancelOrder/'+id,
				dataType:'json',
				method:'GET',
				success:function(data){
					alert(data['success']);
					$('#autoload').load(' #autoload');
				}
			});
		}
	});

});
