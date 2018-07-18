$(function($){
  $.noConflict();
  var $action_approve = $('.action-buttons .approve');
  var $action_deny = $('.action-buttons .deny');
  
  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  $('.js-category-tasklist').select2({
	tags: true,
  });
  
  $('.download').click(function(e){
    e.preventDefault();
  });

  $('table thead th').each(function(){
    var $this = $(this);
    $this.find('div').append('<div class="arrow-up"></div>')
  });
  
  $('.ui-draggable').draggable();

   $(".todo-list").sortable({
        placeholder: "sort-highlight",
        handle: ".handle",
        forcePlaceholderSize: true,
        zIndex: 999999
    }).disableSelection();

  $('input[type="checkbox"]').iCheck({
    checkboxClass: 'icheckbox_square-blue',
    radioClass: 'iradio_square-blue',
    increaseArea: '-10%' // optional
  });

  $('.create-timer').click(function(){
    $('#modal').modal('toggle'); 
  });


  $('.dashboard.home .col-sm-6').matchHeight();
  
  $('#theme-update').change(function(){
    var $this = $(this);
    
    $this.submit();
  });

  $('#table-color, #table-size').change(function() {
    var $this = $(this);
    $this.submit();
  });
  
  $('.nav-tasks input').on('click', function(){
    var $this = $(this);
    $this.parent().css({'text-decoration': 'line-through red'});
  });
  
  $('.subtask-show').on('click', function(e){
    e.preventDefault();
    var $this = $(this);
    $this.next().removeClass('hide');
  });
  
  
  
  $("table").tablesorter({ theme : 'blue' });

  
 function formatAMPM(date) {
  var hours = date.getHours();
  var minutes = date.getMinutes();
  var ampm = hours >= 12 ? 'pm' : 'am';
  hours = hours % 12;
  hours = hours ? hours : 12; // the hour '0' should be '12'
  minutes = minutes < 10 ? '0'+minutes : minutes;
  var strTime = hours + ':' + minutes + ' ' + ampm;
  return strTime;
}
 
  $('.datepicker').datepicker({
    container: $(this),
    zIndex: 1999,
  });
  
  $('.timepicker-start').clockpicker({
    'default': 'now',
    donetext: 'Done',
    placement: 'right',
    autoclose: true,
  });
  
  var date = new Date(); // for now
  date.getHours(); // => 9
  date.getMinutes(); // =>  30
  date.getSeconds(); // => 51
  var three_hr = date.getTime() + (3*60*60*1000);
  three_hr = new Date(three_hr);
 
  
  $('.timepicker-end').clockpicker({
    'default': formatAMPM(three_hr),
    donetext: 'Done',
    placement: 'right',
    autoclose: true,
  });  
  
    
  $('.modal input[type=checkbox]').click(function(){
    var $this = $(this);
    $this.attr('value', 1);
  })
  
  $('.settings input[type=checkbox]').click(function(){
    var $this = $(this);
    if($this.val() === "1"){
      $this.val("0");
    } else {
      $this.val("1");
    }
  });
  
    var pathArray = window.location.pathname.split( '/' );

  $('table#tasks tr td').click(function(){
    var $this = $(this);    
    $id = $this.parent().find('td:nth-child(1)').text();    
    window.location='/dashboard/tasks/task/'+$id;    
  });
  $('table#clients tr td').click(function(){
    var $this = $(this);    
    $id = $this.parent().find('td:nth-child(1)').text();    
    window.location='/dashboard/clients/client/'+$id;    
  });
  $('table#contacts tr td').click(function(){
    var $this = $(this);    
    $id = $this.parent().find('td:nth-child(1)').text();    
    window.location='/dashboard/contacts/contact/'+$id;    
  });   
  $('table#documents tr td').click(function(){
    var $this = $(this);    
    $id = $this.parent().find('td:nth-child(1)').text();    
    window.location='/dashboard/documents/document/'+$id;    
  });     
  $('table#main tr td').click(function(){
    var $this = $(this);
    
    $id = $this.parent().find('td:nth-child(1)').text();
    if(pathArray[2] == 'cases'){
      window.location="/dashboard/cases/case/"+$id;
    } else if (pathArray[2] == 'contacts'){
      window.location="/dashboard/contacts/contact/"+$id;
    } else if (pathArray[2] == 'clients'){
      window.location='/dashboard/clients/client/'+$id;
    } else if (pathArray[2] == 'documents'){
      window.location='/dashboard/documents/document/'+$id;
    } else if (pathArray[2] == 'invoices'){
      window.location='/dashboard/invoices/invoice/'+$id;
    } else if (pathArray[2] == 'tasks'){
      
      if (pathArray[3] === 'task'){
        
 
        
        $('input[type=checkbox]').change(function(){
          var $this = $(this);
          var $tds = [];
          $tds = $this.parent().parent().find('td');
          $tds.each(function(){
           // var $this = $(this);
            //var $html = $("<s>"+ $this.text() + "</s>").html();
           // $this.html($html);
          });
          var subtask_id = $this.parent().parent().find('td:nth-child(1)').text();
          $.ajax(
            {
            type: 'POST',
            contentType: "application/json; charset=utf-8", 
            datatype: 'json',
            url: '/dashboard/tasks/task/complete-subtask/'+subtask_id+'/subtask',
            success:function(data){
              //console.log('success');
              //console.log(data);
            },        
          });

        });
      } else {
       window.location='/dashboard/tasks/task/'+$id;
      }
  
    } else if (pathArray[2] == 'messages'){

    }
    
    if($this.hasClass('st')){
      $('#subtask-modal-' + $id).modal({
        keyboard: true,
        show: true,
        focus: true,
        backdrop: true,
      });
    }
    $('#event-modal-'+$id).modal({
      keyboard: true,
      show: true,
      focus: true,
      background: false,
    });
    $('#user-modal-' + $id).modal({
      keyboard: true,
      show: true,
      focus: true,
      background: false,
    });
 
   
  });
  
  $('input[name=open_date], input[name=close_date]').inputmask({ "mask": "99/99/9999" });
  $('input[name=phone]').inputmask("mask", "(999) 999-9999"); //specifying options
  //$(selector).inputmask("9-a{1,3}9{1,3}"); //mask with dynamic syntax
  $('input[name=email]').inputmask({
    mask: "*{1,20}[.*{1,20}][.*{1,20}][.*{1,20}]@*{1,20}[.*{2,6}][.*{1,2}]",
    greedy: false,
    onBeforePaste: function (pastedValue, opts) {
      pastedValue = pastedValue.toLowerCase();
      return pastedValue.replace("mailto:", "");
    },
    definitions: {
      '*': {
        validator: "[0-9A-Za-z!#$%&'*+/=?^_`{|}~\-]",
        casing: "lower"
      }
    }
  });
  
 $('.modal-popup').modal('show');
  
 var path = window.location.href; // because the 'href' property of the DOM element is the absolute path
 
 $('.navbar ul a').each(function() {
   

 });

  $('.dropdown-toggle').dropdown();
  
  $('.nav-pills a.nav-item').hover(function(){
    var $this = $(this);
    $this.toggleClass('active');
  });
  
  
 
  
  var today = new Date();
  var dd = today.getDate();
  var mm = today.getMonth()+1; //January is 0!
  var yyyy = today.getFullYear();

  if(dd<10) {
    dd = '0'+dd
  } 

  if(mm<10) {
    mm = '0'+mm
  } 

  today = yyyy + '-' + mm + '-' + dd;
  function formatDate(date){
    return date.getMonth() + "/" + date.getDate() + "/" + date.getYear();
  }

  if(pathArray[2] == 'firm'){

  
  
  }
  else if (pathArray[2] == 'calendar'){
     for(var i = 0; i<events.length; i++){
          //console.log(events[i].user);
        }  
        
        /* initialize the external events
     -----------------------------------------------------------------*/
    function init_events(ele) {
      $.each(ele, function (key, value) {

        // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
        // it doesn't need to have a start or end
        var eventObject = {
          title: $.trim($(this).text()) // use the element's text as the event title
        }

        // store the Event Object in the DOM element so we can get to it later
        $(this).data('eventObject', eventObject)

        // make the event draggable using jQuery UI
        $(this).draggable({
          zIndex        : 1070,
          revert        : true, // will cause the event to go back to its
          revertDuration: 0  //  original position after the drag
        })

      })
    }

      init_events($('#external-events div.external-event'))
      $('#calendar').fullCalendar({
        themeSystem: 'bootstrap3',
        weekends: false,
        showNonCurrentDates: false,
        businessHours: {
          // days of week. an array of zero-based day of week integers (0=Sunday)
          dow: [ 1, 2, 3, 4, 5 ], // Monday - Thursday
          start: '09:00', // a start time (10am in this example)
          end: '17:00', // an end time (6pm in this example)
        },
        header: {
          left: 'prev,next today fullScreen',
          center: 'title',
          right: 'month,agendaWeek,agendaDay,listMonth'
        },
        timezone: 'America/Chicago',
        defaultDate: today,
        defaultView: 'month',        
        navLinks: true, // can click day/week names to navigate views
        eventLimit: 4, // allow "more" link when too many events
        events: events,
        draggable: true,
        contentHeight: 600,
        nowIndicator: true,
        editable  : true,
        droppable : true, // this allows things to be dropped onto the calendar !!!
        eventResize: function(event, delta, revertFunc) {
          console.log(delta._data);
          //event is object
          //array ()
          var new_end_date = delta._data;
          var uuid = event.uuid;
          var end_date = event.end_date;
          
          var updated_end_moment = moment(end_date).add(new_end_date);
           $.ajax(
          {
            type: 'POST',
            datatype: 'json',
            data: {
              'event': uuid,
              'new_end_date': updated_end_moment._d,
            },
            url: '/dashboard/calendar/extend-event',
            success:function(data){
              console.log('success');
              //console.log(data);
            },        
          });         
        },
        eventDrop : function( event, delta )  { 
          console.log(delta._data);
          //event is object
          //array ()
          var new_date = delta._data;
          var e = event;
          var uuid = event.uuid;
          var start_date = event.start_date;
          var end_date = event.end_date;
          

          var updated_start_moment = moment(start_date).add(new_date);
          var updated_end_moment = moment(end_date).add(new_date);
          
          
          $.ajax(
          {
            type: 'POST',
            datatype: 'json',
            data: {
              'event': uuid,
              'new_start_date': updated_start_moment._d,
              'new_end_date': updated_end_moment._d,
            },
            url: '/dashboard/calendar/modify-event',
            success:function(data){
              console.log('success');
              //console.log(data);
            },        
          });

        },
        drop : function (date, allDay)
        {
         // retrieve the dropped element's stored Event Object
          var originalEventObject = $(this).data('eventObject');
          
          //console.log(date);

          // we need to copy it, so that multiple events don't have a reference to the same object
          var copiedEventObject = $.extend({}, originalEventObject);

          // assign it the date that was reported
          copiedEventObject.start           = date.format('YYYY-MM-DD');
          copiedEventObject.end             = date.format('YYYY-MM-DD');
          copiedEventObject.allDay          = false;
          copiedEventObject.backgroundColor = $(this).css('background-color');
          copiedEventObject.borderColor     = $(this).css('border-color');
          

          // render the event on the calendar
          // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
          $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);
          
          // is the "remove after drop" checkbox checked?
          if ($('#drop-remove').is(':checked')) {
            // if so, remove the element from the "Draggable Events" list
            $(this).remove()
          }
          
          $.ajax(
          {
            type: 'POST',
            datatype: 'json',
            data: {
              'event': copiedEventObject,
            },
            url: '/dashboard/calendar/drop-event',
            success:function(data){
              console.log('success');
              //console.log(data);
            },        
          });
        }
      });  
        //Add draggable funtionality
      init_events(event)
      
          /* ADDING EVENTS */
      var currColor = '#3c8dbc' //Red by default
      //Color chooser button
      var colorChooser = $('#color-chooser-btn')
      $('#color-chooser > li > a').click(function (e) {
        e.preventDefault()
        //Save color
        currColor = $(this).css('color')
        //Add color effect to button
        $('#add-new-event').css({ 'background-color': currColor, 'border-color': currColor })
      })
      $('#add-new-event').click(function (e) {
        e.preventDefault()
        //Get value and make sure it is not null
        var val = $('#new-event').val()
        if (val.length == 0) {
          return
        }

        //Create events
        var event = $('<div />')
        event.css({
          'background-color': currColor,
          'border-color'    : currColor,
          'color'           : '#fff'
        }).addClass('external-event')
        event.html(val)
        $('#external-events').prepend(event)

        //Add draggable funtionality
        init_events(event)

        //Remove event from text input
        $('#new-event').val('')
      })
    
  }
  
  $( window ).resize(function(e) {
    e.preventDefault();
  if(pathArray[2] === 'calendar'){
   var newHeight = $('#container').height();
   $( ".fc-ltr > table" ).css( "height", newHeight );
  }
  });
  
  $('#show').on('click', function(e){
    var  $hide = $('.hide');
    $hide.removeClass('hide').addClass('show');
  });

  
  $('#show').on('click', function(){
    $('modal').modal();
  });
  $('.documents #show-wysiwyg').on('click', function(){
    $('#wysiwyg-modal').modal();
  });
  
  function smoothScrollingTo(target){
    $('html,body').animate({scrollTop:$(target).offset().top}, 500);
  }

  
if(pathArray[3] == 'task' || pathArray[2] == 'tasks'){
var input1 = document.querySelector('input[name=tags]'),
    tagify1 = new Tagify(input1, {
        whitelist : ["A# .NET", "A# (Axiom)", "A-0 System", "A+", "A++", "ABAP", "ABC", "ABC ALGOL", "ABSET", "ABSYS", "ACC", "Accent", "Ace DASL", "ACL2", "Avicsoft", "ACT-III", "Action!", "ActionScript", "Ada", "Adenine", "Agda", "Agilent VEE", "Agora", "AIMMS", "Alef", "ALF", "ALGOL 58", "ALGOL 60", "ALGOL 68", "ALGOL W", "Alice", "Alma-0", "AmbientTalk", "Amiga E", "AMOS", "AMPL", "Apex (Salesforce.com)", "APL", "AppleScript", "Arc", "ARexx", "Argus", "AspectJ", "Assembly language", "ATS", "Ateji PX", "AutoHotkey", "Autocoder", "AutoIt", "AutoLISP / Visual LISP", "Averest", "AWK", "Axum", "Active Server Pages", "ASP.NET", "B", "Babbage", "Bash", "BASIC", "bc", "BCPL", "BeanShell", "Batch (Windows/Dos)", "Bertrand", "BETA", "Bigwig", "Bistro", "BitC", "BLISS", "Blockly", "BlooP", "Blue", "Boo", "Boomerang", "Bourne shell (including bash and ksh)", "BREW", "BPEL", "B", "C--", "C++ – ISO/IEC 14882", "C# – ISO/IEC 23270", "C/AL", "Caché ObjectScript", "C Shell", "Caml", "Cayenne", "CDuce", "Cecil", "Cesil", "Céu", "Ceylon", "CFEngine", "CFML", "Cg", "Ch", "Chapel", "Charity", "Charm", "Chef", "CHILL", "CHIP-8", "chomski", "ChucK", "CICS", "Cilk", "Citrine (programming language)", "CL (IBM)", "Claire", "Clarion", "Clean", "Clipper", "CLIPS", "CLIST", "Clojure", "CLU", "CMS-2", "COBOL – ISO/IEC 1989", "CobolScript – COBOL Scripting language", "Cobra", "CODE", "CoffeeScript", "ColdFusion", "COMAL", "Combined Programming Language (CPL)", "COMIT", "Common Intermediate Language (CIL)", "Common Lisp (also known as CL)", "COMPASS", "Component Pascal", "Constraint Handling Rules (CHR)", "COMTRAN", "Converge", "Cool", "Coq", "Coral 66", "Corn", "CorVision", "COWSEL", "CPL", "CPL", "Cryptol", "csh", "Csound", "CSP", "CUDA", "Curl", "Curry", "Cybil", "Cyclone", "Cython", "M2001", "M4", "M#", "Machine code", "MAD (Michigan Algorithm Decoder)", "MAD/I", "Magik", "Magma", "make", "Maple", "MAPPER now part of BIS", "MARK-IV now VISION:BUILDER", "Mary", "MASM Microsoft Assembly x86", "MATH-MATIC", "Mathematica", "MATLAB", "Maxima (see also Macsyma)", "Max (Max Msp – Graphical Programming Environment)", "MaxScript internal language 3D Studio Max", "Maya (MEL)", "MDL", "Mercury", "Mesa", "Metafont", "Microcode", "MicroScript", "MIIS", "Milk (programming language)", "MIMIC", "Mirah", "Miranda", "MIVA Script", "ML", "Model 204", "Modelica", "Modula", "Modula-2", "Modula-3", "Mohol", "MOO", "Mortran", "Mouse", "MPD", "Mathcad", "MSIL – deprecated name for CIL", "MSL", "MUMPS", "Mystic Programming L"],
        blacklist : ["react", "angular"]
 });

// listen to custom 'remove' tag event

tagify1.on('remove', onRemoveTag);
// "remove all tags" button "click" even listener
//document.querySelector('.tags--removeAllBtn').addEventListener('click', onRemoveAllTagsClick)
}
  
if (pathArray[2] == 'settings'){
  $('input[name=show]').on('click', function(){
    $.ajax({
      type: 'POST',
      contentType: "application/json; charset=utf-8",      
      url: '/dashboard/settings/show-tasks-calendar',
    });
  });
}
function onRemoveTag(e){
  var detail = e.detail;
  var value = detail.value;
  //console.log(value);  
  $.ajax(
  {
    type: 'POST',
    contentType: "application/json; charset=utf-8",
    url: '/dashboard/tasks/subtask/category/'+detail.value+'/delete',
    datatype: 'json',
});
}

function onAddTag(e){
    console.log(e, e.detail);

}

function onRemoveAllTagsClick(e){
    tagify1.removeAllTags();
}

 function doModal(heading, formContent) {
    html =  '<div id="dynamicModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="confirm-modal" aria-hidden="true">';
    html += '<div class="modal-dialog">';
    html += '<div class="modal-content">';
    html += '<div class="modal-body">';
    html += '<h2><i class="fa fa-calendar-alt"></i> '+heading+'</h2>';
    html += '<hr />';
    html += formContent;
    html += '</div>';
    html += '</div>';  // dialog
    html += '</div>';  // footer
    html += '</div>';  // modalWindow
    $('body').append(html);
    $("#dynamicModal").modal();
    $("#dynamicModal").modal('show');

    $('#dynamicModal').on('hidden.bs.modal', function (e) {
        $(this).remove();
    });

}
  
/*$('.approve').on('click', function(e){
 e.preventDefault();
  var $this = $(this);
  
 var $id = $('input[name=id]').val();
  console.log($id);
  $.ajax(
  {
    type: 'POST',
    contentType: "application/json; charset=utf-8",
    url: '/dashboard/calendar/events/'+$id+'/approve',
    datatype: 'json',
  })  
});*/




    

});