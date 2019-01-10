<? use Studip\Button, Studip\LinkButton; ?>
<?= LinkButton::createBack(_('Zurück'), $controller->url_for('urlaubskalender/')) ?>

<div id="scheduler_here" class="dhx_cal_container" style='width:100%; height:<?= 50*count($keys)+100?>px;'>
    <div id='container' style='width:100%;'>
    <div class="dhx_cal_navline">
        <div class="dhx_cal_prev_button">&nbsp;</div>
        <div class="dhx_cal_next_button">&nbsp;</div>
        <div class="dhx_cal_today_button"></div>
        <div class="dhx_cal_date"></div>
    </div>
    <div class="dhx_cal_header"></div>
    <div class="dhx_cal_data"></div>     
    </div>
</div>


<script>
    scheduler.config.resize_month_events = false; 
    scheduler.config.resize_month_timed= false; 
    scheduler.attachEvent("onBeforeDrag", function(){return false;});
    
    scheduler.createTimelineView({
     name:      "timeline",
     x_unit:    "day",
     x_date:    "%d.%m.",
     x_step:    1,
     x_size:    20,
     x_start:   -2,
     x_length:  20,
     y_unit:
        [   
    <?    
        if($keys){
            foreach($keys as $key => $value){
                echo "{key:" . $value . ", label:\"" . \Studip_User::find_by_user_id($key)->fullname . "\"}," ;
            }
        }
    ?>  
           ],
     y_property:"section_id",
     section_autoheight : false, 
     render:    "bar",
     
});

    scheduler.second_scale ={
        x_unit: "month", // the measuring unit of the axis (by default, 'minute')
        x_date: "%M" //the date format of the axis ("July 01")
     };
    
    
    scheduler.templates.timeline_scaley_class = function (start, end, event) {
    return "employee_event"; };
    
    scheduler.templates.timeline_cell_class = function(evs, date, section) {
			var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth(); //January is 0!
            var yyyy = today.getFullYear();

            if (date.getDay()==0 || date.getDay()==6)
				return "weekend";	
            if(dd == date.getDate() && mm == date.getMonth() && yyyy == date.getFullYear())
                return "today";	
			return "";
		}
    
    scheduler.init('scheduler_here', new Date(),"timeline");
    
    var events = [
    <?    
    if($dates){
        foreach($dates as $d){
            echo "{start_date:\"" . date("m/d/Y", strtotime($d->getValue('begin'))) . "\",end_date:\"" . date("m/d/Y", strtotime($d->getValue('end') . " + 1 day")) . "\", text:\"" . $d->getValue('notice') . "\", section_id:". $keys[$d->getValue('user_id')] . ", color:\"". $controller->color_by_crossfoot($d->getValue('id')) . "\"}," ;
        }
    }
    ?>    
 
    ];

    scheduler.parse(events, "json");//takes the name and format of the data source
    
    
</script>

<style>
    .weekend{
		background:#888;
	}
    .today{
        background:#F2F5A9;
    }
 
</style>
    