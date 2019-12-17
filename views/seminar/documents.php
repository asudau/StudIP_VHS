








<!-- BEGIN LECTUREDETAILS -->
<section class=contentbox><header><h1>Dateien (zum Herunterladen anklicken)</h1></header>


<!-- BEGIN DOCUMENTS -->	
   

<? foreach ($folderwithfiles as $folder => $files){ ?>
    <section class="contentbox folder">
        <a class='folder_open' href=''><?= $folder ?></a>
        <? foreach ($files as $file){ ?>
        <li class='file_download' style="display:none"> <a href='../../../sendfile.php?force_download=1&type=0&file_id=<?= $file['dokument_id']?>&file_name=<?= $file['filename'] ?>'><?= $file['name'] ?></a></li>

        <?}?>
    </section>
<?}?>


<? foreach ($documents as $document){?>        
<article>
       <a title="Download" href="<?=URLHelper::getLink('sendfile.php?force_download=1&type=0&file_id=' . $document['DOCUMENT_ID'] . '&file_name=' . $document['DOCUMENT_FILENAME'])?>"><b>   <?= $document['DOCUMENT_TITLE'] ?> </b> <br>   <?= $document['DOCUMENT_DESCRIPTION'] ?> </a>

</article>
<? } ?>	

 
    <!-- END DOCUMENTS -->
</section>

<br/>

<script>
    $(".folder_open").click(function (e) {
    e.preventDefault();
    e.stopPropagation();
    $(this).siblings('.file_download').toggle();
 });
 </script>


<!-- END LECTUREDETAILS -->