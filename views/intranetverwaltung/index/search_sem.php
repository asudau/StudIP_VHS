<?
use Studip\Button, Studip\LinkButton;
?>

<form name="intranet_seminar" method="post" action="<?= $controller->url_for('intranetverwaltung/index/add_sem/' . $inst_id) ?>" class="default collapsable">
    
   <?= QuickSearch::get('Seminar_id', $search_object)
                        ->setInputStyle("width: 240px")
                        //->fireJSFunctionOnSelect('doktoranden_select')
                        ->defaultValue( 'Veranstaltung suchen') 
                        ->withButton()
                        ->render();?>

      <footer data-dialog-button>
        <?= Button::create(_('Übernehmen')) ?>
    </footer>
</form>
