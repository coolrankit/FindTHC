/*function tog_deli_item(typ){
	jQuery('.deliitem').css("display", "none");
	jQuery('.'+typ).css('display', 'table-row');
}
function tog_seed_item(typ){
	jQuery('.seeditem').css("display", "none");
	jQuery('.'+typ).css('display', 'table-row');
}*/
jQuery('.can-rate a').live('click', function(){
	var w = jQuery(this).parent();
	var ww = jQuery(w).parent();
	var val = jQuery(this).attr('rate-value');
	var pid = jQuery(ww).attr('rate-id');
	var typ = jQuery(ww).attr('rate-type');
	var key = jQuery(ww).attr('rate-key');
	jQuery.ajax({
		url: thcajax,
		type: 'POST',
		data:{
			action: 'thc_rate_it',
			id: pid,
			type: typ,
			key: key,
			val: val
		},
		success: function(data){
			if(data.length > 1){
			var newStr = data.substring(0, data.length-1);
			jQuery(ww).removeClass('can-rate');
			jQuery(ww).css('width', newStr+'px');
			jQuery(w).children('a').attr('title', 'You already rated it.');
			}
		}
	});
});
function ovrOpen(id){
	jQuery('#'+id).show();
	jQuery('.ovrw').show();
}
function ovrClose(){
	jQuery('.ovrd').hide();
	jQuery('.ovrw').hide();
}
function phenfilter(){
	var z = jQuery('#pho option:selected').attr('thcd');
	jQuery('.phenv').attr('disabled', true);
	jQuery('#ph'+z).attr('disabled', false);
	//jQuery('.phenv').hide();
	//jQuery('#ph'+z).show();
}
jQuery('.alfafltr span').live('click', function(){
	jQuery('.alfafltr span').removeClass('selected');
	jQuery('#alfaf').val(jQuery(this).html());
	jQuery(this).addClass('selected');
	jQuery('#fltrs').submit();
});

var gopt = {
  enableHighAccuracy: true,
  timeout: 5000,
  maximumAge: 60
};
function gsuc(position){
	jQuery.ajax({
		url: thcajax,
		type: "POST",
		data:{
			action: "thc_geolocation",
			lat: position.coords.latitude,
			lon: position.coords.longitude,
			geo: dogeo
		},
		dataType: "script",
		//success: function(data){jQuery('#topmap').html(data);}
	});
}
function gerr(er){}
//jQuery( document ).ready(function() {
if(dogeo){if(navigator.geolocation){navigator.geolocation.getCurrentPosition(gsuc, gerr, gopt);} else {gerr(1);}}
//});