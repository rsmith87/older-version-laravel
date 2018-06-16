
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
        <a href="#" onclick="doModal('#myModal', )" title="Relate to case/contact/client">
            <i class="fa fa-sitemap fa-fw"></i>
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
                {{-- <a href="javascript:rename('{{ $item->name }}')">
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
          
<div class="modal fade" id="relate-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
				<h3>
          <i class="fas fa-trash-alt"></i> Relate media to case/contact/client		
        </h3>
				<div class="clearfix"></div>
				<hr />        
        <form method="POST" action="/dashboard/contacts/contact/delete">
          <input type="hidden" name="_token" value="{{ csrf_token() }}"  />
          <input type="hidden" name="id" value="" />
          <p>Click the button below to confirm the  deletion.</p>
          <button type="submit" class="form-control mt-3 btn btn-danger">
            Delete
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
           
<script type="text/javascript">
                  
function doModal(placementId, heading, formContent, btnText)
{
    var html =  '<div id="modalWindow" class="modal hide fade in" style="display:none;">';
    html += '<div class="modal-header">';
    html += '<a class="close" data-dismiss="modal">×</a>';
    html += '<h4>'+heading+'</h4>'
    html += '</div>';
    html += '<div class="modal-body">';
    html += '<p>';
    html += formContent;
    html += '</div>';
    html += '<div class="modal-footer">';
    if (btnText!='') {
        html += '<span class="btn btn-success">';
        html += btnText;
        html += '</span>';
    }
    html += '<span class="btn" data-dismiss="modal">';
    html += 'Close';
    html += '</span>'; // close button
    html += '</div>';  // footer
    html += '</div>';  // modalWindow
    $("#"+placementId).html(html);
    $("#modalWindow").modal();
}

</script>


