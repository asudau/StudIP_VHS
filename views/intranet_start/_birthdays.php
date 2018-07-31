    
 <!--  CONTENT ELEMENT, uid:16/textpic [begin] -->
<div>
<!--  Image block: [begin] -->
<div class="csc-textpic-text">

<!--  Text: [begin] -->
     <img src="<?=URLHelper::getLink("plugins_packages/elanev/IntranetMitarbeiterInnen/assets/images/klee_klein.jpg") ?>" alt="" border="0" width="100%">
     <h2 class="intranet"> <a href="<?=$GLOBALS['ABSOLUTE_URI_STUDIP']. 'plugins.php/IntranetMitarbeiterInnen/urlaubskalender/birthday'?>" title="Opens internal link in current window" class="internal-link">Geburtstage</a></h2>
      <?php if ($birthday_dates): ?>   
        <p class="bodytext">   
        <section class="contentbox folder">
        <? foreach ($birthday_dates as $date){ ?>
        <? $userinfo = UserModel::getUser($date->user_id); ?>
        <li class='birthday' title='... hat heute Geburtstag'><?= Icon::create('star', 'clickable')?> <?= $userinfo['Vorname'] . ' ' . $userinfo['Nachname']?></li>
        <?}?>
        </section>

        </p>
        <?php endif ?>

<!--  Text: [end] -->
</div>
<!--  Image block: [end] -->
</div>
<!--  CONTENT ELEMENT, uid:16/textpic [end] -->  