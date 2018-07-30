<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Studip\Button, Studip\LinkButton;


$dialog_attr = Request::isXhr() ? ' data-dialog="size=50%"' : '';

$message_types = array('msg' => "success", 'error' => "error", 'info' => "info");
?>

<h1>Konfiguration</h1>
<h3>Um einen Intranetbereich einzurichten wählen Sie unter <a href='<?= URLHelper::getURL('dispatch.php/institute/basicdata/index?cid=')?>' >Einrichtungen</a> eine Einrichtung und aktivieren Sie das Attribut Eigener Intranetbereich</h3>


<select name='inst_id' onchange="select_inst_id(this.value)">
    <option value='' > Keine Auswahl </option>
    <?php foreach($institutes_with_intranet as $intranet) : ?>
        <option value='<?=$intranet->id?>' > <?=$intranet->name?> </option>
    <? endforeach ?>
</select> 

<hr>


<form name="intranet-settings" name="settings" method="post" action="<?= $controller->url_for('intranetverwaltung/set') ?>" <?= $dialog_attr ?> class="default collapsable">
    <?= CSRFProtection::tokenTag() ?>
    <input id="open_variable" type="hidden" name="open" value="<?= $flash['open'] ?>">
    
    <?php foreach($institutes_with_intranet as $intranet) : ?>
    <fieldset style='display:none' id="<?=$intranet->id?>" <?= isset($flash['open']) && $flash['open'] != $intranet->id ? 'class="collapsed"' : ''?> data-open="<?=$intranet->id?>">
        <legend><?= $intranet->name ?></legend>
        <table>
            <tr>
                <td>
                    <input type='radio' name ='style' value ='grid' <?= ($style == 'grid') ? 'checked' : '' ?>> <b> Kachelformat: </b> Bequemer und schneller Zugriff auf alle verfügbaren Inhaltselemente
                </td>
            </tr>

        </table>
    </fieldset>
    <?php endforeach ?>
</form>

<script>
    function select_inst_id(value){
        $('fieldset').hide();
        $('#'+value).show();
    }
</script>
