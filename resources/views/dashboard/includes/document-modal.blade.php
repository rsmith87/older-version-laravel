<div class="modal fade" id="document-modal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-body">
				<h3>
				<i class="fas fa-cloud-upload-alt"></i> Upload document
				</h3>
				<div class="clearfix"></div>
				<hr />
				<form method="post" action="/dashboard/forms/upload" enctype="multipart/form-data">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<div class="col-sm-6 col-xs-12">
						<label for="file_upload">File</label>
						<input type="file" class="form-control popup-selector" data-inputid="feature_image" name="file_upload" />
					</div>
					<div class="col-sm-6 col-xs-12">
						<label for="file_name">File Name</label>
						<input type="text" class="form-control" name="file_name" placeholder="File Name" />
					</div>
					<div class="clearfix"></div>
					<div class="col-xs-12">
						<label for="file_description">File Description</label>
						<textarea type="text" class="form-control" name="file_description" placeholder="File Description"></textarea>
					</div>
					<div class="col-xs-12">
						<button class="btn btn-primary mt-3 mb-3" type="submit">
							<i class="fas fa-check"></i> Submit
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>