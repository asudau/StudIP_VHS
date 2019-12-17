<div>
<!--  Image block: [begin] -->
    <div class="intranet_news csc-textpic csc-textpic-intext-right csc-textpic-equalheight">
    <!--  Text: [begin] -->
    <img src="<?=URLHelper::getLink("plugins_packages/elanev/IntranetMitarbeiterInnen/assets/images/Informationen.png") ?>" alt="" border="0" width="100%">
    <h2 class="intranet">
            <a href="" title="Opens internal link in current window" class="internal-link">Interne Informationen</a>
            <? if ($mitarbeiter_admin){ ?>
            <a style="margin-left: 68%;" href="<?=$edit_link_internnews?>" rel="get_dialog">
                <img src="/assets/images/icons/blue/add.svg" alt="add" class="icon-role-clickable icon-shape-add" width="16" height="16">            
            </a>
            <? } ?>
    </h2>

    <?= $this->render_partial($internnewstemplate, compact('widget')) ?>

    <hr>

    </div>

</div>
