
<table class="sortable-table default">
    <head>
        <th data-sort="htmldata">Beginn</th>
        <th data-sort="htmldata">Ende</th>
        <th data-sort="htmldata">Name</th>
        <th data-sort="false">Hinweis</th>
        <th data-sort="false">Aktionen</th>
    </head>
    <tbody>
        <? if($dates) : ?>
            <? foreach($dates as $event): ?>
                <? if ($mitarbeiter_admin || ($event['summary'] == ($user->vorname . ' ' . $user->nachname))): ?>
                <tr>
                    <td><?= date("d.m.Y", $event['start']) ?> </td>
                    <td><?= date("d.m.Y", $event['end']) ?> </td>
                    <td><?= $event['summary']?></td>
                    <td><?= $event['description']?></td>
                    <td>
                        <a href='<?= $controller->url_for('urlaubskalender/new_vacation/' . $event['id']) ?>' data-dialog='size=auto' >
                            <?= Icon::create('edit', 'clickable')?></a>
                        <a href='<?= $controller->url_for('urlaubskalender/delete/'. $event['event_id']) ?>' >
                            <?= Icon::create('trash', 'clickable') ?> </a>
                    </td>
                </tr>
                 <? endif ?>
            <? endforeach ?>
        <? endif ?>
    </tbody>
</table>
