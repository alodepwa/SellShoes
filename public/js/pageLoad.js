$(document).ready(function(){

	$.ajaxSetup({

		headers: {

			'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
		}

	});


	// start search products
	$(document).on('keyup','#search',function(e){
		e.preventDefault();
		var value = $(this).val();
		var delay = 100;
		if(value.length>3){
			$.ajax({
			url:'/user/search',
			type:"post",
			dataType:'json',
			data:{
				'value':value
			},
			success:function(data){
				setTimeout(function(){
					$('#showProduct').html(data);
					$('#pageAdd').html('');
				},delay);
				
			},
			error:function(jqXHR,error, errorThrown){
				if(jqXHR.status == 400){
					setTimeout(function(){
						$('#pageAdd').html('');
						$('#showProduct').html('Không tìm thấy sản phẩm');
					},delay);
				}
			}
			});
		}if(value.length==0){
			$.ajax({
			url:'/user/search',
			type:"post",
			dataType:'json',
			data:{
				'value':''
			},
			success:function(data){
				console.log(data);
				$('#showProduct').html(data);
				$('#pageAdd').load(' #pageAdd');
			}
			});
		}
	});

	// search category
	$(document).on('click','.category',function(e){
		e.preventDefault();
		var id = $(this).attr('data-id');
		console.log(id);
		$.ajax({
			url:'/user/searchCategory/'+id,
			type:'get',
			dataType:'json',
			success:function(data){
				$('#showProduct').html(data);
				$('#pageAdd').html('');
			}
		});
	});


	

	

	// search size
	$(document).on('click','.size',function(){
		var id =$('input[name="checkSize"]:checked').attr('data-id');
		console.log(id);
		$.ajax({
			url:'/user/searchSize/'+id,
			type:'get',
			dataType:'json',
			success:function(data){
				$('#showProduct').html(data);
			}
		});
	});




	//filter prices
	$('#sliderRange').slider({
		range:true,
		min:100000,
		max:1000000,
		values:[100000,1000000],
		slide:function(event,ui){
			$('#amountStart').val(ui.values[0]);
			$('#amountEnd').val(ui.values[1]);
			$('#amount').text('đ'+$('#sliderRange').slider('values',0)+"- đ"+$('#sliderRange').slider('values',1));
			var min = ui.values[0];
			var max = ui.values[1];
			// setTimeout(function(){
				console.log(min);

				// load_product($('#amountStart').val(), $('#amountEnd').val());
				load_product(min,max);
			// },3000);
			
		}

	});

	$('#amount').text('đ'+$('#sliderRange').slider('values',0)+"- đ"+$('#sliderRange').slider('values',1));

	function load_product(min,max){
			$.ajax({
			url:'/user/filterPrice',
			type:'post',
			dataType:'json',
			data:{
				min:min,
				max:max
			},
			success:function(data){
				console.log(data);
				setTimeout(function(){
					$('#showProduct').html(data);
				},500);
			},
			error:function(jqXHR){
				if(jqXHR.status==400){
					$('#showProduct').html('Không tìm thấy sản phẩm trong tầm giá đó');
				}
			}
		});
		
	}

});