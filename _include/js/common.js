Prototip.Styles.postit = {
    className: 'postit',
    border: 1,
    borderColor: '#999',
    radius: 6,
    stem: { height: 12, width: 15 }
};

function urlencode (str) {
	// version: 910.813 - http://phpjs.org/functions/urlencode:573
	var hexStr = function (dec) { return '%' + (dec < 16 ? '0' : '') + dec.toString(16).toUpperCase();};
	var ret = '', unreserved = /[\w.-]/;
	str = (str+'').toString();

	for (var i = 0, dl = str.length; i < dl; i++) {
		var ch = str.charAt(i);
		if (unreserved.test(ch)) {
			ret += ch;
		} else {
			var code = str.charCodeAt(i);
			if (0xD800 <= code && code <= 0xDBFF) {
				ret += ((code - 0xD800) * 0x400) + (str.charCodeAt(i+1) - 0xDC00) + 0x10000;
				i++;
			} else if (code === 32) {
				ret += '+';
			} else if (code < 128) {
				ret += hexStr(code);
			} else if (code >= 128 && code < 2048) {
				ret += hexStr((code >> 6) | 0xC0);
				ret += hexStr((code & 0x3F) | 0x80);
			} else if (code >= 2048) {
				ret += hexStr((code >> 12) | 0xE0);
				ret += hexStr(((code >> 6) & 0x3F) | 0x80);
				ret += hexStr((code & 0x3F) | 0x80);
			}
		}
	}
	return ret;
}

/**
  * Extends default Scriptaculous InPlaceEditor to support empty entries
  */
Ajax.InPlaceEditorWithEmptyText = Class.create(Ajax.InPlaceEditor, {
	initialize : function($super, element, url, options) {
		if (!options.callback) {
			options.callback = function(form, value) { 
				return 'id=' + element + '&new_content=' + urlencode(value) + '&old_content=' + urlencode($(element)) ;
			};
		}
		if (!options.onEnterHover) {
			options.onEnterHover = function(form, value) { 
				$(element).addClassName('ehover'); 
			};
		}
		if (!options.onLeaveHover) {
			options.onLeaveHover = function(form, value) { 
				$(element).removeClassName('ehover'); 
			};
		}
		if (!options.emptyText) { options.emptyText = '(Click to edit...)';}
		if (!options.okText) { options.okText = '';}
		if (!options.cancelControl) { options.cancelControl = 'button';}		
		if (!options.cancelText) { options.cancelText = '';}
		
		if (!options.savingText) { options.savingText = '';}
		if (!options.emptyClassName) { options.emptyClassName = 'inplaceeditor-empty';}

		if (!options.onComplete) { options.onComplete = function(transport, element) {
			new PBSavingMsg().done();
		};}

		$super(element, url, options);
		this.checkEmpty();
	},

	checkEmpty : function() {
		if (this.element.innerHTML.length == 0 && this.options.emptyText) {
			this.element.appendChild(
				new Element("span", { className : this.options.emptyClassName }).update(this.options.emptyText)
			);
		}
	},

	getText : function($super) {
		if (empty_span = this.element.select("." + this.options.emptyClassName).first()) {
			empty_span.remove();
		}
		return $super();
	},

	leaveEditMode : function($super, transport) {
		this.checkEmpty();
		return $super(transport);
	}
});

var PBInPlaceEditor = Class.create({
	initialize: function(fieldname, options) {
		this.priorityEditor = new Ajax.InPlaceEditorWithEmptyText(fieldname, PATH_TO_ROOT + '_ajax/field_update.php', options);
	},
	dispose: function() {
		this.priorityEditor.dispose();
	}
});


// Extends Prototip class
var ProductBacklogTip = Class.create(Tip, {
	initialize : function ($super, element, title, options) {
		if (!options.title) { options.title = title;}		
		if (!options.closeButton) { options.closeButton = true;}
		if (!options.className) { options.className = 'backlogtip';}
		if (!options.showOn) { options.showOn = 'click';}
		if (!options.hideOn) { options.hideOn = { element: 'closeButton', event: 'click'};}
		if (!options.offset) { options.offset = { x: 0, y: 14 };}
		if (!options.width) { options.width = 'auto';}
		if (!options.hideOthers) { options.hideOthers = 'true';}
		$super(element, title, options);
	}
});

