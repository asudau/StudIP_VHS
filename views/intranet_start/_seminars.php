 <!--  CONTENT ELEMENT, uid:seminars/textpic [begin] -->
 
    <div>
        <div class="csc-textpic-text">

        <img src="<?=URLHelper::getLink("plugins_packages/elanev/IntranetMitarbeiterInnen/assets/images/Kursstart.png") ?>" alt="" border="0" width="100%">
        <h2 class="intranet"><a href="">Meine Gruppen/Mein Arbeitsbereich</a></h2>
        <? foreach ($courses as $course){ ?>
        <section class="contentbox course">
            <a href='<?=URLHelper::getLink("/seminar_main.php?auswahl=" . $course->seminar_id )?>'><?= $course->course_name ?></a></section>

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
    <!--  CONTENT ELEMENT, uid:seminars/textpic [end] -->