@extends('layouts.app')

@section('content')
<div class="container">
<div class="row" style="margin-bottom: 0;">
	<div class="card hoverable col s12 m12" style="float: none;">
			<div class="input-field valign-wrapper" >
			<i class="material-icons prefix">search</i>
			<input placeholder="Buscar" id="first_name" type="text" style="border-bottom: none!important;box-shadow: none!important;margin-bottom: 0;">
		</div>
	</div>
</div>
</div>
<div class="row">
	<div class="col s12">
		<ul class="collapsible popout" data-collapsible="accordion">
			@foreach($companies as $key => $value)
			<li>
				<div class="collapsible-header blue-grey darken-3 white-text">
					<i class="material-icons">business</i>
					<b>{{ $value->company_name}}</b>
					<span class="badge">
						<a href="#"><i class="material-icons">edit</i></a>
						<a href="#deleteCompanyModal" class="modal-trigger"><i class="material-icons">delete</i></a>
					</span>
				</div>
				<div class="collapsible-body white">
					
					<h6 class="grey-text text-darken-1">
						<b>Cuentas contables</b>
					</h6>
					<div class="card">
					    <ul class="collection">
							<li class="collection-item row">
								<div class="col s12 m6 left-align">
									<b>Cuenta de IVA acreditable pendiente: </b>
								</div>
								<div class="col s12 m6 right-align">
									<span>{{$value->pending_creditable_vat_account}}</span>
								</div>
							</li>
							<li class="collection-item row">
								<div class="col s12 m6 left-align">
									<b>Cuenta de IVA acreditable pagado: </b>
								</div>
								<div class="col s12 m6 right-align">
									<span>{{$value->paid_creditable_vat_account}}</span>
								</div>
							</li>
							<li class="collection-item row">
								<div class="col s12 m6 left-align">
								<b>Cuenta de IVA trasladado: </b>
								</div>
								<div class="col s12 m6 right-align">
									<span>{{$value->transferred_vat_account}}</span>
								</div>
							</li>
							<li class="collection-item row">
								<div class="col s12 m6 left-align">
									<b>Cuenta de IVA trasladado cobrado: </b>
								</div>
								<div class="col s12 m6 right-align">
									<span>{{$value->charged_transferred_vat_account}}</span>
								</div>
							</li>
							<li class="collection-item row">
								<div class="col s12 m6 left-align">
									<b>Cuenta de retención ISR honorarios: </b>
								</div>
								<div class="col s12 m6 right-align">
									<span>{{$value->fees_retention_isr_account}}</span>
								</div>
							</li>
							<li class="collection-item row">
								<div class="col s12 m6 left-align">
									<b>Cuenta de retención IVA honorarios: </b>
								</div>
								<div class="col s12 m6 right-align">
									<span>{{$value->fees_retention_vat_account}}</span>
								</div>
							</li>
							<li class="collection-item row">
								<div class="col s12 m6 left-align">
									<b>Cuenta de retención IVA fletes: </b>
								</div>
								<div class="col s12 m6 right-align">
									<span>{{$value->freight_retention_vat_account}}</span>
								</div>
							</li>
					    </ul>
				    </div>

			        <h6 class="grey-text text-darken-1">
			    		<b>Catálogos</b>
			    	</h6>
			    	<div class="card">
			    	    <ul class="collection">
		    				<li class="collection-item row">
		    					<div class="col s12 m6 left-align">
		    						<b>Total de Proveedores: </b>
		    					</div>
		    					<div class="col s12 m6 right-align">
		    						<span>{{$value->charged_transferred_vat_account}}</span>
		    					</div>
		    				</li>
		    				<li class="collection-item row">
		    					<div class="col s12 m6 left-align">
		    						<b>Total de Clientes: </b>
		    					</div>
		    					<div class="col s12 m6 right-align">
		    						<span>{{$value->fees_retention_isr_account}}</span>
		    					</div>
		    				</li>
		    				<li class="collection-item row">
		    					<div class="col s12 m6 left-align">
		    						<b>Total de Cuentas Contables: </b>
		    					</div>
		    					<div class="col s12 m6 right-align">
		    						<span>{{$value->fees_retention_vat_account}}</span>
		    					</div>
		    				</li>
		    				<li class="collection-item row">
		    					<div class="col s12 m6 left-align">
		    						<b>Total de Cuentas Bancarias: </b>
		    					</div>
		    					<div class="col s12 m6 right-align">
		    						<span>{{$value->freight_retention_vat_account}}</span>
		    					</div>
		    				</li>
			    	    </ul>
			        </div>

				    <h6 class="grey-text text-darken-1">
						<b>Otros Datos</b>
					</h6>
					<div class="card">
					    <ul class="collection">
							<li class="collection-item row">
								<div class="col s12 m6 left-align">
									<b>Fecha de Creación: </b>
								</div>
								<div class="col s12 m6 right-align">
									<span>{{$value->created_at}}</span>
								</div>
							</li>
							<li class="collection-item row">
								<div class="col s12 m6 left-align">
									<b>Ultima vez modificada: </b>
								</div>
								<div class="col s12 m6 right-align">
									<span>{{$value->updated_at}}</span>
								</div>
							</li>
							<li class="collection-item row">
		    					<div class="col s12 m6 left-align">
		    						<b>Total de CFDI's procesados: </b>
		    					</div>
		    					<div class="col s12 m6 right-align">
		    						<span>{{$value->transferred_vat_account}}</span>
		    					</div>
		    				</li>
					    </ul>
				    </div>
				</div>
			</li>
			@endforeach
		</ul>
    </div>    
