<h2>Add a new message</h2>
<form action="{{ route('messages.update', $thread->id) }}" method="POST">
    {{ csrf_field() }}
        
    <!-- Message Form Input -->
    <div class="form-group">
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
        <button type="submit" id="message-update" class="btn btn-primary form-control">Submit</button>
    </div>
</form>

