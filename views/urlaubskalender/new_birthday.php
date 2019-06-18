<? use Studip\Button, Studip\LinkButton; ?>

<? if (!$user) : ?>
    <?= $quick_search->render(); ?>
<? else: ?>

    <form action="<?= $controller->url_for('urlaubskalender/save_birthday') ?>" class="default" method="POST">
        <fieldset>
            
            <h2 name="add_username" id="add_username"><?= (!$mitarbeiter_admin) ? $user->vorname . ' ' . $user->nachname : '' ?></h2>
            <input id="birthday_user_id"  type="hidden" name="user_id" value="<?= $GLOBALS['user']->id?>" id="user_id"></input><br>
            <div id='holidays' style="<?= (!$mitarbeiter_admin) ? '' : 'display:none;' ?>">
                <label> Datum: </label>
                <input required type="text" id="begin" name="begin" data-date-picker value="<?= $date ? date("m.d.y", $date['start']) : ''?> "></input><br>

                <label> Hinweis/Notiz:</label> <input type="" name="notice" value=""></input>
            </div>
        </fieldset>
      
        <footer data-dialog-button>
          <?= Button::createAccept(_('Speichern'), 'submit') ?>
          <?= LinkButton::createCancel(_('Abbrechen'), $controller->url_for('urlaubskalender/birthday')) ?>
        </footer>
    </form>
            
<? endif ?>
    
