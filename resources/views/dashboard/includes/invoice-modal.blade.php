<div class="modal fade" id="payment-modal-full">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<h3>
					<i class="fas fa-tasks"></i> Create invoice
				</h3>
				<div class="clearfix"></div>
				<hr />
				<form role="form" method="post" action="/dashboard/invoices/invoice/create">
					<input type="hidden" name="case_id" value="{{ $case->id }}"/>
					<input type="hidden" name="invoicable_id" value="{{ !empty($invoicable_id) ? $invoicable_id : "" }}" />
					<input type="hidden" name="total_amount" value="{{ $invoice_amount }}.00" />
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<div class="col-12">
						<label>Amount</label>
						<input type="text" class="form-control" value="{{ $invoice_amount }}.00" name="amount" />
					</div>
					<div class="col-12 mb-2">
						<label class="mt-2">Client</label>
						@if(!empty($clients))
							@foreach($clients as $client)
								<input type="hidden" name="client_id" value="{{ $client->id }}" />
								<input type="text" class="form-control" class="mb-3" name="contact_name" value="{{ $client->first_name }} {{ $client->last_name }}" />
							@endforeach
						@else
							<input type="hidden" name="client_id" />
							<p>Enter a contact name to start creating one now!</p>
							<input type="text" class="form-control" class="mb-3" name="contact_name" />	
						@endif
					</div>
					<div class="col-12">
						<input type="submit" class="btn btn-primary mt-2 form-control" />
					</div>
				</form>
			</div>
		</div>
	</div>
</div>