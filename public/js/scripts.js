$(function(){
  
  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
  



   var timer = new Timer();

  
   $('.nav-clock .startButton').click(function () {

      timer.start();
      timer.addEventListener('secondsUpdated', function (e) {
          $('.nav-clock h3.timer').html(timer.getTimeValues().toString());
      });
    });  
  
   $('.nav-clock .pauseButton').click(function () {
     timer.pause();
   });
  
   $('.nav-clock .stopButton').click(function () {
      timer.stop();
    });  
    
  
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
  $('table tr td').click(function(){
    var $this = $(this);
    
    $id = $this.parent().find('td:nth-child(1)').text();
    
    if($this.hasClass('st')){
      $('#subtask-modal-' + $id).modal({
        keyboard: true,
        show: true,
        focus: true,
        backdrop: true,
      });
    } else {
      $('#task-modal-' + $id).modal({
        keyboard: true,
        show: true,
        focus: true,
        backdrop: true,
    });
    }
    $('#case-modal-' + $id).modal({
      keyboard: true,
      show: true,
      focus: true,
      backdrop: true,
    });

    $('#contact-modal-' + $id).modal({
      keyboard: true,
      show: true,
      focus: true,
      backdrop: true,
    });   
    $('#user-modal-' + $id).modal({
      keyboard: true,
      show: true,
      focus: true,
      background: false,
    });
    $('#document-modal-' + $id).modal({
      keyboard: true,
      show: true,
      focus: true,
      backdrop: true,
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
    if (this.href === path) {
     $(this).parent().addClass('active');
    }
 });
  
  $('.nav-pills a.nav-item').hover(function(){
    var $this = $(this);
    $this.toggleClass('active');
  });
  
  
  $navpillsbg = $('.nav-pills a').css('background-color');
  $('.dashboard .nav.nav-pills').css('background-color', $navpillsbg);
 
  
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
  



  
  var pathArray = window.location.pathname.split( '/' );
 // console.log(pathArray[2]);
  if(pathArray[2] == 'firm'){
    //$('body .container.dashboard').scrollspy({ target: '#navbar-interior' });
    $('#navbar-interior a').click(function(){
       $('html, body').animate({
        scrollTop: $(this).attr('href').split('#')[1].offset().top
    }, 500);
      //$('body').scrollTo($(this).data('target'),{
        //duration:500,
        //offset:70
     
    });

    /*$('a[href*=\\#]').on('click', function(event){     
      event.preventDefault();
      smoothScrollingTo(location.hash);
    });*/
  }
  else if (pathArray[2] == 'calendar'){
  
  $('#calendar').fullCalendar({
    themeSystem: 'bootstrap4',
    eventClick: function(calEvent, jsEvent, view) {
       var s_date = new Date(calEvent.start_date);
       var str = s_date.toString("MM/dd/yyyy");
       var e_date = new Date(calEvent.end_date);
       var str_e = e_date.toString("MM/dd/yyyy");
      //alert(str);
      var formContent = "<form method='post' action='/dashboard/calendar/event/add'>";
      formContent += "<input type='hidden' name='_token' value='"+$('[name="csrf-token"]').attr('content')+"' />"
      formContent += "<input type='hidden' name='id' id='id' value='"+calEvent.id+"'/>";
      formContent += "<div class='col-sm-6 col-12'>";
      formContent += "<label for='name'>Name</label>";
      formContent += "<input type='text' class='form-control' name='name' value='"+calEvent.name+"' />";
      formContent += "</div>";
      formContent += "<div class='col-sm-6 col-12'>";      
      formContent += "<label for='description'>Description</label>";      
      formContent += "<input type='text' class='form-control' name='description' value='"+calEvent.description+"' />";
      formContent += "</div>";      
      formContent += "<div class='col-sm-6 col-12'>";      
      formContent += "<label for='start_date'>Start date</label>";      
      formContent += "<input type='text' class='form-control datepicker' data-toggle'datepicker' name='start_date' value='"+str+"' />";
      formContent += "</div>";      
      formContent += "<div class='col-sm-6 col-12'>";      
      formContent += "<label for='end_date'>End date</label>";      
      formContent += "<input type='text' class='form-control datepicker' data-toggle'datepicker' name='end_date' value='"+str_e+"' />";
      formContent += "</div>";      
      formContent += "<div class='col-sm-6 col-12'>";      
      formContent += "<label for='start_time'>Start time</label>";           
      formContent += "<input type='text' class='form-control timepicker' name='start_time' value='"+calEvent.start_time+"' />";
      formContent += "</div>";      
      formContent += "<div class='col-sm-6 col-12'>";      
      formContent += "<label for='end_time'>End time</label>";           
      formContent += "<input type='text' class='form-control timepicker' name='end_time' value='"+calEvent.end_time+"' />"; 
      formContent += "</div>";
      formContent += "<div class='col-12'>";
      formContent += "<button type='submit' class='btn btn-primary'>Submit</button>";
      formContent += "</div>";
      formContent += "</form>";  
    doModal('Edit Event', formContent);
    var date = new Date(calEvent.start);

    var day = date.getDate();
    var year = date.getFullYear();
    // change the border color just for fun
    $(this).css('border-color', 'red');

  },
    header: {
            center: 'title',
            right: 'month,agendaWeek,agendaDay,listMonth'
          },
          defaultDate: today,
          weekNumbers: true,
          navLinks: true, // can click day/week names to navigate views
          editable: false,
          eventLimit: 3, // allow "more" link when too many events
          events: events,
          dragable: false,
          contentHeight: 600,
  });  
  
  
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
  }
  
  $( window ).resize(function() {
    
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


  
  
    

});