
@if((sizeof($files) > 0) || (sizeof($directories) > 0))
<table class="table table-responsive table-condensed table-striped hidden-xs table-list-view">
  <thead>
  <th style='width:50%;'>{{ Lang::get('laravel-filemanager::lfm.title-item') }}</th>
  <th>{{ Lang::get('laravel-filemanager::lfm.title-size') }}</th>
  <th>{{ Lang::get('laravel-filemanager::lfm.title-type') }}</th>
  <th>{{ Lang::get('laravel-filemanager::lfm.title-modified') }}</th>
  <th>{{ Lang::get('laravel-filemanager::lfm.title-action') }}</th>
</thead>
<tbody>
  @foreach($items as $item)
  <tr>
    <td>
      <i class="fa {{ $item->icon }}"></i>
      <a class="{{ $item->is_file ? 'file' : 'folder'}}-item clickable" data-id="{{ $item->is_file ? $item->url : $item->path }}" title="{{$item->name}}">
        {{ str_limit($item->name, $limit = 40, $end = '...') }}
      </a>
    </td>
    <td>{{ $item->size }}</td>
    <td>{{ $item->type }}</td>
    <td>{{ $item->time }}</td>
    <td class="actions">
      @if($item->is_file)
      <a href="javascript:download('{{ $item->name }}')" title="{{ Lang::get('laravel-filemanager::lfm.menu-download') }}">
        <i class="fa fa-download fa-fw"></i>
      </a>
      <a href="#" data-toggle="modal" data-target="#relate-modal-{{ preg_replace("/[^ \w]+/", "", $item->name) }}" href="#" title="Relate to case/contact/client">
        <i class="fa fa-sitemap fa-fw"></i>
      </a>
      <a href="#" data-toggle="modal" data-target="#share-modal-{{ preg_replace("/[^ \w]+/", "", $item->name) }}" href="#" title="Share with user/client/contact">
        <i class="fa fa-share"></i>
      </a>
      @if($item->thumb)
      <a href="javascript:fileView('{{ $item->url }}', '{{ $item->updated }}')" title="{{ Lang::get('laravel-filemanager::lfm.menu-view') }}">
        <i class="fa fa-image fa-fw"></i>
      </a>
      <a href="javascript:cropImage('{{ $item->name }}')" title="{{ Lang::get('laravel-filemanager::lfm.menu-crop') }}">
        <i class="fa fa-crop fa-fw"></i>
      </a>
      <a href="javascript:resizeImage('{{ $item->name }}')" title="{{ Lang::get('laravel-filemanager::lfm.menu-resize') }}">
        <i class="fa fa-arrows fa-fw"></i>
      </a>
      @endif
      @endif
      <a href="javascript:rename('{{ $item->name }}')" title="{{ Lang::get('laravel-filemanager::lfm.menu-rename') }}">
        <i class="fa fa-edit fa-fw"></i>
      </a>
      <a href="javascript:trash('{{ $item->name }}')" title="{{ Lang::get('laravel-filemanager::lfm.menu-delete') }}">
        <i class="fa fa-trash fa-fw"></i>
      </a>
    </td>
  </tr>
  @endforeach
</tbody>
</table>

