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
    

?>


<div id='mitarbeiter'>
    
    
    <form action="<?= $controller->url_for('urlaubskalender/save_vacation') ?>" class="studip_form" method="POST">
        <fieldset>
            <h2 name="add_username" id="add_username"><?= (!$mitarbeiter_admin) ? $GLOBALS['user']->vorname . ' ' . $GLOBALS['user']->nachname : '' ?></h2>
            <input type="hidden" name="user_id" value="<?= (!$mitarbeiter_admin) ? $GLOBALS['user']->id : '' ?>" id="user_id"></input><br>
            <div id='holidays' >
                <label> Urlaubsbeginn: </label>
                <input required type="text" id="begin" name="begin" data-date-picker='{"<":"#end"}' value=""></input><br>
                <label> Urlaubsende:</label> <input id="end" data-date-picker='{">":"#begin"}' type="" name="end" value=""></input>
                <label> Hinweis/Notiz:</label> <input type="" name="notice" value=""></input>
            </div>
        </fieldset>
      
          <?= Button::createAccept(_('Speichern'), 'submit') ?>
          <?= LinkButton::createCancel(_('Abbrechen'), $controller->url_for('urlaubskalender/')) ?>
    </form>
    

</div>

<script type="text/javascript">
  
    var select_user = function (user_id, fullname) {
        document.getElementById("add_username").innerHTML = fullname;
        jQuery('#user_id').val(user_id);
        document.getElementById("holidays").style.display = "initial"; 
    };                                    
</script>