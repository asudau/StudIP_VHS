
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
            <label><?= _('Zentral oder Seite') ?></label>
            <select name ='button_side'>
                <option <?= $button->content_side == 'left' ? 'selected' : '' ?> value="left"><?= Zentral ?></option> 
                <option <?= $button->content_side == 'right' ? 'selected' : '' ?> value="right"><?= Rechts ?></option> 
            </select>
        </section>
        <section>
            <label><?= _('Link (ohne http(s)://)') ?></label>
            <input type="text" name ='button_target' value='<?= $button->target ?>'>
        </section>
        <section>
            <label><?= _('Icon') ?></label>
            <select name="button_icon">
                <option <?= $button->icon == 'custom' ? 'selected' : '' ?> value="custom"><?= individuell ?></option> 
                <?php foreach($this->plugin->icons as $icon) : ?>
                    <option style="background-image:url(<?= Icon::create($icon, 'clickable')->asImagePath() ?>)" value='<?= $icon ?>' <?= (  $button->icon  == $icon ) ? 'selected' : ''?>> <?= $icon ?></option>
                <?php endforeach ?>
            </select>
            <label><?= _('Link zum Icon (ohne http(s)://)') ?></label>
            <input type="text" name ='button_icon_link' value='<?= $button->icon_link ?>'>
        </section>
    </fieldset>
    <footer data-dialog-button>
        <?= Button::create(_('Übernehmen')) ?>
    </footer>

</form>