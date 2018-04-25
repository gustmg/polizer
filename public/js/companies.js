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
	$('#newCompanyForm').submit();
}

function submitUpdateCompany(company_id) {
	$('#updateCompanyForm'+company_id).submit();
}

function submitDeleteCompany(company_id) {
	$('#deleteCompanyForm'+company_id).submit();
}