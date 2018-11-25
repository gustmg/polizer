//Objetos para cargar archivos
var standard_billing_files = document.getElementById("standard_billing_files");
standard_billing_files.addEventListener("change", getFiles, false);

var policyType;

function setPolicyType(idPolicyType){
	policyType=idPolicyType;
}

//Objetos para manejo de archivos
var files;
var number_of_files;
var jsonFilesData = [];
var total_uploaded_files = 0;
var unreaded_files= [];

//Tablesorter
var table = document.getElementById('billing-tablesorter');
var sort = new Tablesort(table);

function getFiles(e){
    files= e.target.files;
    number_of_files = files.length;
	$(".section1").fadeOut(400,readFile(0));
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
				if(getFileData(e)!=0){
					setTimeout(agregaFilaTablaFacturacion.bind(null, total_uploaded_files), 1);
					total_uploaded_files++;
					readFile(index+1);
				}
				else{
					unreaded_files.push(filename);
					readFile(index+1);
				}
			}
			reader.readAsText(file);
		}
		else{
			//console.log('El archivo no. '+index+' no tiene una extension xml');
			readFile(index+1);
		}
	}
	else{
		// console.log('Se leyeron todos los archivos');
		// console.log("Total de archivos cargados: "+total_uploaded_files);
		// console.log(jsonFilesData);
		$('.progress').css('visibility', 'hidden');
		$('.section2').delay(400).fadeIn(400);
		$('#menu_navbar').slideDown(400);
		sort.refresh();
		if(unreaded_files.length>0){
			unreaded_files.toString();
			Materialize.toast('Archivos XML no válidos o corruptos: '+unreaded_files, 7000);
			unreaded_files=[];
		}
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
	if(passFileDataToJson(file_data)==0){
		return 0;
	}
}

