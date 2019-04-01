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
                    <? $avatar = CourseAvatar::getAvatar($course_id); ?>
                    <img src="<?= ($avatar->is_customized()) ? $avatar->getCustomAvatarURl('original') : $plugin->getPluginURL().'/assets/images/Projektbereich.png' ?>" alt="" border="0" width="100%">
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
                
                
                <div class="intranet_news csc-default csc-space-after-25">
                    <!--  Image block: [begin] -->
                    <div class="csc-textpic csc-textpic-intext-right csc-textpic-equalheight" style='margin-left: 20px'><div class="csc-textpic-text">
                        <!--  Text: [begin] -->
                        <a href='<?=URLHelper::getLink("wiki.php", ['cid' => 'b8d02f67fca5aac0efa01fb1782166d1', 'keyword' => 'moodle-kurse']) ?>'>
                             <img src="<?=$plugin->getPluginURL().'/assets/images/moodle.jpeg' ?>" alt="" border="0" width="100%">
                        </a>
                    <h2 class="intranet">
                        <a href='<?=URLHelper::getLink("wiki.php", ['cid' => 'b8d02f67fca5aac0efa01fb1782166d1', 'keyword' => 'moodle-kurse']) ?>' title="" class="internal-link">Übersicht: Moodle Inhalte in Stud.IP!</a>
                    </h2>
                    <!--  Text: [end] -->
                        </div></div>
                    <!--  Image block: [end] -->
                </div>

                

