/*
* Knack JS Customizations
* Author: Benjamin Sommer
* GitHub: https://github.com/remmosnimajneb
*/


/*
* 1. Replace "State" input to a specified set of dropdown of states for all inputs (in this case, all states in the USA)
*/
$(document).on('knack-view-render.any', function(event, view, data) {
	var sl = $('<select id=slstate style="width:38px"><option value=""></option><option value=AK>AK</option><option value=AL>AL</option><option value=AR>AR</option><option value=AZ>AZ</option><option value=CA>CA</option><option value=CO>CO</option><option value=CT>CT</option><option value=DE>DE</option><option value=FL>FL</option><option value=GA>GA</option><option value=HI>HI</option><option value=IA>IA</option><option value=ID>ID</option><option value=IL>IL</option><option value=IN>IN</option><option value=KS>KS</option><option value=KY>KY</option><option value=LA>LA</option><option value=MA>MA</option><option value=MD>MD</option><option value=ME>ME</option><option value=MI>MI</option><option value=MN>MN</option><option value=MO>MO</option><option value=MS>MS</option><option value=MT>MT</option><option value=NC>NC</option><option value=ND>ND</option><option value=NE>NE</option><option value=NH>NH</option><option value=NJ>NJ</option><option value=NM>NM</option><option value=NV>NV</option><option value=NY>NY</option><option value=OH>OH</option><option value=OK>OK</option><option value=OR>OR</option><option value=PA>PA</option><option value=RI>RI</option><option value=SC>SC</option><option value=SD>SD</option><option value=TN>TN</option><option value=TX>TX</option><option value=UT>UT</option><option value=VA>VA</option><option value=VT>VT</option><option value=WA>WA</option><option value=WI>WI</option><option value=WV>WV</option><option value=WY>WY</option></select>').on('change',function(){Knack.$('input#state').val(this.value);});
  	var tsl = $('#'+view.key + ' input#state');
	sl.val(tsl.val());
	tsl.hide().after(sl);
});

/*
* 2. Add custom links to the menu bar
*/
$(document).on('knack-scene-render.any', function(event, scene) {
  
  	/* Add a link for every scene load, always */
	var formsLink = document.createElement("li");
	formsLink.innerHTML = '<a href="https://builder.knack.com"><span><span><i class="fa fa-gears"></i>&nbsp;&nbsp;<span>Builder</span></span></a>';
	$("#app-menu-list").append(formsLink);

	/* Limit the link to only a certain user role */
	if(Knack.getUserRoles().indexOf("object_18") != -1){
		var formsLink = document.createElement("li");
		formsLink.innerHTML = '<a href="https://google.com"><span><span><i class="fa fa-gears"></i>&nbsp;&nbsp;<span>Another Link</span></span></a>';
		$("#app-menu-list").prepend(formsLink);
	}

});

/*
* Append a block of text to the size a certain view (Notice or notification for example)
*/
$(document).on('knack-view-render.view_1', function(event, view, data) {
	$( ".columns" ).append( '<div class="column"><div style="border: 3px red solid;padding: 15px;font-weight:600;color:black;"><h4>Really important notice! Read me!</div></div>' );
});