<? use Studip\Button, Studip\LinkButton; ?>


<div id='mitarbeiter'>
    
    
    <form action="<?= $controller->url_for('urlaubskalender/edituser_birthday' ) ?>" class="studip_form" method="POST">
        <fieldset>

            <label for="student_search" class="caption">
                <?= _('MitarbeiterIn suchen')?>
                <?= Icon::create('info-circle', 'info', array('title' => $help))?>
            </label>

                <?= $quick_search->render();
            ?>
            <div name="add_username" id="add_username"></div>
            <input type="hidden" name="user_id" value="" id="user_id"></input><br>
        </fieldset>
      
          <?= Button::createAccept(_('Einträge bearbeiten'), 'submit', array('disabled'=> '')) ?>
          <?= LinkButton::createCancel(_('Abbrechen'), $controller->url_for('urlaubskalender/')) ?>
    </form>
    

</div>


<script type="text/javascript">
  
    var select_user = function (user_id, fullname) {
        document.getElementById("add_username").innerHTML = fullname;
        jQuery('#user_id').val(user_id);
        document.getElementsByName("submit")[0].disabled = false;
        //$(this).closest("form").submit();
    };                                    
</script>