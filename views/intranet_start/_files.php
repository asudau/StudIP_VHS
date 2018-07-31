<!--  CONTENT ELEMENT, uid:files/textpic [begin] -->
    <div>
    <!--  Image block: [begin] -->
    <div class="csc-textpic-text">

    <!--  Text: [begin] -->
        <img src="<?=$plugin->getPluginURL().'/assets/images/unterlagen1.png'?>" alt="" border="0" width="100%">
        <h2 class="intranet"> <a href="index.php?id=21" title="Opens internal link in current window" class="internal-link">Dateien</a>
        <? if ($mitarbeiter_admin){ ?>
                <a style="margin-left: 68%;" href="<?=$edit_link_files?>">
                    <img src="/assets/images/icons/blue/edit.svg" alt="add" class="icon-role-clickable icon-shape-add" width="16" height="16">            
                </a>
        <? } ?>
        </h2>

         <? foreach ($mitarbeiter_folderwithfiles as $folder => $files){ ?>
        <section class="contentbox folder">
            <a class='folder_open' href=''><?= $folder ?></a>
            <? foreach ($files as $file){ ?>
            <li class='file_download' style="display:none"> <a href='../../../sendfile.php?force_download=1&type=0&file_id=<?= $file['dokument_id']?>&file_name=<?= $file['filename'] ?>'><?= $file['name'] ?></a></li>

            <?}?>
            </section>
        <?}?>
        <hr>
    <!--  Text: [end] -->
    </div>
    <!--  Image block: [end] -->
    </div>
    <!--  CONTENT ELEMENT, uid:files/textpic [end] -->