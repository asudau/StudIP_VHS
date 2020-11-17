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
            
				<table class="dsR4" cellspacing="0" cellpadding="0" border="0">
					<tbody>
                    <tr>
						<td class="dsR15"><div class="zentriert"><a href="https://kvhs-ammerland.de/index.php?id=6" target="_blank"><img src="<?=$plugin->getPluginURL()."/assets/images/Logo-kvhs-ammerland-rot.gif" ?>" alt="" border="0" width="73" height="72"></a></div></td>
                        <td class="dsR15"><div class="zentriert"><a href="https://www.facebook.com/Kreisvolkshochschule-Ammerland-106403756090356/" target="_blank"><img src="<?=$plugin->getPluginURL()."/assets/images/facebook.png" ?>" alt="" border="0" width="72" height="72"></a></div></td>
                        <td class="dsR15"><div class="zentriert"><a href="https://www.instagram.com/kvhsammerland/" target="_blank"><img src="<?=$plugin->getPluginURL()."/assets/images/instagramm.png" ?>" alt="" border="0" width="73" height="72"></a></div></td>
                    </tr>        
                </table>

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

            <? if ($intranet_buttons): ?>
            <div style='width: 100%; margin:auto; padding-bottom: 10px'>
                <table cellspacing="0" cellpadding="0" border="0">
                    <tbody><tr>
                        <? foreach ($intranet_buttons as $button) : ?>
                        <td class="dsR4"><div class="zentriert intranet-kachel">
                                <? if ($button->target == 'dialog') : ?>
                                    <a data-dialog="title=<?= $button->text ?>;size=1000x800;" href="<?=$this->controller->url_for('intranet_start/index/linklist_dialog')?>" title="Feedback">
                                        <?= Icon::create($button->icon, 'clickable', ['size' => 100])?>
                                        <br>
                                        <?= $button->text ?>
                                    </a>
                                <? elseif ($button->target !=str_replace("mailto","",$button->target)  ) : ?>
                                    <a href="<?=$this->controller->url_for('intranet_start/feedback_form/' . split(':', $button->target)[1])?>" data-dialog="size=auto" title="<?= $button->tooltip ?>" >
                                        <?= Icon::create($button->icon, 'clickable', ['size' => 100])?>
                                        <br>
                                        <?= $button->text ?>
                                    </a>
                                <? else : ?>
                                    <a href="https://<?= $button->target ?>" target='_blank' title="<?= $button->tooltip ?>" >
                                        <?= Icon::create($button->icon, 'clickable', ['size' => 100])?>
                                        <br>
                                        <?= $button->text ?>
                                    </a>
                                <? endif ?>
                        </td>
                        <? endforeach ?>
                    </tr>
                    </tbody>
                </table>
            </div>
            <? endif ?>

            <!-- News -->
            <? foreach ($newsPosition as $course_id => $position) : ?>
                <? $template = $newsTemplates[$course_id]; ?>
                <!--  CONTENT ELEMENT, uid:434/textpic [begin] -->
                <? $fb_leitungen = []; ?>
                <? $dozenten = Seminar::getInstance($course_id)->getMembers('dozent'); ?>
                <? foreach ($dozenten as $dozent) : ?>
                    <? if ( $dozent['label'] == 'Fachbereichsleitung' ) : ?>
                        <? $fb_leitungen[] = $dozent; ?>
                    <? endif ?>
                <? endforeach ?>
                
                <div class="intranet_news csc-default csc-space-after-25">
                    <!--  Image block: [begin] -->
                    <div class="csc-textpic csc-textpic-intext-right csc-textpic-equalheight"><div class="csc-textpic-text">
                            <!--  Text: [begin] -->
                            <? if ($course_id == '2dac34217342bd706ac114d57dd0b3ec' ) : ?>
                                <div width='100%' style='background-color:#ddd;height:160px'>
                                <img src="<?= $plugin->getPluginURL().'/assets/images/Logo-kvhs-ammerland-rot.gif' ?>" alt="" border="0" width="100px" height='100px' style='margin:10px'>
                                <img src="<?= $plugin->getPluginURL().'/assets/images/Logo-ggmbh-ammerland-blau.gif' ?>" alt="" border="0" width="100px" height='100px' style='margin:10px; float:right'>
                                </div>
                            <? else :?>
                                <div width='100%' style='background-color:#ddd;height:160px'>
                                <img src="<?= CourseAvatar::getAvatar($course_id)->getCustomAvatarURl('original') ?>" alt="" border="0" width="100px" height='100px' style='margin:10px'>
                                </div>
                             <? endif ?>
                            <h2 class="intranet" style='color: #5d6a92;'>
                                <div style = 'display:flex; flex-wrap: wrap; justify-content: space-between; margin-right: 20px;'>
                                    <a href="" title="" class="internal-link"><?= $newsCaptions[$course_id] ?></a>
                                </div>
                                <? if (get_title_for_status('dozent', 1, Seminar::getInstance($course_id)->status) == 'Fachbereichsleitung'): ?>
                                <? foreach ($dozenten as $fb_leitung) : ?>
                                    <div>
                                       <?= $fb_leitung['Vorname'] ?> <?= $fb_leitung['Nachname'] ?> - <a href="mailto:<?= $fb_leitung['Email']?>" title="" class="internal-link"> <?= $fb_leitung['Email']?> <?= Icon::create('mail', 'clickable')?> </a> - <?= Institute::find($inst_id)->telefon ?>
                                   </div>
                                <? endforeach ?>
                                <? endif ?>
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