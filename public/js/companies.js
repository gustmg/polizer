$('.newCompanyModal').modal();
$('.updateCompanyModal').modal();
$('.deleteCompanyModal').modal();

$('.company_name').on('blur', function () {
	if (!$('.company_name').hasClass('invalid')) {
		$('.submit_button').attr('disabled', false);
	} else {
		$('.submit_button').attr('disabled', true);
	}
});

function submitNewCompany() {
	$('#add_company_button').attr('disabled', true);
	$('#newCompanyForm').submit();
}

function submitUpdateCompany(company_id) {
	$('#update_company_button').attr('disabled', true);
	$('#updateCompanyForm'+company_id).submit();
}

function submitDeleteCompany(company_id) {
	$('#delete_company_button').attr('disabled', true);
	$('#deleteCompanyForm'+company_id).submit();
}