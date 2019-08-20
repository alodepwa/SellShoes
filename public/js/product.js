$(document).ready(function(){

	$.ajaxSetup({

		headers:
		{
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content")
		}
	});

	$('.notificationS').hide();
	$('.notificationE').hide();

	$(document).on('click','#save',function(e){
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
				if(data != undefined && data.errors !=undefined){
					$.each(data.errors, function(key,value){
						$('.notificationE').show();
						$('.messE').append(value+'<br>');
					});
				}else{
					$('.notificationE').hide();
					alert(data['dataSuccess']);
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
		$(document).on("click",'.editPro', function(){
			$('.notification').hide();
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
								$('.notification').show();
								$('.mess').append('<p>'+value+'</p>');
							});
						}else{
							$('.notification').hide();
							alert(data['message']);
						}
						$("#table_Cate").load(' #table_Cate');
					},
					error:function(error,statusText){
						$('.mess').html("ERROR!!!");
						
					}
				});
		});

		$("#close_Edit").on("click", function(){
			id=null;
		});

	});

	// start updateQuantity
	$(document).on('click','.updateQuantity',function(){
		$('.notification').hide();
		var id =$(this).attr("data-id");
		var size_id;
		$.ajax({
			url:'/admin/product/editPro/'+id,
			type:'GET',
			dataType:'json',
			data:{},
			success:function(data){
				$('#formUpdateQuantity input[name="name"]').val(data.data['name']);
				$('#formUpdateQuantity input[name="quantity"]').val(data['quantity']);
				size_id = data['size'];
			}
		});
		$('#updateQuantity').on('click',function(){
			if(confirm('Bạn có muốn thêm số lượng?')){
			$.ajax({
				url:'/admin/product/updateQuantity',
				type:'post',
				dataType:'json',
				data:{
					'id':id,
					'quantity':$('#formUpdateQuantity input[name="quantity"]').val(),
					'size_id':size_id
				},	
				success:function(data){
					console.log(data);
					if(data != undefined && data.errors != undefined){
						$.each(data.errors,function(key,value){
							$('.notification').hide();
							$('.mess').append('<p>'+value+'</p>')
						});
					}else{
						alert(data['dataSuccess']);
					}
				}
			});
			}
		});
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
		console.log(id);
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



