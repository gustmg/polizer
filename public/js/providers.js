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
	$('.submit_button').attr('disabled', true);
	$('#newProviderForm').submit();
}

function ajaxNewProvider() {
	$('.submit_button').attr('disabled', true);
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
		$(".collection-cfdi .avatar").each(function(index){
			$("#newProviderModal").modal('close');
			if($(this).attr('data-rfc-provider')===data[0].provider_rfc){
				$('li[data-rfc-provider='+data[0].provider_rfc+']').removeClass('red').removeClass('darken-4').removeClass('white-text').removeClass('scrollspy');
				$('li[data-rfc-provider='+data[0].provider_rfc+'] .subtext').addClass('grey-text').addClass('text-darken-2').removeClass('white-text');
				$('li[data-rfc-provider='+data[0].provider_rfc+'] .counterpart').html('');
				$('li[data-rfc-provider='+data[0].provider_rfc+'] .counterpart').append(data[0].counterpart_account.accounting_account_description);
				$('li[data-rfc-provider='+data[0].provider_rfc+'] .collection-concept').attr('data-counterpart-account-number', data[0].counterpart_account.accounting_account_number);
				$("#registerProvider"+$(this).attr('data-file-index')).remove();
				//Agrega conceptos a Json
				var counterpart= [];
				var provider_accounting_account=[data[0].provider_accounting_account];
				$.each(jsonFilesData[$(this).attr('data-file-index')].concepto.descripciones, function(key, value) {
					counterpart.push(data[0].counterpart_account.accounting_account_number);
					//console.log(counterpart);
				});
				$.extend(jsonFilesData[$(this).attr('data-file-index')].concepto.contrapartidas, counterpart);
				$.extend(jsonFilesData[$(this).attr('data-file-index')].proveedor.cuentaContable, provider_accounting_account);
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
	$('#update_provider_button').attr('disabled', true);
	$('#updateProviderForm'+provider_id).submit();
}

function submitDeleteProvider(provider_id) {
	$('#delete_provider_button').attr('disabled', true);
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