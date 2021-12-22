$(function(){

	//Placeholder

	$('[placeholder]').focus(function(){
		
	$(this).attr("data-place",$(this).attr("placeholder"));
	$(this).attr("placeholder"," ");

	}).blur(function(){

	$(this).attr("placeholder",$(this).attr("data-place"));
	});


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

	// Category view Option

	$(".categories h3").on("click",function(){

		$(this).next(".full-view").slideToggle();
	});

	$(".option span").on("click",function(){

		$(this).addClass("active").siblings("span").removeClass("active");

		if ($(this).data("view") == "full") {

			$(".categories .full-view").slideDown();
		}else{
			$(".categories .full-view").slideUp();
		}
	});

	// child category

	$(".child-link").hover(function(){
		$(this).find(".show-delete").fadeIn();
	}).mouseleave(function(){
		$(this).find(".show-delete").fadeOut();
	})









});