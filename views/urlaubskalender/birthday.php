<? use Studip\Button, Studip\LinkButton; ?>


<table class='default'>
    <head>
        <th>Datum</th>
        <th>Name</th>
    </head>
    <tbody>
        <? if($dates) : ?>
            <? foreach($dates as $event): ?>
            <tr>
                <td><a href='<?= $controller->url_for('urlaubskalender/new_birthday/'. $event['event_id']) ?>' data-dialog ><?= date("d.m.", $event['start']) ?> </a></td>
                <td><?= $event['summary']?></td>
            </tr>
            <? endforeach ?>
        <? endif ?>
    </tbody>
</table>

