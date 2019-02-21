

<? 
require_once "lib/classes/CourseAvatar.class.php";
use Studip\LinkButton; 
   
?>
<div id="miniCourse">
<div id="miniCourseLeft">


<div id='miniCourseAvatar'>
<?=
    CourseAvatar::getAvatar($semid)->is_customized()
        ? CourseAvatar::getAvatar($semid)->getImageTag(Avatar::SMALL, array('title' => htmlReady(trim($values["Name"]))))
        : Icon::create('seminar', 'clickable', ['title' => htmlReady(trim($values["Name"]))])->asImg(20); ?>

</div>
	
    
<div id='miniCourseDetails'>
    <h1><?= htmlReady($GLOBALS['SessSemName']["header_line"]) ?></h1>
    <?php if ($GLOBALS['SessSemName'][3]) : ?>
        <b>Untertitel:</b>
        <?= htmlReady($GLOBALS['SessSemName'][3]) ?>
        <br>
    <?php endif ?>
        
    <?php if($description) : ?>
        <b>Kursbeschreibung:</b><br>
        <?= formatReady($description) ?>
        <br><br>
    <?php endif ?>
</div>

<?
$show_link = ($GLOBALS["perm"]->have_studip_perm('autor', $course_id) && $modules['schedule']);
$datesTemplate = $sem->getDatesTemplate('dates/seminar_html', array('link_to_dates' => $show_link, 'show_room' => true));
if(strcmp($datesTemplate, "Die Zeiten der Veranstaltung stehen nicht fest.") !== 0): ?>

		<b><?= _("Zeit/Veranstaltungsort") ?>:</b><br>
        <? echo $datesTemplate; ?>

        <br>
        <br>
<? endif; ?>


        <?
        $next_date = $sem->getNextDate();
        if ($next_date) {
            echo '<section class=contentbox><header><h1>'._("Nächster Termin").':</h1></header>';
            echo '<article>' . $next_date . '</article></section>';
        } else if ($first_date = $sem->getFirstDate()) {
            echo '<section class=contentbox><header><h1>'._("Erster Termin").':</h1></header>';
            echo '<article>' .$first_date . '</article></section>';
        } 

    

    ?>
        <br>
     
    <?if ($perm || $news): ?>
        <?= $this->render_partial($news, compact('widget')) ?>
    <?endif;?>
    	
    <?if ($perm || $questionnaires): ?>
        <?= $this->render_partial($questionnaires, compact('widget')) ?>
    <?endif;?>
    	
    

<? echo $documents; ?>
       

</div>

<div id="miniCourseRight">


<? echo $members; ?>
<br>
 
<?