<!--                   CONTENT ELEMENT, uid:75/textpic [begin] 
                <div id="c75" class="csc-default csc-space-after-25">
                  Image block: [begin] 
                    <div class="csc-textpic-text">
                  Text: [begin] 
                    <img src="<?=$plugin->getPluginURL().'/assets/images/question-mark.jpg' ?>" alt="" border="0" width="100%">
                    <h2 class="intranet"><a href="" title="" class="internal-link">Rund um meine Kurse</a></h2>
                    
                    <section class="contentbox themen">
                        <a href='<?=$this->controller->url_for('start/gebaeudemanagement')?>'>Leitfaden für neue DozentInnen (PDF)</a>
                    </section>
                    <section class="contentbox themen">
                        <a href='<?=$this->controller->url_for('start/gebaeudemanagement')?>'>Formular xyz (DOC)</a>
                    </section>
                    <section class="contentbox themen">
                        <a href='<?=$this->controller->url_for('start/gebaeudemanagement')?>'>Mein Kurs in Studip (PDF) </a>
                    </section>
                    

                    <hr>
                      Text: [end] 
                    </div>
                      Image block: [end] 
                </div>
                  CONTENT ELEMENT, uid:75/textpic [end] -->
                
                
                <? foreach ($folderwithfiles_array as $course_id => $folderwithfiles) : ?>
                <!--  CONTENT ELEMENT, uid:14/textpic [begin] -->
                <div id="c14" class="csc-default csc-space-after-25">
                <!--  Image block: [begin] -->
                <div class="csc-textpic-text">
                
                <!--  Text: [begin] -->
                    <img src="<?= $plugin->getPluginURL().'/assets/images/unterlagen1.png' ?>" alt="" border="0" width="100%">
                    <h2 class="intranet"> <a href="<?=$GLOBALS['ABSOLUTE_URI_STUDIP']?>folder.php?cid=b8d02f67fca5aac0efa01fb1782166d1&cmd=tree" title="Direkt in den Dateibereich wechseln" class="internal-link"><?=$filesCaptions[$course_id]?></a>
                    <? if ($mitarbeiter_admin){ ?>
                            <a style="margin-left: 68%;" href="<?=$edit_link_files?>">
                                <?= Icon::create('add', 'clickable')?>           
                            </a>
                    <? } ?>
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
	       
                   
    <!--  eL4 Kooperation Logo [begin] -->
		<div class="intranet_news csc-default csc-space-after-25">
		<!--  Image block: [begin] -->
			<div class="csc-textpic csc-textpic-intext-right csc-textpic-equalheight"><div class="csc-textpic-text">
		<!--  Text: [begin] -->
            <a href='http://el4.me' target='_blank' title='Zur eL4 Homepage'> 
                <img src="<?=$plugin->getPluginURL().'/assets/images/el4_vhs_jpg.jpg' ?>" alt="" border="0" width="100%">
            </a>
			
		<!--  Text: [end] -->
			</div></div>
		<!--  Image block: [end] -->
			</div>
	<!--  CONTENT ELEMENT [end] -->            
                
    <div class="intranet_news csc-default csc-space-after-25" style='width:95%; margin:auto'>
        <div id="c83" class="csc-default" style='width: 25%; float:left'>
                <!--  Image block: [begin] -->
                    <div class="csc-textpic-text" align='center'>
                <!--  Text: [begin] -->
                    <img src="<?= $plugin->getPluginURL().'/assets/images/bbb.jpeg' ?>" alt="" border="0" width="65%">
                    <h2 class="intranet-inner"><a href="<?=$GLOBALS['ABSOLUTE_URI_STUDIP']?>plugins.php/meetingplugin/index?cid=b8d02f67fca5aac0efa01fb1782166d1" title="Hier kommt ihr direkt zum Meeting-reiter in unserer Internen Veranstaltung, dort braucht ihr nur noch den VK-Raum anklicken und seid dabei!" class="internal-link">
                            Direkt zur eL4 Videokonferenz
                        </a>
                    </h2>
                    </div>
                    <!--  Image block: [end] -->
        </div>
        <div id="c84" class="csc-default" style='width: 25%; float:left'>
                <!--  Image block: [begin] -->
                    <div class="csc-textpic-text" align='center'>
                <!--  Text: [begin] -->
                    <?= Icon::create('glossary', 'clickable', ['size' => 90])?>
                    <h2 class="intranet-inner">
                        <a href="<?= $this->controller->url_for('intranet_start/folder/eb0d49fba9c83fd4bf4373aca82c8f3e') ?>" 
                           title="Schnellzugriff auf Handbücher" class="internal-link" data-dialog='size=400'>
                           Schnellzugriff Handbücher
                        </a>
                    </h2>
                    </div>
                    <!--  Image block: [end] -->
        </div>
        <div id="c85" class="csc-default" style='width: 25%; float:left'>
                <!--  Image block: [begin] -->
                    <div class="csc-textpic-text" align='center'>
                <!--  Text: [begin] -->
                    <?= Icon::create('news', 'clickable', ['size' => 100, 'align' => 'center'])?>
                    <h2 class="intranet-inner">
                        <a href="<?= $this->controller->url_for('intranet_start/folder/52ed2ddd526b5848ff9d96f3ae5bbe93') ?>" 
                           title="Schnellzugriff auf Protokollordner" class="internal-link" data-dialog='size=400'>
                            Schnellzugriff Protokolle
                        </a>
                    </h2>
                    </div>
                    <!--  Image block: [end] -->
        </div>
        <div id="c85" class="csc-default" style='width: 25%; float:left'>
                <!--  Image block: [begin] -->
                    <div class="csc-textpic-text" align='center'>
                <!--  Text: [begin] -->
                    <?= Icon::create('file', 'clickable', ['size' => 100, 'align' => 'center'])?>
                    <h2 class="intranet-inner">
                        <a href="<?= $this->controller->url_for('intranet_start/semfolder/67ef6b9f0e60a5b03fc97287791cf1eb') ?>" 
                           title="Tutorials für Dozierende" class="internal-link" data-dialog='size=600'>
                            Tutorials für Dozierende </a>
                    </h2>
                    </div>
                    <!--  Image block: [end] -->
        </div>
        
        
    </div>    
    
    
    <div style="clear:both; display:none"></div>
    
                
    <!-- News -->
    <? foreach ($newsTemplates as $course_id => $template) : ?>
	<!--  CONTENT ELEMENT, uid:434/textpic [begin] -->
		<div class="intranet_news csc-default csc-space-after-25">
		<!--  Image block: [begin] -->
			<div class="csc-textpic csc-textpic-intext-right csc-textpic-equalheight"><div class="csc-textpic-text">
		<!--  Text: [begin] -->
            <img src="<?=$plugin->getPluginURL().'/assets/images/Projektbereich.png' ?>" alt="" border="0" width="100%">
			<h2 class="intranet">
                    <a href="" title="" class="internal-link"><?= $newsCaptions[$course_id] ?></a>
                    <? if ($mitarbeiter_admin){ ?>
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
	
    <div class="intranet_news csc-default csc-space-after-25">
		<!--  Image block: [begin] -->
			<div class="csc-textpic csc-textpic-intext-right csc-textpic-equalheight" style='margin-left: 20px'><div class="csc-textpic-text">
		<!--  Text: [begin] -->
        <a href='<?=$plugin->getPluginURL().'/assets/images/Intranet eL4.png' ?>' target='_blank'><img src="<?=$plugin->getPluginURL().'/assets/images/Intranet eL4.png' ?>" alt="" height="200px" width="200px"  style="border:1px solid black"></a>
        <a href='<?=$plugin->getPluginURL().'/assets/images/Intranet Osnabrueck.png' ?>' target='_blank'><img src="<?=$plugin->getPluginURL().'/assets/images/Intranet Osnabrueck.png' ?>" alt="" height="200px" width="200px"  style="border:1px solid black"></a>
        <a href='<?=$plugin->getPluginURL().'/assets/images/Intranet Ammerland.png' ?>' target='_blank'><img src="<?=$plugin->getPluginURL().'/assets/images/Intranet Ammerland.png' ?>" alt="" height="200px" width="200px"  style="border:1px solid black"></a>
        <h2 class="intranet">
                    <a href="" title="" class="internal-link">3 Screenshots verschiedener Intranet Startseiten, einfach drauflicken!</a>

            </h2>
		<!--  Text: [end] -->
			</div></div>
		<!--  Image block: [end] -->
			</div>
    
    
    <img src="<?=$plugin->getPluginURL().'/assets/images/cookies.jpg' ?>" alt="" border="0" width="100%">
    
    
    <? if (false && count($courses_upcoming) >0 ){ ?>
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