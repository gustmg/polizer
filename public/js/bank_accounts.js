$('.newBankAccountModal').modal({
	complete: function(){
		$('#bank_account_number').val('');
		$('label[for="bank_account_number"]').removeClass('active');
		$('#bank_account_number').removeClass('valid');
		$('#bank_account_number').removeClass('invalid');
		$('#selectNewBank').val('1');
		$('#counterpart_accounting_account_id').val($('#counterpart_accounting_account_id optgroup option:first').val());
	},
});
$('.updateBankAccountModal').modal({
	ready: function(modal, trigger) { // Callback for Modal open. Modal and trigger parameters available.
    	$('#update_bank_account_number').val(trigger.attr('data-bank-account-number'));
    	$('#update_bank_account_number').attr('data-bank-account-number', trigger.attr('data-bank-account-number'));
    	$('label[for="bank_account_number"]').addClass('active');
    	$('.selectUpdateBank').val(trigger.attr('data-bank-account-bank-id'));
    	$('.selectUpdateAccountingAccount').val(trigger.attr('data-bank-account-counterpart-id'));
    	$('#updateBankAccountForm').attr('action','bank_accounts/'+trigger.attr('data-bank-account-id'));
    	$('#update_bank_account_number').removeClass('valid');
    	$('#update_bank_account_number').removeClass('invalid');
    	$('#deleteBankAccountModalButton').attr('data-bank-account-id', trigger.attr('data-bank-account-id'));
	},
});
$('.deleteBankAccountModal').modal({
	ready: function(modal, trigger){
		$('#deleteBankAccountForm').attr('action','bank_accounts/'+trigger.attr('data-bank-account-id'));
	},
});

function validateForm(){
	validateBankAccountNumber($('#newBankAccountForm #bank_account_number').val());
	if (!$('#bank_account_number').hasClass('invalid')) {
		$('.submit_button').attr('disabled', false);
	} else {
		$('.submit_button').attr('disabled', true);
	}
}

function validateUpdateForm(){
	validateUpdateBankAccountNumber($('#updateBankAccountForm #update_bank_account_number').val());
	if (!$('.bank_account_number').hasClass('invalid')) {
		$('.submit_button').attr('disabled', false);
	} else {
		$('.submit_button').attr('disabled', true);
	}
}

function submitNewBankAccount() {
	$('#new_bank_account_button').attr('disabled', true);
	$('#newBankAccountForm').submit();
}

function submitUpdateBankAccount() {
	$('#update_bank_account_button').attr('disabled', true);
	$('.hidden_bank').val($('.selectUpdateBank option:selected').val());
	$('.hidden_counterpart_account').val($('.selectUpdateAccountingAccount option:selected').val());
	$('#updateBankAccountForm').submit();
}

function submitDeleteBankAccount() {
	$('#delete_bank_account_button').attr('disabled', true);
	$('#deleteBankAccountForm').submit();
}

function validateBankAccountNumber(bank_account_number){
	$('.bank-accounts-table tbody tr').each(function(index){
		if($(this).find('td:first').text() == bank_account_number){
			$('.bank_account_number').addClass('invalid');
		}
	});
}

function validateUpdateBankAccountNumber(bank_account_number) {
	$('.bank-accounts-table tbody tr').each(function(index){
		if($(this).find('td:first').text() == bank_account_number){
			$('#update_bank_account_number').addClass('invalid');
		}
	});
	if(bank_account_number == $('#update_bank_account_number').attr('data-bank-account-number')){
		$('#update_bank_account_number').removeClass('invalid');
		$('#update_bank_account_number').removeClass('valid');
	}
}