var ProductBacklogHelperTip = Class.create({
	initialize : function (element, title, content) {
		new Tip(element, '<p class="helpTitle">' + title + '</p>' + content, { 
			width: 'auto', 
			border: 0, 
			radius: 0,
			style: 'darkgrey',
			hook: { target: 'bottomMiddle', tip: 'topMiddle' },
			offset: { x: 0, y: 15 }
		});		
	}
});

var PBAddButton = Class.create({
	initialize: function(buttontitle, objectname, action, params) {
		new ProductBacklogTip('addnewobject', buttontitle + "... Loading...", {
			title: buttontitle,
			/*TODO style: 'PBtip',*/
			stem: 'topLeft',
			hook: { target: 'topMiddle', tip: 'topLeft' },
			ajax: {
				url: PATH_TO_ROOT + '_ajax/object_add.php?objname=' + objectname + '&' + params,
				options: { 
					method: 'post',
					onFailure: function(response) {
						alert(response.statusText);
					},
					onComplete: function() {
					// Once the "add story" pop-up is shown, add a Click event listener on the "add story" button
					$('addnewobject_cancel').observe('click', function(event) {
						Tips.hideAll();
					});
					$('addnewobject_submit').observe('click', function(event) {
						var tbodyelt = objectname + '_tbody';
						
						var ajaxparams = new Hash();					
						Form.getElements('addnewobject_form').each(function(e) {
							ajaxparams.set(e.id, $F(e.id));
						});
						ajaxparams.set('objname', objectname);
											
						new Ajax.Updater(tbodyelt, PATH_TO_ROOT + '_ajax/object_add_db.php', {
							method:'get',
							parameters: ajaxparams,
							insertion: Insertion.Top,
							onComplete: function(transport){
								if (200 == transport.status) {
									Tips.hideAll();
									var TrElementOfAddedItem = $(tbodyelt).firstDescendant();
									var objectId = parseInt(TrElementOfAddedItem.id.substr(TrElementOfAddedItem.id.lastIndexOf('-')+1),10);
									action(objectId);									
									Effect.Appear(TrElementOfAddedItem.id);
									
									Form.getElements('addnewobject_form').each(function(e) {
										$(e).value='';
									});
								}
							},
							onFailure: function(){ alert('Something went wrong...') }
						});
					});			
				} }
			}
		});	
	}
});


// Extends Calendar
var PBCalendar = Class.create({
	initialize: function(fieldname, options) { 
		if (!options.dateField) { options.dateField = fieldname;}
		if (!options.triggerElement) { options.triggerElement = fieldname;}
		if (!options.closeHandler) { options.closeHandler = function() {
			var S = new PBSavingMsg();
			new Ajax.Request(PATH_TO_ROOT + '_ajax/field_update.php', {
				method:'get',
				parameters: { 
					id: fieldname,
					new_content: this.dateField.innerHTML,
				},
				onSuccess: function(transport){
					if (200 == transport.status) {
						S.done();
					}					
				},
				onFailure: function(){ alert('Something went wrong...') }
			});
			this.hide(); // TODO This should be called in the "onSuccess" method, but doesn't work...
		}}
		Calendar.setup(options);
	}
});

var PBInPlaceCheckbox = Class.create({
	initialize: function(fieldname, action) {
		$(fieldname).observe('click', function(event) {
			var value = $F(fieldname);
			var tValue = 0;
			if (value == 'on') {
				tValue = 1;
			}
			var S = new PBSavingMsg();
			new Ajax.Request(PATH_TO_ROOT + '_ajax/field_update.php', {
				method: 'post',
				parameters: {
					id: fieldname,
					old_content: tValue,
					new_content: tValue
				},
				onSuccess: function(transport) {
					if (200 == transport.status) {
						S.done();
						action();
					}
				},
				onFailure: function(){ alert('Something went wrong...') }
			});
		});		
	}									 
});

