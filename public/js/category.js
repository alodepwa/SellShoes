$(document).ready(function(){
	// fetchData();
	$.ajaxSetup({

		headers:
		{
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content")
		}
	});


	$(document).on('click','#add',function(){
		$('.notificationF').hide();
		$('.notification').hide();
	});
	

	// start add category
	$(document).on('click','#save',function(e){
		$('.mess').html('');
		$('.messF').html('');
		e.preventDefault();
		$('.notification').show();
		$.ajax({
			url:'/admin/category',
			type:"POST",
			dataType:'json',
			data:{
				'name':$('input[name="name"]').val()
			},
			success:function(data){
				console.log(data);
				if(data != undefined && data.errors != undefined){
					$.each(data.errors,function(key,value){
						$('.notificationF').show();
						$('.messF').html(value);
						$('.notification').hide();
					});
				}else{
					if(data.dataFail!=null){
						$('.notificationF').show();
						$('.messF').append(data['dataFail']);
						$('.notification').hide();
					}else{
						$('.notification').show();
						$('.mess').append(data['dataSuccess']);
						$('.notificationF').hide();
					}
					
					// alert(data['dataSuccess']);
				}
			},
			error:function(error){
				$('.mess').html("ERROR!!!");
			}
		})
		.done(function(){
			$("#table_Cate").load(' #table_Cate');
			$("#pageAdd").load(" #pageAdd");
		});
	});  
	// end add

	// start delete category
	$(document).on('click','.delete_Cate',function(e){
		e.preventDefault();
		var curent =$(this);
		if(confirm("Bạn có muốn xóa?")){
			var id=curent.attr("data-id");
			$.ajax({
				url:'/admin/category/'+id,
				type: 'DELETE',
				dataType:'json',
				data:{},
				success:function(data){
					var mess = data['message'];
					alert(mess);
					$("#table_Cate").load(" #table_Cate");
					$("#pageAdd").load(" #pageAdd");
				},
				error:function(error){
					alert("ERROR!!!");
				}
			});
		}
	});

		// start edit category
		$(document).on("click",'.edit_Cate', function(){
			$('.notification').hide();
			$('.mess').html('');
			var id = $(this).attr("data-id");
			var name = $(this).attr("data-name");
			$('input[name="name"]').val(name);
			$(document).on("click",'#save_Edit_Cate', function(){
				$('.mess').html('');
				$.ajax({
					url:'/admin/category/'+id,
					type:'PUT',
					dataType:'json',
					data:{
						'name': $('#ediCate').val(),
					},
					success:function(data){
						if(data !=undefined && data.errors !=undefined){
							$.each(data.errors,function(key,value){
								$('.notification').show();
								$('.mess').append(value);
							});
						}else{
							$('.notification').show();
							$('.mess').append(data['dataSuccess']);
							// alert(data['dataSuccess']);
						}
						$("#table_Cate").load(' #table_Cate');
					}
				});
		});

	});

});

