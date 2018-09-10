@extends('layouts.app')

@section('content')
<div class="container">
<div class="row" style="margin-bottom: 0;">
	<div class="card hoverable col s12 m12" style="float: none;">
			<div class="input-field valign-wrapper" >
			<i class="material-icons prefix">search</i>
			<input placeholder="Buscar" id="search_bank_account" type="text" style="border-bottom: none!important;box-shadow: none!important;margin-bottom: 0;">
		</div>
	</div>
</div>
</div>
<div class="row">
    @if(count($bank_accounts)==0)
    	<h5 class="center"><b>No hay cuentas bancarias registradas. :^(</b></h5>
	@else
		<div class="col s12">
			<table class="card highlight bank-accounts-table" style="table-layout:fixed;">
				<thead class="grey darken-4 white-text">
					<tr>
						<th style="width: 40%;" class="center">Cuenta bancaria</th>
						<th style="width: 40%;" class="center">Cuenta Contable</th>
						<th style="width: 20%;" class="center">Banco</th>
					</tr>
				</thead>
				<tbody>
					@foreach($bank_accounts as $key => $value)
					<tr style="cursor: pointer;" class="modal-trigger" href="#updateBankAccountModal" data-bank-account-id="{{$value->bank_account_id}}" data-bank-account-number="{{$value->bank_account_number}}" data-bank-account-bank-id="{{$value->bank_id}}" data-bank-account-counterpart-id="{{$value->counterpart_accounting_account_id}}">
						<td class="center">{{$value->bank_account_number}}</td>
						@if($value->counterpart_accounting_account_id == null)
							<td class="center">N / A</td>
						@else
							<td class="center tooltipped" data-position="bottom" data-delay="600" data-tooltip="{{$value->counterpart_account->accounting_account_number}}">{{$value->counterpart_account->accounting_account_description}}</td>
						@endif
						@if($value->bank_id == null)
							<td class="center">N / A</td>
						@else
							<td class="center">
								{{$value->bank->bank_name}}
							</td>
						@endif
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
    @endif
</div>
<a style="position:fixed;bottom: 24px;right: 24px;" class="btn-floating btn-large waves-effect waves-light modal-trigger teal accent-4" href="#newBankAccountModal">
	<i class="material-icons">add</i>
</a>
@include('bank_accounts.newBankAccountModal')
@include('bank_accounts.updateBankAccountModal')
@include('bank_accounts.deleteBankAccountModal')
@endsection