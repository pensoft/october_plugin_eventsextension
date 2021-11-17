$(document).ready(function(){
	$("#checkAll").change(function () {
		var checked = $(this).is(':checked'); // Checkbox state
		// Select all
		if(checked){
			$('input[name=attendee]').each(function() {
				$(this).prop('checked',true);
			});
			$('.emailAndExport').show();
		}else{
			// Deselect All
			$('input[name=attendee]').each(function() {
				$(this).prop('checked',false);
			});
			$('.emailAndExport').hide();
		}
	});
	$("input[name=attendee]").change(function () {

		if($("input[name=attendee]:checked").length){
			$('.emailAndExport').show();
		}else{
			$('.emailAndExport').hide();
		}

		// When total options equals to total selected option
		if($("input[name=attendee]").length == $("input[name=attendee]:checked").length) {
			$("#checkAll").prop("checked", true);
		} else {
			$("#checkAll").prop("checked", false);
		}
	});


})


function onLoadEmailForm() {
	var checked  = $("input[name=attendee]:checked");
	var data = [];

	$.each(checked, function(){
		data.push($(this).data("email"));
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


function onLoadEditFieldForm(pAnswerId, pAnswerValue, pOrderQuestionId, pFieldType, pOrderQuestionData) {
	$.request('onLoadEditFieldForm', {
		update: { '@_popup-edit-field-form': '#popupEditFieldForm',
		},
		data: {
			'answer_id': pAnswerId,
			'answer_value': pAnswerValue,
			'order_question_id': pOrderQuestionId,
			'field_type': pFieldType,
			'order_question_data': pOrderQuestionData,
		},
	}).then(response => {
		$('#popupEditFieldForm').modal('show');
	});
}