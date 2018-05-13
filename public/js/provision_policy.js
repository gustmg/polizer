var standard_provision_files = document.getElementById("standard_provision_files");
standard_provision_files.addEventListener("change", getFiles, false);

var files;
var number_of_files;
var jsonFilesData = [];

function getFiles(e){
    files= e.target.files;
    number_of_files = files.length;
    
    
    
    readFile(0);

    // $("#seccion1").fadeOut("slow",leeArchivos(archivos));  
    // $.merge(lista_archivos,archivos);
    // $("#mandaExcel").prop("disabled",false);
    // $("#check-main").prop("disabled",false);
}

// function removeSelectProvisionTypeSection(provision_type_id){
// 	$(".section1").fadeOut('slow', function() {
		
// 	});
// }

function readFile(index) {
	if( index != number_of_files){
		var file = files[index];
		var filename= files[index].name;
		var file_extension = filename.split('.').pop().toLowerCase();

		if(validateExtension(file_extension)){
			var reader = new FileReader();
			reader.onload = function (e){
				getFileData(e);
				setTimeout(agregaFilaTablaProvision.bind(null, index), 1);
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
		
		
		//console.log(jsonFilesData[0]);

		
		// verificaRfcEmisor(datosXML.emisor.rfcEmisor, no_fila, datosXML.emisor.nombreEmisor);
		
	});
}

function agregaFilaTablaProvision(index){
	$('.collection').append(
		'<li class="collection-item avatar" data-file-index="'+index+'">'+
			'<i class="material-icons circle white-text">subject</i>'+
			'<span>'+
				'<b>'+jsonFilesData[index].emisor.nombreEmisor+' (RFC123456789)</b>'+
			'</span>'+
			'<a href="#!" class="secondary-content dropdown-button" data-activates="dropdown-menu'+index+'" data-alignment="right">'+
				'<i class="material-icons">more_vert</i>'+
			'</a><br>'+
			'<span class="grey-text text-darken-2">'+
				'<b>Serie:</b> <i>A</i>&nbsp;&nbsp;<b>Folio:</b> <i>1026</i>'+
			'</span><br>'+
			'<span class="grey-text text-darken-2">'+
				'<b>Fecha de emisión:</b> <i>Marzo, 2015</i></span><br>'+
			'<span class="grey-text text-darken-2">'+
				'<b>Conceptos:</b> <i>Recarga telefónica&nbsp;&nbsp;</i>'+
				'<a href="#">Ver todos los conceptos...</a>'+
			'</span><br>'+
			'<span class="grey-text text-darken-2">'+
				'<b>Contrapartida:</b> <i>1200-000-000 Recarga telefónica</i>'+
			'</span>'+
			'<ul id="dropdown-menu'+index+'" class="dropdown-content" style="min-width: 200px;">'+
				'<li><a href="#!">Agregar proveedor</a></li>'+
				'<li><a href="#!">Cambiar cuenta destino</a></li>'+
				'<li><a href="#!">Eliminar XML</a></li>'+
			'</ul>'+
		'</li>');
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