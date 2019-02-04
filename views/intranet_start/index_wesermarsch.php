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
                    <h2 class="intranet">
                        <a href="<?=$GLOBALS['ABSOLUTE_URI_STUDIP']?>dispatch.php/my_courses" title="Zur ausführlichen Übersicht" class="internal-link">
                                Meine Gruppen/Mein Arbeitsbereich
                        </a>
                    </h2>
                    <? if ($GLOBALS['perm']->have_perm('tutor')) : ?>
                    <section title='Neuen Kurs/Arbeitsgruppe/etc. erstellen' class="contentbox course" style='background-color: #eee'>
                        <a href="<?=URLHelper::getLink("dispatch.php/course/wizard/step/0") ?>" target='_blank' <?= Icon::create('add', 'clickable')?> Neue Veranstaltung anlegen</a></section>
                    <? endif ?>     
                    
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
                    <h2 class="intranet"> <a href="<?=$GLOBALS['ABSOLUTE_URI_STUDIP']?>folder.php?cid=<?=$course_id?>&cmd=tree" title="Direkt in den Dateibereich wechseln" class="internal-link"><?=$filesCaptions[$course_id]?></a>
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
                
              
  
  
				<h4 class="intranet">Unsere Angebote</h4>
				<table class="dsR4" cellspacing="0" cellpadding="0" border="0">
					<tbody>
                    <tr>
						<td class="dsR15"><div class="zentriert"><a href="https://kvhs-wsm.de/vhs/index.php?id=92&katid=80&catDepth=1&kathaupt=1#kateg80" target="_blank"><img src="<?=$plugin->getPluginURL()."/assets/images/pro_sprachen.png" ?>" alt="" border="0" width="73" height="72"><br>
							Sprachen</a></div></td>
                        <td class="dsR15"><div class="zentriert"><a href="https://kvhs-wsm.de/vhs/index.php?id=92&kathaupt=1&katid=148&catDepth=1#kateg148" target="_blank"><img src="<?=$plugin->getPluginURL()."/assets/images/pro_grundbildung.png" ?>" alt="" border="0" width="72" height="72"><br>
							Grundbildung</a></div></td>
                        <td class="dsR15"><div class="zentriert"><a href="https://kvhs-wsm.de/vhs/index.php?id=92&katid=188&catDepth=1&kathaupt=1#kateg188" target="_blank"><img src="<?=$plugin->getPluginURL()."/assets/images/pro_kultur.png" ?>" alt="" border="0" width="73" height="72"><br>
                            Kulturelle Bildung</a></div></td>
                    </tr>        
                        <td class="dsR15"><div class="zentriert"><a href="https://kvhs-wsm.de/vhs/index.php?id=92&kathaupt=1&katid=189&catDepth=1#kateg189" target="_blank"><img src="<?= $plugin->getPluginURL()."/assets/images/pro_gesellschaft.png" ?>" alt="" border="0" width="73" height="72"><br>
							Gesellschaft</a></div></td>
                        <td class="dsR15"><div class="zentriert"><a href="https://kvhs-wsm.de/vhs/index.php?id=92&kathaupt=1&katid=189&catDepth=1#kateg189" target="_blank"><img src="<?=$plugin->getPluginURL()."/assets/images/gesundheit.png" ?>" alt="" border="0" width="73" height="72"><br>
							Gesundheits- bildung</a></div></td>
						<td class="dsR15"><div class="zentriert"><a href="https://kvhs-wsm.de/vhs/index.php?id=92&katid=222&catDepth=1&kathaupt=1#kateg222" target="_blank"><img src="<?= $plugin->getPluginURL()."/assets/images/kochen.png" ?>" alt="" border="0" width="73" height="72"><br>
						Kochen und Ernährung</a></div></td>
						
					</tr>
					<tr>
                        <td class="dsR15"><div class="zentriert"><a href="https://www.kvhs-ammerland.de/index.php?id=69" target="_blank"><img src="<?=$plugin->getPluginURL()."/assets/images/pro_beruf.png" ?>" alt="" border="0" width="73" height="72"><br>
							Berufliche Bildung</a></div></td>
						<td class="dsR15"><div class="zentriert"><a href="https://kvhs-wsm.de/vhs/index.php?id=92&kathaupt=1&katid=243&catDepth=1#kateg243"><img src="<?=$plugin->getPluginURL()."/assets/images/computer.png" ?>" alt="" border="0" width="73" height="72"><br>
							Computer und neue Medien</a></div></td>
						<td class="dsR15"><div class="zentriert"><a href="https://kvhs-wsm.de/vhs/index.php?id=140" target="_blank"><img src="<?=$plugin->getPluginURL()."/assets/images/bildurlaub.png" ?>" alt="" border="0" width="73" height="72"><br>
							Bildungsurlaub</a></div></td>
						

				</tbody></table>
				
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
		
    <div class="intranet_news csc-default csc-space-after-50">
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
    
    
<!--    <iframe width="655px" height="600px" src="https://www.yumpu.com/de/embed/view/3Zg6zYDHcToasqF5" frameborder="0" allowfullscreen="true"  allowtransparency="true"></iframe>
	-->
    	
    
     <? if (PluginManager::getInstance()->getPlugin('SchwarzesBrettWidget')) : ?>
    <!--  CONTENT ELEMENT, uid:42/textpic [begin] -->
		<div id="c42" class="csc-default csc-space-after-25">
		<!--  Image block: [begin] -->
			<div class="csc-textpic-text">
		<!--  Text: [begin] -->
            <img src="<?=$plugin->getPluginURL().'/assets/images/schwarzesbrett.jpg' ?>" alt="" border="0" width="100%">
			<h2 class="intranet"> <a href="<?=URLHelper::getLink("/plugins.php/schwarzesbrettplugin/category")?>" title="" class="internal-link">Schwarzes Brett</a>
                <a style="margin-left: 74%;" data-dialog='' href="<?=URLHelper::getLink($GLOBALS['ABSOLUTE_URI_STUDIP']. "/plugins.php/schwarzesbrettplugin/article/create", array('return_to' => $GLOBALS['ABSOLUTE_URI_STUDIP']. 'plugins.php/studip_vhs/intranet_start'))?>">
                    <?= Icon::create('add', 'clickable')?>            
                </a>      
            </h2>
                <?php 
                $schwarzesBrett = PluginManager::getInstance()->getPlugin('SchwarzesBrettWidget');
                $template = $schwarzesBrett->getPortalTemplate();
                $template = $schwarzesBrett->getContent();
                $layout = $GLOBALS['template_factory']->open('shared/index_box');
                $layout = NULL;
                echo $template;
                //echo $template->render(NULL, $layout);
                //$layout->clear_attributes();
                ?>
            <hr>
		<!--  Text: [end] -->
			</div>
		<!--  Image block: [end] -->
			</div>
	<!--  CONTENT ELEMENT, uid:42/textpic [end] -->
    <? endif ?>
    
    
    
    
   
	<!--  CONTENT ELEMENT -->
<!--		<div class="csc-space-after-25">
		  Image block: [begin] 
            <iframe width="560" height="315" src="https://www.youtube.com/embed/VAibAJquJSo" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
		  Image block: [end] 
        </div>-->
	<!--  CONTENT ELEMENT, uid:13/textpic [end] -->

    
    
		
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