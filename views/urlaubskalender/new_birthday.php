<? use Studip\Button, Studip\LinkButton; ?>



<?= LinkButton::createBack(_('Zurück'), $controller->url_for('urlaubskalender/birthday')) ?>
<div id='mitarbeiter'>
    
    
    <form action="<?= $controller->url_for('urlaubskalender/save/birthday') ?>" class="studip_form" method="POST">
        <fieldset>
            <? if ($mitarbeiter_hilfskraft){ ?>
            <label for="student_search" class="caption">
                <?= _('MitarbeiterIn suchen')?>
                <?= Icon::create('info-circle', 'info', array('title' => $help))?>
            </label>

                <?= $quick_search->render();
            ?>
            <? } ?>
            <br>
            <h2 name="add_username" id="add_username"><?= (!$mitarbeiter_admin) ? $GLOBALS['user']->vorname . ' ' . $GLOBALS['user']->nachname : '' ?></h2>
            <input type="hidden" name="user_id" value="<?= (!$mitarbeiter_admin) ? $GLOBALS['user']->id : '' ?>" id="user_id"></input><br>
            <div id='holidays' style="<?= (!$mitarbeiter_admin) ? '' : 'display:none;' ?>">
                <label> Datum: </label>
                <input required type="text" id="beginn" name="begin" data-date-picker value=""></input><br>

                <label> Hinweis/Notiz:</label> <input type="" name="notice" value=""></input>
            </div>
        </fieldset>
      
          <?= Button::createAccept(_('Speichern'), 'submit') ?>
          <?= LinkButton::createCancel(_('Abbrechen'), $controller->url_for('urlaubskalender/birthday')) ?>
    </form>
    

</div>

<script type="text/javascript">
  
    var select_user = function (user_id, fullname) {
        document.getElementById("add_username").innerHTML = fullname;
        jQuery('#user_id').val(user_id);
        document.getElementById("holidays").style.display = "initial"; 
    };                                    
</script>