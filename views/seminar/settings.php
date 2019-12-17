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

<? if (is_array($flash['msg'])) foreach ($flash['msg'] as $msg) : ?>
    <?= MessageBox::$message_types[$msg[0]]($msg[1]) ?>
<? endforeach ?>

<form name="course-settings" name="settings" method="post" action="<?= $controller->url_for('seminar/set/' . $course->id) ?>" <?= $dialog_attr ?> class="default collapsable">
    <?= CSRFProtection::tokenTag() ?>
    <input id="open_variable" type="hidden" name="open" value="<?= $flash['open'] ?>">
    
     <fieldset <?= isset($flash['open']) && $flash['open'] != 'begin_course' ? 'class="collapsed"' : ''?> data-open="begin_course">
        <legend><?= sprintf(_('Kursfreigabe für %s'),  get_title_for_status('autor', 2)) ?></legend>
            <label for="start_date"><?= sprintf(_('Kurs für %s freigeben ab'), get_title_for_status('autor', 2)) ?></label>
             <input style='width:140px; max-width:140px' type='date' name ='start_date' value='<?= date('Y-m-d', $coursebegin) ?>'>
    </fieldset>
    
    
    <fieldset <?= !isset($flash['open']) || $flash['open'] != 'bd_basicsettings' ? 'class="collapsed"' : ''?> data-open="bd_basicsettings">
        <legend><?= _('Aufbau der Übersichtsseite wählen') ?></legend>
        <table>
            <tr>
                <td>    
                    <img class='style-preview box-shadow' src='<?= URLHelper::getURL($this->plugin->getpluginPath() . '/assets/images/standard.png')?>'/>
                </td>
                <td>
                    <input type='radio' name ='style' value ='standard' <?= ($style == 'standard') ? 'checked' : '' ?>> <b> Standardformat: </b> Die Stud.IP Standardübersicht
                </td>
            </tr>
            
            <tr>
                <td>    
                    <img class='style-preview box-shadow' src='<?= URLHelper::getURL($this->plugin->getpluginPath() . '/assets/images/grid.png')?>'/>
                </td>
                <td>
                    <input type='radio' name ='style' value ='grid' <?= ($style == 'grid') ? 'checked' : '' ?>> <b> Kachelformat: </b> Bequemer und schneller Zugriff auf alle verfügbaren Inhaltselemente
                </td>
            </tr>
            
            <tr>
                <td>
                    <img class='style-preview box-shadow' src='<?= URLHelper::getURL($this->plugin->getpluginPath() . '/assets/images/full.png')?>'/>
                </td>
                <td>
                    <input type='radio' name ='style' value ='full' <?= ($style == 'full') ? 'checked' : '' ?>> <b> Zusammenfassung: </b> Alle wichtigen Informationen und Inhalte direkt auf der Übersichtsseite.
                </td>
            </tr>
            
            <tr>
                <td>
                    <img class='style-preview box-shadow' src='<?= URLHelper::getURL($this->plugin->getpluginPath() . '/assets/images/individual.png')?>'/>
                </td>
                <td>
                    <input type='radio' name ='style' value ='individual' <?= ($style == 'individual') ? 'checked' : '' ?>> <b> Individuell: </b> Die Übersichtsseite ganz individuell mit Informationen zum Kurs gestalten.
                </td>
            </tr>
           
        </table>

       
    </fieldset>
    
    <fieldset <?= !isset($flash['open']) || $flash['open'] != 'inset' ? 'class="collapsed"' : ''?> data-open="bd_inst">
        <legend><?= _('Individuelle Startseite gestalten') ?></legend>
            <label for="description"><?= _('Inhalt') ?></label>
            <textarea name="description" id="description" class="add_toolbar wysiwyg"><?= htmlready($course->beschreibung) ?></textarea>
    </fieldset>
    

    <fieldset <?= !isset($flash['open']) || $flash['open'] != 'inset' ? 'class="collapsed"' : ''?> data-open="bd_inst">
        <legend><?= _('Navigation') ?></legend>


<!--        <label>
            <h1>Navigationsart</h1>
            <input type='radio' name ='navigation' value ='default' <?= ($style == 'default') ? 'checked' : '' ?>> Navigation über Veranstaltungsreiter <br>
            <input type='radio' name ='navigation' value ='base-grid' <?= ($style == 'base-grid') ? 'checked' : '' ?>> Navigation über Kacheln auf Übersichtseite <br>
            <input type='radio' name ='navigation' value ='left-hand' <?= ($style == 'eft-hand') ? 'checked' : '' ?>> Linksseitige Navigation <br>
        </label>-->
        <label>
            <input name="new_order" value="" type="hidden" />
            <h1>Benennung, Reihenfolge und Sichtbarkeit der Navigationselemente</h1>
            <ul id="sortable">
            <? $tab_num = 0; ?>
            <? foreach($tabs as $tab){?>
                <li name="<?=$tab_num?>" class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                <? if(!in_array($tab['tab'], $ignore_visibility_tabs)){ ?>
                    <input type="checkbox" name="visible_<?=$tab_num?>" <?=$tab['visible']?>/> 
                <? } else {
                strcmp($tab['visible'],'checked') == 0 ? $visible = 'on': $visible = 'off';
                ?>
                <input type="hidden" name="visible_<?=$tab_num?>" value="<?=$visible?>"/>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <? } ?> 
                <input type="hidden" value="<?= $tab['tab']; ?>" name="tab_title_<?=$tab_num?>" />
                <input value="<?= $tab['title']; ?>" name="new_tab_title_<?=$tab_num?>" size="20"/>
                <input type="hidden" value="<?= $tab['position']; ?>" name="tab_position_<?=$tab_num?>" />
                (<?= $tab['orig_title']; ?>)</p>
                </li>
                <?$tab_num++;

            }?>

            </ul>
            <input type="hidden" name="tab_num" value="<?=$tab_num?>" />
        </label>
        
    
    </fieldset>
    
    <footer data-dialog-button>
        <?= Button::create(_('Übernehmen')) ?>
    </footer>
</form>