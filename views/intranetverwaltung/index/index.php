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
<h3>Um einen Intranetbereich einzurichten w�hlen Sie unter <a href='<?= URLHelper::getURL('dispatch.php/institute/basicdata/index?cid=')?>' >Einrichtungen</a> eine Einrichtung und aktivieren Sie das Attribut Eigener Intranetbereich</h3>


<select name='inst_id' onchange="select_inst_id(this.value)">
    <option value='' > Keine Auswahl </option>
    <?php foreach($institutes_with_intranet as $intranet) : ?>
        <option value='<?=$intranet->id?>' > <?=$intranet->name?> </option>
    <? endforeach ?>
</select> 

<hr>

<?php if($intranet_inst) : ?>
<form name="intranet-settings" name="settings" method="post" action="<?= $controller->url_for('intranetverwaltung/index/set/' . $intranet_inst->id) ?>" <?= $dialog_attr ?> class="default collapsable">
    <?= CSRFProtection::tokenTag() ?>
    <input id="open_variable" type="hidden" name="open" value="<?= $flash['open'] ?>">

    <h1><?= $intranet_inst->name ?></h1>
    <fieldset <?= isset($flash['open']) && $flash['open'] != "courses" ? 'class="collapsed"' : ''?> data-open="courses">
        <legend>Zugeh�rige Veranstaltungen</legend>
        <table>
            <?php if($sem_for_instid[$intranet_inst->institut_id]) : ?>
            <?php foreach($sem_for_instid[$intranet_inst->institut_id] as $course) : ?>
            <tr>
                <td>
                    <input type='checkbox' name ='seminare' value ='grid' <?= ($style == 'grid') ? 'checked' : '' ?>> <?= $course->name ?>
                </td>
            </tr>
            <?php endforeach ?>
            <?php endif ?>
        </table>
    </fieldset>

    <fieldset <?= !isset($flash['open']) || $flash['open'] != 'page' ? 'class="collapsed"' : ''?> data-open="page">
        <legend><?= _('Individuelle Startseite gestalten') ?></legend>
            <label for="description"><?= _('Template w�hlen') ?></label>
            <input type="text" name="template" id="template" value="<?= $inst_config[$intranet_inst->institut_id] ?> "/>
    </fieldset>
    
    <button title="�nderungen �bernehmen" name="submit" class="button" type="submit">�bernehmen</button></p>
    
    
</form>
<?php endif ?>

<script>
    function select_inst_id(value){
        window.location.href = '<?=$controller->url_for('intranetverwaltung/index/index/')?>' + value;
    }
</script>
