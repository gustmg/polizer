//Return to prev section function
var back_prev_section = document.getElementById("back_prev");
back_prev_section.addEventListener("click", returnSection, false);

function returnSection() {
	location.reload();
}

//Select all rows function
var toggle_select_all_rows= document.getElementById("toggle_select_all_rows");
toggle_select_all_rows.addEventListener("click", selectAllRows, false);
var check_status=0;

function selectAllRows(){
	if(check_status==0){
		$('tr td:nth-child(1) input').prop('checked', true);
		check_status=1;
	}
	else{
		$('tr td:nth-child(1) input').prop('checked', false);
		check_status=0;
	}
}

//Add more rows function
var add_more_files = document.getElementById("add_more_files");
add_more_files.addEventListener("click", triggerAddFiles,false);

function triggerAddFiles() {
	$("#policy-type-"+policyType).trigger('click');
}

//Open advanced config modal function
var cfdi_config = document.getElementById("cfdi_config");
cfdi_config.addEventListener("click", openCfdiConfigModal, false);
var generate_by_toggle=0;
$('#modalFilesConfig').modal();

function openCfdiConfigModal() {
	$('#modalFilesConfig').modal('open');
}

function setGenerateByToggle() {
	if($('#cfdi_generate_by_toggle').is(':checked')){
		generate_by_toggle=1;
	}
	else{
		generate_by_toggle=0;
	}
}

function validateIndexSerie() {
	if($('#cfdi_index_serie').val() < 1){
		$('#saveChanges').attr('disabled', true);
	}
	else
		$('#saveChanges').attr('disabled', false);
}

//Generate excel file function
var send_json_files = document.getElementById("send_json_files");
send_json_files.addEventListener("click", sendJsonFiles, false);

//Remove rows
$('#modalRemoveRows').modal();
function removeRows(){
	$('#modalRemoveRows').modal('close');
	$('tbody tr').each(function(index){
		if($(this).find('.row-select').is(':checked')){
			$(this).fadeOut(200,function(){
				$(this).remove();
				if($('tbody tr').length===0){
					location.reload();
				}
			});
		}
	});
}