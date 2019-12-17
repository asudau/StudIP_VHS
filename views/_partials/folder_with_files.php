 <? foreach ($folderwithfiles as $folder => $files): ?>
    <? if ($parentfolder[$folder] == $parent) : ?>
        <section class="contentbox folder" <?= $parent ? 'style="display:none"' : '' ?>>
        <a class='folder_open' href=''><?= Icon::create('folder-full', 'clickable')?> <?= DocumentFolder::find($folder)->name ?></a>
        <? if(array_keys($parentfolder, $folder)) : ?>
            <? foreach (array_keys($parentfolder, $folder) as $subfolder): ?>
                <?= $this->render_partial('_partials/folder_with_files', 
                    array('folderwithfiles' => [$subfolder => $folderwithfiles[$subfolder]], 
                        'parentfolder' => $parentfolder,
                        'parent' => $folder)) ?>
            <? endforeach ?>
        <? endif ?>
        <? foreach ($files as $file): ?>
            <li class='file_download' style="display: <?=$display? : 'none' ?> "> <a href='<?=$GLOBALS['ABSOLUTE_URI_STUDIP']?>sendfile.php?force_download=1&type=0&file_id=<?= $file['dokument_id']?>&file_name=<?= $file['filename'] ?>'><?= $file['name'] ?></a></li>

        <? endforeach ?>
        </section>
    <? endif ?>
<? endforeach ?>
