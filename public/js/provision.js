//Objetos para cargar archivos
var standard_provision_files = document.getElementById("standard_provision_files");
var ieps_provision_files = document.getElementById("ieps_provision_files");
var honorarium_provision_files = document.getElementById("honorarium_provision_files");
var freight_provision_files = document.getElementById("freight_provision_files");
standard_provision_files.addEventListener("change", getFiles, false);
ieps_provision_files.addEventListener("change", getFiles, false);
honorarium_provision_files.addEventListener("change", getFiles, false);
freight_provision_files.addEventListener("change", getFiles, false);

//Handler para tipo de provisión
var provisionType;

function setProvisionType(idProvisionType){
	provisionType=idProvisionType;
}

//Objetos para manejo de archivos
var files;
var number_of_files;
var jsonFilesData = [];
var total_uploaded_files = 0;

//Modals
$('#modalShowConcepts').modal({
	ready: function(modal, trigger) {
		file_index = trigger.parent().parent().attr("data-file-index");
		
	},
});

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
		console.log('Se leyeron todos los archivos');
		console.log("Total de archivos cargados: "+total_uploaded_files);
		console.log(jsonFilesData);
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
				"importes"	: importes,
				"contrapartidas" : contrapartidas
			},

			"impuestos" : {
				"totalImpuestosTrasladados" : impuestos.attr('totalImpuestosTrasladados')
			},

			"proveedor" : {
				"cuentaContable" : cuenta_proveedor
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
				"importes"	: importes,
				"contrapartidas" : contrapartidas
			},

			"impuestos" : {
				"totalImpuestosTrasladados" : impuestos.attr('TotalImpuestosTrasladados')
			},

			"proveedor" : {
				"cuentaContable" : cuenta_proveedor
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

//Funciones para mostrar tabla
function agregaFilaTablaProvision(index){
	$('tbody').append(
    '<tr data-file-index="'+index+'" data-rfc-provider="'+jsonFilesData[index].emisor.rfcEmisor+'">'+
        '<td class="center-align valign-wrapper">'+
            '<input type="checkbox" class="filled-in row-select" id="row-select-'+index+'"/>'+
            '<label for="row-select-'+index+'"></label>'+
        '</td>'+
        '<td style="width: 7%;" class="center-align">'+personalizaFecha(jsonFilesData[index].comprobante.fecha)+'</td>'+
        '<td style="width: 10%;" class="center-align">'+jsonFilesData[index].comprobante.serie+'</td>'+
        '<td style="width: 25%;" class="hover-'+index+'">'+
        	'<span class="truncate" style="width: 90%;">'+jsonFilesData[index].emisor.nombreEmisor+'</span>'+
            '<div class="card-panel card-panel-'+index+'" style="position: absolute;display: none;">'+
                '<span>'+jsonFilesData[index].emisor.nombreEmisor+'</span><br>'+
                '<span>'+jsonFilesData[index].emisor.rfcEmisor+'</span><br>'+
                '<span>FOLIO: '+jsonFilesData[index].comprobante.folio+'</span><br>'+
                '<span>Serie: '+jsonFilesData[index].comprobante.serie+'</span><br>'+
            '</div>'+
        '</td>'+
        '<td style="width: 30%;">'+
        	'<span class="truncate" style="width: 90%;">'+jsonFilesData[index].concepto.descripciones[0]+'</span>'+
        '</td>'+
        '<td style="width: 10%;" class="center-align">$'+personalizaTotal(jsonFilesData[index].comprobante.total)+'</td>'+
        '<td style="width: 10%;" class="center-align">'+
			'<a href="#newProviderModal" class="modal-trigger newProviderFromProvision">'+
				'<i class="material-icons black-text">person_add</i>'+
			'</a>'+
			'&nbsp;'+
			'<a href="#modalShowConcepts" class="modal-trigger" onclick="loadConcepts('+index+');">'+
				'<i class="material-icons black-text">list</i>'+
			'</a>'+
        '</td>'+
	'</tr>');
	makeHoverIntent(index);
}

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

function loadConcepts (file_index){
	$('#conceptList').html('');
	$.each(jsonFilesData[file_index].concepto.descripciones, function(key, description) {
		$('#conceptList').append(
		'<li class="collection-item">'+
			'<div class="row no-margin">'+
				'<div class="col s6">'+
					'<span ><b class="truncate">'+description+'</b> $200.00</span>'+
				'</div>'+
				'<div class="col s6">'+
					'<select class="browser-default secondary-content">'+
					    '<option value="" disabled selected>Choose your option</option>'+
					    '<option value="1">Option 1</option>'+
					    '<option value="2">Option 2</option>'+
					    '<option value="3">Option 3</option>'+
					'</select>'+
				'</div>'+
			'</div>'+
		'</li>');
	});
}