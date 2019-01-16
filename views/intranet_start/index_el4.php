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
                    <h2 class="intranet"><a href="<?=$GLOBALS['ABSOLUTE_URI_STUDIP']?>dispatch.php/my_courses" title="Zur ausf�hrlichen �bersicht" class="internal-link">Meine Gruppen/Mein Arbeitsbereich</a></h2>
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
                
                <!--  CONTENT ELEMENT, uid:73/textpic [begin] -->
                <div id="c73" class="csc-default csc-space-after-25">
                <!--  Image block: [begin] -->
                    <div class="csc-textpic-text" align='center'>
                <!--  Text: [begin] -->
                    <img src="<?= $plugin->getPluginURL().'/assets/images/bbb.jpeg' ?>" alt="" border="0" width="50%">
                    <h2 class="intranet"><a href="<?=$GLOBALS['ABSOLUTE_URI_STUDIP']?>plugins.php/meetingplugin/index?cid=b8d02f67fca5aac0efa01fb1782166d1" title="Hier kommt ihr direkt zum Meeting-reiter in unserer Internen Veranstaltung, dort braucht ihr nur noch den VK-Raum anklicken und seid dabei!" class="internal-link">Abk�rzung zur eL4 Videokonferenz in BigBlueButton</a></h2>
                    </div>
                    <!--  Image block: [end] -->
                </div>
                <!--  CONTENT ELEMENT, uid:73/textpic [end] -->

                

<!--                   CONTENT ELEMENT, uid:75/textpic [begin] 
                <div id="c75" class="csc-default csc-space-after-25">
                  Image block: [begin] 
                    <div class="csc-textpic-text">
                  Text: [begin] 
                    <img src="<?=$plugin->getPluginURL().'/assets/images/question-mark.jpg' ?>" alt="" border="0" width="100%">
                    <h2 class="intranet"><a href="" title="" class="internal-link">Rund um meine Kurse</a></h2>
                    
                    <section class="contentbox themen">
                        <a href='<?=$this->controller->url_for('start/gebaeudemanagement')?>'>Leitfaden f�r neue DozentInnen (PDF)</a>
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
                     
                     <? foreach ($folderwithfiles as $folder => $files): ?>
                    <section class="contentbox folder">
                        <a class='folder_open' href=''><?= $folder ?></a>
                        <? foreach ($files as $file): ?>
                        <li class='file_download' style="display:none"> <a href='<?=$GLOBALS['ABSOLUTE_URI_STUDIP']?>sendfile.php?force_download=1&type=0&file_id=<?= $file['dokument_id']?>&file_name=<?= $file['filename'] ?>'><?= $file['name'] ?></a></li>
                        
                        <? endforeach ?>
                        </section>
                    <? endforeach ?>
                    <hr>
                <!--  Text: [end] -->
                </div>
                <!--  Image block: [end] -->
                </div>
                <!--  CONTENT ELEMENT, uid:14/textpic [end] -->
                <? endforeach ?>

				
			</div>
			<div class="haupt">
	       
                
    <!--  CONTENT ELEMENT [begin] -->
		<div class="intranet_news csc-default csc-space-after-25">
		<!--  Image block: [begin] -->
			<div class="csc-textpic csc-textpic-intext-right csc-textpic-equalheight"><div class="csc-textpic-text">
		<!--  Text: [begin] -->
            <img src="<?=$plugin->getPluginURL().'/assets/images/el4_vhs_jpg.jpg' ?>" alt="" border="0" width="100%">
			<h2 class="intranet">
                    <? if ($mitarbeiter_admin){ ?>
                    <a style="margin-left: 68%;" href="<?=URLHelper::getLink("dispatch.php/news/edit_news/new/" . $course_id) ?>" rel="get_dialog">
                        <?= Icon::create('add', 'clickable')?>             
                    </a>
                    <? } ?>
            </h2>

            
            
            <hr>
		<!--  Text: [end] -->
			</div></div>
		<!--  Image block: [end] -->
			</div>
	<!--  CONTENT ELEMENT [end] -->            
                
                
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
	
    <img src="<?=$plugin->getPluginURL().'/assets/images/cookies.jpg' ?>" alt="" border="0" width="100%">
    
    
    <? if (false && count($courses_upcoming) >0 ){ ?>
	<!--  CONTENT ELEMENT, uid:13/textpic [begin] -->
		<div id="c13" class="csc-default csc-space-after-25">
		<!--  Image block: [begin] -->
			<div class="csc-textpic-text">
		<!--  Text: [begin] -->
            <img src="<?=$plugin->getPluginURL().'/assets/images/Kursstart.png' ?>" alt="" border="0" width="100%">
			<h2 class="intranet"> <a href="index.php?id=21" title="Opens internal link in current window" class="internal-link">Kurse, die demn�chst starten</a>
                <? if ($mitarbeiter_admin){ ?>
                    <a style="margin-left: 58%;" href="<?= $this->controller->url_for('start/insertCoursebegin')?>" rel="get_dialog">
                        <?= Icon::create('add', 'clickable')?>             
                    </a>
                 <? } ?>        
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
    var courses = 3;
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
 });
</script>