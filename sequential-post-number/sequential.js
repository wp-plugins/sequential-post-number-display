jQuery(document).ready(function($) {
	if($(".pagechkbox").length == $(".pagechkbox:checked").length) {
		$("#page").html("Deselect All");
		$("#chkAllPages").attr("checked", "checked");
	} else {
		$("#page").html("Select All");
		$("#chkAllPages").removeAttr("checked");
	}
	$("#chkAllPages").click(function () {
		if($("#page").html() == 'Select All') {
			$('.pagechkbox').attr('checked', this.checked);
			$("#page").html('Deselect All');
		}else {
			$(".pagechkbox").removeAttr("checked");
			$("#page").html('Select All');
		}
	});
	
	$(".pagechkbox").click(function(){
		if($(".pagechkbox").length == $(".pagechkbox:checked").length) {
			$("#chkAllPages").attr("checked", "checked");
			$("#page").html("Deselect All");
		}else {
			$("#chkAllPages").removeAttr("checked");
			$("#page").html("Select All");
		}
	});
});

jQuery(document).ready(function($) {
	if($(".catchkbox").length == $(".catchkbox:checked").length) {
		$("#cat").html("Deselect All");
		$("#chkAllCatFiles").attr("checked", "checked");
	} else {
		$("#cat").html("Select All");
		$("#chkAllCatFiles").removeAttr("checked");
	}
	$("#chkAllCatFiles").click(function () {
		if($("#cat").html() == 'Select All') {
			$('.catchkbox').attr('checked', this.checked);
			$("#cat").html('Deselect All');
		}else {
			$(".catchkbox").removeAttr("checked");
			$("#cat").html('Select All');
		}
	});
	
	$(".catchkbox").click(function(){
		if($(".catchkbox").length == $(".catchkbox:checked").length) {
			$("#chkAllCatFiles").attr("checked", "checked");
			$("#cat").html("Deselect All");
		}else {
			$("#chkAllCatFiles").removeAttr("checked");
			$("#cat").html("Select All");
		}
	});
});

jQuery(document).ready(function($) {
	if($(".tagchkbox").length == $(".tagchkbox:checked").length) {
		$("#tag").html("Deselect All");
		$("#chkAllTagFiles").attr("checked", "checked");
	} else {
		$("#tag").html("Select All");
		$("#chkAllTagFiles").removeAttr("checked");
	}
	$("#chkAllTagFiles").click(function () {
		if($("#tag").html() == 'Select All') {
			$('.tagchkbox').attr('checked', this.checked);
			$("#tag").html('Deselect All');
		}else {
			$(".tagchkbox").removeAttr("checked");
			$("#tag").html('Select All');
		}
	});
	
	$(".tagchkbox").click(function(){
		if($(".tagchkbox").length == $(".tagchkbox:checked").length) {
			$("#chkAllTagFiles").attr("checked", "checked");
			$("#tag").html("Deselect All");
		}else {
			$("#chkAllTagFiles").removeAttr("checked");
			$("#tag").html("Select All");
		}
	
	});
});

jQuery(document).ready(function($) {
	$(".blogPage").click(function() {
		if($(this).attr('checked')) { 
			$(".blogPage").attr("checked", "checked");
		} else {
			$(".blogPage").removeAttr("checked");	
		}
	});
});