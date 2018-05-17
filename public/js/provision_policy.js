var standard_provision_files = document.getElementById("standard_provision_files");
var add_standard_provision_files = document.getElementById("add_standard_provision_files");
var back_prev_section = document.getElementById("back_prev");
var send_json_files = document.getElementById("send_json_files");
add_standard_provision_files.addEventListener("click", triggerAddFiles,false);
standard_provision_files.addEventListener("change", getFiles, false);
back_prev_section.addEventListener("click", returnSection, false);
send_json_files.addEventListener("click", sendJsonFiles, false);
var file_index;
var concept_index;

$('#modalContrapartida1').modal({
	ready: function(modal, trigger) {
		file_index = trigger.attr("data-file-index");
		concept_index = trigger.attr("data-concept-index");
	},
});
$('#modalRemoveFile').modal({
	ready: function(modal, trigger) { // Callback for Modal open. Modal and trigger parameters available.
	    var remove_file_id = trigger.parent().parent().parent().attr('data-file-index');
	    $('#removeFileConfirmButton').attr('onclick', 'removeFile('+remove_file_id+');');
	},
});
$('select').material_select();

function setConceptCounterpart(accounting_account_number, accounting_account_description) {
	$('#conceptList'+file_index+' li:nth-child('+concept_index+')').attr('data-counterpart-account-number', accounting_account_number);
	$('#conceptList'+file_index+' li:nth-child('+concept_index+') .counterpart').html('');
	$('#conceptList'+file_index+' li:nth-child('+concept_index+') .counterpart').append(accounting_account_description);
	$('#modalContrapartida1').modal('close');
	//$('#conceptList'+file_index+':nth-child('+(concept_index+1)+') span:nth-child(2)').append(accounting_account_description);
}

function returnSection() {
	location.reload();
}

var files;
var number_of_files;
var jsonFilesData = [];
var total_uploaded_files = 0;

function getFiles(e){
    files= e.target.files;
    number_of_files = files.length;
	$(".section1").fadeOut(400,readFile(0));
}

function triggerAddFiles() {
	$("#add_files").trigger('click');
}

function readFile(index) {
	$('.progress').css('visibility', 'visible');
	if( index != number_of_files){
		var file = files[index];
		var filename= files[index].name;
		var file_extension = filename.split('.').pop().toLowerCase();

		if(validateExtension(file_extension)){
			var reader = new FileReader();
			reader.onload = function (e){
				getFileData(e);
				setTimeout(agregaFilaTablaProvision.bind(null, total_uploaded_files), 1);
				total_uploaded_files++;
				readFile(index+1);
			}
			reader.readAsDataURL(file);
		}
		else{
			console.log('El archivo no. '+index+' no tiene una extension xml');
			readFile(index+1);
		}
	}
	else{
		console.log('Se leyeron todos los archivos');
		console.log("Total de archivos cargados: "+total_uploaded_files);
		$('.progress').css('visibility', 'hidden');
		$('.section2').delay(400).fadeIn(400);
		$('#menu_navbar').slideDown(400);
	}
}

function validateExtension(file_extension) {
	if($.inArray(file_extension, ['xml']) == -1) {
	    return 0; //Invalid
	}
	else{
		return 1; //Valid
	}
}

function getFileData(e){
	var file_data=e.target.result;
	passFileDataToJson(file_data);
}

function passFileDataToJson(file_data){
	$.get(file_data, function (xml) {
		jsonFilesData.push(obtenerDatosXML(xml));		
		// verificaRfcEmisor(datosXML.emisor.rfcEmisor, no_fila, datosXML.emisor.nombreEmisor);
	});
}

