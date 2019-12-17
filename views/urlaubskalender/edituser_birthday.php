<? use Studip\Button, Studip\LinkButton; ?>


<div id='mitarbeiter'>
    
    <h1 name="add_username" id="add_username"><?= $user_id ? \Studip_User::find_by_user_id($user_id)->fullname : "" ?></h1>
 
     <? if ($entries){ 
        foreach($entries as $entry){?>
    
    <form action="<?= $controller->url_for('urlaubskalender/save/birthday/'.$entry->getValue('id')) ?>" class="studip_form" method="POST">
        <fieldset>

            <input type="hidden" name="user_id" value="<?= $user_id ? $user_id : "" ?>" id="user_id"></input><br>
            
                 <label> Datum: </label>
                <input required type="text" id="beginn" name="begin" data-date-picker value="<?= $entry->getValue('begin') ?>"></input><br>

                <label> Hinweis/Notiz:</label> <input type="" name="notice" value="<?= $entry->getValue('notice') ?>"></input>
            <?= Button::createAccept(_('Änderung speichern'), 'submit') ?>
            <?= LinkButton::create(_("Löschen"), $controller->url_for('urlaubskalender/delete/'.$entry->getValue('id')), array('onClick' => "return window.confirm('"._("Wirklich löschen?")."');"))?>
            
        </fieldset>
      
          
    </form>
    <? }?>
    

    <?= LinkButton::createCancel(_('Abbrechen'), $controller->url_for('urlaubskalender/birthday')) ?>
    <? } else{ ?>
    <h2> Noch keine Einträge vorhanden </h2>
    <? }?>
    
    
</div>

<style>
    fieldset{
        padding-top: 0em !important;
    }
    </style>