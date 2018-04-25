$('.newAccountingAccountModal').modal();
$('.updateAccountingAccountModal').modal();
$('.deleteAccountingAccountModal').modal();

$('.accounting_account_number').on('blur', function () {
	if (!$('.accounting_account_number').hasClass('invalid')) {
		$('.submit_button').attr('disabled', false);
	} else {
		$('.submit_button').attr('disabled', true);
	}
});

$('.accounting_account_description').on('blur', function () {
	if (!$('.accounting_account_description').hasClass('invalid')) {
		$('.submit_button').attr('disabled', false);
	} else {
		$('.submit_button').attr('disabled', true);
	}
});

function submitNewCompany() {
	$('#newAccountingAccountForm').submit();
}

function submitUpdateCompany(accounting_account_id) {
	$('#updateAccountingAccountForm'+accounting_account_id).submit();
}

function submitDeleteCompany(accounting_account_id) {
	$('#deleteAccountingAccountForm'+accounting_account_id).submit();
}