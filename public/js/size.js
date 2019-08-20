$(document).ready(function(){

	$.ajaxSetup({

		headers:
		{
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content")
		}
	});

	$('.notificationS').hide();
	$('.notificationE').hide();

	$('#save').click(function(e){
		$.ajax({
			url:'/admin/size',
			type:"POST",
			dataType:'json',
			data:{
				'name':$('input[name="name"]').val()
			},
			success:function(data){
				console.log(data);
				if(data != undefined && data.errors !=undefined ){
					$.each(data['errors'], function(key, value){
						$('.notificationE').show();
						$('.notificationS').hide();
						$('.messE').append(value);
					});
				}
				if(data != undefined && data.datasuccess != undefined){
					$.each(data['datasuccess'], function(key, value){
						$('.notificationS').hide();
						$('.notificationE').hide();
					});
				}
				$("#table_Cate").load(' #table_Cate');
				$("#pageAdd").load(" #pageAdd");
			},
			error:function(error,data){
				console.log(data);
				$('.mess').html("ERROR!!!");
			}
		});
	});  
	// end add



	$(document).on('click', '.delete_Cate',function(e){
		e.preventDefault();
		var curent =$(this);
		console.log(curent);
		if(confirm("Bạn có muốn xóa?")){
			var id=curent.attr("data-id");
			$.ajax({
				url:'/admin/size/'+id,
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

	// end delete
	



		$(document).on('click','.edit_Cate' ,function(){
			var id = $(this).attr("data-id");
			var name = $(this).attr("data-name");
			$('.notification').hide();
			$('input[name="name"]').val(name);
			$('#save_Edit_Cate').on("click", function(){
				$.ajax({
					url:'/admin/size/'+id,
					type:'PUT',
					dataType:'json',
					data:{
						'name': $('#ediCate').val(),
					},
					success:function(data){
						console.log(data);
						if(data != undefined && data.errors !=undefined){
							$.each(data['errors'],function(key, value){
								console.log(value)
								$('.notificationE').show();
								$('.notificationS').hide();
								$('.messE').append(value);
							});
						}
						if(data != undefined && data.datasuccess !=undefined){
							$.each(data['datasuccess'],function(key, value){
								$('.notificationS').show();
								$('.notificationE').hide();
								$('.messS').append(value);
							});
							$("#table_Cate").load(' #table_Cate');
						}
					}
				});
		});

		$("#close_Edit").on("click", function(){
			id=null;
		});
	});
});

