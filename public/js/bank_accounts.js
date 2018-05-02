$('.newBankAccountModal').modal();
$('.updateBankAccountModal').modal();
$('.deleteBankAccountModal').modal();

$('.selectNewBank').on('change', function(event) {
	$('.selectNewBank').material_select('destroy');
	$('.selectNewBank').material_select();
	$('.selectNewBank').val($(this).val());
	$('.selectNewBank option[value="'+$(this).val()+'"]').attr("selected", "selected");
});

$('.selectNewAccountingAccount').on('change', function(event) {
	$('.selectNewAccountingAccount').material_select('destroy');
	$('.selectNewAccountingAccount').material_select();
	$('.selectNewAccountingAccount').val($(this).val());
	$('.selectNewAccountingAccount option[value="'+$(this).val()+'"]').attr("selected", "selected");
});

$('.selectUpdateBank').on('change', function(event) {
	$('.selectUpdateBank').material_select('destroy');
	$('.selectUpdateBank').val($(this).val());
	$(".hidden_bank").val($(this).val());
	$('.selectUpdateBank').material_select();
	$('.selectUpdateBank option[value="'+$(this).val()+'"]').attr("selected", "selected");
});

$('.selectUpdateAccountingAccount').on('change', function(event) {
	$('.selectUpdateAccountingAccount').material_select('destroy');
	$('.selectUpdateAccountingAccount').val($(this).val());
	$(".hidden_counterpart_account").val($(this).val());
	$('.selectUpdateAccountingAccount').material_select();
	$('.selectUpdateAccountingAccount option[value="'+$(this).val()+'"]').attr("selected", "selected");
});


function validateForm(){
	if (!$('.bank_account_number').hasClass('invalid')) {
		$('.submit_button').attr('disabled', false);
	} else {
		$('.submit_button').attr('disabled', true);
	}
}

function submitNewBankAccount() {
	$('#newBankAccountForm').submit();
}

function submitUpdateBankAccount(bank_account_id) {
	$('#updateBankAccountForm'+bank_account_id).submit();
}

function submitDeleteBankAccount(bank_account_id) {
	$('#deleteBankAccountForm'+bank_account_id).submit();
}

function updateBankAccountModal(id, bank_id, counterpart_account_id) {
	$('#updateBankAccountModal').modal('open');
	$('.selectUpdateBank').material_select('destroy');
	$('.selectUpdateBank').val(bank_id);
	$('.selectUpdateBank').material_select();
	$('.selectUpdateAccountingAccount').material_select('destroy');
	$('.selectUpdateAccountingAccount').val(counterpart_account_id);
	$('.selectUpdateAccountingAccount').material_select();
}

function createSelects() {
	$('.selectNewBank').material_select('destroy');
	//$('.selectNewBank').val(0);
	$('.selectNewBank').material_select();

	$('.selectNewAccountingAccount').material_select('destroy');
	//$('.selectNewAccountingAccount').val(0);
	$('.selectNewAccountingAccount').material_select();
}

