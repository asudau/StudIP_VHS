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

<h1>Konfiguration <?= ($intranet_inst) ? ' : ' . $intranet_inst->name : ''?></h1>
<p>Um einen Intranetbereich einzurichten wählen Sie unter <a href='<?= URLHelper::getURL('dispatch.php/institute/basicdata/index?cid=')?>' >Einrichtungen</a> eine Einrichtung und aktivieren Sie das Attribut <b>Eigener Intranetbereich</b></p>


<select name='inst_id' onchange="select_inst_id(this.value)">
    <option value='' > Auswählen </option>
    <?php foreach($institutes_with_intranet as $intranet) : ?>
        <? if ( $GLOBALS['perm']->have_perm('root') || in_array($intranet->id, IntranetConfig::getIntranetIDsForUser(User::findCurrent())) ) : ?>
            <option <?= ($intranet->id == $intranet_inst->id) ? 'selected' :'' ?> value='<?=$intranet->id?>' > <?=$intranet->name?> </option>
        <? endif ?>
    <? endforeach ?>
</select> 

<hr>

<?php if($intranet_inst) : ?>
<form name="intranet-settings" name="settings" method="post" action="<?= $controller->url_for('intranetverwaltung/index/set/' . $intranet_inst->id) ?>" <?= $dialog_attr ?> class="default collapsable">
    <?= CSRFProtection::tokenTag() ?>
    <input id="open_variable" type="hidden" name="open" value="<?= $flash['open'] ?>">

    <h1><?= $intranet_inst->name ?></h1>
    <fieldset <?= isset($flash['open']) && $flash['open'] != "courses" ? 'class="collapsed"' : ''?> data-open="courses">
        <legend>Zugehörige Veranstaltungen</legend>
        <table>
            <?php if($intranet_courses) : ?>
            <?php foreach($intranet_courses as $course) : ?>
            <tr>
                <td>
                    <a data-dialog href ='<?= $controller->url_for('intranetverwaltung/index/editseminar/' . $course->id . '/' . $intranet_inst->institut_id )?>'> <?= $course->name ?>
                </td>
                <td>
                    <? $sem_config = IntranetSeminar::find([$course->id, $intranet_inst->id]); ?>
                    <? if ($sem_config): ?>
                        Automatischer Eintrag als '<?= $sem_config->add_instuser_as ?>'
                    <? else: ?>
                        Kein automatischer Eintrag konfiguriert
                    <? endif ?>
                </td>
                <td>
                    <? if ($sem_config->show_news): ?>
                        <?= Icon::create('news', 'clickable')?>
                    <? endif ?>
                    <? if ($sem_config->use_files): ?>
                        <?= Icon::create('files', 'clickable')?>
                    <? endif ?>
                </td>
            </tr>
            <?php endforeach ?>
            <?php endif ?>
        </table>
    </fieldset>

    <fieldset <?= !isset($flash['open']) || $flash['open'] != 'page' ? 'class="collapsed"' : ''?> data-open="page">
        <legend><?= _('Individuelle Startseite gestalten') ?></legend>
            <label for="description"><?= _('Template wählen') ?></label>
            <select name="template">
                <?php foreach($plugin->templates as $template) : ?>
                    <option value='<?= $template?>' <?= ( $inst_config[$intranet_inst->institut_id]  == $template ) ? 'selected' : ''?>><?= $template?></option>
                <?php endforeach ?>
            </select>
    </fieldset>

    <fieldset <?= !isset($flash['open']) || $flash['open'] != 'buttons' ? 'class="collapsed"' : ''?> data-open="buttons">
        <legend><?= _('Individuelle Startseitenbuttons gestalten') ?></legend>
        <table class="default">
            <thead>
            <tr>
                <th>
                    Titel
                </th>
                <th>
                    Tooltip
                </th>
                <th>
                    Ziel
                </th>
                <th>
                    Icon
                </th>
                 <th>
                    Anordnung
                </th>
                <th>
                    Aktionen
                </th>
            </tr>
            </thead>

        <?php if($intranet_buttons) : ?>
            <?php foreach($intranet_buttons as $button) : ?>
                <tr>
                    <td><?= $button->text ?></td>
                    <td><?= $button->tooltip ?></td>
                    <td><?= $button->target ?></td>
                    <td>
                        <? if ($button->icon != 'custom') : ?>
                            <?= Icon::create($button->icon, 'clickable') ?>
                        <? else : ?>
                            <img style='heigth:1em;width:1em' src='<?= $button->icon_link ?>' >
                        <? endif ?>
                    </td>
                    <td><?= ($button->content_side == 'right') ? 'Rechts' : 'Zentral'?></td>
                    <td>
                        <a data-dialog href="<?= $controller->url_for('intranetverwaltung/index/edit_buttons/' . $intranet_inst->id . '/' . $button->Button_id)?>">
                        <?= Icon::create('edit', 'clickable') ?>
                        </a>
                        <a href="<?= $controller->url_for('intranetverwaltung/index/delete_button/' . $intranet_inst->id . '/' . $button->Button_id)?>">
                        <?= Icon::create('trash', 'clickable') ?>
                        </a>
                    </td>
                </tr>

            <?php endforeach ?>
        <?php endif ?>
            <tr>
                <td>
                    <a data-dialog href="<?= $controller->url_for('intranetverwaltung/index/edit_buttons/' . $intranet_inst->id)?>">
                        <?= Icon::create('add', 'clickable') ?>
                    </a>
                </td>
            </tr>
        </table>
    </fieldset>
    
    <button title="Änderungen übernehmen" name="submit" class="button" type="submit">Übernehmen</button></p>
    
    
</form>
<?php endif ?>

<script>
    function select_inst_id(value){
        window.location.href = '<?=$controller->url_for('intranetverwaltung/index/index/')?>' + value;
    }
</script>
