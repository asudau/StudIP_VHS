<? use Studip\Button, Studip\LinkButton; ?>

<? if (!$user) : ?>
    <form action="<?= $controller->url_for('urlaubskalender/new_birthday') ?>" class="default" method="POST" data-dialog='size=auto'>
        <?= $quick_search->render(); ?>
        <footer data-dialog-button>
          <?= Button::createAccept(_('Nutzer wählen'), 'submit') ?>
          <?= LinkButton::createCancel(_('Abbrechen'), $controller->url_for('urlaubskalender/birthday')) ?>
        </footer>
    </form>
<? else: ?>

    <form action="<?= $controller->url_for('urlaubskalender/save_birthday') ?>" class="default" method="POST">
        <fieldset>
            
            <h2 name="add_username" id="add_username"><?= $user->vorname . ' ' . $user->nachname ?></h2>
            <input id="birthday_user_id"  type="hidden" name="user_id" value="<?= $user->id?>" id="user_id"></input>
            <input id="birthday_id"  type="hidden" name="date_id" value="<?= ($date) ? $date->id : ''?>"></input>
            <div id='holidays'>
                <label> Datum: </label>
                <input required id ='birthday' name ='birthday' value='<?= $date ? date("d.m.", $date['start']) : ''?> '></input><br>
            </div>
        </fieldset>
      
        <footer data-dialog-button>
          <?= Button::createAccept(_('Speichern'), 'submit') ?>
          <?= LinkButton::createCancel(_('Abbrechen'), $controller->url_for('urlaubskalender/birthday')) ?>
        </footer>
    </form>
            
<? endif ?>
    
<script>
   $(function() {
       $( "#birthday" ).datepicker({
            showButtonPanel: true,
            dateFormat: 'd.m.',
            });
   });
</script>