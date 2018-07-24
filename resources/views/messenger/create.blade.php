
    <h1>Create a new message</h1>
    <form action="{{ route('messages.store') }}" method="post">
        {{ csrf_field() }}
            <!-- Subject Form Input -->
            <div class="form-group">
                <label class="control-label">Subject</label>
                <input type="text" class="form-control" name="subject" placeholder="Subject"
                       value="{{ old('subject') }}">
            </div>


            <!-- Message Form Input -->
            <div class="form-group">
                <label class="control-label">Message</label>
                <textarea name="message" class="form-control">{{ old('message') }}</textarea>
            </div>
		  <div class="form_group">
			<label>Users</label>
			@if(count($firm_users) > 0)
			  <div class="checkbox">
				@foreach($firm_users as $f_u)
				  @foreach($f_u->Firm as $u)
					<label title="{{ !empty($u->name) ? $u->name : $u->email }}">
					  <input type="checkbox" name="recipients[]" value="{{ $u->id }}">{{ !empty($u->name) ? $u->name : $u->email }}</label>
					<br />
				  @endforeach
				@endforeach
			  </div>
			@endif
		  </div>
    
            <!-- Submit Form Input -->
            <div class="form-group">
                <button type="submit" class="btn btn-primary form-control">Submit</button>
            </div>
        </div>
    </form>
</div>

