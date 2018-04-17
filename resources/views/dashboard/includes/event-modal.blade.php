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
					<div class="col-sm-6 col-12">
						<label>Name</label>    
						<input type="text" class="form-control" name="name" aria-label="Event name">
					</div>
					<div class="col-sm-6 col-12">
						<label>Description</label>
						<input type="text" class="form-control" name="description" aria-label="Event description">
					</div>
					<div class="col-sm-6 col-12">
						<label>Start date</label>
						<input type="text" class="form-control datepicker" data-toggle="datepicker" id="start_date" name="start_date" aria-label="Start date">
					</div>
					<div class="col-sm-6 col-12">
						<label>End date</label>
						<input type="text" class="form-control datepicker" data-toggle="datepicker" id="end_date" name="end_date" aria-label="End date">
					</div>
					<div class="col-sm-6 col-12">
						<label>Time start</label>
						<input type="text" class="form-control timepicker-start" id="start_time" name="start_time" aria-label="Start time">
					</div>
					<div class="col-sm-6 col-12">
						<label>Time end</label>
						<input type="text" class="form-control timepicker-end" id="end_time" name="end_time" aria-label="End time">
					</div>
					<div class="col-12">
						<button class="btn btn-primary mt-4"><i class="fas fa-check"></i> Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>