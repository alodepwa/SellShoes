$(document).ready(function(){

	$.ajaxSetup({

		headers:
		{
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content")
		}
	});

	$('.notificationS').hide();
	$('.notificationF').hide();

	// start add promotion
	$('#save').click(function(e){
		e.preventDefault();
		$('.mess').html('');
		// $('.notification').show();
		$('.1').html('');
		$('.2').html('');
		$('.3').html('');
		$('.4').html('');
		var unit = $('#addPromotion input[name="unit"]').val();
		
			$.ajax({
				url:'/admin/promotion',
				type:"POST",
				dataType:'json',
				data:{
					'name':$('#addPromotion input[name="name"]').val(),
					'unit':unit,
					'end':$('#addPromotion input[name="end"]').val(),
					'start':$('#addPromotion input[name="start"]').val(),
					'product_id':$('#addPromotion select[name="product_id"]').val(),
				},
				success:function(data){
					console.log(data);
					if(data != undefined && data.errors != undefined){
						$.each(data.errors,function(key,value){
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
							
						});
					}else{
						if(data['dataSuccess']!=null){
							$('.notificationS').show();
							$('.mess').html(data['dataSuccess']);
							$('.notificationF').hide();
						}else{
							$('.notificationF').show();
							$('.messF').html(data['dataFail']);
							$('.notificationS').hide();
						}
						$("#table_Cate").load(' #table_Cate');
						$("#pageAdd").load(" #pageAdd");
					}
				},
				error:function(error){
					$('.mess').html("ERROR!!!");
				}
			});
	});  

	$(document).on('click','#add',function(){
		$('.notificationF').hide();
		$('.notificationS').hide();
	});
	// end add


	// start delte promotion
	$(document).on('click','.delete_Cate',function(e){
		e.preventDefault();
		var curent =$(this);
		console.log(curent);
		if(confirm("Bạn có muốn xóa?")){
			var id=curent.attr("data-id");
			$.ajax({
				url:'/admin/promotion/'+id,
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
			id=null;
			console.log("id"+id);
		};
	});

	// start update promotion
	$('.notificationES').hide();
	$('.notificationEF').hide();
	$(document).on('click','.edit_Cate', function(){
		$('.notificationES').hide();
		$('.notificationEF').hide();
		$('#saveEditPromotion').attr('disabled',false);
		var id = $(this).attr("data-id");
		console.log(id);
		var name = $(this).attr("data-name");
		$.ajax({
			url:'/admin/promotion/show/'+id,
			type:'GET',
			dataType:'json',
			data:{},
			success:function(data){
				$('#editPromotion input[name="name"]').val(data['name']);
				$('#editPromotion input[name="unit"]').val(data['unit']);
				$('#editPromotion input[name="start"]').val(data['start']);
				$('#editPromotion input[name="end"]').val(data['end']);
				$('#editPromotion select[name="product_id"]').val(data['product_id']);
			}
		});

		$('#saveEditPromotion').on("click", function(){
			$('.11').html('');
			$('.22').html('');
			$('.33').html('');
			$('.44').html('');
			$.ajax({
				url:'/admin/promotion/'+id,
				type:'PUT',
				dataType:'json',
				data:{
					'name':$('#editPromotion input[name="name"]').val(),
					'code':$('#editPromotion input[name="code"]').val(),
					'unit':$('#editPromotion input[name="unit"]').val(),
					'end':$('#editPromotion input[name="end"]').val(),
					'start':$('#editPromotion input[name="start"]').val(),
					'product_id':$('#editPromotion select[name="product_id"]').val(),
				},
				success:function(data){
					if(data !=undefined && data.errors !=undefined){
						$.each(data.errors, function(key,value){
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
						});
					}
					else{
						if(data['message']!=null){
							$('.notificationES').show();
							$('.messES').html(data['message']);
							$('notificationEF').hide();
							$('#saveEditPromotion').attr('disabled','disabled');
						}else{
							$('.notificationEF').show();
							$('.messEF').html(data['messageFail']);
							$('notificationES').hide();
						}
						$("#table_Cate").load(' #table_Cate');
					}
				}
			});
		});
	});


	// start hover show info
	$('.hover').popover({
		content:ShowInfo,
		html:true,
		trigger:'hover',
		placement:'right'
	});
	function ShowInfo(){
		var dataShow ="";
		var id = $(this).attr('promotionId');
		console.log(id);
		$.ajax({
			url:'/admin/promotion/ShowInfo/'+id,
			type:'GET',
			dataType:'json',
			async:false,
			data:{},
			success:function(data){
				dataShow+='<p><label>Code: '+data['code']+'</label><p>';
				dataShow+='<p><label>unit: '+data['unit']+'</label><p>';
				dataShow+='<p><label>Start: '+data['start']+'</label><p>';
				dataShow+='<p><label>End: '+data['end']+'</label><p>';
			}
		});
		return dataShow;
	}
});
