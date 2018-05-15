$('.newProviderModal').modal({
	ready: function(modal, trigger) { // Callback for Modal open. Modal and trigger parameters available.
	    if(trigger.hasClass('newProviderFromProvision')){
	    	var index_file_id = trigger.parent().parent().parent().attr('data-file-index');
	    	$('#provider_name').val(jsonFilesData[index_file_id].emisor.nombreEmisor);
	    	$('label[for="provider_name"]').addClass('active');
	    	$('#provider_rfc').val(jsonFilesData[index_file_id].emisor.rfcEmisor);
	    	$('label[for="provider_rfc"]').addClass('active');
	    }
	},
});
$('.updateProviderModal').modal();
$('.deleteProviderModal').modal();

$('.selectNew').on('change', function(event) {
	$('.selectNew').material_select('destroy');
	$('.selectNew').material_select();
	$('.selectNew').val($(this).val());
	$('.selectNew option[value="'+$(this).val()+'"]').attr("selected", "selected");
});

$('.selectUpdate').on('change', function(event) {
	$('.selectUpdate').material_select('destroy');
	$('.selectUpdate').val($(this).val());
	$(".hidden_counterpart_account").val($(this).val());
	$('.selectUpdate').material_select();
	$('.selectUpdate option[value="'+$(this).val()+'"]').attr("selected", "selected");
});

function validateForm(){
	if (!$('.provider_name').hasClass('invalid') 
		&& !$('.provider_rfc').hasClass('invalid')
		&& !$('.provider_accounting_account').hasClass('invalid')) {
		$('.submit_button').attr('disabled', false);
	} else {
		$('.submit_button').attr('disabled', true);
	}
}

function submitNewProvider() {
	$('#newProviderForm').submit();
}

function ajaxNewProvider() {
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	$.ajax({
		url: '/provision_policy',
		type: 'POST',
		data: { _token: CSRF_TOKEN,
				handler: 'newProvider',
				provider_name: $("#provider_name").val(),
				provider_rfc: $("#provider_rfc").val(),
				provider_accounting_account: $("#provider_accounting_account").val(),
				counterpart_accounting_account_id: $("#counterpart_accounting_account_id").val(),
		},
	})
	.done(function(data) {
		$(".collection-cfdi li").each(function(index){
			$("#newProviderModal").modal('close');
			if($(this).attr('data-rfc-provider')===data.provider_rfc){
				console.log("Eliminando warning de archivo "+$(this).attr('data-file-index'));
				$("#unknown_provider"+$(this).attr('data-file-index')).addClass('hide');
				$("#registerProvider"+$(this).attr('data-file-index')).remove();
			}
		});
		console.log("success");
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
}

function submitUpdateProvider(provider_id) {
	$('#updateProviderForm'+provider_id).submit();
}

function submitDeleteProvider(provider_id) {
	$('#deleteProviderForm'+provider_id).submit();
}

function updateProviderModal(id, counterpart_account_id) {
	$('#updateProviderModal').modal('open');
	$('.selectUpdate').material_select('destroy');
	$('.selectUpdate').val(counterpart_account_id);
	$('.selectUpdate').material_select();
}

function createSelectCounterpart() {
	$('.selectNew').material_select('destroy');
	$('.selectNew').val(0);
	$('.selectNew').material_select();
}

