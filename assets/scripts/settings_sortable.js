// JS code here
function birthday_select_user_id(value){
    //var s= document.getElementById('birthday_user_id');
    //s.value = value;   
        window.location.href = 'new_birthday/' + value;
    }

$( document ).ready(function() {
       $( "#sortable" ).sortable();

	$("#sortable").sortable({
    	 stop: function(event, ui) {
        var data = "";
	 data = $( "#sortable" ).sortable( "toArray", {attribute: 'name'} );
        $("label > [name='new_order']").val(data);
    	 }
	});
       
});