function agregaFilaTablaProvision(index){
	$('.collection-cfdi').append(
		'<li class="collection-item avatar dismissable" data-file-index="'+index+'" data-rfc-provider="'+jsonFilesData[index].emisor.rfcEmisor+'">'+
			'<i class="material-icons circle white-text">subject</i>'+
			'<span>'+
				'<b>'+jsonFilesData[index].emisor.nombreEmisor+' ('+jsonFilesData[index].emisor.rfcEmisor+')</b>'+
			'</span>'+
			'<span id="unknown_provider'+index+'" class="red-text hide"><br><b><i>Proveedor no registrado</i></b></span>'+
			'<a href="#!" class="secondary-content dropdown-button" data-activates="dropdown-menu'+index+'" data-alignment="right">'+
				'<i class="material-icons subtext">more_vert</i>'+
			'</a><br>'+
			'<span class="subtext grey-text text-darken-2">'+
				'<b>Serie:</b> <i>'+jsonFilesData[index].comprobante.serie+'</i>&nbsp;&nbsp;<b>Folio:</b> <i>'+jsonFilesData[index].comprobante.folio+'</i>'+
			'</span><br>'+
			'<span class="subtext grey-text text-darken-2">'+
				'<b>Fecha de emisión:</b> <i>'+personalizaFecha(jsonFilesData[index].comprobante.fecha)+'</i></span><br>'+
				'<span class="subtext grey-text text-darken-2">'+
				'<b>Total:</b> <i>$'+personalizaTotal(jsonFilesData[index].comprobante.total)+'</i></span><br>'+
			'<span class="grey-text text-darken-2">'+
				'<b class="subtext">Conceptos:</b>'+
				'<ul id="conceptList'+index+'" class="collection card" style="border: none;overflow: visible;"></ul>'+
			'</span><br>'+
			'<ul id="dropdown-menu'+index+'" class="dropdown-content" style="min-width: 200px;">'+
				'<li id="registerProvider'+index+'"><a href="#newProviderModal" class="modal-trigger newProviderFromProvision">Agregar proveedor</a></li>'+
				'<li><a href="#modalRemoveFile" class="modal-trigger">Eliminar XML</a></li>'+
			'</ul>'+
		'</li>');
	agregaConceptos(index)
	$('.dropdown-button').dropdown();
	$('select').material_select();
}

function agregaConceptos(indexJson){
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

	$.ajax({
		url: '/provision_policy',
		type: 'POST',
		data: {_token: CSRF_TOKEN, handler: 'getProvider', provider_rfc : jsonFilesData[indexJson].emisor.rfcEmisor},
	})
	.done(function(data) {
		if(data.length===0){
			$.each(jsonFilesData[indexJson].concepto.descripciones, function(key, value) {
				$('#conceptList'+indexJson).append(
				'<li class="collection-item collection-concept" data-counterpart-account-number="0">'+
					'<b>'+value+'</b> <span class="right">$'+jsonFilesData[indexJson].concepto.importes[key]+'</span><br>'+
					'<b>Contrapartida:</b> <span class="counterpart">Contrapartida no asignada</span><br>'+
					'<a href="#modalContrapartida1" class="modal-trigger" data-file-index="'+indexJson+'" data-concept-index="'+(key+1)+'">Cambiar contrapartida para este concepto</a>'+
				'</li>');
			});
			$('li[data-rfc-provider='+jsonFilesData[indexJson].emisor.rfcEmisor+']').addClass('red').addClass('darken-4').addClass('white-text').addClass('scrollspy');
			$('li[data-rfc-provider='+jsonFilesData[indexJson].emisor.rfcEmisor+'] .subtext').removeClass('grey-text').removeClass('text-darken-2').addClass('white-text');
			//$('li[data-rfc-provider='+jsonFilesData[indexJson].emisor.rfcEmisor+'] i.circle').html('');
			//$('li[data-rfc-provider='+jsonFilesData[indexJson].emisor.rfcEmisor+'] i.circle').html('warning');
		}
		else{
			$.each(jsonFilesData[indexJson].concepto.descripciones, function(key, value) {
				$('#conceptList'+indexJson).append(
				'<li class="collection-item collection-concept" data-counterpart-account-number="'+data[0].counterpart_account.accounting_account_number+'">'+
					'<b>'+value+'</b> <span class="right">$'+jsonFilesData[indexJson].concepto.importes[key]+'</span><br>'+
					'<b>Contrapartida:</b> <span class="counterpart">'+data[0].counterpart_account.accounting_account_description+'</span><br>'+
					'<a href="#modalContrapartida1" class="modal-trigger" data-file-index="'+indexJson+'" data-concept-index="'+(key+1)+'">Cambiar contrapartida para este concepto</a>'+
				'</li>');
			});
			$("#registerProvider"+indexJson).remove();
		}
	})
	.fail(function() {
		console.log("Error al buscar proveedor");
	})
}

