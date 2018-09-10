$('.newAccountingAccountModal').modal({
	complete: function(modal,trigger){
		$('#accounting_account_number').val('');
		$('#accounting_account_number').removeClass('valid');
		$('#accounting_account_number').removeClass('invalid');
		$('label[for="accounting_account_number"]').removeClass('active');
		$('#accounting_account_description').val('');
		$('#accounting_account_description').removeClass('valid');
		$('#accounting_account_description').removeClass('invalid');
		$('label[for="accounting_account_description"]').removeClass('active');
		$('#selectNewAccountingAccount').val($('#selectNewAccountingAccount option:first').val());
	},
});

$('.updateAccountingAccountModal').modal({
	ready: function(modal, trigger) { // Callback for Modal open. Modal and trigger parameters available.
    	$('#update_accounting_account_number').val(trigger.attr('data-accounting-account-number'));
    	$('#update_accounting_account_number').attr('data-accounting-account-number', trigger.attr('data-accounting-account-number'));
    	$('#update_accounting_account_number').removeClass('invalid');
    	$('label[for="accounting_account_number"]').addClass('active');
    	$('#update_accounting_account_description').val(trigger.attr('data-accounting-account-description'));
    	$('#update_accounting_account_description').removeClass('invalid');
    	$('label[for="accounting_account_description"]').addClass('active');
    	$('#updateAccountingAccountForm').attr('action','accounting_accounts/'+trigger.attr('data-accounting-account-id'));
    	$('#deleteAccountingAccountModalButton').attr('data-accounting-account-id', trigger.attr('data-accounting-account-id'));
	},
});

$('.deleteAccountingAccountModal').modal({
	ready: function(modal, trigger){
		$('#deleteAccountingAccountForm').attr('action','accounting_accounts/'+trigger.attr('data-accounting-account-id'));
	},
});

$('.tooltipped').tooltip({delay: 50});

function validateForm(){
	validateAccountingAccountNumber($('#newAccountingAccountForm #accounting_account_number').val());
	if (!$('#accounting_account_number').hasClass('invalid') && !$('#accounting_account_description').hasClass('invalid') ) {
		$('.submit_button').attr('disabled', false);
	} else {
		$('.submit_button').attr('disabled', true);
	}
}

function validateUpdateForm(){
	validateUpdateAccountingAccountNumber($('#updateAccountingAccountForm #update_accounting_account_number').val());
	if (!$('#update_accounting_account_number').hasClass('invalid') && !$('#update_accounting_account_description').hasClass('invalid') ) {
		$('.submit_button').attr('disabled', false);
	} else {
		$('.submit_button').attr('disabled', true);
	}
}

function submitNewAccountingAccount() {
	$('#new_accounting_account_button').attr('disabled', true);
	$('#newAccountingAccountForm').submit();
}

function submitUpdateAccountingAccount() {
	$('.hidden_account_type').val($('.selectUpdate option:selected').val());
	$('#update_accounting_account_button').attr('disabled', true);
	$('#updateAccountingAccountForm').submit();
}

function submitDeleteAccountingAccount() {
	$('#delete_accounting_account_button').attr('disabled', true);
	$('#deleteAccountingAccountForm').submit();
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

function validateAccountingAccountNumber(accounting_account_number) {
	$('.accounting-accounts-tablesorter tbody tr').each(function(index){
		if($(this).find('td:first').text() == accounting_account_number){
			$('#newAccountingAccountForm #accounting_account_number').addClass('invalid');
		}
	});
}

function validateUpdateAccountingAccountNumber(accounting_account_number) {
	$('.accounting-accounts-tablesorter tbody tr').each(function(index){
		if($(this).find('td:first').text() == accounting_account_number){
			$('#update_accounting_account_number').addClass('invalid');
		}
	});
	if(accounting_account_number == $('#update_accounting_account_number').attr('data-accounting-account-number')){
		$('#update_accounting_account_number').removeClass('invalid');
		$('#update_accounting_account_number').removeClass('valid');
	}
}