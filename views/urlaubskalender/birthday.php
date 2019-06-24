<? use Studip\Button, Studip\LinkButton; ?>


<table class='default'>
    <head>
        <th>Datum</th>
        <th>Name</th>
        <th>Aktionen</th>
    </head>
    <tbody>
        <? if($dates) : ?>
            <? foreach($dates as $event): ?>
            <tr>
                <td><?= date("d.m.", $event['start']) ?></td>
                <td><?= $event['summary']?></td>
                <td>
                    <a href='<?= $controller->url_for('urlaubskalender/new_birthday/'. $event['event_id']) ?>' data-dialog >
                    <?= Icon::create('edit', 'clickable') ?> </a>
                    <a href='<?= $controller->url_for('urlaubskalender/delete_birthday/'. $event['event_id']) ?>' >
                    <?= Icon::create('trash', 'clickable') ?> </a>
                </td>
            </tr>
            <? endforeach ?>
        <? endif ?>
    </tbody>
</table>

