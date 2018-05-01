$('.newProviderModal').modal();
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

