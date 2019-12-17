<article class="<?= ContentBoxHelper::classes($termin['id']) ?>" id="<?= $termin['id'] ?>">
    <header>
        <h1>
            <a href="<?= ContentBoxHelper::href($termin['id']) ?>">
                <?= Assets::img('icons/16/grey/date.png', array('class' => 'text-bottom')) ?>
                <?= htmlReady($termin['title']) ?>
            </a>
        </h1>
        <nav>
            <span>
                <?= $termin['room'] ? _('Raum') . ': ' . htmlReady($termin['room']) : '' ?>
            </span>
            <? if($admin && $isProfile && !$termin['seminar_id']): ?>
            <a href="<?= URLHelper::getLink("calendar.php", array('cmd' => 'edit', 'termin_id' => $termin['id'], 'atime' => time(), 'source_page' => 'dispatch.php/profile')) ?>">
                <?= Assets::img('icons/16/blue/admin.png', array('class' => 'text-bottom')) ?>
            </a>
            <? endif; ?>
            <? if($termin['seminar_id']) : ?>
            <a href="<?= URLHelper::getLink("dispatch.php/course/dates", array('cid' => $termin['seminar_id'] )) ?>">
                <?= Assets::img('icons/16/blue/schedule.png', array('class' => 'text-bottom')) ?>
            </a>
            <? endif; ?>
        </nav>
    </header>
    <div>
<? if ($termin['description'] || count($termin['topics'])) : ?>
        <p><?= formatReady($termin['description']) ?></p>
    <? foreach ($termin['topics'] as $thema): ?>
        <h3>
            <?= Assets::img('icons/20/grey/topic.png', array('class' => "text-bottom")) ?>
            <?= htmlReady($thema['title']) ?>
        </h3>
        <div>
            <?= formatReady($thema['description']) ?>
        </div>
    <? endforeach ?>
<? else: ?>
        <?= _('Keine Beschreibung vorhanden') ?>
<? endif; ?>
    </div>
    <footer>
        <? foreach($termin['info'] as $type => $info): ?>
        <? if (!is_numeric($type)): ?>
            <em><?= htmlReady($type) ?>: </em>
        <? endif; ?>
        <?= htmlReady($info) ?>
        <? endforeach; ?>
    </footer>
</article>