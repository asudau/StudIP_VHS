<form name="write_message" action="<?=$this->controller->url_for('intranet_start/send_form')?>" method="post" style="margin-left: auto; margin-right: auto;" >
    <div>
        <h4><span style='color:black'>Ihre Meldung wird in Form einer Email an die zuständige Stelle weitergeleitet und zeitnah bearbeitet.</span>
<!--    <div>
        <label for="user_id_1"><h4><?= _("An") ?></h4></label>
            <span class="visual">
                <?= htmlReady($user['fullname']) ?>
            </span>
    </div>-->
    <div>
        <label>
            <h4><span style='color:black'><?= _("Betreff") ?>:</span></h4>
            <input type="text" name="message_subject" style="width: 100%" required value="<?= htmlReady($default_message['subject']) ?>">
        </label>
    </div>
    <div>
        <label>
            <h4><span style='color:black'><?= _("Nachricht") ?>:</span></h4>
            <textarea style="width: 100%; height: 100px; color: black" name="message_body" class="wysiwyg"></textarea>
        </label>
    </div>
    


    <div style="text-align: center;" data-dialog-button>
        <?= \Studip\Button::create(_('Abschicken'), null) ?>
    </div>

</form>


