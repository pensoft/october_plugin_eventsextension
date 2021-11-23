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

function s2ab(s) {
		var buf = new ArrayBuffer(s.length);
		var view = new Uint8Array(buf);
		for (var i=0; i!=s.length; ++i) view[i] = s.charCodeAt(i) & 0xFF;
		return buf;
}

function onExportAttendees() {
	var checked  = $("input[name=attendee]:checked");
	var data = [];
	var eventId = null;

	$.each(checked, function(){
		data.push($(this).data("id"));
		eventId = $(this).data("eventid");
	});


	$.request('onExportAttendees', {
		data: {
			'attendee_ids': data,
			'event_id': eventId,
		},
	})
	.then(function(result, status, xmlHeaderRequest) {
        // The actual download
		var bin = window.atob(result['data']);
		var ab = s2ab(bin);
        var blob = new Blob([ab], {
            type: xmlHeaderRequest.getResponseHeader('Content-Type')
        });
		var url = window.URL || window.webkitURL;
        var link = document.createElement('a');
        link.href = url.createObjectURL(blob);
        link.download = 'export_attendees.xlsx';

        document.body.appendChild(link);

        link.click();
        document.body.removeChild(link);
	});

	
	// bkp
	// $.request('onExportAttendees', {
	// 	data: {
	// 		'attendees': data,
	// 	},
	// })
	// .then(function(result) {
	// 	var blob = new Blob(
	// 		[result],
	// 		// {type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,"}
	// 		{type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,"}
	// 	);
	// 	// Programatically create a link and click it:
	// 	var a = document.createElement("a");
	// 	a.href = URL.createObjectURL(blob);
	// 	a.download = 'data.xlsx';
	// 	document.body.appendChild(a);
	// 	a.click();
	// 	document.body.removeChild(a);
	// });
}


function onLoadEditFieldForm(pAnswerId, pAnswerValue, pOrderQuestionData) {
	$.request('onLoadEditFieldForm', {
		update: { '@_popup-edit-field-form': '#popupEditFieldForm',
		},
		data: {
			'answer_id': pAnswerId,
			'answer_value': pAnswerValue,
			'order_question_data': pOrderQuestionData,
		},
	}).then(response => {
		$('#popupEditFieldForm').modal('show');
	});
}