function removeFile(no_file){
	$('#modalRemoveFile').modal('close');
	$('li[data-file-index="'+no_file+'"]').fadeOut(400).remove();
}

function sendJsonFiles(){
	$('.progress').css('visibility', 'visible');
	var jsonFiles=[];
	if(verifyUnregisteredProviders()!=0){
		$('.progress').css('visibility', 'hidden');
		Materialize.toast('No se pudo procesar la petición. El proveedor de algún comprobante no está registrado.', 4000);
	}
	else{
		if(verifyUnregisteredProviders()!=0){
			$('.progress').css('visibility', 'hidden');
			Materialize.toast('No se pudo procesar la petición. Hay conceptos sin contrapartida.', 4000);
		}
		else{
			Materialize.toast('Procesando archivos.', 4000);
			$('.collection-cfdi li.avatar').each(function(index){
				var indexJsonFile=$(this).attr("data-file-index");
				jsonFiles.push(jsonFilesData[indexJsonFile]);
				
			});
			//TO-DO send json to excel by ajax
			console.log(jsonFiles);
		}
		
	}
}

function verifyUnregisteredProviders() {
	var amount_unregistered_providers=0;
	$('.collection-cfdi li.avatar').each(function(index){
		if($(this).hasClass('red')){
			amount_unregistered_providers++;
		}
	});
	return amount_unregistered_providers;
}

function verifyUnasignedCounterparts() {
	var amount_unasigned_counterparts=0;
	$('.collection-cfdi li.avatar .counterpart:contains("Contrapartida no asignada");').each(function(index){
		amount_unasigned_counterparts++;
	});
	return amount_unasigned_counterparts;
}

