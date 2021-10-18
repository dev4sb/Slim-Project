$(document).ready(function(){
	image();
});



$(document).ready(function(){
	$("#showimg").click(function(){
		image();
	});
	
});

function image(){
		$.ajax({
			type:'GET',
			url:'api/index.php/curl',
			success:function(data,textStatus){
				console.log(data.length);

				var res=$.parseJSON(data);
				jQuery.each(res, function(index, value){
				// console.log(res[index]);
				// console.log(res.message);
				$('#ImageView').html('<img src=' + res.message + ' class="img-fluid" alt="image loading...."  width="400px" height="400px">');
			});
			},
			error:function(error){
				console.log(error);
			}
		});
}