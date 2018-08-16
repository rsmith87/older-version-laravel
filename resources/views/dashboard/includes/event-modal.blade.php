<div class="modal fade" id="event-modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<h3>
				<i class="fas fa-calendar-plus"></i> Create an event
				</h3>
				<div class="clearfix"></div>
				<hr />
				<form method="post" action="/dashboard/calendar/event/add">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
          @if(isset($case))
            @if(count($case) > 0)
              <input type="hidden" name="c_id" value="{{ $case->id }}" />
			@elseif(count($requested_case) > 0)
				<input type="hidden" name="c_id" value="{{ $requested_case->id }}" />
            @endif
		  @endif
					<div class="col-sm-6 col-xs-12">
						<label>Date</label>
						<input type="text" class="form-control dp" data-toggle="dp" id="start_date" name="start_date" aria-label="Start date">
					</div>
					<div class="clearfix"></div>
					<div class="col-sm-6 col-xs-12">
						<label>Name</label>    
						<input type="text" class="form-control" name="name" aria-label="Event name">
					</div>
					<div class="col-sm-6 col-xs-12">
						<label>Type</label>
						<select name="event_type" class="form-control">
							@foreach($event_types as $t)
								<option value={{ $t }}>{{ ucwords($t) }}</option>
							@endforeach
						</select>
					</div>
					<div class="col-xs-12">
						<label>Description</label>
						<textarea class="form-control" name="description" aria-label="Event description"></textarea>
					</div>
					<div class="col-sm-6 col-xs-12">
						<label>Start time</label>
						<input type="text" class="form-control timepicker-start" id="start_time" name="start_time" aria-label="Start time">
					</div>
					<div class="col-sm-6 col-xs-12">
						<label>End time</label>
						<input type="text" class="form-control timepicker-start" id="end_time" name="end_time" aria-label="End time">
					</div>					
					<div class="col-xs-12">
						<button class="btn btn-primary mt-4"><i class="fas fa-check"></i> Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>