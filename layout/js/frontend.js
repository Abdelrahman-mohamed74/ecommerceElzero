$(function(){

	//Placeholder

	$('[placeholder]').focus(function(){
		
	$(this).attr("data-place",$(this).attr("placeholder"));
	$(this).attr("placeholder"," ");

	}).blur(function(){

	$(this).attr("placeholder",$(this).attr("data-place"));

	});

	// Switch between login and signup

	$(".form h3 span").on("click",function (){

		$(this).addClass("active").siblings().removeClass("active");

		$(".form form").hide();

		$('.'+ $(this).data("class")).fadeIn(800);
	})


	// Asterisk

	$("input").each(function(){
		if ($(this).attr("required") === "required") {
			$(this).after("<span class='asterisk'>*</span>");
		}
	});

	//show and hide Password with icon

	$("#show-hide").on('click',function(){
		$eye =$(this).attr('class');

		if ($eye == 'far fa-eye-slash') {

			$(this).attr('class','fas fa-eye');
			$("#input-eye").attr('type','password');

		}else{
			$(this).attr('class','far fa-eye-slash');
			$("#input-eye").attr('type','text');
		}
	});

	// confirmation message on button

	$(".confirm").on('click',function(){

		return confirm("Are You Sure ?");
	});

	/////

	$(".live").keyup(function(){
		
		$($(this).data("class")).text($(this).val());

	});








});