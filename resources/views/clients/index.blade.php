@extends('layouts.app')
@section('content')
<div class="container">
<div class="row" style="margin-bottom: 0;">
	<div class="card hoverable col s12 m12" style="float: none;">
			<div class="input-field valign-wrapper" >
			<i class="material-icons prefix">search</i>
			<input placeholder="Buscar" id="search_client" type="text" style="border-bottom: none!important;box-shadow: none!important;margin-bottom: 0;">
		</div>
	</div>
</div>
</div>
<div class="row">
    @if(count($clients)==0)
    	<h5 class="center"><b>No hay clientes registrados. :^(</b></h5>
	@else
		<div class="col s12">
			<table id="clients-tablesorter" class="card highlight" style="table-layout:fixed;">
				<thead class="grey darken-4 white-text">
					<tr>
						<th style="width: 20%;" class="center selectable">Cuenta Contable <i class="tiny material-icons no-margin">unfold_more</i></th>
						<th style="width: 40%;" class="center selectable">Cliente <i class="tiny material-icons no-margin">unfold_more</i></th>
						<th style="width: 20%;" class="center" data-sort-method='none'>RFC</th>
						<th style="width: 20%;" class="center" data-sort-method='none'>Contrapartida</th>
					</tr>
				</thead>
				<tbody>
					@foreach($clients as $key => $value)
					<tr style="cursor: pointer;" class="modal-trigger" href="#updateClientModal" data-client-id="{{$value->client_id}}" data-client-accounting-account="{{$value->client_accounting_account}}" data-client-name="{{$value->client_name}}" data-client-rfc="{{$value->client_rfc}}"  data-client-counterpart-id="{{$value->counterpart_accounting_account_id}}" data-client-bank-account="{{$value->client_bank_account_number}}" data-client-bank="{{$value->bank_id}}">
						<td class="center">{{$value->client_accounting_account}}</td>
						<td class="truncate">{{$value->client_name}}</td>
						<td class="center">{{$value->client_rfc}}</td>
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
<a style="position:fixed;bottom: 24px;right: 24px;" class="btn-floating btn-large waves-effect waves-light modal-trigger teal accent-4" href="#newClientModal">
	<i class="material-icons">add</i>
</a>
@include('clients.newClientModal')
@include('clients.updateClientModal')
@include('clients.deleteClientModal')
@endsection