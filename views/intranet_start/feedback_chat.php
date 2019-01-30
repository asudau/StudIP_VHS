<? if ($access) : ?>
<form name="write_message" action="<?=$this->controller->url_for('intranet_start/send_feedback')?>" method="post" style="margin-left: auto; margin-right: auto;">
     
    <div>
        <label>
            <h4><span style='color:black'><?= _("Schreiben Sie ein Feedback und klicken Sie auf 'Abschicken'. Ihr Feedback wird anschließend im Intranet-Chat veröffentlicht") ?>:</span></h4>
            <textarea style="width: 100%; height: 300px;" name="content" class="add_toolbar"></textarea>
        </label>
    </div>
     Oder: 
     <a href='<?= URLHelper::getLink("/plugins.php/blubber/streams/forum?cid=" . $this->seminar_id) ?>'>
        Um den gesamten Chatverlauf zu sehen, klicken Sie hier
     </a>


    <div style="text-align: center;" data-dialog-button>
        <?= \Studip\Button::create(_('Abschicken'), 'submit') ?>
    </div>

</form>
<? endif ?>