if ($votes || $evaluations): ?>
<section class="contentbox">
    <header>
        <h1>
            <?= Assets::img('icons/16/black/vote.png'); ?>
            <?= _('Umfragen') ?>
        </h1>
    </header>

        <? foreach ($votes as $vote): ?>
            	<? $is_new = ($vote->chdate >= object_get_visit($vote->id, 'vote', false, false)) && ($vote->author_id != $GLOBALS['user']->id);
		?>
		<article class="<?= ContentBoxHelper::classes($vote->id, $is_new) ?>" id="<?= $vote->id ?>" data-visiturl="<?=URLHelper::getScriptLink('dispatch.php/vote/visit')?>">
   	 	   <header>
        		<h1>
            		<a href="<?= ContentBoxHelper::switchhref($vote->id, array('contentbox_type' => 'vote')) ?>">
                	<?= htmlReady($vote->title) ?>
            		</a>
        		</h1>
        		<nav>
            		<a href="<?= $vote->author ? URLHelper::getLink('dispatch.php/profile', array('username' => $vote->author->username)) : '' ?>">
                	<?= $vote->author ? htmlReady($vote->author->getFullName()) : '' ?>
            		</a>
            		<span>
                		<?= strftime("%d.%m.%Y", $vote->mkdate) ?>
            		</span>
            		<span>
                	<?= $vote->count ?>
           		</span>
                     </nav>
    		   </header>
    			
			<?= $GLOBALS['vote_message'][$vote->id] ?>
			<? $show_result = $controller->showResult($vote) ?>
			<? $maxvotes = $vote->maxvotes ?>
			<section>
    			<?= formatReady($vote->question) ?>
			</section>
			<form action="<?= ContentBoxHelper::href($vote->id) ?>" method="post">
    			<section class="answers">
        		<? foreach (Request::submitted('sort') ? $vote->answers->orderBy("count desc") : $vote->answers as $answer): ?>
        		<div class="answer">
            		<? if ($show_result): ?>
            		<div class="bar">
                		<div class="percent">
                    				<?= htmlReady($vote->count ? (round($answer->count * 100 / $vote->count)) : 0) ?>%
                		</div>
                	<? $width = $maxvotes ? $answer->count / $maxvotes : 0; ?>
                	<div style="display: inline-block;
                     	border:1px solid black;
                     	width: <?= 100 * ($width) ?>px;
                     	height: 8px;
                     	background-color: rgb(<?= 255 - round(215 * ($width)) ?>, <?= 255 - round(182 * ($width)) ?>, <?= 255 - round(131 * ($width)) ?>); ">
                				</div>
            				</div>
            				<div class="text">
                				<?= formatReady($answer->answer) ?>
            				</div>
            				<div class="infotext">
                					(<?= $answer->count ?> <?= $answer->count == 1 ? _("Stimme") : _("Stimmen") ?>)
                				<? if (Request::get('revealNames') === $vote->id && !$vote->anonymous && $vote->namesvisibility): ?>
                				( <?= join(', ', $answer->getUsernames()) ?> )
                				<? endif; ?>
            					</div>
           				 <? else: ?>
            				<label>
                				<? if ($vote->multiplechoice): ?>
                				<input type="checkbox" name="vote_answers[]" value="<?= $answer->position ?>">
                				<? else: ?>
                				<input type="radio" name="vote_answers[]" value="<?= $answer->position ?>">
                				<? endif ?>
                				<?= formatReady($answer->answer) ?>
            			</label>
            				<? endif; ?>
        				</div>
        				<? endforeach; ?>
				 </section>

    

    			<footer>
        			<? if ($vote->multiplechoice): ?>
        			<?= _('Sie konnten mehrere Antworten auswählen.') ?>
        			<? endif; ?>
        			<?= $vote->countInfo ?>
        			<?= $vote->anonymousInfo ?>
        			<?= $vote->endInfo ?>
        			<div class="buttons">
            			<? if (!$controller->showResult($vote)): ?>
    					<?= Studip\Button::create(_('Abstimmen'), 'vote', array('value' => $vote->id)) ?>
    					<?= Studip\LinkButton::create(_('Ergebnisse'), ContentBoxHelper::href($vote->id, array('preview[]' => $vote->id))) ?>
					<? else: ?>
    					<?= Studip\LinkButton::create(_('Ergebnisse ausblenden'), ContentBoxHelper::href($vote->id, array('preview' => 0))) ?>
    					<?= Request::get('sort')
        					? Studip\LinkButton::create(_('Nicht sortieren'), ContentBoxHelper::href($vote->id, array('preview[]' => $vote->id, 'sort' => 0))) 
        					: Studip\LinkButton::create(_('Sortieren'), ContentBoxHelper::href($vote->id, array('preview[]' => $vote->id, 'sort' => 1)))
    					?>
    					<? if ($vote->changeable && $vote->state == 'active'): ?>
        					<?= Studip\LinkButton::create(_('Antwort ändern'), ContentBoxHelper::href($vote->id, array('change' => 1))) ?>
    					<? endif; ?>
    					<? if ($vote->namesvisibility): ?>
        					<? if (Request::get('revealNames') === $vote->id) : ?>
            					<?= Studip\LinkButton::create(_('Namen ausblenden'), ContentBoxHelper::href($vote->id, array('revealNames' => null))) ?>
        					<? else : ?>
            					<?= Studip\LinkButton::create(_('Namen zeigen'), ContentBoxHelper::href($vote->id, array('revealNames' => $vote->id))); ?>
    					<? endif; ?>
    					<? endif; ?>
					<? endif; ?>
        			</div>
    			</footer>
			</form>
		</article>		

	<? endforeach; ?>
           
        <footer>
            <? if (Request::get('show_expired')): ?>
                <a href="<?= URLHelper::getLink('', array('show_expired' => 0)) ?>"><?= _('Abgelaufene Umfragen ausblenden') ?></a>
            <? else: ?>
                <a href="<?= URLHelper::getLink('', array('show_expired' => 1)) ?>"><?= _('Abgelaufene Umfragen einblenden') ?></a>
            <? endif; ?>
        </footer>
   
       
</section>
<? endif; ?>





</div>


</div>



			

	
