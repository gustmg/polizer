$('#company_name').on('blur', function () {
	if (!$('#company_name').hasClass('invalid')) {
		$('#registrate_button').attr('disabled', false);
	} else {
		$('#registrate_button').attr('disabled', true);
	}
});

function submitNewCompany() {
	$('#newCompanyForm').submit();
}

function submitDeleteCompany($company_id) {
	
}