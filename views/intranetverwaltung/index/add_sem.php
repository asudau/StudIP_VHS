
<?
use Studip\Button, Studip\LinkButton;
?>

<html>


<form name="settings" method="post" action="<?= $controller->url_for('intranetverwaltung/index/') ?>" data-dialog class="default collapsable">
    Hier stehen Sachen drin
    
    <footer data-dialog-button>
        <?= Button::create(_('Übernehmen')) ?>
    </footer>
</form>
<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