var PBInPlaceSelect = Class.create({
	initialize: function(fieldname, optionsfieldname, options) {
		var editor=new Ajax.InPlaceEditorWithEmptyText(fieldname, PATH_TO_ROOT + '_ajax/field_update.php', options);
		Object.extend(editor, {
			createEditField: function() {
				// Scriptaculous code BEGIN
				var text = (this.options.loadTextURL ? this.options.loadingText : this.getText());
				// Scriptaculous code END

				var fld = document.createElement('select');
				fld.innerHTML = $(optionsfieldname).innerHTML;

				// Scriptaculous code BEGIN
				fld.name = this.options.paramName;
				fld.value = text; // No HTML breaks conversion anymore
				fld.className = 'editor_field';
				if (this.options.submitOnBlur)
				fld.onblur = this._boundSubmitHandler;
				this._controls.editor = fld;
				if (this.options.loadTextURL) this.loadExternalText();
				this._form.appendChild(this._controls.editor);		
				// Scriptaculous code END	
			}
		});		
	}
});

var PBDeleteObject = Class.create({
	initialize: function(fieldname, label, rowid) {
		// Display the tooltip to manage the deletion of an object
		if ($(fieldname) != undefined) {
			new ProductBacklogTip(fieldname, label, {
				title: label,
				stem: 'topRight',
				hook: { target: 'topMiddle', tip: 'topRight' },
				ajax: {
					url: PATH_TO_ROOT + '_ajax/object_delete.php?id=' + fieldname,
					options: { onComplete: function() {
						// When clicking on the "CANCEL" button, do nothin
						$(fieldname + '-cancel').observe('click', function(event) {
							Tips.hideAll();
						});
						// When clicking on the "GO AHEAD" button, do an AJAX call to remove the story from the DB.
						$(fieldname + '-go').observe('click', function(event) {
							new Ajax.Request(PATH_TO_ROOT + '_ajax/object_delete_db.php?id=' + fieldname, {
							method: 'get',
							onComplete: function(transport) {
								if (transport.responseText == "FAILED") {
									alert('Problem during object deletion');
								} else {
									Tips.hideAll();
									Effect.Fade(rowid);
								}
							}
						});
					});
				} }
				}
			});
		}
	}
});



var PBLightview = Class.create({
	initialize: function(fieldname, windowtitle, windowhref) {
		$(fieldname).observe('click', function() {
			Lightview.show({ 
				href: windowhref, 
				rel: 'iframe',
				title: windowtitle,
				options: { 
					width:800,
					height:600,
					menubar:'top'
				} 
			});
		});		
	}
});

/**
  * Helper
  */
var PBSavingMsg = Class.create({
	initialize: function() {
		$('savingText').setStyle({ display: 'block'});
	},
	done: function() {
		setTimeout("$('savingText').setStyle({ display: 'none'});",200);			
	}
});

/** 
  * Helper to manage the click event on a DOM element
  * @param eltName Element name (its HTML ID)
  * @param func The JS function to execute on click event
  */
function PBClick(eltName, func) { 
	if ($(eltName) != undefined) { 
		$(eltName).observe('click', func);
	}
}

/**
  * Helper to manage the reset of an input field
  * @param eltName Element name (its HTML ID) that needs to be clicked to reset the input field
  * @param eltReset Element name (its HTML ID) to be reset
  */
function PBReset(eltName, eltReset){
	$(eltName).observe('click', function(event) {
		$(eltReset).value = '';
		$(eltReset).focus();
	});	
}

/**
  * Helper to manage Cancel button displayed in Tips
  * @param eltName Element name (its HTML ID) of the Cancel button
  */
function PBCancelButton(eltName){
	$(eltName).observe('click', function(event) {
		Tips.hideAll();
	});
}