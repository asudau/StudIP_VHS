
<?
use Studip\Button, Studip\LinkButton;
?>


<form name="intranet-button" method="post" action="<?= $controller->url_for('intranetverwaltung/index/save_buttons/' . $intranet_id . '/' . $button->Button_id) ?>" <?= $dialog_attr ?> class="default collapsable">
    <?= CSRFProtection::tokenTag() ?>
    <fieldset>
        <section>
            <label><?= _('Button-Text') ?></label>
            <input type='text' name ='button_text' value='<?= $button->text ?>'>
        </section>
        <section>
            <label><?= _('Tooltip-Text') ?></label>
            <input type='text' name ='button_tooltip' value='<?= $button->tooltip ?>'>
        </section>
        <section>
            <label><?= _('Position') ?></label>
            <input type='text' name ='button_position' value='<?= $button->position ?>'>
        </section>
        <section>
            <label><?= _('Link (ohne http(s)://)') ?></label>
            <input type="text" name ='button_target' value='<?= $button->target ?>'>
        </section>
        <section>
            <label><?= _('Icon') ?></label>
            <select name="button_icon">
                <?php foreach($this->plugin->icons as $icon) : ?>
                    <option style="background-image:url(<?= Icon::create($icon, 'clickable')->asImagePath() ?>)" value='<?= $icon ?>' <?= (  $button->icon  == $icon ) ? 'selected' : ''?>> <?= $icon ?></option>
                <?php endforeach ?>
            </select>
        </section>
    </fieldset>
    <footer data-dialog-button>
        <?= Button::create(_('Übernehmen')) ?>
    </footer>

</form>