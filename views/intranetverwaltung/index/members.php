<? if ($inst): ?>

<h1> NutzerInnen: <?= $inst->name ?></h1>
<table id="keywordstt" class="sortable-table default">
    <thead>
		<tr>
        <th data-sort="text"><span>Username</span></th>
        <th data-sort="text"><span>Name</span></th>
        <th data-sort="text"><span>eMail</span></th>
        <th data-sort="text"><span>Status</span></th>
        <th data-sort="text"><span>Zuordnung <br/> Intranetveranstaltungen</span></th> 
        <!--<th><span>Aktionen</span></th>-->
    </tr>
    </thead>
    
    <tbody>
    
    <?php foreach ($members as $member): ?>
        <tr>
            <td><a href='<?= URLHelper::getLink('dispatch.php/profile', ['username' => $member->username]) ?>'><?= $member->username ?></a></td>
            <td><?= $member->vorname . ' ' . $member->nachname?></td>
            <td><?= $member->email ?></td>
            <td><?= ($member->inst_perms == 'dozent')? 'Dozent' : 'Autor' ?></td>
            <td>
                <?= IntranetConfig::CourseMembershipsOfUser($inst->id, $member->user_id) ?> / <?= sizeof($inst_courses)?>
                <? if (IntranetConfig::CourseMembershipsOfUser($inst->id, $member->user_id) < sizeof($inst_courses)) : ?>
                    <a title='In fehlende Veranstaltungen eintragen' href = <?= $controller->url_for('/intranetverwaltung/index/add_missing_course_assignments/'. $inst->id . '/' . $member->user_id )?>>&nbsp; <?=Icon::create('door-enter', 'clickable')?>
                <? endif ?>
            </td>
<!--            <td>
                <a title='(Nochmal) Einladen' href = <?= $controller->url_for('index/send_register_invitation/' . $member->user_id )?>> <?=Icon::create('mail', 'clickable')?>
            </td>-->
        </tr>
    <?php endforeach ?>
        
    </tbody>
</table>

<? endif; ?>