</div>
<a style="position:fixed;bottom: 24px;right: 24px;" class="btn-floating btn-large waves-effect waves-light modal-trigger teal" href="#newCompanyModal">
	<i class="material-icons">add</i>
</a>
<div id="newCompanyModal" class="modal newCompanyModal modal-fixed-footer">
	<div class="modal-content">
		<div class="row">
			<div class="col s12">
				<h5>Nueva empresa</h5>
			</div>
			<form id="newCompanyForm" class="col s12 no-padding" method="POST" action="{{ route('companies') }}">
				{{ csrf_field() }}
				<div class="row" style="margin-bottom: 10px;">
					<div class="col s12 grey-text text-darken-2"><b>Información general</b></div>
					<div class="input-field col s12 m7">
			          <input id="company_name" name="company_name" type="text" class="validate" required>
			          <label for="company_name" data-error="Verifique este campo" data-success="Campo validado">Nombre de la empresa *</label>
			        </div>
			        <div class="input-field col s12 m5">
			          <input id="company_rfc" name="company_rfc" type="text" class="validate">
			          <label for="company_rfc">RFC de la empresa</label>
			        </div>
		        </div>
		        <div class="row">
					<div class="col s12 grey-text text-darken-2"><b>Cuentas contables</b></div>
					<div class="col s12 m6">
						<div class="input-field no-padding">
				          <input id="pending_creditable_vat_account" name="pending_creditable_vat_account" name="pending_creditable_vat_account" placeholder="XXXX-XXX-XXX" type="text" class="validate">
				          <label for="pending_creditable_vat_account">IVA Acreditable Pendiente</label>
						</div>
					</div>
					<div class="col s12 m6">
						<div class="input-field no-padding">
				          <input id="paid_creditable_vat_account" name="paid_creditable_vat_account" placeholder="XXXX-XXX-XXX" type="text" class="validate">
				          <label for="paid_creditable_vat_account">IVA Acreditable Pagado</label>
						</div>
					</div>
					<div class="col s12 m6">
						<div class="input-field no-padding">
				          <input id="transferred_vat_account" name="transferred_vat_account" placeholder="XXXX-XXX-XXX" type="text" class="validate">
				          <label for="transferred_vat_account">IVA Trasladado</label>
						</div>
					</div>
					<div class="col s12 m6">
						<div class="input-field no-padding">
				          <input id="charged_transferred_vat_account" name="charged_transferred_vat_account" placeholder="XXXX-XXX-XXX" type="text" class="validate">
				          <label for="charged_transferred_vat_account">IVA Trasladado Cobrado</label>
						</div>
					</div>
					<div class="col s12 m6">
						<div class="input-field no-padding">
				          <input id="fees_retention_isr_account" name="fees_retention_isr_account" placeholder="XXXX-XXX-XXX" type="text" class="validate">
				          <label for="fees_retention_isr_account">Retención ISR Honorarios</label>
						</div>
					</div>
					<div class="col s12 m6">
						<div class="input-field no-padding">
				          <input id="fees_retention_vat_account" name="fees_retention_vat_account" placeholder="XXXX-XXX-XXX" type="text" class="validate">
				          <label for="fees_retention_vat_account">Retención IVA Honorarios</label>
						</div>
					</div>
					<div class="col s12 m6">
						<div class="input-field no-padding">
				          <input id="freight_retention_vat_account" name="freight_retention_vat_account" placeholder="XXXX-XXX-XXX" type="text" class="validate">
				          <label for="freight_retention_vat_account">Retención IVA Fletes</label>
						</div>
					</div>
		        </div>
			</form>
		</div>
	</div>
	<div class="modal-footer">
		<a href="#!" class="modal-action modal-close waves-effect btn-flat"><b>Cancelar</b></a>
		<button id="registrate_button" onclick="submitNewCompany();" class="modal-action btn waves-effect" disabled><b>Registrar</b></button>
	</div>
</div>

<div id="deleteCompanyModal" class="modal deleteCompanyModal">
	<div class="modal-content">
		<h5>Eliminar Empresa</h5>
	</div>
	<div class="modal-footer">
		<a href="#!" class="modal-action modal-close waves-effect btn-flat"><b>Cancelar</b></a>
		<button id="registrate_button" onclick="submitNewCompany();" class="modal-action btn-flat waves-effect" disabled><b>Eliminar Empresa</b></button>
	</div>
</div>
@endsection