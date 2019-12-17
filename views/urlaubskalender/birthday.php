<? use Studip\Button, Studip\LinkButton; ?>


<table class='default'>
    <head>
        <th>Datum</th>
        <th>Name</th>
        <th>Hinweis</th>
        <? if ($mitarbeiter_hilfskraft) : ?>
            <th>Aktionen</th>
        <? endif ?>
    </head>
    <tbody>
        <? if($dates) : ?>
            <? foreach($dates as $event): ?>
            <tr>
                <td><?= date("d.m.", $event['start']) ?></td>
                <td><?= $event['summary']?></td>
                <td><?= $event['description']?></td>
                <? if ($mitarbeiter_hilfskraft) : ?>
                <td>
                    <a href='<?= $controller->url_for('urlaubskalender/new_birthday/'. $event['event_id']) ?>' data-dialog >
                    <?= Icon::create('edit', 'clickable') ?> </a>
                    <a href='<?= $controller->url_for('urlaubskalender/delete_birthday/'. $event['event_id']) ?>' >
                    <?= Icon::create('trash', 'clickable') ?> </a>
                </td>
                <? endif ?>
            </tr>
            <? endforeach ?>
        <? endif ?>
    </tbody>
</table>

