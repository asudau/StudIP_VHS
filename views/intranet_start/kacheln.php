
		
<div class="mitte">
    <div class="haupttabelle">
        <div class="hauptlinks">
        </div>
			
        <div class="rechts">
		
            <?= $this->render_partial('intranet_start/_seminars.php', array('courses' => $courses)) ?>  
         
  
            <h4 class="intranet">Unsere Angebote</h4>
            <table class="dsR4" cellspacing="0" cellpadding="0" border="0">
                <tbody><tr>
                    <td class="dsR15"><div class="zentriert"><a href="https://www.kvhs-ammerland.de/index.php?id=64" target="_blank"><img src="/studip3.4/public/plugins_packages/elanev/IntranetMitarbeiterInnen/assets/images/pro_gesellschaft.png" alt="" width="73" height="72" border="0"><br>
                        Gesellschaft</a></div></td>
                    <td class="dsR15"><div class="zentriert"><a href="https://www.kvhs-ammerland.de/index.php?id=65" target="_blank"><img src="/studip3.4/public/plugins_packages/elanev/IntranetMitarbeiterInnen/assets/images/pro_paedagogik.png" alt="" width="73" height="72" border="0"><br>
                    Pädagogik</a></div></td>
                    <td class="dsR15"><a href="https://www.kvhs-ammerland.de/index.php?id=66" target="_blank"></a><div class="zentriert"><a href="index.php?id=66"><img src="/studip3.4/public/plugins_packages/elanev/IntranetMitarbeiterInnen/assets/images/pro_zielgruppen.png" alt="" width="73" height="72" border="0"><br>
                        Zielgruppen</a></div></td>
                </tr>
                <tr>
                    <td class="dsR15"><div class="zentriert"><a href="https://www.kvhs-ammerland.de/index.php?id=67" target="_blank"><img src="/studip3.4/public/plugins_packages/elanev/IntranetMitarbeiterInnen/assets/images/pro_grundbildung.png" alt="" width="72" height="72" border="0"><br>
                        Grundbildung</a></div></td>
                    <td class="dsR15"><div class="zentriert"><a href="https://www.kvhs-ammerland.de/index.php?id=68" target="_blank"><img src="/studip3.4/public/plugins_packages/elanev/IntranetMitarbeiterInnen/assets/images/pro_gesundheit.png" alt="" width="73" height="72" border="0"><br>
                        Gesundheit</a></div></td>
                    <td class="dsR15"><div class="zentriert"><a href="https://www.kvhs-ammerland.de/index.php?id=69" target="_blank"><img src="/studip3.4/public/plugins_packages/elanev/IntranetMitarbeiterInnen/assets/images/pro_beruf.png" alt="" width="73" height="72" border="0"><br>
                        Beruf</a></div></td>
                </tr>
                <tr>
                    <td class="dsR15"><div class="zentriert"><a href="https://www.kvhs-ammerland.de/index.php?id=70" target="_blank"><img src="/studip3.4/public/plugins_packages/elanev/IntranetMitarbeiterInnen/assets/images/pro_sprachen.png" alt="" width="73" height="72" border="0"><br>
                        Sprachen</a></div></td>
                    <td class="dsR15"><div class="zentriert"><a href="https://www.kvhs-ammerland.de/index.php?id=71" target="_blank"><img src="/studip3.4/public/plugins_packages/elanev/IntranetMitarbeiterInnen/assets/images/pro_kultur.png" alt="" width="73" height="72" border="0"><br>
                    Kultur</a></div></td>
                    <td class="dsR15"><div class="zentriert"><a href="https://www.kvhs-ammerland.de/index.php?id=4" target="_blank"><img src="/studip3.4/public/plugins_packages/elanev/IntranetMitarbeiterInnen/assets/images/pro_beruf.png" alt="" width="73" height="72" border="0"><br>
                    Projekte</a></div></td>
                </tr>
            </tbody></table>
		
        </div>
            
        <div class="haupt">
            <?= $this->render_partial('intranet_start/_notifications.php', array('mitarbeiter_admin' => true, 'edit_link_internnews' => '', 'internnewstemplate' => $newsTemplates[0]['template'], 'this' => $this)) ?> 
        </div>
                
    </div>
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




