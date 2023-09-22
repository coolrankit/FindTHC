function add_meds_vals(){
	var v = jQuery("#smvalso").val();
	if(v && v!=0 && v!='0'){
	var mv = '<p><input type="hidden" name="smvals[]" value="'+v+'"><input type="text" value="'+v+'" disabled> <input type="button" class="button" value="X" onClick="rem_meds_vals(this)"></p>';
	jQuery('#meds-vals').append(mv);
	}
}
function add_meds_cons(){
	var v = jQuery("#umconso").val();
	if(v && v!=0 && v!='0'){
	var mc = '<p><input type="hidden" name="umcons[]" value="'+v+'"><input type="text" value="'+v+'" disabled> <input type="button" class="button" value="X" onClick="rem_meds_cons(this)"></p>';
	jQuery('#meds-cons').append(mc);
	}
}
function rem_meds_cons(el){
	jQuery(el).parent().remove();
}
function rem_meds_vals(el){
	jQuery(el).parent().remove();
}
function add_flavs_vals(){
	var v = jQuery("#sfvalso").val();
	if(v && v!=0 && v!='0'){
	var fv = '<p><input type="hidden" name="sfvals[]" value="'+v+'"><input type="text" value="'+v+'" disabled> <input type="button" class="button" value="X" onClick="rem_flavs_vals(this)"></p>';
	jQuery('#flavs-vals').append(fv);
	}
}
function rem_flavs_vals(el){
	jQuery(el).parent().remove();
}
/*function add_phens_valsm(){
	var pv = '<p><input type="text" name="apkeys[]" placeholder="Phenotype" value=""> : <input type="text" name="apvals[]" placeholder="Option Values" value=""> <input type="button" class="button" value="X" onClick="rem_phens_vals(this)"></p>';
	jQuery('#phens-vals').append(pv);
}*/
function add_phens_vals(){
	var v = jQuery("#phenso").val();
	if(v && v!=0 && v!='0'){
		jQuery.ajax({
			url: ajaxurl,
			type: 'POST',
			data:{
				action: 'thc_add_new_phen',
				pheno: v
			},
			success: function(data){
				if(data.length > 1){
				var newStr = data.substring(0, data.length-1);
				jQuery('#phens-vals').append(newStr);
				}
			}
		});
	}
}
function add_sets_vals(){
	var i = jQuery('#sets-vals').children('.postbox').length;
	if(aci && i<6){
		jQuery.ajax({
			url: ajaxurl,
			type: 'POST',
			data:{
				action: 'thc_add_new_set',
				aci: aci
			},
			success: function(data){
				if(data.length > 1){
				aci++;
				var newStr = data.substring(0, data.length-1);
				jQuery('#sets-vals').append(newStr);
				}
			}
		});
	} else { alert('Maximum 6 phenotypes are allowed.');}
}
function exp_sets_vals(el){
	jQuery(el).parent().toggleClass("closed");
}
function rem_phens_vals(el){
	jQuery(el).parent().remove();
}
function rem_sets_vals(el){
	var i = jQuery(el).parent().parent().children('.postbox').length;
	if(i>1){
		jQuery(el).parent().remove();
		var rid = jQuery(el).attr('thcID');
		var rids = jQuery('#setrems').val();
		if(rid && rids){jQuery('#setrems').val(rids + ',' + rid);}
		else if(rid){jQuery('#setrems').val(rid);}
	}
	else {alert('You must have atleast one phenotype');}
}
function tog_prices(){
	var st = jQuery('#sdtype').val();
	if(st=='seed' || st=='clone'){jQuery('#prices-weight').hide(); jQuery('#prices-seed').show();}
	else{jQuery('#prices-seed').hide(); jQuery('#prices-weight').show();}
}
function tog_deli_item(typ){
	jQuery('.itemdetail').hide();
	jQuery('.seeditem').css("display", "none");
	jQuery('.deliitem').css("display", "none");
	jQuery('.'+typ).css('display', 'table-row');
}
function tog_seed_item(typ){
	jQuery('.itemdetail').hide();
	jQuery('.deliitem').css("display", "none");
	jQuery('.seeditem').css("display", "none");
	jQuery('.'+typ).css('display', 'table-row');
}
/*function tog_des_typ(){
	var dtp = jQuery('#ddtype').val();
	if(dtp == "clone"){
		jQuery('#dispensary-menu-box .inside').addClass("iclone");
		jQuery('#dispensary-menu-box .inside').removeClass("idelivery");
	} else {
		jQuery('#dispensary-menu-box .inside').addClass("idelivery");
		jQuery('#dispensary-menu-box .inside').removeClass("iclone");
	}
}*/
jQuery('.thc-upload').live('click', function(e){
	e.preventDefault();
	var button = jQuery(this);
	var wr = button.next();
	var nm = button.attr('thc-name'); //undefined
	var ml = button.attr('thc-multiple'); //undefined
	var i = wr.children().length;
	var tx;
	if((ml && ml != 'undefined' && i<ml) || (!ml || ml == 'undefined')){
		wp.media.editor.send.attachment = function(props, attachment) {
			tx = '<span class="thc-uploaded" style="background-image:url(\''+attachment.sizes.thumbnail.url+'\')"><input type="hidden" value="'+attachment.sizes.thumbnail.url+'" name="'+nm+'['+attachment.id+']" /><span></span></span>';
			if(ml && ml != 'undefined' && i<ml){wr.append(tx);}
			else if(!ml || ml == 'undefined'){wr.html(tx);}
		}
		wp.media.editor.open(button);
	} else {alert('You can upload maximum '+ml+' images here.');}
	return false;
});
jQuery('.thc-upload-d').live('click', function(e){
	e.preventDefault();
	var button = jQuery(this);
	var wr = button.next();
	var nm = button.attr('thc-name'); //undefined
	var ml = button.attr('thc-multiple'); //undefined
	var i = wr.children().length;
	var tx;
	if((ml && ml != 'undefined' && i<ml) || (!ml || ml == 'undefined')){
		wp.media.editor.send.attachment = function(props, attachment) {
			tx = '<span class="thc-uploaded" style="background-image:url(\''+attachment.sizes.thumbnail.url+'\')"><input type="hidden" value="'+attachment.id+'" name="'+nm+'" /><span></span></span>';
			if(ml && ml != 'undefined' && i<ml){wr.append(tx);}
			else if(!ml || ml == 'undefined'){wr.html(tx);}
		}
		wp.media.editor.open(button);
	} else {alert('You can upload maximum '+ml+' images here.');}
	return false;
});
jQuery('.thc-uploaded').live('click', function(e){
	e.preventDefault();
	jQuery(this).remove();
});
function thc_listing_update(){
	jQuery('.thcloading').toggle();
	tinyMCE.triggerSave();
	var fields = jQuery("#lfwrap").find("input, select, textarea").serializeArray();
	var x = y = "";
	jQuery.each( fields, function( i, field ) {
		y = '"'+field.name+'":"'+field.value+'", ';
		x += y.replace("[", "][");
	});
	x = '{'+x.substring(0, x.length-2)+'}';
	jQuery.ajax({
		url: ajaxurl,
		type: 'POST',
		data:{
			action: 'thc_listing_update',
			data:jQuery.parseJSON(x)
		},
		success: function(data){
			if(data.length > 1){
			var newStr = data.substring(0, data.length-1);
			jQuery("#ltwrap").html(newStr);
			thc_listing_reset();
			jQuery('.thcloading').toggle();
			}
		}
	});
}
function thc_listing_edit(id){
	jQuery.ajax({
		url: ajaxurl,
		type: 'POST',
		data:{
			action: 'thc_listing_edit',
			alid:id
		},
		success: function(data){
			if(data.length > 1){
			var newStr = data.substring(0, data.length-1);
			jQuery("#lfwrapper").html(newStr);
			tog_prices();
			thc_scrollTo();
			}
		}
	});
}
function thc_listing_delete(id){
	jQuery.ajax({
		url: ajaxurl,
		type: 'POST',
		data:{
			action: 'thc_listing_delete',
			alid:id
		},
		success: function(data){
			if(data.length > 1){
			var newStr = data.substring(0, data.length-1);
			jQuery("#ltwrap").html(newStr);
			}
		}
	});
}
function thc_listing_reset(){
	var p = jQuery("input[name='listing[post_parent]']").val();
	var a = jQuery("input[name='listing[post_author]']").val();
	jQuery('#lfwrapper').find("select").prop('selected', function(){return this.defaultSelected;});
	jQuery('#lfwrapper').find("input, select.chosen").val('');
	jQuery('.chosen').trigger('chosen:updated');
	jQuery('#lfwrap').find("textarea").html('');
	jQuery('.thc-uploaded').remove();
	jQuery("#lfwrap input[type='hidden']").val('');
	jQuery("#lcontent").val('');
	jQuery("#lcontent").html('');
	tinyMCE.activeEditor.setContent('');
	tinyMCE.triggerSave();
	jQuery("input[name='listing[post_parent]']").val(p);
	jQuery("input[name='listing[post_author]']").val(a);
	tog_prices();
}
function thc_scrollTo(){
	location.hash = "#dispensary-menu-box";
}
jQuery('.itemtitle').live('click', function(e){
	jQuery('.itemdetail').hide();
	jQuery(this).parent().next().toggle();
});
/**************************************************************************************************/
  (function( $ ) {
    $.widget( ".combobox", {
      _create: function() {
        this.wrapper = $( "<span>" )
          .addClass( "custom-combobox" )
          .insertAfter( this.element );
 
        this.element.hide();
        this._createAutocomplete();
        this._createShowAllButton();
      },
 
      _createAutocomplete: function() {
        var selected = this.element.children( ":selected" ),
          value = selected.val() ? selected.text() : "";
 
        this.input = $( "<input>" )
          .appendTo( this.wrapper )
          .val( value )
          .attr( "title", "" )
          .addClass( "custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left" )
          .autocomplete({
            delay: 0,
            minLength: 0,
            source: $.proxy( this, "_source" )
          })
          .tooltip({
            tooltipClass: "ui-state-highlight"
          });
 
        this._on( this.input, {
          autocompleteselect: function( event, ui ) {
            ui.item.option.selected = true;
            this._trigger( "select", event, {
              item: ui.item.option
            });
          },
 
          autocompletechange: "_removeIfInvalid"
        });
      },
 
      _createShowAllButton: function() {
        var input = this.input,
          wasOpen = false;
 
        $( "<a>" )
          .attr( "tabIndex", -1 )
          .attr( "title", "Show All Items" )
          .tooltip()
          .appendTo( this.wrapper )
          .button({
            icons: {
              primary: "ui-icon-triangle-1-s"
            },
            text: false
          })
          .removeClass( "ui-corner-all" )
          .addClass( "custom-combobox-toggle ui-corner-right" )
          .mousedown(function() {
            wasOpen = input.autocomplete( "widget" ).is( ":visible" );
          })
          .click(function() {
            input.focus();
 
            // Close if already visible
            if ( wasOpen ) {
              return;
            }
 
            // Pass empty string as value to search for, displaying all results
            input.autocomplete( "search", "" );
          });
      },
 
      _source: function( request, response ) {
        var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
        response( this.element.children( "option" ).map(function() {
          var text = $( this ).text();
          if ( this.value && ( !request.term || matcher.test(text) ) )
            return {
              label: text,
              value: text,
              option: this
            };
        }) );
      },
 
      _removeIfInvalid: function( event, ui ) {
 
        // Selected an item, nothing to do
        if ( ui.item ) {
          return;
        }
 
        // Search for a match (case-insensitive)
        var value = this.input.val(),
          valueLowerCase = value.toLowerCase(),
          valid = false;
        this.element.children( "option" ).each(function() {
          if ( $( this ).text().toLowerCase() === valueLowerCase ) {
            this.selected = valid = true;
            return false;
          }
        });
 
        // Found a match, nothing to do
        if ( valid ) {
          return;
        }
 
        // Remove invalid value
        this.input
          .val( "" )
          .attr( "title", value + " didn't match any item" )
          .tooltip( "open" );
        this.element.val( "" );
        this._delay(function() {
          this.input.tooltip( "close" ).attr( "title", "" );
        }, 2500 );
        this.input.autocomplete( "instance" ).term = "";
      },
 
      _destroy: function() {
        this.wrapper.remove();
        this.element.show();
      }
    });
  })( jQuery );
 
  jQuery(function() {
    jQuery( ".combo" ).combobox();
    jQuery( "#toggle" ).click(function() {
      jQuery( ".combo" ).toggle();
    });
  });