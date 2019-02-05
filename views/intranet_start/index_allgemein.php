<? if (sizeof($intranets) >1) : ?>
    <?= $this->render_partial('_partials/intranet_selector', array('intranets' => $intranets)) ?>
<? endif ?>

<? if ($flash['question']): ?>
    <?= $flash['question'] ?>
<? endif; ?>
		
<div class="mitte"><div class="haupttabelle">
			<div class="hauptlinks"></div>
			<div class="rechts">
				<!--<div align="center"><a href="index.php?id=144"><img src="/fileadmin/template/img/suche1.png" alt=""></a></div>
				<!--<div align="center"><a href="index.php?id=146"><img src="/fileadmin/template/img/suche2.png" alt=""></a></div>
				<br>

               	 <!--  CONTENT ELEMENT, uid:73/textpic [begin] -->
                <div id="c73" class="csc-default csc-space-after-25">
                <!--  Image block: [begin] -->
                    <div class="csc-textpic-text">
                <!--  Text: [begin] -->
                    <img src="<?= $plugin->getPluginURL().'/assets/images/Kursstart.png' ?>" alt="" border="0" width="100%">
                    <h2 class="intranet"><a href="<?=$GLOBALS['ABSOLUTE_URI_STUDIP']?>dispatch.php/my_courses" title="Zur ausführlichen Übersicht" class="internal-link">Meine Gruppen/Mein Arbeitsbereich</a></h2>
                    <? foreach ($courses as $course){ ?>
                    <section class="contentbox course">
                        <a href='<?=$GLOBALS['ABSOLUTE_URI_STUDIP']. 'seminar_main.php?auswahl=' . $course['Seminar_id'] ?>'><?= $course['Name'] ?></a></section>
                        
                    <?}
                    
                    if (count($courses) > 6){
                    ?>
                        <a class="all_courses" href="#"></a>
                    <?}

                    ?>
                    <hr>
                    <!--  Text: [end] -->
                    </div>
                    <!--  Image block: [end] -->
                </div>
                <!--  CONTENT ELEMENT, uid:73/textpic [end] -->
                
                
                
               <? foreach ($folderwithfiles_array as $course_id => $folderwithfiles) : ?>
                <!--  CONTENT ELEMENT, uid:14/textpic [begin] -->
                <div id="c14" class="csc-default csc-space-after-25">
                <!--  Image block: [begin] -->
                <div class="csc-textpic-text">
                
                <!--  Text: [begin] -->
                    <img src="<?= $plugin->getPluginURL().'/assets/images/unterlagen1.png' ?>" alt="" border="0" width="100%">
                    <h2 class="intranet"> 
                        <div style = 'display:flex; flex-wrap: wrap; justify-content: space-between; margin-right: 20px;'>
                        <a href="<?=$GLOBALS['ABSOLUTE_URI_STUDIP']?>folder.php?cid=<?=$course_id?>&cmd=tree" title="Direkt in den Dateibereich wechseln" class="internal-link"><?=$filesCaptions[$course_id]?></a>
                        <? if ($GLOBALS['perm']->have_studip_perm('dozent', $course_id)){ ?>
                            <a href="<?=$edit_link_files?>">
                                <?= Icon::create('add', 'clickable')?>           
                            </a>
                        <? } ?>
                        </div>
                    </h2>
                        <?= $this->render_partial('_partials/folder_with_files', array('folderwithfiles' => $folderwithfiles, 'parentfolder' => $parentfolder, 'parent' => NULL)) ?>
                    <hr>
                <!--  Text: [end] -->
                </div>  
                <!--  Image block: [end] -->
                </div>
                <!--  CONTENT ELEMENT, uid:14/textpic [end] -->
                <? endforeach ?>

				
			</div>
			<div class="haupt">
	       
                
    <!-- News -->
    <? foreach ($newsTemplates as $course_id => $template) : ?>
	<!--  CONTENT ELEMENT, uid:434/textpic [begin] -->
		<div class="intranet_news csc-default csc-space-after-25">
		<!--  Image block: [begin] -->
			<div class="csc-textpic csc-textpic-intext-right csc-textpic-equalheight"><div class="csc-textpic-text">
		<!--  Text: [begin] -->
            <? $avatar = CourseAvatar::getAvatar($course_id); ?>
            <img src="<?= ($avatar->is_customized()) ? $avatar->getCustomAvatarURl('original') : $plugin->getPluginURL().'/assets/images/Projektbereich.png' ?>" alt="" border="0" width="100%">
            <h2 class="intranet">
                    <a href="" title="" class="internal-link"><?= $newsCaptions[$course_id] ?></a>
                    <? if ($GLOBALS['perm']->have_studip_perm('dozent', $course_id)){ ?>
                    <a style="margin-left: 68%;" href="<?=URLHelper::getLink("dispatch.php/news/edit_news/new/" . $course_id) ?>" rel="get_dialog">
                        <?= Icon::create('add', 'clickable')?>             
                    </a>
                    <? } ?>
            </h2>

            <?= $this->render_partial($template, compact('widget')) ?>
            
            <hr>
		<!--  Text: [end] -->
			</div></div>
		<!--  Image block: [end] -->
			</div>
	<!--  CONTENT ELEMENT, uid:434/textpic [end] -->
    <? endforeach ?>
	
  
    
    
    <? if (count($courses_upcoming) >0 ){ ?>
	<!--  CONTENT ELEMENT, uid:13/textpic [begin] -->
		<div id="c13" class="csc-default csc-space-after-25">
		<!--  Image block: [begin] -->
			<div class="csc-textpic-text">
		<!--  Text: [begin] -->
            <img src="<?=$plugin->getPluginURL().'/assets/images/Kursstart.png' ?>" alt="" border="0" width="100%">
            
			<h2 class="intranet"> <a href="index.php?id=21" title="Opens internal link in current window" class="internal-link"></a>
                
            </h2>
            <? foreach ($courses_upcoming as $course){ ?>
                    <section class="contentbox">
                        
                        <? if ($mitarbeiter_admin){ ?>
                            <a href="<?= $this->controller->url_for('start/insertCoursebegin/' . $course['event_id'])?>" rel="get_dialog">
                            <img src="/assets/images/icons/blue/edit.svg" alt="edit" class="icon-role-clickable icon-shape-add" width="16" height="16">            
                            </a>
                        <? } ?>   
                        <a target='_blank'  href='<?= $course['description'] ?>'><?= $course['summary'] ?>  <?= date('d.m.Y', $course['start']) ?></a>
                        
                    </section>
                        
                    <?}?>
            <hr>
		<!--  Text: [end] -->
			</div>
		<!--  Image block: [end] -->
			</div>
	<!--  CONTENT ELEMENT, uid:13/textpic [end] -->
    <? } ?>
    
    
		
		</div></div>
		</div>

<script>
    var courses = 6;
hidecourses = "- zuklappen";
showcourses = "+ Alle Kurse anzeigen";

$(".all_courses").html( showcourses );
$(".course:not(:lt("+courses+"))").hide();

$(".all_courses").click(function (e) {
   e.preventDefault();
       if ($(".course:eq("+courses+")").is(":hidden")) {
           $(".course:hidden").show();
           $(".all_courses").html( hidecourses );
       } else {
           $(".course:not(:lt("+courses+"))").hide();
           $(".all_courses").html( showcourses );
       }
});


$(".folder_open").click(function (e) {
    e.preventDefault();
    e.stopPropagation();
    $(this).siblings('.file_download').toggle();
    $(this).siblings('.folder').toggle();
 });
</script>