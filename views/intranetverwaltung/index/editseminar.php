
<?
use Studip\Button, Studip\LinkButton;
?>

<html>


<form name="intranet_seminar" method="post" action="<?= $controller->url_for('intranetverwaltung/index/saveseminar/' . $sem_id . '/' . $inst_id) ?>" class="default collapsable">
    <?= CSRFProtection::tokenTag() ?>
    <input id="open_variable" type="hidden" name="open" value="<?= $flash['open'] ?>">
    
    
    <fieldset <?= isset($flash['open']) && $flash['open'] != 'bd_basicsettings' ? 'class="collapsed"' : ''?> data-open="bd_basicsettings">
        <legend>News/Ankündigungen</legend>

        <table>
            <tr>
                <td style="width:700px"  >
                    Ankündigungen, die in dieser Veranstaltung angelegt werden, auf der zugehörigen Startseite anzeigen:
                </td>
                <td style="width:300px"  >
                    <input type='checkbox' name="show_news" <?= ($entry->show_news ) ? 'checked' : ''?> ></input>
                </td>
            </tr>
            <tr>
                <td>
                    Überschrift für News-Box:
                </td>
                <td style="width:700px">
                    <input type='text' name ='news_caption' value = '<?= ($entry->news_caption )?>'> 
                </td>
            </tr>
        </table>
        <legend>Dateien</legend>

        <table>
            <tr>
                <td style="width:700px"  >
                    Dateien, die in dieser Veranstaltung angelegt werden, auf der zugehörigen Startseite anzeigen:
                </td>
                <td style="width:300px"  >
                    <input type='checkbox' name="use_files" <?= ($entry->use_files ) ? 'checked' : ''?>></input>
                </td>
            </tr>
            <tr>
                <td>
                    Überschrift für Datei-Box:
                </td>
                <td style="width:700px">
                    <input type='text' name ='files_caption' value='<?= ($entry->files_caption )?>'> 
                </td>
            </tr>
        </table>
        
        <legend>Automatisches Eintragen von Nutzern</legend>

        <table>
            <tr>
                <td style="width:700px"  >
                    Nutzer der aktuellen Einrichtung automatisch in diese Veranstaltung eintragen:
                </td>
                <td style="width:300px"  >
                    <select name="add_instuser_as">
                        <option value='' <?= (!$entry->add_instuser_as ) ? 'selected' : ''?>>Nicht eintragen</option>
                        <option value='autor' <?= ($entry->add_instuser_as == 'autor' ) ? 'selected' : ''?>>Teilnehmer/Autor</option>
                        <option value='tutor' <?= ($entry->add_instuser_as == 'tutor' ) ? 'selected' : ''?>>Tutor</option>
                        <option value='dozent' <?= ($entry->add_instuser_as == 'dozent' ) ? 'selected' : ''?>>Administrator/Dozent</option> 
                    </select>
                </td>
            </tr>
        </table>
       
    </fieldset>

    
    <footer data-dialog-button>
        <?= Button::create(_('Übernehmen')) ?>
    </footer>
</form>




