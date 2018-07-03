//Objetos para cargar archivos
var standard_provider_payment_files = document.getElementById("standard_provider_payment_files");
var honorarium_provider_payment_files = document.getElementById("honorarium_provider_payment_files");
standard_provider_payment_files.addEventListener("change", getFiles, false);
honorarium_provider_payment_files.addEventListener("change", getFiles, false);

var policyType;

function setPolicyType(idPolicyType){
	policyType=idPolicyType;
}

//Objetos para manejo de archivos
var files;
var number_of_files;
var jsonFilesData = [];
var total_uploaded_files = 0;

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
				getFileData(e);
				setTimeout(agregaFilaTablaProvision.bind(null, total_uploaded_files), 1);
				total_uploaded_files++;
				readFile(index+1);
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
	jsonFilesData.push(obtenerDatosXML($.parseXML(file_data)));	
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
	var cuenta_bancaria_origen='0';
	var cuenta_contable_origen='0';
	var cuenta_bancaria_destino='0';
	var cuenta_contable_destino='0';
	var numero_cheque='0';
	var banco_origen= '0';
	var banco_destino= '0';
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
				"formaPago"     : comprobante.attr('metodoDePago'),
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

			"datosOrigen" : {
				"cuentaBancariaOrigen" : cuenta_bancaria_origen,
				"cuentaContableOrigen" : cuenta_contable_origen,
				"bancoOrigen" : banco_origen
			},

			"datosDestino" : {
				"cuentaBancariaDestino" : cuenta_bancaria_destino,
				"cuentaContableDestino" : cuenta_contable_destino,
				"numeroCheque" : numero_cheque,
				"bancoDestino" : banco_destino
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
				"formaPago"     : comprobante.attr('FormaPago'),
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

			"datosOrigen" : {
				"cuentaBancariaOrigen" : cuenta_bancaria_origen,
				"cuentaContableOrigen" : cuenta_contable_origen,
				"bancoOrigen" : banco_origen
			},

			"datosDestino" : {
				"cuentaBancariaDestino" : cuenta_bancaria_destino,
				"cuentaContableDestino" : cuenta_contable_destino,
				"numeroCheque" : numero_cheque,
				"bancoDestino" : banco_destino
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

//Funciones para mostrar tabla
function agregaFilaTablaProvision(index){
	$('tbody').append(
	'<tr data-file-index="'+index+'" data-rfc-provider="'+jsonFilesData[index].emisor.rfcEmisor+'">'+
		'<td class="center-align">'+
			'<input type="checkbox" class="filled-in row-select" id="row-select-'+index+'"/>'+
			'<label for="row-select-'+index+'" style="height: 16px;"></label>'+
		'</td>'+
		'<td class="center-align" style="width: 10%;">'+
			'<input id="fecha-'+index+'" type="date">'+
		'</td>'+
		'<td class="center-align" style="width: 10%;">'+
			jsonFilesData[index].comprobante.serie+
		'</td>'+
		'<td style="width: 25%;" class="hover-'+index+'">'+
        	'<span class="truncate provider-name" style="width: 90%;">'+jsonFilesData[index].emisor.nombreEmisor+'</span>'+
            '<div class="card-panel card-panel-'+index+'" style="position: absolute;display: none;">'+
                '<span>'+jsonFilesData[index].emisor.nombreEmisor+'</span><br>'+
                '<span>'+jsonFilesData[index].emisor.rfcEmisor+'</span><br>'+
                '<span>FOLIO: '+jsonFilesData[index].comprobante.folio+'</span><br>'+
                '<span>Serie: '+jsonFilesData[index].comprobante.serie+'</span><br>'+
            '</div>'+
        '</td>'+
		'<td class="center-align" style="width: 10%;">$'+
			personalizaTotal(jsonFilesData[index].comprobante.total)+
		'</td>'+
		'<td style="width: 30%;">'+
			'<div>'+
				'<h6 class="center-align"><b>Forma de Pago</b></h6>'+
    			'<select class="select-payform browser-default" data-file-index="'+index+'" onchange="changeDestiny(this);">'+
					'<option selected value="01">01 - Efectivo</option>'+
					'<option value="02">02 - Cheque</option>'+
					'<option value="03">03 - Transferencia</option>'+
				'</select>'+
			'</div>'+
			'<div class="origin-'+index+'" data-file-index="'+index+'">'+
    			'<h6 class="center-align"><b>Origen</b></h6>'+
			'</div>'+
			'<div class="destiny-'+index+'" data-file-index="'+index+'">'+
    			'<h6 class="center-align"><b>Destino</b></h6>'+
			'</div>'+
		'</td>'+
	'</tr>');
	$('#modalShowConcepts'+index).modal({dismissible: false,});
	makeHoverIntent(index);
	loadBankAccounts(index);
	verifyProvider(index);
	setPayform(index);
}

function setPayform (index){
	if(	jsonFilesData[index].comprobante.formaPago == '01' ||
		jsonFilesData[index].comprobante.formaPago == '02' ||
		jsonFilesData[index].comprobante.formaPago == '03'){
		$('.select-payform[data-file-index="'+index+'"]').val(jsonFilesData[index].comprobante.formaPago);
	}
	changeDestiny($('.select-payform[data-file-index="'+index+'"]'));
}

function changeDestiny(select) {
	var index_row = $(select).attr('data-file-index');
	if($(select).val()==1){
		$('.destiny-'+index_row).hide();
	}else if($(select).val()==2){
		$('.destiny-'+index_row).html('');
		$('.destiny-'+index_row).append('<h6 class="center-align"><b>Destino</b></h6>');
		$('.destiny-'+index_row).append('<div class="input-field">'+
				'<input id="check-number-'+index_row+'" type="text" class="validate" pattern="[0-9]+" data-length="5" maxlength="5">'+
				'<label for="check-number-'+index_row+'">Número de cheque</label>'+
        	'</div>');
		$('#check-number-'+index_row).characterCounter();
		$('.destiny-'+index_row).show();
	}else if($(select).val()==3){
		var list= $('.bank-accounts').html();
		$('.destiny-'+index_row).html('');
		$('.destiny-'+index_row).append('<h6 class="center-align"><b>Destino</b></h6>');
		$('.destiny-'+index_row).append('<div class="input-field">'+
				'<input id="bank-account-destiny-'+index_row+'" type="text" class="validate" pattern="[0-9]+" data-length="18" maxlength="18">'+
				'<label for="bank-account-destiny-'+index_row+'">Cuenta bancaria destino</label>'+
        	'</div>');
		$('#bank-account-destiny-'+index_row).characterCounter();
		$('.destiny-'+index_row).show();
	}else{
		$('.destiny-'+index_row).hide();
	}
}

//Makes hover card on each provider column
function makeHoverIntent (index){
	$( ".hover-"+index).hoverIntent(
	  function() {
	    $('.card-panel-'+index).slideUp('fast');
	    $( this ).find('div').slideDown('fast');
	  }, function() {
	    $( this ).find('div').slideUp('fast');
	  }
	);
}

function loadBankAccounts (file_index){
	var list= $('.bank-accounts').html();
	$('.origin-'+file_index).append(list);
	// $('.destiny-'+file_index).append(list);
}

function setConceptsToJson(json_index) {
	var counterpart_list= [];
	$('#conceptList'+json_index+' li div div:nth-child(2) select').each(function(){
		counterpart_list.push($(this).find(':selected').attr('data-accounting-account-number'));
	});
	$.extend(jsonFilesData[json_index].concepto.contrapartidas, counterpart_list);
	//console.log(jsonFilesData[json_index]);
}

function verifyProvider(row_index) {
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

	$.ajax({
		url: 'ajaxProvision',
		type: 'POST',
		data: {_token: CSRF_TOKEN, handler: 'getProvider', provider_rfc : jsonFilesData[row_index].emisor.rfcEmisor},
	})
	.done(function(data) {
		if(data.length===0){
			$('tr[data-file-index="'+row_index+'"] td:nth-child(4) span.truncate').addClass('red-text');
		}
		else{
			$('tr[data-file-index="'+row_index+'"] td:nth-child(7) .newProviderFromProvision').remove();
			$('#modalShowConcepts'+row_index+' .accounting-account-list').each(function(){
				$(this).val(data[0].counterpart_accounting_account_id); //Set default counterpart on concept list
			});
			setConceptsToJson(row_index); //Pass counterpart accounts to json
			var provider_accounting_account=[data[0].provider_accounting_account];
			setProviderAccountToJson(row_index, provider_accounting_account);
			//console.log(jsonFilesData[row_index]);
		}
	})
	.fail(function() {
		//console.log("Error al buscar proveedor");
	})
}

function setProviderAccountToJson(row_index, provider_accounting_account) {
	$.extend(jsonFilesData[row_index].proveedor.cuentaContable, provider_accounting_account);
}

function sendJsonFiles(){
	setGenerateByToggle();
	var jsonFiles=[];
	if(verifyUnregisteredProviders()!=0){
		Materialize.toast('No se pudo procesar la petición. El proveedor de algún comprobante no está registrado.', 4000);
	}
	else{
		if(verifyUnregisteredProviders()!=0){
			Materialize.toast('No se pudo procesar la petición. Hay conceptos sin contrapartida.', 4000);
		}
		else{
			// $('#menu_navbar').slideUp();
			$('.progress').css('visibility', 'visible');
			Materialize.toast('Procesando archivos.', 2000);
			$('tbody tr').each(function(index){
				var indexJsonFile=$(this).attr("data-file-index");
				jsonFilesData[indexJsonFile].comprobante.fecha=$('#fecha-'+indexJsonFile).val();
				jsonFilesData[indexJsonFile].datosOrigen.cuentaBancariaOrigen=$('.origin-'+indexJsonFile+' select option:selected').attr('data-bank-account-number');
				jsonFilesData[indexJsonFile].datosOrigen.cuentaContableOrigen=$('.origin-'+indexJsonFile+' select option:selected').val();
				jsonFilesData[indexJsonFile].datosOrigen.bancoOrigen=$('.origin-'+indexJsonFile+' select option:selected').parent().attr('data-bank-id');
				jsonFilesData[indexJsonFile].comprobante.formaPago=$('.select-payform[data-file-index="'+indexJsonFile+'"] option:selected').val();
				// console.log(jsonFilesData[indexJsonFile].datosOrigen.cuentaBancariaOrigen);
				// console.log(jsonFilesData[indexJsonFile].datosOrigen.cuentaContableOrigen);
				//console.log(jsonFilesData[indexJsonFile].datosOrigen.bancoOrigen);

				if($('.select-payform[data-file-index="'+indexJsonFile+'"] option:selected').val()=='02'){
					jsonFilesData[indexJsonFile].datosDestino.numeroCheque=$('#check-number-'+indexJsonFile).val();
					// console.log(jsonFilesData[indexJsonFile].datosDestino.numeroCheque);
				}else if($('.select-payform[data-file-index="'+indexJsonFile+'"] option:selected').val()=='03'){
					jsonFilesData[indexJsonFile].datosDestino.cuentaBancariaDestino=$('#bank-account-destiny-'+indexJsonFile).val();
					// console.log(jsonFilesData[indexJsonFile].datosDestino.cuentaBancariaDestino);
				}
				jsonFiles.push(JSON.stringify(jsonFilesData[indexJsonFile]));
			});

			$.ajax({
				url: 'ajaxProviderPayment',
				type: 'POST',
				data: {handler: 'export' , policyType: policyType, jsonFiles: jsonFiles, generateByProvider: generate_by_toggle, cfdiIndexSerie: $('#cfdi_index_serie').val()},
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

function verifyUnregisteredProviders() {
	var amount_unregistered_providers=0;
	$('.provider-name').each(function(index){
		if($(this).hasClass('red-text')){
			amount_unregistered_providers++;
		}
	});
	return amount_unregistered_providers;
}