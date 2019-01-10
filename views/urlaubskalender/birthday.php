<? use Studip\Button, Studip\LinkButton; ?>

<div id="scheduler_here" class="dhx_cal_container" style='width:100%; height:100%;'>
    <div class="dhx_cal_navline">
        <div class="dhx_cal_prev_button">&nbsp;</div>
        <div class="dhx_cal_next_button">&nbsp;</div>
        <div class="dhx_cal_today_button"></div>
        <div class="dhx_cal_date"></div>
        <div class="dhx_cal_tab" name="day_tab" style="right:204px;"></div>
        <div class="dhx_cal_tab" name="week_tab" style="right:140px;"></div>
        <div class="dhx_cal_tab" name="month_tab" style="right:76px;"></div>
    </div>
    <div class="dhx_cal_header"></div>
    <div class="dhx_cal_data"></div>       
</div>

<style type="text/css" media="screen">
    html, body{
        margin:0px;
        padding:0px;
        height:100%;
        width: 100%;
        overflow:hidden;
    }   
</style>

<script>
    scheduler.config.resize_month_events = false; 
    scheduler.config.resize_month_timed= false; 
    scheduler.attachEvent("onBeforeDrag", function(){return false;});
    
    scheduler.init('scheduler_here', new Date(),"month");
    
    var events = [
       
    <?    
    if($dates){
        foreach($dates as $d){
            echo "{id:".$d->getValue('id') . ", text:\"" . \Studip_User::find_by_user_id($d->getValue('user_id'))->fullname . ($d->getValue('notice') ? (" (" . $d->getValue('notice') . ")") : "") . "\",start_date:\"" . date("m/d/Y", strtotime($d->getValue('begin'))) . "\",end_date:\"" . date("m/d/Y", strtotime($d->getValue('end') . " + 1 day")) . "\", color:\"". $controller->color_by_crossfoot($d->getValue('id')) . "\"}," ;
        }
    }
    ?>    
 
    ];

    scheduler.parse(events, "json");//takes the name and format of the data source
    
    
</script>