<table class="table visible-xs">
  <tbody>
    @foreach($items as $item)
    <tr>
      <td>
        <div class="media" style="height: 70px;">
          <div class="media-left">
            <div class="square {{ $item->is_file ? 'file' : 'folder'}}-item clickable"  data-id="{{ $item->is_file ? $item->url : $item->path }}">
              @if($item->thumb)
              <img src="{{ $item->thumb }}">
              @else
              <i class="fa {{ $item->icon }} fa-5x"></i>
              @endif
            </div>
          </div>
          <div class="media-body" style="padding-top: 10px;">
            <div class="media-heading">
              <p>
                <a class="{{ $item->is_file ? 'file' : 'folder'}}-item clickable" data-id="{{ $item->is_file ? $item->url : $item->path }}">
                  {{ str_limit($item->name, $limit = 20, $end = '...') }}
                </a>
                &nbsp;&nbsp;
                    {{-- <a href="javascript:rename('{{ $item->name }}'                        )">
                            <i class="fa fa-edit"></i>
                      </a> --}}
                      </p>
                      </div>
                      <p style="color: #aaa;font-weight: 400">{{ $item->time }}</p>
                      </div>
                      </div>
                      </td>
                      </tr>
                      @endforeach
                      </tbody>
                      </table>

                      @else
                      <p>{{ trans('laravel-filemanager::lfm.message-empty') }}</p>
                      @endif

                      @foreach($items as $item)
                      <div class="modal fade" id="relate-modal-{{preg_replace("/[^ \w]+/", "", $item->name)}}">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-body">
                              <h3>
                                <i class="fas fa-trash-alt"></i> Relate media to case/contact/client
                              </h3>
                              <div class="clearfix"></div>
                              <hr />
                              <form method="POST" action="/dashboard/documents/document/relate">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}"  />
                                <input type="hidden" name="id" value="{{ $item->name }}" />
                                <p>Search below in the text input to relate this media to a Case, client or contact.</p>

                                <label>Case</label>
                                <input type="hidden" name="case_id" />
                                <input type="text" name="case_name" class="form-control" autocomplete="off" />
                                <label>Contact</label>
                                <input type="hidden" name="contact_id" />
                                <input type="text" name="contact_name" class="form-control" autocomplete="off" />
                                <label>Client</label>
                                <input type="hidden" name="client_id" />
                                <input type="text" name="client_name" class="form-control" autocomplete="off" />

                                <button type="submit" class="form-control mt-3 btn btn-primary">
                                  Relate
                                </button>
                              </form>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="modal fade" id="share-modal-{{preg_replace("/[^ \w]+/", "", $item->name)}}">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-body">
                              <h3>
                                <i class="fas fa-trash-alt"></i> Relate media to case/contact/client
                              </h3>
                              <div class="clearfix"></div>
                              <hr />
                              <form method="POST" action="/dashboard/documents/document/share">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}"  />
                                <input type="hidden" name="id" value="{{ $item->name }}" />
                                <p>Share person</p>
                                <select name='firm_users'>


                                </select>
                                <button type="submit" class="form-control mt-3 btn btn-primary">
                                  Delete
                                </button>
                              </form>
                            </div>
                          </div>
                        </div>
                      </div>
                      @endforeach

<script src="{{ asset('js/autocomplete.js') }}"></script>

<script type="text/javascript">


          @if (isset($clients))
          @if (count($clients) > 0)
  var clients = {!! json_encode($clients->toArray()) !!}

  for (var i = 0; i < clients.length; i++) {
	clients[i].data = clients[i]['id'];
	clients[i].value = clients[i]['first_name'] + " " + clients[i]['last_name'];
  }

  $('input[name="client_name"]').autocomplete({
	lookup: clients,
	width: 'flex',
	triggerSelectOnValidInput: true,
	onSelect: function (suggestion) {
	  var thehtml = '<strong>Case ' + suggestion.data + ':</strong> ' + suggestion.value + ' ';
	  //alert(thehtml);
	  var $this = $(this);
	  $('#outputcontent').html(thehtml);
	  $this.prev().val(suggestion.data);
	}
  });
          @endif
          @endif
          @if (isset($contacts))
          @if (count($contacts) > 0)
  var contacts = {!! json_encode($contacts->toArray()) !!}
  ;
  for (var i = 0; i < contacts.length; i++) {
	contacts[i].data = contacts[i]['id'];
	contacts[i].value = contacts[i]['first_name'] + " " + contacts[i]['last_name'];
  }


  $('input[name="contact_name"]').autocomplete({
	lookup: contacts,
	width: 'flex',
	triggerSelectOnValidInput: true,
	onSelect: function (suggestion) {
	  var thehtml = '<strong>Case ' + suggestion.data + ':</strong> ' + suggestion.value + ' ';
	  //alert(thehtml);
	  var $this = $(this);
	  $('#outputcontent').html(thehtml);
	  $this.prev().val(suggestion.data);
	}

  });
          @endif
          @endif



          @if (isset($cases))
          @if (count($cases) > 0)
  var cases = {!! json_encode($cases->toArray()) !!}
  ;
  for (var i = 0; i < cases.length; i++) {
	cases[i].data = cases[i]['id'];
	cases[i].value = cases[i]['name'];
  }

  $('input[name="case_name"]').autocomplete({
	lookup: cases,
	width: 'flex',
	triggerSelectOnValidInput: true,
	onSelect: function (suggestion) {
	  var thehtml = '<strong>Case ' + suggestion.data + ':</strong> ' + suggestion.value + ' ';
	  //alert(thehtml);
	  var $this = $(this);
	  $('#outputcontent').html(thehtml);
	  $this.prev().val(suggestion.data);
	}
  });

  @endif
  @endif

</script>
