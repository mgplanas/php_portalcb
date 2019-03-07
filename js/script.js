  $(function () {

    /* initialize the external events
     -----------------------------------------------------------------*/
    function init_events(ele) {
      ele.each(function () {

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

    /* initialize the calendar
     -----------------------------------------------------------------*/
    //Date for the calendar events (dummy data)
    var date = new Date()
    var d    = date.getDate(),
        m    = date.getMonth(),
        y    = date.getFullYear()
    $('#calendar').fullCalendar({

    locale  : 'es',  
    header    : {
        left  : 'prev,next today',
        center: 'title',
        right : 'month,agendaWeek,agendaDay,listMonth'
      },
      buttonText: {
        today: 'Hoy',
        month: 'mes',
        week : 'semana',
        day  : 'día',
        list : 'listado'
      },
      //Read events from Database
      events    : "getCalendar.php",
      
      eventClick:  function(event, jsEvent, view) {  // when some one click on any event
        endtime = $.fullCalendar.moment(event.end).format('h:mm A');
        starttime = $.fullCalendar.moment(event.start).format('dddd, MMMM Do YYYY, h:mm A');
        var mywhen = starttime + ' - ' + endtime;
        $('#modalTitle').html(event.title);
        $('#modalWhen').text(mywhen);
        $('#eventID').val(event.id_calendario);
        $('#calendarModal').modal();
      },

      editable  : true,
      selectable: true,
        
      eventDrop: function(event, delta){ // event drag and drop
           $.ajax({
               url: 'calendario.php',
               data: 'action=update&title='+event.title+'&start='+moment(event.start).format()+'&end='+moment(event.end).format()+'&id='+event.id_calendario+'&allDay='+event.allDay+'&tipo='+event.tipo,
               type: "POST",
               success: function(json) {
                   location.reload();
               //alert(json);
               }
           });
      },
        
      eventResize: function(event) {  // resize to increase or decrease time of event
           $.ajax({
               url: 'calendario.php',
               data: 'action=update&title='+event.title+'&start='+moment(event.start).format()+'&end='+moment(event.end).format()+'&id='+event.id_calendario+'&allDay='+event.allDay+'&tipo='+event.tipo,
               type: "POST",
               success: function(json) {
                   location.reload();
               //alert(json);
               }
           });
      },
        
        /*
      select: function(start, end, jsEvent) {  // click on empty time slot to add an event
        endtime = $.fullCalendar.moment(end).format('h:mm A');
        starttime = $.fullCalendar.moment(start).format('dddd, MMMM Do YYYY, h:mm A');
        var mywhen = starttime + ' - ' + endtime;
        start = moment(start).format();
        end = moment(end).format();
        $('#createEventModal #startTime').val(start);
        $('#createEventModal #endTime').val(end);
        $('#createEventModal #when').text(mywhen);
        $('#createEventModal').modal('toggle');
      },*/
      droppable : false, // this allows things to be dropped onto the calendar !!!
      drop      : function (date, allDay) { // this function is called when something is dropped

        // retrieve the dropped element's stored Event Object
        var originalEventObject = $(this).data('eventObject')

        // we need to copy it, so that multiple events don't have a reference to the same object
        var copiedEventObject = $.extend({}, originalEventObject)

        // assign it the date that was reported
        copiedEventObject.start           = date
        copiedEventObject.allDay          = allDay
        copiedEventObject.backgroundColor = $(this).css('background-color')
        copiedEventObject.borderColor     = $(this).css('border-color')

        // render the event on the calendar
        // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
        $('#calendar').fullCalendar('renderEvent', copiedEventObject, true)

        // is the "remove after drop" checkbox checked?
        if ($('#drop-remove').is(':checked')) {
          // if so, remove the element from the "Draggable Events" list
          $(this).remove()
        }

      }
    })
    $('#submitButton').on('click', function(e){ // add event submit
       // We don't want this to act as a link so cancel the link action
       e.preventDefault();
       doSubmit(); // send to form submit function
    });

    $('#deleteButton').on('click', function(e){ // delete event clicked
       // We don't want this to act as a link so cancel the link action
       e.preventDefault();
       doDelete(); //send data to delete function
    });
    
    function doDelete(){  // delete event 
       $("#calendarModal").modal('hide');
       var eventID = $('#eventID').val();
        

       $.ajax({
           url: 'calendario.php',
           data: 'action=delete&id='+eventID,
           type: "POST",
           success: function(json) {
               if(json == 1)
                    $("#calendar").fullCalendar('removeEvents',eventID);

               else
                    return false;
           }
       });
    }
    function doSubmit(){ // add event
       $("#createEventModal").modal('hide');
       var title = $('#title').val();
       var startTime = $('#startTime').val();
       var endTime = $('#endTime').val();

       $.ajax({
           url: 'calendario.php',
           data: 'action=add&title='+title+'&start='+startTime+'&end='+endTime,
           type: "POST",
           success: function(json) {
               $("#calendar").fullCalendar('renderEvent',
               {
                   id: json.id,
                   title: title,
                   start: startTime,
                   end: endTime,
               },
               true);
           }
       });
    }
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
  })