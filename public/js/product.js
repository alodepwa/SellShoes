$(document).ready(function(){

	$.ajaxSetup({

		headers:
		{
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content")
		}
	});

	$('.notificationS').hide();
	$('.notificationF').hide();

// thêm mới sản phẩm
	$(document).on('click','#save',function(e){
		$('.1').html('');
		$('.2').html('');
		$('.3').html('');
		$('.4').html('');
		$('.5').html('');
		$('.messS').html('');
		$('.messF').html('');
		e.preventDefault();
		$('.notification').show();
		$('.messE').html('');
		$.ajax({
			url: '/admin/product',
			type: 'POST',
			dataType:'json',
			data:{
				'name':$('#addProduct input[name="name"]').val(),
				'status':$('#addProduct input[name="status"]').val(),
				'price':$('#addProduct input[name="price"]').val(),
				'category_id':$('#addProduct select[name="category_id"]').val(),
				'brand_id':$('#addProduct select[name="brand_id"]').val(),
				'size_id':$('#addProduct select[name="size_id[]"]').val(),
				'quantity':$('#addProduct input[name="quantity"]').val(),
				'description':$('#addProduct textarea[name="description"]').val(),
			},
			success:function(data){
				// console.log(data);
				if(data != undefined && data.errors !=undefined){
					$('.notificationS').hide();$('.notificationF').hide();
					$.each(data.errors, function(key,value){
						switch(value.charAt(0)){
							case '1':$('.1').text(value.slice(2));
								break;
							case '2': $('.2').text(value.slice(2));
								break;
							case '3': $('.3').text(value.slice(2));
								break;
							case '4': $('.4').text(value.slice(2));
								break;
							case '5': $('.5').text(value.slice(2));
								break;
						}
					});
				}else{
					if(data['dataSuccess']!= null){
						$('.notificationS').show();
						$('.messS').html(data['dataSuccess']);
						$('.notificationF').hide();
					}else{
						$('.notificationF').show();
						$('.messF').html(data['dataFail']);
						$('.notificationS').hide();
					}
					$('#save').attr('disabled',true);
				}
			},
			error:function(error){
				$('.mess').html("ERROR!!!");
			}
		}).done(function(){
			$("#table_Cate").load(' #table_Cate');
			$("#pageAdd").load(" #pageAdd");
		});
	});  

	$('#add').click(function(){
		$(this).attr('disabled',false);
	})
	// end add


	// start delete
	$(document).on('click', '.delete_Cate', function(e){
		e.preventDefault();
		var curent =$(this);
		console.log(curent);
		if(confirm("Bạn có muốn xóa?")){
			var id=curent.attr("data-id");
			$.ajax({
				url:'/admin/product/'+id,
				type: 'DELETE',
				dataType:'json',
				data:{},
				success:function(data){
					console.log(data);
					var mess = data['message'];
					alert(mess);
					$("#table_Cate").load(" #table_Cate");
					$("#pageAdd").load(" #pageAdd");
				},
				error:function(error){
					alert("ERROR!!!");
				}
			});
		}else{
			return false;
		}
	});


		// start edit
		$('.notificationES').hide();
		$('.notificationEF').hide();
		$(document).on("click",'.editPro', function(){
			$('#save_Edit_Cate').attr('disabled',false);
			$('.notification').hide();
			$('.notificationES').hide();
			$('.notificationEF').hide();
			var id = $(this).attr("data-id");
			$.ajax({
				url:'/admin/product/editPro/'+id,
				type:'GET',
				dataType:'json',
				data:{},
				success:function(data){
					$('#formEdit input[name="name"]').val(data.data['name']);
					$('#formEdit input[name="status"]').val(data.data['status']);
					$('#formEdit input[name="price"]').val(data.data['price']);
					$('#formEdit input[name="quantity"]').val(data['quantity']);
					$('#formEdit textarea[name="description"]').val(data.data['description']);
					$('#formEdit select[name="brand_id"]').val(data.data['brand_id']);
					$('#formEdit select[name="category_id"]').val(data.data['category_id']);
				}
			});
			$('#save_Edit_Cate').on("click", function(){
				$('.mess').html('');
				$('.11').html('');
				$('.22').html('');
				$('.33').html('');
				$('.44').html('');
				$('.55').html('');
				$('.messES').html('');
				$('.messEF').html('');
				$.ajax({
					url:'/admin/product/'+id,
					type:'PUT',
					dataType:'json',
					data:{
						'id':id,
						'name':$('#formEdit input[name="name"]').val(),
						'status':$('#formEdit input[name="status"]').val(),
						'price':$('#formEdit input[name="price"]').val(),
						'category_id':$('#formEdit select[name="category_id"]').val(),
						'brand_id':$('#formEdit select[name="brand_id"]').val(),
						'size_id':$('#formEdit select[name="size_id[]"]').val(),
						'quantity':$('#formEdit input[name="quantity"]').val(),
						'description':$('#formEdit textarea[name="description"]').val(),
					},
					success:function(data){
						if(data !=undefined && data.errors != undefined){
							$.each(data.errors,function(key,value){
								switch(value.charAt(0)){
									case '1':$('.11').text(value.slice(2));
										break;
									case '2':$('.22').text(value.slice(2));
											break;
									case '3':$('.33').text(value.slice(2));
											break;
									case '4':$('.44').text(value.slice(2));
											break;
									case '5':$('.55').text(value.slice(2));
										break;
								
								}
							});
						}else{
							if(data['message']!=null){
								$('.notificationES').show();
								$('.messES').html(data['message']);
								$('.notificationEF').hide();
							}else{
								$('.notificationEF').show();
								$('.messES').html(data['messageFail']);
								$('.notificationES').hide();
							}
							$('#save_Edit_Cate').attr('disabled',true);
						}
						
						$("#table_Cate").load(' #table_Cate');
					},
					error:function(error,statusText){
						$('.mess').html("ERROR!!!");
						
					}
				});
		});

		$("#close_Edit").on("click", function(){
			$('#save_Edit_Cate').attr('disabled',false);
		});

	});

	// start updateQuantity
	$('.notificationU').hide();
	var size_id;
	$(document).on('click','.updateQuantity',function(e){
		e.preventDefault();
		$('#updateQuantity').attr('disabled',false);
		$('.111').html('');
		$('.messU').html('');
		$('.notification').hide();
		$('.notificationU').hide();
		var id =$(this).attr("data-id");
		
		$.ajax({
			url:'/admin/product/editPro/'+id,
			type:'GET',
			dataType:'json',
			data:{},
			success:function(data){
				// console.log(data);
				$('#formUpdateQuantity input[name="name"]').val(data.data['name']);
				$('#formUpdateQuantity input[name="quantity"]').val(data['quantity']);
				$('#formUpdateQuantity input[name="idPro"]').val(data.data.id);
				size_id = data['size'];
			}
		});
		
	});

	$(document).on('click','#updateQuantity',function(){
			$('.111').html('');
			$('.messU').html('');
			if(confirm('Bạn có muốn thêm số lượng?')){
			var idProduct = $('#formUpdateQuantity input[name="idPro"]').val();
			$.ajax({
				url:'/admin/product/updateQuantity',
				type:'post',
				dataType:'json',
				data:{
					'id':idProduct,
					'quantity':$('#formUpdateQuantity input[name="quantity"]').val(),
					'size_id':size_id
				},	
				success:function(data){
					// console.log(data);
					if(data != undefined && data.errors != undefined){
						// $.each(data.errors,function(key,value){
						// 	$('.111').text(value);
						// });
						$('.111').text(data.errors);
					}else{
						$('.notificationU').show();
						$('.messU').html(data['dataSuccess']);
						$('#updateQuantity').attr('disabled',true);
					}
				}
			});
			}
		});

	// start popover
	$('.hover').popover({
		content:fetchData,
		html:true,
		trigger:'hover',
		placement:'right'
	});
	function fetchData(){
		var dataShow = "";
		var id = $(this).attr('productID');
		// console.log(id);
		$.ajax({
			url:'/admin/product/popover/'+id,
			dataType:'json',
			async:false,
			type:'GET',
			data:{},
			success:function(data){
				dataShow=data;
			}
		});
		return dataShow;
	}

	// load product search
	$(document).on('keyup','#search',function(){
		var value = $(this).val();
		$.ajax({
			url:'/admin/product/searchPoduct',
			type:'post',
			dataType:'json',
			data:{
				'value':value
			},
			success:function(data){
				console.log(data);
				$('#searchProduct').fadeIn();
				$('#table_Cate').html(data);
			}
		});
	});

	// search direct product
	$(document).on('keyup','#search',function(){
		var data = $(this).val();
		$.ajax({
			url:'/admin/product/searchPoductQuickly',
			type:'post',
			dataType:'json',
			data:{
				'value':value
			},
			success:function(data){
				console.log(data);
				$('#searchProduct').fadeIn();
				$('#searchProduct').html(data);
				// $('#table_Cate').html(data);
			}
		});
	});

	$(document).on('click','li',function(){
		$('#search').val($(this).text());
		$('#searchProduct').fadeOut();

	});
});