function passFileDataToJson(file_data){
	var data= obtenerDatosXML($.parseXML(file_data));
	if(data!=0){
		jsonFilesData.push(data);	
	}
	else{
		return 0;
	}
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
	var contrapartidas = [];
	var cuenta_proveedor = [];
	var cuenta_cliente = [];
	var impuesto_iva;
	var datosXML;

	if(comprobante.attr('version')=='3.2'){
		//Validacion de atributos del XML
		if(typeof emisor.attr('nombre') == 'undefined'){
			// alert("Nombre Emisor no encontrado");
			return 0;
		}
		if(typeof emisor.attr('rfc') == 'undefined'){
			// alert("Rfc Emisor no encontrado");
			return 0;
		}
		if(typeof receptor.attr('nombre') == 'undefined'){
			// alert("Nombre Receptor no encontrado");
			return 0;
		}
		if(typeof receptor.attr('rfc') == 'undefined'){
			// alert("Rfc Receptor no encontrado");
			return 0;
		}
		if(typeof tfd.attr('UUID') == 'undefined'){
			// alert("UUID no encontrado");
			return 0;
		}
		if(typeof comprobante.attr('subTotal') == 'undefined'){
			// alert("Subtotal no encontrados");
			return 0;
		}
		if(typeof comprobante.attr('total') == 'undefined'){
			// alert("Total no encontrados");
			return 0;
		}
		if(typeof comprobante.attr('tipoDeComprobante') == 'undefined'){
			// alert("Comprobante Tipo no encontrados");
			return 0;
		}

		conceptos.find("cfdi\\:Concepto , Concepto").each(function(){
			//Valida conceptos del xml
			if(typeof $(this).attr("descripcion") == 'undefined'){
				// alert("Concepto Tipo no encontrados");
				return 0;
			}
			if(typeof $(this).attr("importe") == 'undefined'){
				// alert("Importe Tipo no encontrados");
				return 0;
			}

			descripciones.push($(this).attr("descripcion"));
			importes.push($(this).attr("importe"));
		});
		impuestos.find("cfdi\\:Traslado , Traslado").each(function(){
			if($(this).attr("impuesto")=="IVA"){
				impuesto_iva=$(this).attr("importe");
			}
			else if(typeof $(this).attr("impuesto") == 'undefined'){
				return 0;
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
				"importes"	: importes,
				"contrapartidas" : contrapartidas
			},

			"impuestos" : {
				"totalImpuestosTrasladados" : impuestos.attr('totalImpuestosTrasladados')
			},

			"proveedor" : {
				"cuentaContable" : cuenta_proveedor
			},

			"cliente" : {
				"cuentaContable" : cuenta_cliente
			}
		}
		datosXML.comprobante.folio=validaDatoDefinido(datosXML.comprobante.folio);
		datosXML.comprobante.serie=validaDatoDefinido(datosXML.comprobante.serie);
		datosXML.receptor.nombreReceptor=validaDatoDefinido(datosXML.receptor.nombreReceptor);
	}

	else if(comprobante.attr('Version')=='3.3'){
		//Validacion de atributos del XML
		if(typeof emisor.attr('Nombre') == 'undefined'){
			return 0;
		}
		if(typeof emisor.attr('Rfc') == 'undefined'){
			return 0;
		}
		if(typeof receptor.attr('Nombre') == 'undefined'){
			return 0;
		}
		if(typeof receptor.attr('Rfc') == 'undefined'){
			return 0;
		}
		if(typeof tfd.attr('UUID') == 'undefined'){
			return 0;
		}
		if(typeof comprobante.attr('SubTotal') == 'undefined'){
			return 0;
		}
		if(typeof comprobante.attr('Total') == 'undefined'){
			return 0;
		}
		if(typeof comprobante.attr('TipoDeComprobante') == 'undefined'){
			return 0;
		}

		conceptos.find("cfdi\\:Concepto , Concepto").each(function(){
			//Valida conceptos del xml
			if(typeof $(this).attr("Descripcion") == 'undefined'){
				return 0;
			}
			if(typeof $(this).attr("Importe") == 'undefined'){
				return 0;
			}

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
				"importes"	: importes,
				"contrapartidas" : contrapartidas
			},

			"impuestos" : {
				"totalImpuestosTrasladados" : impuestos.attr('TotalImpuestosTrasladados')
			},

			"proveedor" : {
				"cuentaContable" : cuenta_proveedor
			},

			"cliente" : {
				"cuentaContable" : cuenta_cliente
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
			mes_letra='Ene';
			break;
		case '02':
			mes_letra='Feb';
			break;
		case '03':
			mes_letra='Mar';
			break;
		case '04':
			mes_letra='Abr';
			break;
		case '05':
			mes_letra='May';
			break;	
		case '06':
			mes_letra='Jun';
			break;
		case '07':
			mes_letra='Jul';
			break;
		case '08':
			mes_letra='Ago';
			break;
		case '09':
			mes_letra='Sep';
			break;
		case '10':
			mes_letra='Oct';
			break;
		case '11':
			mes_letra='Nov';
			break;
		case '12':
			mes_letra='Dic';
			break;
	}
	var fecha_personalizada= dia+'-'+mes_letra;
	return fecha_personalizada;
}

function formatDate(fecha) {
	var mes=fecha.substr(5,2);
	var dia=fecha.substr(8,2);
	var year=fecha.substr(0,4);
	var fecha_personalizada= dia+'-'+mes+'-'+year;
	return fecha_personalizada;
}

$('#modalShowData').modal({
	ready: function(modal, trigger) { // Callback for Modal open. Modal and trigger parameters available.
	    $('#modalShowData .modal-content').append(
	    	'<h5>Datos de CFDI</h5>'+
	    	'<span class="client-name-modal valign-wrapper" data-client-name="'+trigger.attr('data-client-name')+'" data-client-rfc="'+trigger.attr('data-client-rfc')+'"><b>Cliente: </b>'+trigger.attr('data-client-name')+
	    	'</span><b>RFC: </b>'+trigger.attr('data-client-rfc')+
	    	'<br><b>Serie: </b>'+trigger.attr('data-serie'));
	    if(trigger.hasClass('red-text')){
	    	$('.client-name-modal').append('&nbsp;&nbsp;<a href="#newClientModal" class="modal-trigger newClientFromPolicy" onclick="closeDataModal();">'+
				'<i class="material-icons black-text">person_add</i>'+
			'</a>');
	    }
	},
	complete: function() {
		$('#modalShowData .modal-content').html('');
	}
});

function closeDataModal(){
	$('#modalShowData').modal('close');
}

//Funciones para mostrar tabla
function agregaFilaTablaFacturacion(index){
	$('#billing-tablesorter tbody').append(
    '<tr data-file-index="'+index+'" data-rfc-client="'+jsonFilesData[index].receptor.rfcReceptor+'">'+
        '<td class="center-align valign-wrapper">'+
            '<input type="checkbox" class="filled-in row-select" id="row-select-'+index+'"/>'+
            '<label for="row-select-'+index+'"></label>'+
        '</td>'+
        '<td style="width: 7%;" class="center-align" data-sort="'+formatDate(jsonFilesData[index].comprobante.fecha)+'">'+personalizaFecha(jsonFilesData[index].comprobante.fecha)+'</td>'+
        '<td style="width: 10%;" class="center-align">'+jsonFilesData[index].comprobante.folio+'</td>'+
        '<td style="width: 25%;" class="hover-'+index+'">'+
            '<span class="truncate client-name selectable modal-trigger" href="#modalShowData" style="width: 90%;" data-client-name="'+jsonFilesData[index].receptor.nombreReceptor+'" data-client-rfc="'+jsonFilesData[index].receptor.rfcReceptor+'" data-serie="'+jsonFilesData[index].comprobante.serie+'">'+jsonFilesData[index].receptor.nombreReceptor+'</span>'+
        '</td>'+
        '<td style="width: 30%;">'+
        	'<span class="truncate" style="width: 90%;">'+jsonFilesData[index].concepto.descripciones[0]+'</span>'+
        '</td>'+
        '<td style="width: 10%;" class="center-align">$'+personalizaTotal(jsonFilesData[index].comprobante.total)+'</td>'+
        '<td style="width: 10%;" class="center-align">'+
			'<a href="#modalShowConcepts'+index+'" class="modal-trigger">'+
				'<i class="material-icons black-text">list</i>'+
			'</a>'+
	        '<div id="modalShowConcepts'+index+'" class="modal modal-fixed-footer">'+
				'<div class="modal-content">'+
					'<ul id="conceptList'+index+'" class="collection concept-list" data-row-index="'+index+'">'+
					'</ul>'+
				'</div>'+
				'<div class="modal-footer">'+
					'<button class="btn modal-close" onclick="setConceptsToJson('+index+')"><b>Listo</b></button>'+
				'</div>'+
			'</div>'+
        '</td>'+
	'</tr>');
	$('#modalShowConcepts'+index).modal({dismissible: false,});
	loadConcepts(index);
	verifyClient(index);
}

function loadConcepts (file_index){
	var list= $('.accounts').html();
	$.each(jsonFilesData[file_index].concepto.descripciones, function(key, description) {
		$('#conceptList'+file_index).append(
		'<li class="collection-item" data-counterpart-account-number="0">'+
			'<div class="row no-margin">'+
				'<div class="col s6 left-align">'+
					'<span ><b class="truncate">'+description+'</b> '+jsonFilesData[file_index].concepto.importes[key]+'</span>'+
				'</div>'+
				'<div class="col s6 accounting-accounts-column-'+file_index+'">'+list+
				'</div>'+
			'</div>'+
		'</li>');
	});
}

function setConceptsToJson(json_index) {
	var counterpart_list= [];
	$('#conceptList'+json_index+' li div div:nth-child(2) select').each(function(){
		counterpart_list.push($(this).find(':selected').attr('data-accounting-account-number'));
	});
	$.extend(jsonFilesData[json_index].concepto.contrapartidas, counterpart_list);
	//console.log(jsonFilesData[json_index]);
	var amount_undefined_concepts=0
	$('#billing-tablesorter #conceptList'+json_index+' .accounting-account-list').each(function(index){
		if($(this).val()==null){
			amount_undefined_concepts++;
		}
	});
	if(amount_undefined_concepts==0){
		if($('#billing-tablesorter tbody tr:nth-child('+(json_index+1)+') td:last').children('i').length == 1){
			$('#billing-tablesorter tbody tr:nth-child('+(json_index+1)+') td:last i.red-text').hide();
		}
	}
}

function verifyClient(row_index) {
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

	$.ajax({
		url: 'ajaxBilling',
		type: 'POST',
		data: {_token: CSRF_TOKEN, handler: 'getClient', client_rfc : jsonFilesData[row_index].receptor.rfcReceptor},
	})
	.done(function(data) {
		if(data.length===0){
			$('tr[data-file-index="'+row_index+'"] td:nth-child(4) span.truncate').addClass('red-text');
		}
		else{
			$('tr[data-file-index="'+row_index+'"] td:nth-child(7) .newClientFromPolicy').remove();
			$('#modalShowConcepts'+row_index+' .accounting-account-list').each(function(){
				$(this).val(data[0].counterpart_accounting_account_id); //Set default counterpart on concept list
			});
			setConceptsToJson(row_index); //Pass counterpart accounts to json
			var client_accounting_account=[data[0].client_accounting_account];
			setClientAccountToJson(row_index, client_accounting_account);
			//console.log(jsonFilesData[row_index]);
		}
	})
	.fail(function() {
		//console.log("Error al buscar proveedor");
	})
}

function setClientAccountToJson(row_index, client_accounting_account) {
	$.extend(jsonFilesData[row_index].cliente.cuentaContable, client_accounting_account);
}

function sendJsonFiles(){
	setGenerateByToggle();
	var jsonFiles=[];
	if(verifyUnregisteredClients()!=0){
		Materialize.toast('No se pudo procesar la petición. El cliente de algún comprobante no está registrado.', 4000);
	}
	else{
		if(verifyUnregisteredClients()!=0){
			Materialize.toast('No se pudo procesar la petición. Hay conceptos sin contrapartida.', 4000);
		}
		if(verifyAccountingAccountLists()!=0){
			Materialize.toast('No se pudo procesar la petición. Hay XMLs sin contrapartida.', 4000);	
		}
		else{
			$('#menu_navbar').slideUp();
			$('.progress').css('visibility', 'visible');
			Materialize.toast('Procesando archivos.', 2000);
			$('tbody tr').each(function(index){
				var indexJsonFile=$(this).attr("data-file-index");
				jsonFiles.push(JSON.stringify(jsonFilesData[indexJsonFile]));
			});
			
			//console.log(policyType);
			$.ajax({
				url: 'ajaxBilling',
				type: 'POST',
				data: {handler: 'export' , policyType: policyType, jsonFiles: jsonFiles, generateByClient: generate_by_toggle, cfdiIndexSerie: $('#cfdi_index_serie').val()},
			})
			.done(function(data) {
				$('#menu_navbar').slideDown();
				Materialize.toast('Archivo generado.', 2000);
				window.location.href = data;
			})
			.fail(function(data) {
				console.log(data);
				$('#menu_navbar').slideDown();
				Materialize.toast('Hubo un error al generar el archivo.', 2000);
				console.log("error");
			})
			.always(function() {
				$('.progress').css('visibility', 'hidden');
			});
		}
	}
}

function verifyUnregisteredClients() {
	var amount_unregistered_clients=0;
	$('.client-name').each(function(index){
		if($(this).hasClass('red-text')){
			amount_unregistered_clients++;
		}
	});
	return amount_unregistered_clients;
}

function verifyAccountingAccountLists(){
	var amount_unregistered_counterparts=0;
	$('#billing-tablesorter .accounting-account-list').each(function(index){
		if($(this).val()== null){
			amount_unregistered_counterparts++;
		}
	});
	return amount_unregistered_counterparts;
}