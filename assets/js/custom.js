$(document).ready(function(){
	$("#checkAll").change(function () {
		$("input[name=attendee]").prop('checked', $(this).prop("checked"));
		if($("input[name=attendee]:checked").length){
			$('.emailAndExport').show();
		}else{
			$('.emailAndExport').hide();
		}
	});
	$("input[name=attendee]").change(function () {

		if($("input[name=attendee]:checked").length){
			$('.emailAndExport').show();
		}else{
			$('.emailAndExport').hide();
		}
	});


})


function onLoadEmailForm() {
	var checked  = $("input[name=attendee]:checked");
	var data = [];

	$.each(checked, function(){
		data.push($(this).data("record"));
	});

	$.request('onLoadEmailForm', {
		update: { '@_popup-email-form': '#popupEmailForm',
		},
		data: {
			'attendees': data,
		},
	}).then(response => {
		$('#popupEmailForm').modal('show');
	});
}