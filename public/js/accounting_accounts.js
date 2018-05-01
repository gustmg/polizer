$('.newAccountingAccountModal').modal();
$('.updateAccountingAccountModal').modal();
$('.deleteAccountingAccountModal').modal();
$('.selectNew').material_select();
 $('.tooltipped').tooltip({delay: 50});

$(".selectUpdate").on('change', function(event) {
	$(".hidden_account_type").val($(this).val());
});

function validateForm(){
	if (!$('.accounting_account_number').hasClass('invalid') && !$('.accounting_account_description').hasClass('invalid')) {
		$('.submit_button').attr('disabled', false);
	} else {
		$('.submit_button').attr('disabled', true);
	}
}

function submitNewAccountingAccount() {
	$('#newAccountingAccountForm').submit();
}

function submitUpdateAccountingAccount(accounting_account_id) {
	$('#updateAccountingAccountForm'+accounting_account_id).submit();
}

function submitDeleteAccountingAccount(accounting_account_id) {
	$('#deleteAccountingAccountForm'+accounting_account_id).submit();
}

function updateAccountingAccountModal(id, type_id) {
	$('#updateAccountingAccountModal'+id).modal('open');
	
	setAccountingAccountType(type_id);
}

function setAccountingAccountType(type_id) {
	$('.selectUpdate').material_select('destroy');
	$('.selectUpdate').val(type_id);
	$('.selectUpdate').material_select();
}