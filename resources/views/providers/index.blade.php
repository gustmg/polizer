@extends('layouts.app')

@section('content')
<div class="container">
<div class="row" style="margin-bottom: 0;">
	<div class="card hoverable col s12 m12" style="float: none;">
			<div class="input-field valign-wrapper" >
			<i class="material-icons prefix">search</i>
			<input placeholder="Buscar" id="search_provider" type="text" style="border-bottom: none!important;box-shadow: none!important;margin-bottom: 0;">
		</div>
	</div>
</div>
</div>
<div class="row">
    @if(count($providers)==0)
    	<h5 class="center"><b>No hay proveedores registrados. :^(</b></h5>
	@else
		<div class="col s12">
			<table class="card highlight providers-tablesorter" style="table-layout:fixed;">
				<thead class="grey darken-4 white-text">
					<tr>
						<th style="width: 20%;" class="center selectable">Cuenta Contable <i class="tiny material-icons no-margin">unfold_more</i></th>
						<th style="width: 40%;" class="center selectable">Proveedor <i class="tiny material-icons no-margin">unfold_more</i></th>
						<th style="width: 20%;" class="center">RFC</th>
						<th style="width: 20%;" class="center">Contrapartida</th>
					</tr>
				</thead>
				<tbody>
					@foreach($providers as $key => $value)
					<tr style="cursor: pointer;" class="modal-trigger" href="#updateProviderModal" data-provider-id="{{$value->provider_id}}" data-provider-accounting-account="{{$value->provider_accounting_account}}" data-provider-name="{{$value->provider_name}}" data-provider-rfc="{{$value->provider_rfc}}" data-provider-counterpart-id="{{$value->counterpart_accounting_account_id}}">
						<td class="center">{{$value->provider_accounting_account}}</td>
						<td class="truncate">{{$value->provider_name}}</td>
						<td class="center">{{$value->provider_rfc}}</td>
						@if($value->counterpart_accounting_account_id == null)
							<td class="center">N / A</td>
						@else
							<td class="center tooltipped" data-position="bottom" data-delay="600" data-tooltip="{{$value->counterpart_account->accounting_account_description}}">{{$value->counterpart_account->accounting_account_number}}</td>
						@endif
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
    @endif
</div>
<a style="position:fixed;bottom: 24px;right: 24px;" class="btn-floating btn-large waves-effect waves-light modal-trigger teal accent-4" href="#newProviderModal">
	<i class="material-icons">add</i>
</a>
@include('providers.newProviderModal')
@include('providers.updateProviderModal')
@include('providers.deleteProviderModal')
@endsection