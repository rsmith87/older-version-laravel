<div class="modal fade" id="event-list-modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<h3>
				<i class="fas fa-calendar-plus"></i> My events
				</h3>
				<div class="clearfix"></div>
				<hr />
				@foreach($events as $event)
					<div class="col-xs-12">
						<div class="col-sm-6">
							<p>
								@if(\Carbon\Carbon::parse($event->start_date)->format('m/d/y') === \Carbon\Carbon::parse($event->end_date)->format('m/d/y')))
									{{ $event->start_date }}
								@endif
							</p>
						</div>
					</div>
				@endforeach
			</div>
		</div>
	</div>
</div>