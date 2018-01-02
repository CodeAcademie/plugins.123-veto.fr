<?php

$filter=["date>=CURDATE()"];
$order=["date"];
$result = Meet::findFilter($filter, $order);

?>
<link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css'>
<link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css'>

<style media="screen">
body {
margin-top: 40px;
text-align: center;
font-size: 14px;
font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
}

#wrap {
width: 1500px;
margin: 0 auto;
}

#external-events {
float: left;
width: 150px;
padding: 0 10px;
border: 1px solid #ccc;
background: #eee;
text-align: left;
}

#external-events h4 {
font-size: 16px;
margin-top: 0;
padding-top: 1em;
}

#external-events .fc-event {
margin: 10px 0;
cursor: pointer;
}

#external-events p {
margin: 1.5em 0;
font-size: 11px;
color: #666;
}

#external-events p input {
margin: 0;
vertical-align: middle;
}

#calendar {
float: right;
width: 900px;
}
.mb-20{
margin-bottom:20px;
}
</style>
<div class="container">
    <div class="col-md-10">
      <div id='calendar'></div>
    </div>
</div>
<div id="eventContent" title="Event Details">
    <div id="eventInfo"></div>
    <p><strong><a id="eventLink" target="_blank">Read More</a></strong></p>
</div>
<?php

$calendar_events = [];
if(!is_null($result)){
foreach ($result as $k => $value){
  $m = new Meet($value);
  $to_json_event = sprintf("{ title  : 'RDV : %s',allDay : false, start  : '%s', end:'%s'}",
  $m->getName(),
  $m->getCalendarStartDate(),
  $m->getCalendarEndDate()
);
  $calendar_events[]=$to_json_event;
}
}
echo "<script> var calendar_events = [".join(",",$calendar_events)."]; console.log(calendar_events); </script>";
 ?>
<script src='https://code.jquery.com/jquery-1.11.2.min.js'></script>
<script src='https://code.jquery.com/ui/1.11.2/jquery-ui.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js'></script>
<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.js'></script>
<script  src="js/index.js"></script>
