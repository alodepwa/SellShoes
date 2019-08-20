$(document).ready(function(){

	$.ajaxSetup({

		headers:
		{
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content")
		}
	});

	$('.notification').hide();

	// start add promotion
	$('#save').click(function(e){
		e.preventDefault();
		$('.mess').html('');
		// $('.notification').show();
		var unit = $('#addPromotion input[name="unit"]').val();
		if(unit <=100 && unit >= 0){
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
							$('.notification').show();
							$('.mess').append(value+'<br>');
						});
					}else{
						$('.notification').hide();
						alert(data['dataSuccess']);
						$("#table_Cate").load(' #table_Cate');
				$("#pageAdd").load(" #pageAdd");
					}
				},
				error:function(error){
					$('.mess').html("ERROR!!!");
				}
			});
		}else{
			$('.notification').show();
			$('.mess').append('Unit cần bé hơn 100');
		}
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
	$(document).on('click','.edit_Cate', function(){

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
							$('.notification').show();
							$('.mess').append(value+'<br>');
						});
					}
					else{
						alert(data['message']);
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
