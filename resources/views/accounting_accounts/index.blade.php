@extends('layouts.app')

@section('content')
<div class="container">
<div class="row" style="margin-bottom: 0;">
	<div class="card hoverable col s12 m12" style="float: none;">
			<div class="input-field valign-wrapper" >
			<i class="material-icons prefix">search</i>
			<input placeholder="Buscar" id="search_accounting_account" type="text" style="border-bottom: none!important;box-shadow: none!important;margin-bottom: 0;">
		</div>
	</div>
</div>
</div>
<div class="row">
    @if(count($accounting_accounts)==0)
    	<h5 class="center"><b>No hay cuentas contables registradas. :^(</b></h5>
	@else
		<div class="col s12">
			<table id="accounting-accounts-tablesorter" class="card highlight" style="table-layout:fixed;">
				<thead class="grey darken-4 white-text">
					<tr>
						<th style="width: 20%;" class="center selectable">Cuenta Contable <i class="tiny material-icons no-margin">unfold_more</i></th>
						<th style="width: 40%;" class="center selectable">Descripción <i class="tiny material-icons no-margin">unfold_more</i></th>
						<th style="width: 40%;" class="center" data-sort-method='none'>Tipo de Cuenta</th>
					</tr>
				</thead>
				<tbody>
					@foreach($accounting_accounts as $key => $value)
					<tr onclick="updateAccountingAccountModal({{$value->accounting_account_id}},{{$value->accounting_account_type_id}});" style="cursor: pointer;" class="modal-trigger" href="#updateAccountingAccountModal" data-accounting-account-id="{{$value->accounting_account_id}}" data-accounting-account-number="{{$value->accounting_account_number}}" data-accounting-account-description="{{$value->accounting_account_description}}" data-accounting-account-type-description="{{$accounting_account_types[($value->accounting_account_type_id)-1]->accounting_account_type_description}}">
						<td class="center">{{$value->accounting_account_number}}</td>
						<td class="truncate tooltipped" data-position="bottom" data-delay="600" data-tooltip="{{$value->accounting_account_description}}">{{$value->accounting_account_description}}</td>
						<td class="center">{{$accounting_account_types[($value->accounting_account_type_id)-1]->accounting_account_type_description}}</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
    @endif
</div>
<a style="position:fixed;bottom: 24px;right: 24px;" class="btn-floating btn-large waves-effect waves-light modal-trigger teal accent-4" href="#newAccountingAccountModal">
	<i class="material-icons">add</i>
</a>
@include('accounting_accounts.newAccountingAccountModal')
@include('accounting_accounts.updateAccountingAccountModal')
@include('accounting_accounts.deleteAccountingAccountModal')
@endsection