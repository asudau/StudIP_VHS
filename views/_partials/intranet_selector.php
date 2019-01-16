
<select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
<option value="">Wählen Sie den gewünschten Startbereich...</option>
    <? foreach ($intranets as $intranet) : ?>
        <option value="<?=$GLOBALS['ABSOLUTE_URI_STUDIP']?>plugins.php/studip_vhs/intranet_start/index/<?=$intranet?>"><?=Institute::find($intranet)->Name?></option>
    <? endforeach ?>
</select> 

