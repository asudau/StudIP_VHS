
<table class='default'>
    <head>
        <th>Beginn</th>
        <th>Ende</th>
        <th>Name</th>
        <th>Aktionen</th>
    </head>
    <tbody>
        <? if($dates) : ?>
            <? foreach($dates as $event): ?>
            <tr>
                <td><?= date("d.m.Y", $event['start']) ?> </td>
                <td><?= date("d.m.Y", $event['end']) ?> </td>
                <td><?= $event['summary']?></td>
                <td><a href='<?= $controller->url_for('urlaubskalender/new_vacation/' . $event['id']) ?>' data-dialog='size=auto' ><?= Icon::create('edit', 'clickable')?></a></td>
            </tr>
            <? endforeach ?>
        <? endif ?>
    </tbody>
</table>
