<? use Studip\Button, Studip\LinkButton;

        /**
        $mp = MultiPersonSearch::get("contacts_statusgroup_" . $id)
        ->setLinkText("")
        ->setDefaultSelectedUser(array_keys(getPersonsForRole($id)))
        ->setTitle(_('Personen eintragen'))
        ->setExecuteURL(URLHelper::getLink("admin_statusgruppe.php"))
        ->setSearchObject($search_obj)
        ->addQuickfilter(_("Veranstaltungsteilnehmende"), $quickfilter_sem)
        ->addQuickfilter(_("MitarbeiterInnen"), $quickfilter_inst) 
        ->addQuickfilter(_("Personen ohne Gruppe"), $quickfilter_sem_no_group)
        ->render();
        **/
    
    if (!$user) : ?>
    <form action="<?= $controller->url_for('urlaubskalender/new_vacation') ?>" class="default" method="POST" data-dialog='size=auto'>
        <?= $quick_search->render(); ?>
        <footer data-dialog-button>
          <?= Button::createAccept(_('Nutzer wählen'), 'submit') ?>
          <?= LinkButton::createCancel(_('Abbrechen'), $controller->url_for('urlaubskalender/birthday')) ?>
        </footer>
    </form>
<? else: ?>

    <form action="<?= $controller->url_for('urlaubskalender/save_vacation') ?>" class="studip_form" method="POST">
        <fieldset>
            <input type="hidden" name="user_id" value="<?= $GLOBALS['user']->id ?>" id="user_id"></input>
            <input type="hidden" name="event_id" value="<?= ($entry) ? $entry->getValue('id') : '' ?>" id="event_id"></input>
            <div id='holidays' >
                <label> Urlaubsbeginn: </label>
                <input required type="text" id="begin" name="begin" data-date-picker='{"<":"#end"}' value="<?= ($entry) ? date("d.m.Y", $entry->getValue('start')) :'' ?>"></input><br>
                <label> Urlaubsende:</label> <input id="end" data-date-picker='{">":"#begin"}' type="" name="end" value="<?= ($entry) ? date("d.m.Y", $entry->getValue('end')) : ''?>"></input>
                <label> Hinweis/Notiz:</label> <input type="" name="notice" value="<?= ($entry) ? $entry->getValue('summary') : ''?>"></input>
            </div>
        </fieldset>
      
          <?= Button::createAccept(_('Speichern'), 'submit') ?>
          <?= LinkButton::createCancel(_('Abbrechen'), $controller->url_for('urlaubskalender/')) ?>
    </form>
<? endif ?>

<script type="text/javascript">
  
    var select_user = function (user_id, fullname) {
        document.getElementById("add_username").innerHTML = fullname;
        jQuery('#user_id').val(user_id);
        document.getElementById("holidays").style.display = "initial"; 
    };                                    
</script>