function obtenerDatosXML(xml){
	var comprobante   = $(xml).find("cfdi\\:Comprobante, Comprobante");
	var emisor        = $(xml).find("cfdi\\:Emisor, Emisor");
	var receptor      = $(xml).find("cfdi\\:Receptor, Receptor");
	var tfd           = $(xml).find("tfd\\:TimbreFiscalDigital, TimbreFiscalDigital");
	var traslado      = $(xml).find("cfdi\\:Traslado, Traslado");
	var conceptos     = $(xml).find("cfdi\\:Conceptos , Conceptos");
	var impuestos     = $(xml).find("cfdi\\:Impuestos, Impuestos");
	var descripciones = [];
	var importes    = [];
	var impuesto_iva;
	var datosXML;

	if(comprobante.attr('version')=='3.2'){
		conceptos.find("cfdi\\:Concepto , Concepto").each(function(){
			descripciones.push($(this).attr("descripcion"));
			importes.push($(this).attr("importe"));
		});
		impuestos.find("cfdi\\:Traslado , Traslado").each(function(){
			if($(this).attr("impuesto")=="IVA"){
				impuesto_iva=$(this).attr("importe");
			}
		});
		datosXML={
			"comprobante" : {
				"folio"            : comprobante.attr('folio'),
				"fecha"            : comprobante.attr('fecha'),
				"subtotal"         : comprobante.attr('subTotal'),
				"total"            : comprobante.attr('total'),
				"tipoDeComprobante": comprobante.attr('tipoDeComprobante'),
				"metodoDePago"     : comprobante.attr('metodoDePago'),
				"serie"            : comprobante.attr('serie')
			},

			"emisor" : {
				"nombreEmisor" : emisor.attr('nombre'),
				"rfcEmisor"    : emisor.attr('rfc')
			},

			"receptor" : {
				"nombreReceptor" : receptor.attr('nombre'),
				"rfcReceptor"    : receptor.attr('rfc')
			},

			"timbreFiscalDigital" : {
				"uuid" : tfd.attr('UUID')
			},

			"traslado" : {
				"importe" : impuesto_iva
			},

			"concepto" : {
				"descripciones" : descripciones,
				"importes"	: importes
			},

			"impuestos" : {
				"totalImpuestosTrasladados" : impuestos.attr('totalImpuestosTrasladados')
			}
		}
		datosXML.comprobante.folio=validaDatoDefinido(datosXML.comprobante.folio);
		datosXML.comprobante.serie=validaDatoDefinido(datosXML.comprobante.serie);
		datosXML.receptor.nombreReceptor=validaDatoDefinido(datosXML.receptor.nombreReceptor);
	}

	else if(comprobante.attr('Version')=='3.3'){
		conceptos.find("cfdi\\:Concepto , Concepto").each(function(){
			descripciones.push($(this).attr("Descripcion"));
			importes.push($(this).attr("Importe"));
		});
		impuestos.each(function(index){
			if (index==impuestos.length-1){
				impuesto_iva = $(this).attr('TotalImpuestosTrasladados');
			}
		});

		datosXML={
			"comprobante" : {
				"folio"            : comprobante.attr('Folio'),
				"fecha"            : comprobante.attr('Fecha'),
				"subtotal"         : comprobante.attr('SubTotal'),
				"total"            : comprobante.attr('Total'),
				"tipoDeComprobante": comprobante.attr('TipoDeComprobante'),
				"metodoDePago"     : comprobante.attr('FormaPago'),
				"serie"            : comprobante.attr('Serie')
			},

			"emisor" : {
				"nombreEmisor" : emisor.attr('Nombre'),
				"rfcEmisor"    : emisor.attr('Rfc')
			},

			"receptor" : {
				"nombreReceptor" : receptor.attr('Nombre'),
				"rfcReceptor"    : receptor.attr('Rfc')
			},

			"timbreFiscalDigital" : {
				"uuid" : tfd.attr('UUID')
			},

			"traslado" : {
				"importe" : impuesto_iva
			},

			"concepto" : {
				"descripciones" : descripciones,
				"importes"	: importes
			},

			"impuestos" : {
				"totalImpuestosTrasladados" : impuestos.attr('TotalImpuestosTrasladados')
			}
		}
		datosXML.comprobante.folio=validaDatoDefinido(datosXML.comprobante.folio);
		datosXML.comprobante.serie=validaDatoDefinido(datosXML.comprobante.serie);
	}
	return datosXML;
}

function validaDatoDefinido(dato){
	if(typeof dato == 'undefined'){
		dato=0;
	}
	return dato;
}

function personalizaTotal(total) {
	for(i=0;i<total.length;i++){
		if(total.charAt(i)=="."){
			var separador=".";
			var subcadenas_total=total.split(separador);
			var centavos=subcadenas_total[1].substr(0,2);
			total=subcadenas_total[0]+'.'+centavos;
		}
	}
	return total;
}

function personalizaFecha(fecha) {
	var mes=fecha.substr(5,2);
	var dia=fecha.substr(8,2);
	var mes_letra;
	switch(mes){
		case '01':
			mes_letra='Enero';
			break;
		case '02':
			mes_letra='Febrero';
			break;
		case '03':
			mes_letra='Marzo';
			break;
		case '04':
			mes_letra='Abril';
			break;
		case '05':
			mes_letra='Mayo';
			break;	
		case '06':
			mes_letra='Junio';
			break;
		case '07':
			mes_letra='Julio';
			break;
		case '08':
			mes_letra='Agosto';
			break;
		case '09':
			mes_letra='Septiembre';
			break;
		case '10':
			mes_letra='Octubre';
			break;
		case '11':
			mes_letra='Noviembre';
			break;
		case '12':
			mes_letra='Diciembre';
			break;
	}
	var fecha_personalizada= dia+' de '+mes_letra;
	return fecha_personalizada;
}