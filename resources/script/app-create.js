var richeditor = [];

Array.prototype.insert = function(index) {
	this.splice.apply(this, [index, 0].concat(
		Array.prototype.slice.call(arguments, 1)));
	return this;
};

Array.prototype.remove = function(from, to) {
	var rest = this.slice((to || from) + 1 || this.length);
	this.length = from < 0 ? this.length + from : from;
	return this.push.apply(this, rest);
}

if(typeof taogiEditVMM == 'undefined') {
	var taogiEditVMM = Class.extend({});

	taogiEditVMM.debug = true;
}
if(typeof taogiEditVMM != 'undefined' && typeof taogiEditVMM.Util == 'undefined') {
	taogiEditVMM.Util = ({ 
		init: function() {
			return this;
		}
	}).init();
}

(function ($) {
	window.addRule = function (selector, styles, sheet) {

		styles = (function (styles) {
			if (typeof styles === "string") return styles;
			var clone = "";
			for (var p in styles) {
				if (styles.hasOwnProperty(p)) {
					var val = styles[p];
					p = p.replace(/([A-Z])/g, "-$1").toLowerCase(); // convert to dash-case
					clone += p + ":" + (p === "content" ? '"' + val + '"' : val) + "; ";
				}
			}
			return clone;
		}(styles));
		sheet = sheet || document.styleSheets[document.styleSheets.length - 1];

		if (sheet.insertRule) sheet.insertRule(selector + " {" + styles + "}", sheet.cssRules.length);
		else if (sheet.addRule) sheet.addRule(selector, styles);

		return this;
	};

	if ($) $.fn.addRule = function (styles, sheet) {
		addRule(this.selector, styles, sheet);
		return this;
    };

	/*! Copyright 2012, Ben Lin (http://dreamerslab.com/)
	 * Licensed under the MIT License (LICENSE.txt).
	 *
	 * Version: 1.0.16
	 *
	 * Requires: jQuery >= 1.2.3
	 */
	jQuery.fn.addBack = jQuery.fn.addBack || jQuery.fn.andSelf;

	jQuery.fn.extend({
		actual : function ( method, options ) {
			// check if the jQuery method exist
			if( !this[ method ]){
				throw '$.actual => The jQuery method "' + method + '" you called does not exist';
			}

			var defaults = {
				absolute      : false,
				clone         : false,
				includeMargin : false
			};

			var configs = jQuery.extend( defaults, options );

			var $target = this.eq( 0 );
			var fix, restore;

			if( configs.clone === true ){
				fix = function (){
					var style = 'position: absolute !important; top: -1000 !important; ';

					// this is useful with css3pie
					$target = $target.
						clone().
						attr( 'style', style ).
						appendTo( 'body' );
				};

				restore = function (){
					// remove DOM element after getting the width
					$target.remove();
				};
			}else{
				var tmp   = [];
				var style = '';
				var $hidden;

				fix = function (){
					// get all hidden parents
					$hidden = $target.parents().addBack().filter( ':hidden' );
					style   += 'visibility: hidden !important; display: block !important; ';

					if( configs.absolute === true ) style += 'position: absolute !important; ';

					// save the origin style props
					// set the hidden el css to be got the actual value later
					$hidden.each( function () {
						// Save original style. If no style was set, attr() returns undefined
						var $this     = jQuery( this );
						var thisStyle = $this.attr( 'style' );

						tmp.push( thisStyle );
						// Retain as much of the original style as possible, if there is one
						$this.attr( 'style', thisStyle ? thisStyle + ';' + style : style );
					});
				};

				restore = function (){
					// restore origin style values
					$hidden.each( function ( i ){
						var $this = jQuery( this );
						var _tmp  = tmp[ i ];

						if( _tmp === undefined ){
							$this.removeAttr( 'style' );
						}else{
							$this.attr( 'style', _tmp );
						}
					});
				};
			}

			fix();
			// get the actual value with user specific methed
			// it can be 'width', 'height', 'outerWidth', 'innerWidth'... etc
			// configs.includeMargin only works for 'outerWidth' and 'outerHeight'
			var actual = /(outer)/.test( method ) ?
			$target[ method ]( configs.includeMargin ) :
			$target[ method ]();

			restore();
			// IMPORTANT, this plugin only return the value of the first element
			return actual;
		}
	});

	jQuery.fn.offsetRelative = function(top) {
		var $this = jQuery(this);
		var $parent = $this.offsetParent();
		var offset = $this.position();
		if(!top) return offset; // Didn't pass a 'top' element 
		else if($parent.get(0).tagName == "BODY") return offset; // Reached top of document
		else if(jQuery(top,$parent).length) return offset; // Parent element contains the 'top' element we want the offset to be relative to 
		else if($parent[0] == jQuery(top)[0]) return offset; // Reached the 'top' element we want the offset to be relative to 
		else { // Get parent's relative offset
			var parent_offset = $parent.offsetRelative(top);
			offset.top += parent_offset.top;
			offset.left += parent_offset.left;
			return offset;
		}
	};

	jQuery.fn.positionRelative = function(top) {
		return jQuery(this).offsetRelative(top);
	};

	function TaogiEditor(element,options) {
		var self = this;
		this.settings = $.extend({}, $.fn.taogiEditor.defaults, options);
		this.supports = {};

		this.Root = jQuery(element);
		this.RootStyle = this.Root[0].style;
		this.slideRoot = this.Root.find('.slide-items');
		this.scrollBody = jQuery(this.settings.scrollBody);

		this.scrollMargin = this.Root.positionRelative(this.settings.scrollBody).top + parseInt(this.Root.css('margin-top'));

		if('ontouchstart' in window) {
			this.hasTouch = true;
		}

		function _getVendorPropertyName(prop) {
			var prefixes = ['Moz', 'Webkit', 'O', 'ms'];
			var prop_ = prop.charAt(0).toUpperCase() + prop.substr(1);

			for (var i=0; i<prefixes.length; ++i) {
				var vendorProp = prefixes[i] + prop_;
				if (vendorProp in self.RootStyle) { return '-'+prefixes[i]+'-'+prop_; }
			}
		}

		var TransitionEndeventNames = {
			'transition':      'transitionEnd',
			'-Moz-Transition':  'transitionend',
			'-O-Transition':      'oTransitionEnd',
			'-Webkit-Transition': 'webkitTransitionEnd',
			'-ms-Transition':    'msTransitionEnd'
        };
		this._useWebkitTransition = false;
        this.supports.transition        = _getVendorPropertyName('transition');
        if(this.hasTouch || this.supports.transition) {
			this._useWebkitTransition = true;
			this.supports.transform = _getVendorPropertyName('transform');
            this.supports.transitionEnd = TransitionEndeventNames[this.supports.transition] || null;
            this.supports.opacity = _getVendorPropertyName('opacity');
        }

		this.items = [];
		this.itemIndex = {};
		this.galleryIndex = {};

		this.fMarkup = this.Root.find('#media_inline_form').outerHTML();
		this.Root.find('#media_inline_form').remove();
		this.errorMarkup = this.Root.find('#taogi-editor-error').outerHTML();
		this.Root.find('#taogi-editor-error').remove();
		var Items = this.Root.find('fieldset.timeline_properties, .slide-item');
		Items.each(function(index) {
			var item = jQuery(this);
			/* last item have new markup */
			if(item.attr('id') == 'date___SLIDE_ID__') {
				var mn = item.find('.media-nav');
				self.meMarkup = mn.find('li:first').outerHTML();
				self.maMarkup = mn.find('li.add').outerHTML();
				self.mfMarkup = mn.find('li.fieldset-container').outerHTML();
				mn.find('ul').html('');
				self.mMarkup = mn.outerHTML();
//				self.mMarkup = item.find('.media-nav').outerHTML();
				item.find('.media-nav').remove();
				self.sMarkup = item.outerHTML();
				self.saMarkup = item.find('.feature .switch').outerHTML();
			}
			self.addItem(item,index,true);
		});

		this.isSorting = false;
		this.isChanged = 0;

		this.Root.find('.button.save').click(function(e) {
			self.save();
		});

		if(this.settings.menubar) {
			this.menubar = jQuery('#'+this.settings.menubar);
			this.iniMenuBar();
		}
		if(this.settings.configure) {
			this.configure = jQuery('#'+this.settings.configure);
			this.configureSetting();
		}

		jQuery(document).keydown(function(event) {
			var code = event.charCode || event.keyCode;
			if(code == 83 && (event.ctrlKey || event.altKey)) {
				event.preventDefault();
				self.save();
			}
		});
		jQuery(window).bind('beforeunload', function(e) {
			if(self.isChanged) {
				return '변경된 내용이 저장되지 않았어요. 그래도 이 페이지에서 나가실 건가요?';
			}
		});

		jQuery(window).on('unload', function(e) {
			self.unlock();
		});
		jQuery(window).resize(function(e) {
			self.reSize();
		});

		this.reSize();
	}

	TaogiEditor.prototype = {

		reSize: function() {
			this.Width = jQuery(window).width();
			this.Height = jQuery(window).height();
		},

		iniMenuBar: function() {
			var self = this;
			this.collapsedHandler(this.menubar);
			this.menubar.find('.status-publish .button').mouseenter(function(e) {
				var m = jQuery(this).parent().find('.status-draft-notice');
				if(m.length < 1) {
					var m = jQuery('<div class="status-notice status-draft-notice">'+jQuery(this).attr('data-title')+'</div>');
					m.appendTo(jQuery(this).parent());
				}
				m.css({ 'top' : (jQuery(this).height()+10)+'px', 'opacity' : 1 });
			})
			.mouseleave(function(e) {
				jQuery(this).parent().find('.status-draft-notice').remove();
			})
			.click(function(e) {
				self.menubar.find('.status-publish').removeClass('is-public');
				self.menubar.find('.status-draft').removeClass('is-public');
				self.Root.find('input#is_public').val('0');
				self.updateStatus();
			});
			this.menubar.find('.status-draft .button').mouseenter(function(e) {
				var m = jQuery(this).parent().find('.status-publish-notice');
				if(m.length < 1) {
					var m = jQuery('<div class="status-notice status-publish-notice">'+jQuery(this).attr('data-title')+'</div>');
					m.appendTo(jQuery(this).parent());
				}
				m.css({ 'top' : (jQuery(this).height()+10)+'px', 'opacity' : 1 });
			})
			.mouseleave(function(e) {
				jQuery(this).parent().find('.status-publish-notice').remove();
			})
			.click(function(e) {
				self.menubar.find('.status-publish').addClass('is-public');
				self.menubar.find('.status-draft').addClass('is-public');
				self.Root.find('input#is_public').val('2');
				self.save();
			});
			this.menubar.find('.status-keep .button').click(function(e) {
				self.save();
			});
			this.menubar.find('[data-submenu]').each(function() {
				var $button = jQuery(this);
				$button.on('click',function(e){
					e.preventDefault();
					var $submenu_class = 'mode-'+jQuery(this).attr('data-submenu');
					jQuery('body').toggleClass($submenu_class);
				});
			});
			this.menubar.find('.preview .button').on('click',function(e){
				var $button = jQuery(this);
				var $target = window.location.href.replace(/\/(modify|create)[^\/]*$/,'');
				window.open($target);
			});
		},

		addItem: function(item,index,preload) {
			var self = this;
			var obj = {};
			obj.index = index;
			if(preload === true) item.addClass('requireLoad');
			var _id = item.attr('data-id');
			if( typeof _id === 'undefined' || _id === false || !_id) {
				obj.id = taogiVMM.Util.unique_ID(6);
				item.attr({ 'id':'date_'+obj.id, 'data-id':obj.id });
			} else {
				obj.id = _id;
				item.attr({ 'id':'date_'+obj.id });
			}
			var published = item.attr('attr-published');
			if(typeof published !== 'undefined' && published !== false) {
				obj.published = parseInt(published);
			} else {
				obj.published = -1;
				item.attr('attr-published',-1);
			}
			item.find('.button.media.add').click(function(e) {
				e.preventDefault();
				self.MediaDialog(obj.id);
			});
			item.find('.button.article.add').attr('data-target','date_'+obj.id).click(function(e) {
				self.newSlide('date_'+obj.id);
				self.scrollTo(self.items[(index+1)].id);
				self.focus(self.items[(index+1)].article[0].item);
			});
			obj.startDate = null;
			var sdate = item.find('[data-name="startDate"]').text();
			if(sdate) {
				obj.sDate = sdate;
				obj.startDate = taogiVMM.Date.parse(sdate).getTime();
			}
			this.setSlideIndex(item,index);
			obj.article = [];
			obj.media = [];
			obj.figures = [];
			obj.galleries = [];
			item.find('.editable.article').each(function(index2) {
				var item2 = jQuery(this);
				item2.attr({ 'data-id':obj.id, 'data-type':'article', 'data-index':index2 });
				self.editorKeyEvent(item2);
				var obj2 = {};
				obj2.index = index2;
				obj2.item = item2;
				obj.article.push(obj2);
			});
			item.find('.button.datepicker').click(function(e) {
				e.preventDefault();
				self.datepicker(jQuery(this));
			});
			item.find('.editable.media').each(function(index2) {
				var item2 = jQuery(this);
				item2.attr({ 'data-id':obj.id, 'data-type':'media', 'data-index':index2 });
				self.editorKeyEvent(item2);
				var obj2 = {};
				obj2.index = index2;
				obj2.item = item2;
				obj.media.push(obj2);
			});
			self.collapsedHandler(item);
			self.galleryIndex[obj.id] = {};
			item.find('.console .hide').attr('data-id',obj.id).click(function(e) {
				self.hideSlide(obj.id);
			});
			item.find('.console .show').attr('data-id',obj.id).click(function(e) {
				self.showSlide(obj.id);
			});
			item.find('.console .remove').attr('data-id',obj.id).click(function(e) {
				self.removeSlide(obj.id);
			});
			obj.featured = -1;
			var fig = item.find('.figure');
			item.find('.media-nav .thumbnails .thumbnail').each(function(index3) {
				var item3 = jQuery(this);
				var m = {};
				m.url = item3.attr('href');
				m.uid = item3.attr('data-gid');
				if(!item3.attr('data-id')) {
					item3.attr('data-id',obj.id);
				}
				if(!m.uid) {
					m.uid = taogiVMM.Util.unique_ID(6);
					item3.attr('id','t_'+m.uid)
					item3.attr('data-gid',m.uid)
				}
				m.credit = item3.attr('credit');
				m.caption = item3.attr('caption');
				m.thumbnail = item3.attr('thumbnail');
				m.use_proxy = item3.attr('use_proxy');
				m.use_thumb_proxy = item3.attr('use_thumb_proxy');
				m.thumb = 1;
				m.featured = parseInt(item3.attr('featured'));
				if(m.featured) {
					obj.featured = index3;
				}
				if(this._useWebkitTransition == true) {
					m.wmode = 'transparent';
				} else {
					m.wmode = 'window';
				}
				if(m.featured == 1) {
					fig.attr('id',m.uid+'_thumb');
				}
				var fm = m;
				obj.galleries.push(m);
				self.galleryIndex[obj.id][m.uid] = index3;
			});
			if(obj.galleries.length > 0) {
				var addButton = item.find('.media-nav .thumbnails .add');
				addButton.attr('id',obj.id+'_add').find('a').click(function(e) {
                    e.preventDefault();
                    self.MediaDialog(obj.id);
                });
				self.sortable(obj.id);
			}
			obj.item = item;
			if(index <= self.items.length - 1) {
				self.items.insert(index,obj);
			} else {
				self.items.push(obj);
			}
			self.itemIndex[obj.id] = index;
		},

		/**
		 * set index value at slide index markup
		 **/
		setSlideIndex: function(item,index) {
			item.attr('data-index',index);
			var l = item.find('legend.article');
			if(!l.find('a').text()) {
				l.find('a').attr('data-content',l.attr('data-default-value')+' '+(index));
			}
		},

		/**
		 * key event handler
		 **/
		editorKeyEvent: function(element) {
			var self = this;
			if(!element.html())
				element.attr('data-content',element.attr('data-default-value'));
			element.keydown(function(event) {
				var code = event.charCode || event.keyCode;
				var f_name = jQuery(this).attr('data-name');
				if(code == 13 && (event.ctrlKey || event.shiftKey)) {
					event.preventDefault();
					self.isChanged = 1;
					if (f_name == 'text' && window.getSelection) {
						/*
						var selection = window.getSelection(),
							range = selection.getRangeAt(0),
							br = document.createElement("br"),
							textNode = document.createTextNode("\u00a0"); //Passing " " directly will not end up being shown correctly
						range.deleteContents();//required or not?
						range.insertNode(br);
						range.collapse(false);
						range.insertNode(textNode);
						range.selectNodeContents(textNode);

						selection.removeAllRanges();
						selection.addRange(range);
						return false;
						*/
					}				
				} else if(code == 9 || code == 13) {
					event.preventDefault();
					if(!jQuery.trim(jQuery(this).html())) {
						jQuery(this).removeClass('valid').attr('data-content',jQuery(this).attr('data-default-value'));
					}
					if(f_name == 'startDate') {
						var ret = self.checkTimeFormat(jQuery(this));
						if(ret == false) return;
					}
					if(f_name == "startDate" || f_name == "headline") {
						self.makeSlideTitle(jQuery(this).attr('data-id'));
					}
					var dataType = jQuery(this).attr('data-type');
					jQuery(this).data('isEditing',0);
					if(dataType == 'article')
						self.nextArticleEditElement(code,jQuery(this).attr('data-id'),parseInt(jQuery(this).attr('data-index')));
					else if(dataType == 'media')
						self.nextMediaEditElement(code,jQuery(this).attr('data-id'),parseInt(jQuery(this).attr('data-index')));
				} else if(code == 8 || code == 46) {
					if(!jQuery.trim(jQuery(this).html())) {
						event.preventDefault();
						jQuery(this).attr('data-content',jQuery(this).attr('data-default-value'));
					}
				} else {
					jQuery(this).addClass('valid').removeClass('focus').attr('data-content','');
					self.isChanged = 1;
				}
			})
			.focusin(function(event) {
				if(jQuery(this).data('isEditing') !== 1) {
					jQuery(this).data('isEditing',1);
				}
				var data_name = jQuery(this).attr('data-name');
				if(data_name == 'startDate') {
					jQuery(this).data('origin-startDate',jQuery(this).text());
				}
				if(data_name == 'text') {
					//self.editToolBar(jQuery(this).attr('data-id'),'show');
					if(typeof jQuery(this).attr('id')=='undefined'){
						var rid = 'rid-'+jQuery(this).closest('.slide-item').attr('id');
						jQuery(this).attr('id',rid);
					}
					if(typeof richeditor[jQuery(this).attr('id')]=='undefined'){
						richeditor[jQuery(this).attr('id')] = new MediumEditor('#'+jQuery(this).attr('id'),self.settings.richeditorOptions);
						console.log('RICHEDITOR: ['+jQuery(this).attr('id')+'] activated');
					}
				}
			})
			.focusout(function(event) {
				var f_name = jQuery(this).attr('data-name');
				if(!jQuery.trim(jQuery(this).html())) {
					jQuery(this).attr('data-content',jQuery(this).attr('data-default-value'));
				} else {
					if(jQuery(this).data('isEditing') == 1) {
						jQuery(this).data('isEditing', 0);
						if(f_name == 'startDate') {
							var ret = self.checkTimeFormat(jQuery(this));
							if(ret == false) return;
						}
						if(f_name == "startDate" || f_name == "headline") {
							self.makeSlideTitle(jQuery(this).attr('data-id'));
						}
						if(self.isSorting == false) {
							self.sortItemsById(jQuery(this).attr('data-id'),1);
						}
					}
				}
				if(f_name == 'text') {
					//self.editToolBar(jQuery(this).attr('data-id'),'hide');
					/*
					delete richeditor[jQuery(this).attr('id')];
					console.log('RICHEDITOR: ['+jQuery(this).attr('id')+'] deactivated');
					*/
				}
			});

			element.on('paste',function(e) {
				self.handlepaste(this,e);
				self.isChanged = 1;
			});
		},

		/*
		 * Strip Tag from content at pasting to editable area
		 * handlepaste, waitforpastedata, processpaste
		 */
		handlepaste: function(elem, e) {
			var savedcontent = elem.innerHTML;
			if (e && e.clipboardData && e.clipboardData.getData) {// Webkit - get data from clipboard, put into editdiv, cleanup, then cancel event
				if (/text\/html/.test(e.clipboardData.types)) {
					elem.innerHTML = e.clipboardData.getData('text/html');
				} else if (/text\/plain/.test(e.clipboardData.types)) {
					elem.innerHTML = e.clipboardData.getData('text/plain');
				} else {
					elem.innerHTML = "";
				}
				this.waitforpastedata(elem, savedcontent);
				if (e.preventDefault) {
					e.stopPropagation();
					e.preventDefault();
				}
				return false;
			} else {// Everything else - empty editdiv and allow browser to paste content into it, then cleanup
				elem.innerHTML = "";
				this.waitforpastedata(elem, savedcontent);
				return true;
			}
		},

		waitforpastedata: function(elem, savedcontent) {
			var self = this;
			if (elem.childNodes && elem.childNodes.length > 0) {
				this.processpaste(elem, savedcontent);
			} else {
				that = {
					e: elem,
					s: savedcontent
				}
				that.callself = function () {
					self.waitforpastedata(that.e, that.s)
				}
				setTimeout(that.callself,20);
			}
		},

		processpaste: function(elem, savedcontent) {
			pasteddata = elem.innerHTML;
			//^^Alternatively loop through dom (elem.childNodes or elem.getElementsByTagName) here

			elem.innerHTML = savedcontent;

			// Do whatever with gathered data;
			var hi = jQuery('<div></div>');
			hi.append(pasteddata);
			elem.innerHTML += hi.text();
		},

		editToolBar: function(id,showOpt) {
			/*
			var editMenu = jQuery('#date_'+id).find('.toolbar');
			if(this._useWebkitTransition == true) {
				var transition = this.supports.transition.toLowerCase();
				var transitionEnd = this.supports.transitionEnd;
				if(showOpt == 'show') {
					editMenu.css('display','block');
					this.editToolBarHandle(editMenu);
					editMenu.css({transition: 'opacity 500ms ease-out', 'opacity':1});
				} else {
					editMenu.css({transition: 'opacity 500ms ease-out', 'opacity':0});
					editMenu.bind(transitionEnd,function() {
						jQuery(this).css('display','none');
						jQuery(this).unbind(transitionEnd);
					});
				}
			} else {
				if(showOpt == 'show') {
					editMenu.css('display','block');
					this.editToolBarHandle(editMenu);
					editMenu.animate({'opacity':1}, 500);
				} else {
					editMenu.animate({'opacity':0}, 500, function() {
						jQuery(this).css('display','none');
					});
				}
			}
			*/
		},

		/**
		 * text editor handle
		 **/
        editToolBarHandle: function(toolbar) {
			/*
			var self = this;
			if(toolbar.data('event-init') == true) return;
            toolbar.find('li a').bind('click.taogi',function(e) {
				e.preventDefault();
				var work = jQuery(this).parent().attr('data-code');
			});
			toolbar.data('event-init',true);
			*/
        },

		datepicker: function(element) {
			var self = this;
			var p = element.parent();
			var sd = p.find('[data-name="startDate"]');
			var d = sd.text();
			var pi = jQuery('<input type="text" class="datetimepicker" value="'+d+'">');
			p.find('.article, .button').hide();
			pi.appendTo(p);
			pi.datetimepicker({
				lang: 'ko',
				i18n: {
					ko: {
						months: [ '1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월' ],
						dayOfWeek: [ '일', '월', '화', '수', '목', '금', '토' ]
					}
				},
				timepicker: true,
				format: 'Y.m.d H:i:s',
				closeOnDateSelect: false,
				onChangeDateTime: function(db,$input) {
					if($input.val()) {
						sd.attr('data-content','');
					} else {
						sd.attr('data-content',sd.attr('data-default-value'));
					}
					sd.text($input.val());
					self.makeSlideTitle(sd.attr('data-id'));
					self.isChanged = 1;
				},
				onClose: function() {
					p.find('.article, .button').show();
					pi.remove();
					self.focus(sd);
					self.makeSlideTitle(sd.attr('data-id'));
				}
			});
			pi.focus();
		},

		checkTimeFormat: function(item) {
			var iText = item.text();
			if(iText) {
				var hs = iText.split(' ');
				iText = iText.replace(/[\/\-,]/g,".");
				console.log(iText);
//				if(hs[1]) {
//					iText += ":";
//				}
				var d = taogiVMM.Date.parse(iText);
				if(!d || d == 'Invalid Date') {
					if(item.parent().find('span.alert').length < 1)
						item.after('<span class="alert">잘못된 날짜 형식입니다.</span>');
					return false;
				} else {
					var dTime = this.setRealTime(item,d,iText);
				}
			}
			return true;
		},

		setRealTime: function(item,d,iText) {
			var id = item.attr('data-id');
			var idx = this.itemIndex[id];
			var o = iText.split(' ');
			var t = dateFormat(d,'isoDateTime').split('T');
			var displayTime = '';
			if(o[0]) {
				var _h = t[0].split("-");
				var _oh = o[0].split(".");
				for(var i=0; i<_oh.length; i++) {
					if(!_oh[i]) break;
					displayTime += (i ? "." : "")+_h[i];
				}
			}
			if(o[1]) {
				displayTime += ' ';
				var _t = t[1].split(":");
				var _ot = o[1].split(":");
				for(var i=0; i<_ot.length; i++) {
					if(!_ot[i]) break;
					displayTime += (i ? ":" : "")+_t[i];
				}
			}
			this.items[idx].startDate = d.getTime();
			this.items[idx].sDate = iText;
			item.parent().find('span.alert').remove();
			item.text(displayTime);
			this.isChanged = 1;
		},

		/**
		 * TOGGLE FIELDSET
		 **/
		collapsedHandler: function(element) {
			var self = this;
			element.find('fieldset.extendable > legend > a').on('click',function(e){
				if(jQuery(this).parent().parent().hasClass('collapsed')){
					self.uncollapse(element);
				}else{
					self.collapse(element);
				}
			});
		},

		collapse: function(element) {
			var a = element.find('fieldset.extendable > legend > a');
			a.children('.show').css('display','inline');
			a.children('.hide').css('display','none');
			var f = a.parent().parent();
			f.children('.wrap').css('display','none');
			f.addClass('collapsed');

			var rid = jQuery(this).find('.editable.article').attr('id');
			delete richeditor[rid];
			console.log('RICHEDITOR: ['+rid+'] deactivated');
		},

		uncollapse: function(element) {
			var self = this;
			var a = element.find('fieldset.extendable > legend > a');
			a.children('.show').css('display','none');
			a.children('.hide').css('display','inline');
			var f = a.parent().parent();
			f.children('.wrap').css('display','block');
			f.removeClass('collapsed');
			/* loading gallery thumbnail and featured */
			if(element.hasClass('requireLoad')) {
				element.find('.thumbnails .thumbnail').each(function(i) {
					var $this = jQuery(this);
					var id = $this.attr('data-id');
					var uid = $this.attr('data-gid');
					self.buildThumbnail(id,uid);
				});
				element.removeClass('requireLoad');
			}
		},

		checkValid: function(index) {
			for(var i=0; i<this.items[index].article.length; i++) {
				if(this.items[index].article[i].item.text()) {
					if(this.items[index].published == -1) {
						this.items[index].published = 1;
						this.items[index].item.attr('attr-published',1);
					}
					return true;
				}
			}
			if(this.items[index].published != -1) {
				this.items[index].published = -1;
				this.items[index].item.attr('attr-published',-1);
			}
			return false;
		},

		nextArticleEditElement: function(code,id,index) {
			var idx = this.itemIndex[id];
			if(index <this.items[idx].article.length-1) {
				this.focus(this.items[idx].article[index+1].item);
			} else {
				if(idx < this.items.length-1) {
					for(var i = idx+1;  i<this.items.length; i++) {
						if(this.items[i].article.length) {
							this.collapse(this.items[idx].item);
							this.uncollapse(this.items[i].item);
							var ret = -1;
							if(idx > 0) ret = this.sortItemsById(id,2);
							if(ret == -1) {
								this.focus(this.items[i].article[0].item);
								this.scrollTo(this.items[i].id);
							}
							break;
						}
					}
				} else {
					if(code == 13) {
						if(this.checkValid(idx) === false) return;
						this.collapse(this.items[idx].item);
						this.newSlide('date_'+id);
						this.focus(this.items[(idx+1)].article[0].item);
						this.scrollTo(this.items[(idx+1)].id);
						if(idx > 0) this.sortItemsById(id,0);
					}
				}
			}
		},

		nextMediaEditElement: function(id,index) {
			var idx = this.itemIndex[id];
			if(index <this.items[idx].media.length-1) {
				this.items[idx].media[index+1].item.focus();
			} else {
			}
		},

		focus: function(item) {
			var val = item.html();
			if(!val) val = item.val();
			//if(val) item.caret(-1);
			else item.focus();
			item.data('isEditing',1);
		},

		newSlide: function(target) {
			var newS = jQuery(this.sMarkup);
			jQuery('#'+target).after(newS);
			var id = jQuery('#'+target).attr('data-id');
			var idx = this.itemIndex[id];
			this.addItem(newS,idx+1,false);
			if(idx < this.items.length-1) {
				for(var i = idx+2; i<this.items.length; i++) {
					this.setSlideIndex(this.items[i].item,i);
					this.itemIndex[this.items[i].id] = i;
					this.items[i].index = i;
				}
			}
		},

		scrollTo: function(id) {
			var h = jQuery('#date_'+id).height();
			var sh = screen.height;
			if(h > this.Height) {
				var pos = jQuery('#date_'+id).position().top;
			} else {
				var pos = jQuery('#date_'+id).position().top + Math.round((this.Height - h)/2);
			}
			this.scrollBody.animate({'scrollTop': pos+'px'},300);
		},

		sortItems: function(item,posOpt) {
			var id = item.attr('data-id');
			return this.sortItemsById(id,posOpt);
		},

		sortItemsById: function(id,posOpt) {
			var sInput = jQuery('#date_'+id).find('[data-name="startDate"]');
			if(this.checkTimeFormat(sInput) === false) return;
			if(sInput.data('origin-startDate') == sInput.text()) {
				return -1;
			}
			var idx = this.itemIndex[id];
			var moveTo = 0;
			for(var i=1; i<this.items.length; i++) {
				if(typeof(this.items[i].startDate) === 'undefined' || this.items[i].startDate === null) continue;
				if(idx > i && (this.items[i].startDate > this.items[idx].startDate)) {
					moveTo = i;
					break;
				} else if(idx < i) {
					if(this.items[i].startDate < this.items[idx].startDate) {
						moveTo = i+1;
					} else {
						break;
					}
				}
			}
			if(moveTo && moveTo != idx) {
				this.moveToIndex(idx,moveTo,posOpt);
			}
			return 1;
		},

		moveToIndex: function(from,to,posOpt){
			var self = this;
			var row = this.items[from].item;
			var h = row.outerHeight();
			var w = row.width();
			var pos = row.position();
			var topos = this.items[to].item.position();
			if(from != to) {
				this.isSorting = true;
				row.css({
					'position': 'absolute',
					'width' : w+'px',
					'left': pos.left+'px',
					'top' : pos.top+'px',
					'z-index' : 2
				});
				var dummy = jQuery('<div class="dummySorting" style="width:'+w+'px; height:0px; z-index:-1;"></div>');
				this.items[to].item.before(dummy);
			}
			if(this._useWebkitTransition === true) {
				var transition = this.supports.transition.toLowerCase();
				var transitionEnd = this.supports.transitionEnd;
			}
			if(from > to) {
				if(posOpt == 2) {
					var scrollPos = this.items[from+1].item.position();
					trace("scrollPos : "+scrollPos.top);
				}
				if(from < this.items.length-1) {
					this.items[from+1].item.css('margin-top', h+'px');
				}
				if(this._useWebkitTransition === true) {
					setTimeout(function() {
						row.css({transition: 'top '+self.settings.sortspeed+'ms ease-in-out'});
						row.css({'top': topos.top+'px'});
						row.bind(transitionEnd,function() {
							self.items[to].item.before(row);
							jQuery(this).css({transition:'','position':'static','z-index':'0','top':'','left':'','width':'auto'});
							jQuery('.dummySorting').remove();
							self.resortItem(from,to);
							if(posOpt === 2) self.focus(self.items[(from+1)].article[0].item);
							self.isSorting = false;
							jQuery(this).unbind(transitionEnd);
						});
						dummy.css({transition: 'height '+self.settings.sortspeed+'ms ease-in-out', 'height': h+'px'});
						if(from < self.items.length-1) {
							self.items[from+1].item.css({transition:'margin-top '+self.settings.sortspeed+'ms ease-in-out','margin-top':'0px'});
							self.items[from+1].item.bind(transitionEnd,function() {
								jQuery(this).css({transition:''});
								jQuery(this).unbind(transitionEnd);
							});
						}
					},10);
				} else {
					row.animate({'top':topos.top+'px'},self.settings.sortspeed,function() {
						self.items[to].item.before(row);
						jQuery(this).css({'position':'static','z-index':'0','top':'','left':'','width':'auto'});
						jQuery('.dummySorting').remove();
						self.resortItem(from,to);
						if(posOpt === 2) self.focus(self.items[(from+1)].article[0].item);
						self.isSorting = false;
					});
					dummy.animate({'height': h+'px'},self.settings.sortspeed);
					if(from < this.items.length-1) {
						this.items[from+1].item.animate({'margin-top': '0'},self.settings.sortspeed);
					}
				}
				if(posOpt >= 1) {
					var sh = screen.height;
					if(h > self.Height) {
						var spos = (posOpt == 2 ? scrollPos.top : topos.top);
					} else {
						var spos = (posOpt == 2 ? scrollPos.top : topos.top) + Math.round((self.Height - h)/2);
					}
					trace("spos : "+spos);
					this.scrollBody.animate({'scrollTop': spos+'px'},self.settings.sortspeed);
				}
			} else if(from < to) {
				if(from < this.items.length-1) {
					this.items[from+1].item.css('margin-top', h+'px');
				}
				if(this._useWebkitTransition === true) {
					setTimeout(function() {
						if(from < self.items.length-1) {
							self.items[from+1].item.css({transition:'margin-top '+self.settings.sortspeed+'ms ease-out', 'margin-top': '0px'});
							self.items[from+1].item.bind(transitionEnd,function() {
								jQuery(this).css({transition:''});
								jQuery(this).unbind(transitionEnd);
							});
						}
						dummy.css({transition: 'height '+self.settings.sortspeed+'ms ease-out', 'height': h+'px'});
						row.css({transition: 'top '+self.settings.sortspeed+'ms ease-out', 'top':(topos.top-h)+'px'});
						row.bind(transitionEnd,function() {
							self.items[to].item.before(row);
							jQuery(this).css({transition:'', 'position':'static','z-index':'0','top':'','left':'','width':'auto'});
							jQuery('.dummySorting').remove();
							self.resortItem(from,to);
							if(posOpt === 2) self.focus(self.items[(from)].article[0].item);
							self.isSorting = false;
							jQuery(this).unbind(transitionEnd);
						});
					},10);
				} else {
					if(from < this.items.length-1) {
						this.items[from+1].item.animate({'margin-top': '0'},self.settings.sortspeed);
					}
					row.animate({'top':topos.top+'px'},self.settings.sortspeed,function() {
						self.items[to].item.before(row);
						jQuery(this).css({'position':'static','z-index':'0','top':'','left':'','width':'auto'});
						jQuery('.dummySorting').remove();
						self.resortItem(from,to);
						if(posOpt === 2) self.focus(self.items[(from)].article[0].item);
						self.isSorting = false;
					});
					dummy.animate({'height': h+'px'},self.settings.sortspeed);
				}
				if(posOpt >= 1) {
					var sh = screen.height;
					if(h > self.Height) {
						var spos = (posOpt == 2 ? pos.top+h : topos.top-h);
					} else {
						var spos = (posOpt == 2 ? pos.top+h : topos.top-h) + Math.round((self.Height - h)/2);
					}
					this.scrollBody.animate({'scrollTop': (spos)+'px'},self.settings.sortspeed);
				}
			}
		},

		resortItem: function(from,to) {
			var row = this.items[from];
			var length = this.items.length;
			if(from > to) {
				this.items.remove(from);
				this.items.insert(to,row);
			} else if(from < to) {
				if(to < length - 1)
					this.items.insert(to,row);
				else
					this.items.push(row);
				this.items.remove(from);
			}
			for(var i=Math.max(1,((from > to ? to : from)-1)); i<=Math.min(length-1,((from > to ? from : to)+1)); i++) {
				this.setSlideIndex(this.items[i].item,i);
				this.itemIndex[this.items[i].id] = i;
				this.items[i].index = i;
			}
		},

		makeSlideTitle: function(id) {
			var idx = this.itemIndex[id];
			var sDate = this.items[idx].item.find('[data-name="startDate"]').text();
			var stitle = this.items[idx].item.find('[data-name="headline"]').text();
			if(sDate || stitle) {
				this.items[idx].item.find('legend.article a').attr('data-content','').html((sDate ? '<span class="date">'+ sDate + '</span>' : '')+(sDate && stitle ? ' ' : '')+(stitle ? '<span class="title">' + stitle + '</span>' : ''));
			}
		},

		hideSlide: function(id) {
			var self = this;
			var idx = this.itemIndex[id];
			var row = this.items[idx].item;
			this.items[idx].published = 0;
			row.attr('attr-published',0).addClass('trashed');
			this.isChanged = 1;
		},

		showSlide: function(id) {
			var self = this;
			var idx = this.itemIndex[id];
			var row = this.items[idx].item;
			this.items[idx].published = 1;
			row.attr('attr-published',1).removeClass('trashed');
			this.isChanged = 1;
		},

		removeSlide: function(id) {
			var self = this;
			var idx = this.itemIndex[id];
			var row = this.items[idx].item;
			row.find('.console').remove();
			var slide = row.children('.slide');
			var w = slide.outerWidth();
			var h = slide.outerHeight();
			if(this._useWebkitTransition === true) {
				var transition = this.supports.transition.toLowerCase();
				var transform = this.supports.transform.toLowerCase();
				var transitionEnd = this.supports.transitionEnd;
			}
			row.css({
				'position':'relative',
				'width':'auto',
				'height':'auto'
			});
			
			slide.css({
				'width':w+'px',
				'height':h+'px',
				'overflow':'hidden'
			});
			if(this._useWebkitTransition === true) {
				setTimeout(function() {
					slide.css({transition:'width '+self.settings.sortspeed+'ms ease-in-out, height '+self.settings.sortspeed+'ms ease-in-out, opacity '+self.settings.sortspeed+'ms ease-in-out'});
					slide.css({'width':'0px', 'height':'0px', 'opacity':'0'});
					row.css({transition: (self.supports.transform)+' '+self.settings.sortspeed+'ms ease-in-out', transform: 'translate3d('+Math.round(w/2)+'px,0,0)'});
					row.bind(transitionEnd,function() {
						self.removeItem(idx);
						jQuery(this).remove();
					});
				},10);
			} else {
				row.animate({'max-height':'0px'},self.settings.sortspeed,function() {
					self.removeItem(idx);
					jQuery(this).parent().remove();
				});
			}
		},

		removeItem: function(idx) {
			this.items.remove(idx);
			for(var i=idx; i<this.items.length; i++) {
				this.setSlideIndex(this.items[i].item,i);
				this.itemIndex[this.items[i].id] = i;
				this.items[i].index = i;
			}
			this.isChanged = 1;
		},

		/**
		 * color picker : this function require jquery-ui
		 **/
		spectrum: function(element) {
			element.find('input.color').spectrum(this.settings.spectrumOptions);
		},

		/**
		 * get_filemanager at media url input form
		 **/
		MediaDialog: function(id) {
			var self = this;
			var fm = jQuery(this.fMarkup);
			fm.appendTo('body');
			fm.css({'display':'block'});
			var fms = fm.children('.media-inline-form-skin');
			var tarea = fms.find('textarea.file');
			fm.find('#mediamultisource').attr('id',id+'_mediamultisource');
			tarea.focus();
			var w = fms.width();
			var h = fms.height();
			if(w >= this.Width - 20) {
				var l = 10;
				w = this.Width - 20;
			} else {
				var l = Math.round((this.Width - w)/2);
			}
			if(h >= this.Height - 20) {
				var t = 10;
				h = this.Height - 20;
			} else {
				var t = Math.round((this.Height - h)/2);
			}
			fms.css({'left': l+'px', 'top': t+'px', 'width': w+'px', 'height': h+'px'});
			jQuery(document).bind('keydown.MediaDialog',function(event) {
				var code = event.charCode || event.keyCode;
				if(code == 27) self.closeMediaDialog();
			});
			fm.find('.media-inline-form-close, button.cancel').click(function(e) {
				self.closeMediaDialog();
			});
			fm.find('.media-inline-form-overlay').bind('touchstart click',function(e) {
				self.closeMediaDialog();
			});
			var up = fm.find('.upload');
			this.callfilemanager(id+'_mediamultisource',2,'multi');
			jQuery.getScript(base_uri+'contribute/autosize/jquery.autosize.min.js',function() {
				tarea.autosize();
			});
			fm.find('button.ok').click(function(e) {
				if(jQuery.trim(tarea.val())) {
					self.addMedia(id,jQuery.trim(tarea.val()));
					self.closeMediaDialog();
				} else {
					tarea.focus();
				}
			});
		},

		closeMediaDialog: function() {
			jQuery(document).unbind('keydown.MediaDialog');
			jQuery('#media_inline_form').remove();
		},

		callfilemanager: function(id,type,multi) {
			var self = this;
			var update = false;
			if(id.match(/(_media|_thumbnail)/i)) {
				var ids = id.split('_');
				update = true;
			}
			var url = base_uri+'contribute/filemanager/filemanager/dialog.php?type='+type+'&subfolder=&editor=mce_0&field_id='+id+'&lang=ko_KR&taogi_select_mode='+multi;
			jQuery('#'+id).parent().find('a.upload').attr('href',url).click(function(e) {
				e.preventDefault();
				var options = jQuery.extend({},self.settings.fancyboxFilemanagerOptions,{
					href		: url,
					afterClose	: function() {
						var inp = jQuery('.currentFileManagerTarget');
						if(inp.length > 0) {
							var ids = inp.attr('id').split('_');
							if(ids[1] == 'mediamultisource') {
								var images = inp.val().split(',');
								var ids = inp.attr('id').split('_');
								for(var i=0; i<images.length; i++) {
									if(images[i]) {
										self.addMedia(ids[0],images[i]);
									}
								}
								inp.removeClass('currentFileManagerTarget');
								self.closeMediaDialog();
							} else {
								inp.removeClass('currentFileManagerTarget');
								self.updateMedia(ids[0],ids[1],true);
							}
						}
					}
				});
				if(update == true || multi == 'multi') {
					jQuery('#'+id).addClass('currentFileManagerTarget');
				}
				jQuery.fancybox.open(options);
			});
		},

		addThumbnail: function(id,m,article,autoEdit) {
			var self = this;
			var mediaNav = article.find('.media-nav');
			var newGallery = false;
			if(mediaNav.length < 1) {
				newGallery = true;
				var mediaNav = jQuery(this.mMarkup);
				article.find('.slide .console').before(mediaNav);
				this.sortable(id);
			}
			var thumbnails = mediaNav.find('ul.thumbnails');
			var thumbnail = jQuery(this.meMarkup);
			thumbnail.find('.remove').click(function(e) {
				e.preventDefault();
				thumbnail.data('removeMedia',1);
				self.removeMedia(id,m.uid);
			});
			thumbnail.attr({'id': 't_'+m.uid, 'data-id':id, 'data-gid':m.uid, 'href':m.id, 'tabindex': 100 });
			thumbnail.addClass(m.type.replace(/-/i,''));
			if(m.featured) {
				this.setFeature(article,m);
				thumbnail.addClass('featured');
			}
			jQuery(this.createThumbnail(m)).prependTo(thumbnail);
			if(newGallery) {
				thumbnail.appendTo(thumbnails);
				var addButton = jQuery(this.maMarkup);
				addButton.appendTo(thumbnails);
				addButton.attr('id',id+'_add').find('a').click(function(e) {
					e.preventDefault();
					self.MediaDialog(id);
				});
			} else {
				var addButton = mediaNav.find('li.add');
				var p = addButton.prev();
				if(p && (addButton.position().top != p.position().top) && p.hasClass('fieldset-container')) {
					p.before(thumbnail);
				} else {
					addButton.before(thumbnail);
				}
			}
			this.bindThumbnailClick(thumbnail,id,m);
			if(autoEdit === true) {
				this.setThumbWidth(thumbnail,m.uid,m);
			}
			return mediaNav;
		},

		bindThumbnailClick: function(element,id,m) {
			var self = this;
			element.unbind('click.taogi');
			element.unbind('keydown.taogi');
			element.css('cursor','pointer').bind('click.taogi',function(e) {
				e.preventDefault();
				if(jQuery('#date_'+id+' .thumbnails #'+m.uid+'_editor.fieldset-container').length > 0) {
					self.closeMediaEditor(id,m.uid,true);
				} else {
					if(jQuery(this).data('removeMedia') !== 1) {
						m.featured = parseInt(jQuery(this).attr('featured'));
						self.openMediaEditor(m);
					}
				}
			})
			.bind('keydown.taogi',function(e) {
				var code = event.charCode || event.keyCode;
				if(code == 13) {
					e.preventDefault();
					if(jQuery('#date_'+id+' .thumbnails #'+m.uid+'_editor.fieldset-container').length > 0) {
						self.closeMediaEditor(id,m.uid,true);
					} else {
						m.featured = parseInt(jQuery(this).attr('featured'));
						self.openMediaEditor(m);
					}
				} else if(code == 9) {
					e.preventDefault();
					var n = jQuery(this).next();
					if(typeof n !== 'undefined' && n != null && n.hasClass('fieldset-container')) {
						n = n.next();
					}
					if(n.length > 0) {
						if(n.hasClass('add')) n.children('a').focus();
						else n.focus();
					} else {
						self.nextArticleEditElement(code,jQuery(this).attr('data-id'),1000);
					}
				}
			});
		},

		sortable: function(id) {
			var self = this;
			jQuery('#date_'+id+' .thumbnails').sortable({
				cursor: "move",
				items: 'li.thumbnail',
				placeholder: 'sortable-placeholder',
				opacity: 0.7,
				start: function(event,ui) {
					var w = ui['item'].outerWidth();
					var h = ui['item'].outerHeight();
					var ph = ui['placeholder'];
					ph.css({ 'width': w+'px', 'height': h+'px' });
				},
				update: function(event,ui) {
					var id = ui['item'].attr('data-id');
					var gid = ui['item'].attr('data-gid');
					var eC = jQuery('#date_'+id+' .fieldset-container');
					if(eC.length > 0) {
						var uid = eC.attr('id').replace(/_editor/i,'');
						var gItem = jQuery('#date_'+id+' #t_'+uid);
						var insertPos = self.MediaEditorPosition(gItem);
						var n = eC.next();
						var prevPos;
						if(n.length>0) {
							prevPOs = n;
						}
						if(insertPos && prevPos && insertPos != prevPos.attr('id')) {
							jQuery('#'+insertPos).before(eC);
						} else if(insertPos && !prevPos) {
							jQuery('#'+insertPos).before(eC);
						} else if(prevPos && !insertPos) {
							ui['item'].parent().append(eC);
						}
						self.setMediaEditorBefore(uid,gItem);
						self.resortItemGallery(id);
					}
				}
			});
		},

		resortItemGallery: function(id) {
			var self = this;
			var idx = this.itemIndex[id];
			var galleries = jQuery.extend({},this.items[idx].galleries);
			var thumbnails = jQuery('#date_' + id + ' .media-nav ul.thumbnails');
			var n_galleries = [];
			thumbnails.find('li.thumbnail').each(function(index) {
				var uid = jQuery(this).attr('data-gid');
				var oid = self.galleryIndex[id][uid];
				n_galleries.push( galleries[oid] );
				self.galleryIndex[id][uid] = index;
			});
			this.items[idx].galleries = n_galleries;
			this.isChanged = 1;
		},

		addMedia: function(id,src) {
			var self = this;
			var idx = this.itemIndex[id];
			var article = jQuery('#date_'+id);
			var mediaNav = article.find('.media-nav');
			var newGallery = false;
			if(mediaNav.length < 1) {
				newGallery = true;
			}
			src = src.replace(/"/g,"'");
			var m = taogiVMM.ExternalAPI.MediaType(src);
			m.url = src;
			m.uid = taogiVMM.Util.unique_ID(6);
			m.credit = '';
			m.caption = '';
			m.thumbnail = '';
			m.use_proxy = '';
			m.use_thumb_proxy = '';
			m.thumb = 1;
			if(this._useWebkitTransition == true) {
				m.wmode = 'transparent';
			} else {
				m.wmode = 'window';
			}
			if(newGallery) {
				m.featured = 1;
				this.items[idx].featured = 0;
			}
			this.items[idx].galleries.push(m);
			this.galleryIndex[id][m.uid] = this.items[idx].galleries.length-1;

			mediaNav = this.addThumbnail(id,m,article,true);
			this.isChanged = 1;
		},

		setFeatureId: function(id,m) {
			var article = jQuery('#date_'+id);
			this.setFeature(article,m);
		},

		setFeature: function(article,m) {
			var figure = jQuery('<div class="article"><figure class="figure" href="'+m.url+'" thumbnail="'+m.thumbnail+'" caption="'+m.caption+'" credit="'+m.credit+'" id="'+m.uid+'_thumb"></figure></article>');
			article.find('.feature').html('').prepend(figure);
			var fm = jQuery.extend({},m);
			fm.uid = m.uid+'_thumb';
			fm.thumb = 1;
			var mediaElem = this.createFigureElement(fm);
			if(mediaElem) {
				if(fm.type == 'image' || fm.type == 'instagram') {
					taogiVMM.alignattachElement('#'+fm.uid, mediaElem, '#'+fm.uid+(fm.thumb ? ' .feature_image' : ''), (fm.thumb ? 1 : 0));
				} else {
					taogiVMM.attachElement('#'+fm.uid,mediaElem);
				}
			}
			taogiVMM.ExternalAPI.pushQues();
			this.isChanged = 1;
		},

		openMediaEditor: function(m) {
			var self = this;
			var gItem = jQuery('#t_'+m.uid);
			gItem.addClass('current').siblings().removeClass('current');
			var id = gItem.attr('data-id');
			this.closeMediaEditor(id,'',false);
			var container = jQuery(this.mfMarkup.replace(/date___SLIDE_ID___media___MEDIA_ID/gi,id+'_'+m.uid).replace(/___/gi,'_'));
			var insertPos = this.MediaEditorPosition(gItem);
			if(insertPos) {
				jQuery('#'+insertPos).before(container);
			} else {
				gItem.parent().append(container);
			}
			var eh = container.positionRelative('#date_'+id).top + parseInt(container.css('margin-bottom')) + parseInt(container.css('margin-top'));
			container.css({'z-index': '10', 'display': 'none'});
			container.attr('id',m.uid+"_editor");
			this.setMediaEditorBefore(m.uid,gItem);
			container.find('legend.media').text(m.uid);
			container.find('.wrap > .console > label').attr('for',id+'_'+m.uid+'_asset');
			/* change featured asset event */
			container.find('.wrap > .console input[type="radio"]').attr({'id':id+'_'+m.uid+'_asset','name':id+'_'+m.uid+'_asset'}).val(id+'_'+m.uid).change(function(e) {
				if(jQuery(this).is(":checked")) {
					self.changeFeatured(jQuery(this).val());
				}
			});
			if(m.featured) {
				container.find('.wrap > .console input[type="radio"]').attr('checked',true);
			} else {
				container.find('.wrap > .console input[type="radio"]').attr('checked',false);
				container.find('.wrap > .console input[type="radio"]').attr({'data-tab-index': '1'});
			}
			container.find('.wrap > .console .remove').attr({'data-tab-index': '2'}).click(function(e) {
				e.preventDefault();
				self.removeMedia(id,m.uid);
			});

			/* media input */
			container.find('.field.source > textarea').attr({
					'name': id+'_'+m.uid+'_media',
					'data-tab-index': '4',
					'upload-tab-index': '4_1'
				}).val(m.url).
				parent().find('.upload').attr({ 'data-tab-index': '4-1' });
			this.callfilemanager(id+'_'+m.uid+'_media',2,'single');
			/* thumbnail input */
			container.find('.field.thumbnail > input').attr({
					'name': id+'_'+m.uid+'_thumbnail',
					'data-tab-index': '5',
					'upload-tab-index': '5_1'
				}).
				val(m.thumbnail).
				parent().find('.upload').attr({ 'data-tab-index': '5-1' });
			this.callfilemanager(id+'_'+m.uid+'_thumbnail',1,'single');
			/* credit input */
			container.find('.field.credit > input').attr({
					'name': id+'_'+m.uid+'_credit',
					'data-tab-index': '6'
				}).val(m.credit);
			/* caption input */
			container.find('.field.caption > input').attr({
					'name': id+'_'+m.uid+'_caption',
					'data-tab-index': '7'
				}).val(m.caption);
			container.find('.field.console > .update').attr({'data-tab-index': '8'}).click(function(e) {
				e.preventDefault();
				self.updateMedia(id,m.uid,false);
			});
			container.find('.wrap .close').attr({'data-tab-index': '9'}).click(function(e) {
				self.closeMediaEditor(id,m.uid,true);
			});

			m.thumb = 0;

			/* scroll page to editable */
			var h = container.actual('outerHeight');
			var sp = jQuery('#date_'+id).positionRelative('#'+this.Root.attr('id')).top + this.scrollMargin;
			var cS = self.scrollBody.scrollTop();

			if(h > self.Height) {
				var pos = sp + eh;
			} else {
				var pos = sp + eh - (self.Height - h);
			}
			/* 2015-01-11 no scrolling at click thumbnail */
//			if(pos > cS) {
//				self.scrollBody.animate({'scrollTop': pos+'px'},self.settings.sortspeed);
//			}

			/* enter/tab key handle */
			this.handleMediaEditorKeyDown(container);

			container.slideDown(self.settings.sortspeed,function() {
				jQuery(this).css({'z-index': '0'});
				self.focus(jQuery(this).find('.field.source > textarea'));
				/* create media element */
				var figure = jQuery('<figure class="figure" href="'+m.url+'" id="'+m.uid+'"></figure>');
				container.find('.preview').html('').append(figure);
				var mediaElem = self.createFigureElement(m);
				if(mediaElem) {
					if(m.type == 'image' || m.type == 'instagram') {
						taogiVMM.alignattachElement('#'+m.uid, mediaElem, '#'+m.uid+(m.thumb ? ' .feature_image' : ''), (m.thumb ? 1 : 0));
					} else {
						taogiVMM.attachElement('#'+m.uid,mediaElem);
					}
				}
				taogiVMM.ExternalAPI.pushQues();
			});
		},

		MediaEditorPosition: function(me) {
			var width = me.parent().innerWidth();
			var myPos = me.position().top;
			var w = me.position().left - me.parent().position().left + me.outerWidth(true);
			var insertItem = '';
			me.nextAll().each(function(index) {
				var $this = jQuery(this);
				if(!$this.hasClass('fieldset-container')) {
					w = w + $this.outerWidth(true);
					if(w > width) {
						insertItem = $this.attr('id');
						return false;
					}
/*					trace("this.position.top: "+$this.position().top);
					if(myPos != $this.position().top) {
						insertItem = $this.attr('id');
						trace("insertItem: "+insertItem);
						return false;
					} else {
						w = w + $this.outerWidth(true);
						trace("w : "+w);
						if(w > width) {
							insertItem = $this.attr('id');
							trace("insertItem2: "+insertItem);
							return false;
						}
					} */
				}
			});
			return insertItem;
		},

		closeMediaEditor: function(id,uid,aniOpt) {
			var self = this;
			if(typeof uid === 'undefined' || !uid) {
				var container = jQuery('#date_'+id+' .thumbnails .fieldset-container');
			} else {
				var container = jQuery('#date_'+id+' .thumbnails #'+uid+'_editor.fieldset-container');
			}
			container.data('isClosing',1);
			if(aniOpt == true) {
				container.slideUp(self.settings.sortspeed,function() {
					if(typeof uid !== 'undefined' && uid) {
						jQuery('#t_'+uid).removeClass('current');
					}
					jQuery(this).remove();
				});
			} else {
				if(typeof uid !== 'undefined' && uid) {
					jQuery('#t_'+uid).removeClass('current');
				}
				container.remove();
			}
		},

		changeFeatured: function(val) {
			var v = val.split('_');
			var id = v[0];
			var uid = v[1];
			var idx = this.itemIndex[id];
			var gindex = this.galleryIndex[id][uid];
			if(this.items[idx].featured != gindex) {
				var m = this.items[idx].galleries[gindex];
				m.featured = 1;
				this.items[idx].galleries[gindex].featured = 1;
				this.items[idx].galleries[this.items[idx].featured].featured = 0;
				this.items[idx].featured = gindex;
				this.setFeatureId(id,m);
				jQuery('#t_'+m.uid).addClass('featured').attr('featured',1).siblings().removeClass('featured').attr('featured',0);
			}
		},

		/**
		 * handleMediaEditorKeyDown
		 *   - Handling key down event at MediaEditor
		 *   - MediaEditor is activated at click thumbnail
		 */
		handleMediaEditorKeyDown: function(container) {
			var self = this;
			container.find('input, textarea, a, button').each(function(index) {
				var tab = jQuery(this).attr('data-tab-index');
				if(tab) {
					jQuery(this).keydown(function(event) {
						var code = event.charCode || event.keyCode;
						var $this = jQuery(this);
						if(code == 9 || code == 13) {
							if(code == 9 && jQuery(this).attr('upload-tab-index')) {
								container.find('[data-tab-index="'+$this.attr('upload-tab-index')+'"]').focus();
							} else if(code == 13 && $this.hasClass('update')) {
								return;
							} else {
								if($this.hasClass('text')) {
									var _ids = $this.attr('id').split(/_/);
									if(_ids[2] == 'credit' || _ids[2] == 'caption') {
										self.saveMediaAttribute(_ids[0],_ids[1],_ids[2],$this.val().replace(/"/g,"&quot;"));
										$this.data('isEditing',0);
									} else {
										if(code == 13 && jQuery('#'+$this.attr('id')+'mode_textarea').prop('checked')) {
											return;
										} else {
											self.updateMedia(_ids[0],_ids[1],true);
											$this.data('isEditing',0);
										}
									}
								}
								var _tab = tab.split('_');
								var tab_index = parseInt(_tab[0])+1;
								var n = container.find('[data-tab-index="'+tab_index+'"]');
								if(n.length > 0) {
									event.preventDefault();
									if(n[0].tagName.toLowerCase() == 'a' || n[0].tagName.toLowerCase() == 'button') {
										n.focus();
									} else {
										self.focus(n);
									}
								} else if(code == 9) {
									var id = container.attr('id');
									var tid = id.replace(/_editor/i,'');
									var n = jQuery('#t_'+tid).next();
									if(n.attr('id') == id) n = n.next();
									if(n.length > 0) {
										event.preventDefault();
										if(n.hasClass('add')) {
											n.children('a').focus();
										} else {
											n.focus();
										}
									}
								}
							}
						}
					})
					.focusin(function(e) {
						var $this = jQuery(this);
						if($this.hasClass('text')) {
							$this.data('isEditing',1);
						}
					})
					.focusout(function(e) {
						var $this = jQuery(this);
						if($this.hasClass('text') && $this.data('isEditing') == 1) {
							var _ids = $this.attr('id').split(/_/);
							if(_ids[2] == 'credit' || _ids[2] == 'caption') {
								self.saveMediaAttribute(_ids[0],_ids[1],_ids[2],$this.val().replace(/"/g,"&quot;"));
							} else {
								self.updateMedia(_ids[0],_ids[1],true);
							}
							$this.data('isEditing',0);
						}
					});
				}
			});
		},

		buildThumbnail: function(id,uid) {
			var thumbnail = jQuery('#t_'+uid);
			var src = thumbnail.attr('href');
			var m = taogiVMM.ExternalAPI.MediaType(src);
            m.url = src;
			m.uid = uid;
			m.thumbnail = thumbnail.attr('thumbnail');
			m.credit = thumbnail.attr('credit');
			m.caption = thumbnail.attr('caption');
            m.use_proxy = '';
            m.use_thumb_proxy = '';
            m.thumb = 1;
            if(this._useWebkitTransition == true) {
                m.wmode = 'transparent';
            } else {
                m.wmode = 'window';
            }
			m.featured = parseInt(thumbnail.attr('featured'));
			if(m.type) {
				thumbnail.addClass(m.type.replace(/-/i,''));
			}
			thumbnail.prepend(jQuery(this.createThumbnail(m)));
			this.bindThumbnailClick(thumbnail,id,m);
			if(m.featured) {
				this.setFeatureId(id,m);
			}
		},

		updateMedia: function(id,uid,now) {
			var self = this;
			var idx = this.itemIndex[id];
			var gidx = this.galleryIndex[id][uid];
			var om = this.items[idx].galleries[gidx];
			var src = jQuery.trim(jQuery('#'+id+'_'+uid+'_media').val());
			if(now === true) {
				if(om.url == src && om.thumbnail == jQuery.trim(jQuery('#'+id+'_'+uid+'_thumbnail').val())) {
					return;
				}
			}
			var m = taogiVMM.ExternalAPI.MediaType(src);
            m.url = src;
            m.uid = om.uid;
            m.credit = jQuery.trim(jQuery('#'+id+'_'+uid+'_credit').val());
            m.caption = jQuery.trim(jQuery('#'+id+'_'+uid+'_caption').val());
            m.thumbnail = jQuery.trim(jQuery('#'+id+'_'+uid+'_thumbnail').val());
            m.use_proxy = '';
            m.use_thumb_proxy = '';
            m.thumb = 1;
            if(this._useWebkitTransition == true) {
                m.wmode = 'transparent';
            } else {
                m.wmode = 'window';
            }
			m.featured = om.featured;
			this.items[idx].galleries[gidx] = m;

			var thumbnail = jQuery('#t_'+m.uid);
			thumbnail.attr({'href': m.url, 'thumbnail': m.thumbnail, 'credit': m.credit, 'caption': m.caption});
			if(om.type) {
				thumbnail.removeClass(om.type.replace(/-/i,''));
			}
			thumbnail.addClass(m.type.replace(/-/i,''));
			thumbnail.children(':first-child').remove();
			thumbnail.prepend(jQuery(this.createThumbnail(m)));
			if(om.url != src || om.thumbnail != jQuery.trim(jQuery('#'+id+'_'+uid+'_thumbnail').val()))
				this.setThumbWidth(thumbnail,uid);
			this.bindThumbnailClick(thumbnail,id,m);

			if(m.featured) {
				this.setFeatureId(id,m);
			}
			m.thumb = 0;
			var figure = jQuery('<figure class="figure" href="'+m.url+'" thumbnail="'+m.thumbnail+'" credit="'+m.credit+'" caption="'+m.caption+'" id="'+m.uid+'"></figure>');
			jQuery('#'+m.uid+'_editor .preview').html('').append(figure);
			var mediaElem = this.createFigureElement(m);
			if(mediaElem) {
				if(m.type == 'image' || m.type == 'instagram') {
					taogiVMM.alignattachElement('#'+m.uid, mediaElem, '#'+m.uid+(m.thumb ? ' .feature_image' : ''), (m.thumb ? 1 : 0));
				} else {
					taogiVMM.attachElement('#'+m.uid,mediaElem);
				}
			}
			this.isChanged = 1;
			taogiVMM.ExternalAPI.pushQues();
		},

		saveMediaAttribute: function(id,uid,attr,value) {
			var idx = this.itemIndex[id];
			var gidx = this.galleryIndex[id][uid];
			eval('this.items[idx].galleries[gidx].'+attr+' = value');
			var at = {};
			at[attr] = value;
			jQuery('#t_'+uid).attr(at);
			jQuery('#'+uid).attr(at);
			if(this.items[idx].galleries[gidx].featured == 1) {
				jQuery('#'+uid+"_thumb").attr(at);
			}
			this.isChanged = 1;
		},

		setThumbWidth: function(thumbnail,uid,m) {
			var self = this;
			var im = thumbnail.find('img');
			var sp = thumbnail.find('img');
			var t_height = thumbnail.innerHeight();
			if(im.length > 0) {
                var hiddenImg = new Image();
                hiddenImg.onload = function() {
                    var w = parseInt(t_height * this.width / this.height);
                    thumbnail.css({'width': w+'px'});
					if(typeof(m) !== 'undefined')
						self.openMediaEditor(m);
					if(typeof uid !== 'undefined' && uid)
						self.setMediaEditorBefore(uid,thumbnail);
                }
                hiddenImg.src = im.attr('src');
            } else {
				if(sp.length > 0) {
					thumbnail.css({'width': sp.outerWidth()+'px'});
				}
				if(typeof(m) !== 'undefined')
					self.openMediaEditor(m);
				if(typeof uid !== 'undefined' && uid)
					self.setMediaEditorBefore(uid,thumbnail);
            }
		},

		setMediaEditorBefore: function(uid,gItem) {
			var ap = gItem.position().left + Math.round(gItem.width() / 2) - gItem.parent().position().left;
			jQuery('#'+uid+'_editor:before').addRule({'left': ap+'px'});
		},

		removeMedia: function(id,uid) {
			var self = this;
			var thumb = jQuery('#t_'+uid);
			this.closeMediaEditor(id,uid,true);
			var idx = this.itemIndex[id];
			var gidx = this.galleryIndex[id][uid];
			var isfeatured = this.items[idx].featured;
			var change_feature = false;
			if(isfeatured == gidx) {
				change_feature = true;
			}

			this.items[idx].galleries.remove(gidx);
			delete this.galleryIndex[id][uid];
			this.isChanged = 1;

			var n_f_uid = '';
			if(this.items[idx].galleries.length > 0) {
				for(var i=gidx; i<this.items[idx].galleries.length; i++) {
					var n_uid = this.items[idx].galleries[i].uid;
					this.galleryIndex[id][n_uid] = i;
					if(this.items[idx].galleries[i].featured == 1) {
						this.items[idx].featured = i;
					}
				}
				if(change_feature) {
					if(isfeatured > 0) var n_featured = isfeatured - 1;
					else n_featured = 0;
					this.items[idx].galleries[n_featured].featured = 1;
					this.items[idx].featured = n_featured;
					var m = this.items[idx].galleries[n_featured];
					jQuery('#date_'+id+' #t_'+m.uid).addClass('featured');
					var n_f_uid = m.uid;
					this.setFeatureId(id,m);
				}
				var focus_uid = this.items[idx].galleries[Math.min(gidx,this.items[idx].galleries.length-1)].uid;
				jQuery('#t_'+focus_uid).focus();
			}
			thumb.animate({'width':'0px', 'height':'0px', 'opacity':'0'}, this.settings.sortspeed, function() {
				jQuery(this).remove();
				var editorContainer = jQuery('#date_'+id+' .fieldset-container');
				if(editorContainer.length > 0 && editorContainer.data('isClosing') !== 1) {
					var eUID = editorContainer.attr('id').replace(/_editor/i,'');
					self.setMediaEditorBefore(eUID,jQuery('#t_'+eUID));
					if(n_f_uid && eUID == n_f_uid) {
						editorContainer.find('.wrap > .console input[type="radio"]').attr('checked',true);
					}
				}
			});
			if(this.items[idx].galleries.length < 1) {
				jQuery('#'+id+'_add').animate({'width':'0px', 'height':'0px', 'opacity':'0'}, this.settings.sortspeed, function() {
					jQuery('.media-nav').remove();
					self.items[idx].item.find('.feature').html('').append(jQuery(self.saMarkup));
					self.items[idx].item.find('.button.media.add').click(function(e) {
						e.preventDefault();
						self.MediaDialog(id);
					});
				});
			}
		},

		createFigureElement:function(m) {
			var self = this;
			var mediaElem = '';
			var loading_message = loadingmessage("Loading...");
			if(m.type == "image") {
				if(m.thumb) jQuery('#'+m.uid).addClass('thumb-image taogi_buildGallery');
				else jQuery('#m'+m.uid).addClass('image');
				if(m.use_proxy) {
					m.id = './library/api.php?type=proxy&taogiauth=ACA20D8B4F7B63D8639C7824AC458D3A53F7E275&skip_referer=1&url='+encodeURIComponent(m.id);
				}
				mediaElem = "<img src='"+m.id+"' class='feature_image' />";
				if(m.credit) mediaElem += "<h5 class='caption'>"+m.caption+"</h5>";
			} else if(m.type == "flickr") {
				jQuery('#'+m.uid).addClass((m.thumb ? 'thumb-flickr taogi_buildGallery' : 'flickr')).html(loading_message);
				taogiVMM.ExternalAPI.flickr.get(m);
			} else if(m.type == "instagram") {
				jQuery('#'+m.uid).addClass('taogi_buildGallery');
				mediaElem = "<img src='"+taogiVMM.ExternalAPI.instagram.get(m)+"' class='feature_image' />";
				if(m.credit) mediaElem += "<h5 class='caption'>"+m.caption+"</h5>";
			} else if(m.type == "youtube") {
				jQuery('#'+m.uid).addClass((m.thumb ? 'thumb-youtube taogi_buildGallery taogi-icon-player' : 'youtube'));
				vw = this.resolutionOfVideo(m);
				m.width = vw.width;
				m.height = vw.height;
				taogiVMM.appendElement('#'+m.uid,'<div id="'+m.uid+'_youtube" style="width:'+m.width+'px;height:'+m.height+'px;"></div>');
				taogiVMM.attachElement('#'+m.uid+'_youtube',loading_message);
				taogiVMM.ExternalAPI.youtube.get(m);
			} else if(m.type == "googledoc") {
				jQuery('#'+m.uid).addClass((m.thumb ? 'thumb-googledoc taogi_buildGallery' : 'googledoc')).html(loading_message);
				taogiVMM.ExternalAPI.googledocs.get(m);
			} else if(m.type == "vimeo") {
				jQuery('#'+m.uid).addClass((m.thumb ? 'thumb-vimeo taogi_buildGallery taogi-icon-player' : 'vimeo')).html(loading_message);
				m.width = this.resolutionOfVideo(m);
				taogiVMM.ExternalAPI.vimeo.get(m);
			} else if(m.type == "vine") {
				jQuery('#'+m.uid).addClass((m.thumb ? 'thumb-vine taogi_buildGallery taogi-icon-player' : 'vine')).html(loading_message);
				m.width = this.resolutionOfVideo(m);
				taogiVMM.ExternalAPI.vine.get(m);
			} else if(m.type == "dailymotion") {
				jQuery('#'+m.uid).addClass((m.thumb ? 'thumb-dailymotion taogi_buildGallery taogi-icon-player' : 'dailymotion')).html(loading_message);
				taogiVMM.ExternalAPI.dailymotion.get(m);
			} else if(m.type == "twitter") {
				jQuery('#'+m.uid).addClass((m.thumb ? 'thumb-' : '')+'twitter').html(loading_message);
				taogiVMM.ExternalAPI.twitter.get(m);
			} else if(m.type == "twitter-ready") {
				jQuery('#'+m.uid).addClass('textMedia');
				mediaElem = m.id;
			} else if(m.type == "soundcloud") {
				jQuery('#'+m.uid).addClass((m.thumb ? 'thumb-soundcloud taogi_buildGallery taogi-icon-player' : 'soundcloud')).html(loading_message);
				taogiVMM.ExternalAPI.soundcloud.get(m);
			} else if(m.type == "google-map") {
				jQuery('#'+m.uid).addClass((m.thumb ? 'thumb-googlemap taogi_buildGallery' : 'googlemap')).html(loading_message);
				taogiVMM.ExternalAPI.googlemaps.get(m);
			} else if(m.type == "googleplus") {
				jQuery('#'+m.uid).addClass((m.thumb ? 'thumb-googleplus taogi_buildGallery' : 'googleplus')).html(loading_message);
				taogiVMM.ExternalAPI.googleplus.get(m);
			} else if(m.type == "wikipedia") {
				jQuery('#'+m.uid).addClass((m.thumb ? 'thumb-' : '')+'wikipedia').html(loading_message);
				taogiVMM.ExternalAPI.wikipedia.get(m);
			} else if(m.type == "rigvedawiki") {
				jQuery('#'+m.uid).addClass((m.thumb ? 'thumb-' : '')+'rigvedawiki').html(loading_message);
				taogiVMM.ExternalAPI.rigvedawiki.get(m);
			} else if(m.type == "storify") {
				jQuery('#'+m.uid).addClass('textMedia');
				mediaElem = m.uid;
			} else if (m.type == "iframe") {
				jQuery('#'+m.uid).addClass((m.thumb ? 'thumb-iframe taogi_buildGallery' : 'iframe')).html(loading_message);
				taogiVMM.ExternalAPI.iframe.get(m);
            } else if (m.type == "mediaelements") {
				jQuery('#'+m.uid).addClass((m.thumb ? 'thumb-' : '')+"mediaelements").html(loading_message);
				taogiVMM.ExternalAPI.mediaelements.get(m);
            } else if (m.type == "pdf") {
				jQuery('#'+m.uid).addClass((m.thumb ? 'thumb-' : '')+"pdf").html(loading_message);
				taogiVMM.ExternalAPI.pdf.get(m);
            } else if (m.type == "attachment") {
				jQuery('#'+m.uid).addClass((m.thumb ? "thumb-" : "")+'attachment');
				taogiVMM.ExternalAPI.attachment.create(m);

			} else if(m.type == "quote") {
				jQuery('#'+m.uid).addClass((m.thumb ? "thumb-" : "")+'textMedia');
				mediaElem = m.id.replace(/<blockquote>/i,'<blockquote><p>').replace(/<\/blockquote>/i,'</p></blockquote>');
			} else if(m.type == "unknown") {
				jQuery('#'+m.uid).addClass('textMedia');
				mediaElem = "<div class='container'>" + taogiVMM.Util.properQuotes(m.id) + "</div>";
			} else if(m.type == "website") {
				jQuery('#'+m.uid).addClass((m.thumb ? 'thumb-' : '')+'website').html(loading_message);
				taogiVMM.ExternalAPI.webthumb.get(m); 
			} else {
				trace("NO KNOWN MEDIA TYPE FOUND");
				trace(m.type);
			}
			return mediaElem;
		},

		resolutionOfVideo:function(m) {
			var v = {};
			v.width = jQuery('#'+m.uid).width();
			v.height = jQuery('#'+m.uid).outerHeight(true);
			var max_width = Math.round(v.height * 16 / 9);
			if(max_width < v.width) v.width = max_width;

			return v;
        },

		createThumbnail: function(m) {
			var mediaElem = '';
			if(m.thumbnail) {
				if(m.use_thumb_proxy) {
					m.thumbnail = './library/api.php?type=proxy&taogiauth=ACA20D8B4F7B63D8639C7824AC458D3A53F7E275&skip_referer=1&url='+encodeURIComponent(m.thumbnail);
				}
				mediaElem = '<img src="'+m.thumbnail+'" alt="'+m.caption+' '+m.credit+'" />';
			} else {
				switch(m.type) {
					case 'image':
						if(m.use_proxy) {
							m.id = './library/api.php?type=proxy&taogiauth=ACA20D8B4F7B63D8639C7824AC458D3A53F7E275&skip_referer=1&url='+encodeURIComponent(m.id);
						}
						mediaElem = '<img src="'+m.id+'" alt="'+m.caption+' '+m.credit+'" />';
						break;
					default:
						mediaElem = '<span>'+(m.caption ? m.caption : m.credit)+'</span>';
                        break;
				}
			}
			return mediaElem;
		},

		/**
		 * save timeline : this funtion require resources/script/default.js
		 **/
		save: function() {
			var self = this;
			var timelineJSON = {};
			timelineJSON.timeline = {};
			timelineJSON.timeline.date = []
			timelineJSON.timeline.era = {};
			timelineJSON.timeline.asset = {};
			timelineJSON.timeline.extra = {};

			if(this.Root.find('span.alert').length > 0) {
				var ao = this.Root.find('span.alert');
				var cao = ao.closest('.slide-item');
				this.saveError('입력값 오류','수정되지 않은 오류가 있습니다.',cao.attr('data-id'));
				if(cao.length > 0) {
					var c = cao.find('fieldset.extendable');
					if(c.hasClass('collapsed')){
						this.uncollapse(cao);
					}
					this.focus(ao.parent().find('.editable'));
				}
				return false;
			}

			timelineJSON.timeline.headline = jQuery.trim(jQuery('.timeline_properties #timeline_headline').text());
			if(!timelineJSON.timeline.headline) {
				this.saveError('입력값 오류','타임라인 제목을 입력하세요','');
				return this.checkField('.timeline_properties #timeline_headline','타임라인 제목을 입력하세요');
			}
			timelineJSON.timeline.era.headline = timelineJSON.timeline.headline;
			timelineJSON.timeline.text = jQuery.trim(jQuery('.timeline_properties #timeline_text').html());
			if(!timelineJSON.timeline.text) {
				this.saveError('입력값 오류','타임라인에 대한 간단한 설명을 입력하세요','');
				return this.checkField('.timeline_properties #timeline_text','타임라인에 대한 간단한 설명을 입력하세요');
			}

			timelineJSON.timeline.permalink = jQuery.trim(jQuery('.timeline_properties #timeline_url').text());

			timelineJSON.timeline.extra.author = jQuery.trim(jQuery('.timeline_properties #extra_author').text());
			for(var i=1; i<this.items.length; i++) {
				if(this.checkValid(i) == false) continue;
				if(!this.items[i].item.find('legend.article a').text()) continue;
				if(this.checkTimeFormat(this.items[i].item.find('[data-name="startDate"]')) == false) {
					this.focus(this.items[i].item.find('[data-name="startDate"]'));
					this.saveError('입력값 오류','잘못된 날짜 형식입니다.',this.items[i].id);
					return;
				}
				var $this = this.items[i].item;
				var item = {};
				item.startDate = this.items[i].sDate;
				item.headline = jQuery.trim(this.items[i].item.find('[data-name="headline"]').html().replace(/"/g,"&quot;"));
				item.text = jQuery.trim(this.items[i].item.find('[data-name="text"]').html()).replace(/"/g,"&quot;");
				item.published = this.items[i].published;
				if(this.items[i].featured >= 0) {
					item.asset = {};
					item.asset.media = this.setAdjustForJson(this.items[i].galleries[this.items[i].featured].url);
					item.asset.caption = this.setAdjustForJson(this.items[i].galleries[this.items[i].featured].caption);
					item.asset.credit = this.setAdjustForJson(this.items[i].galleries[this.items[i].featured].credit);
					item.asset.thumbnail = this.setAdjustForJson(this.items[i].galleries[this.items[i].featured].thumbnail);
					item.asset.featured = this.items[i].galleries[this.items[i].featured].featured;
				}
				if(this.items[i].galleries.length > 0) {
					item.media = [];
					for(var j=0; j<this.items[i].galleries.length; j++) {
						var media = {};
						media.media = this.setAdjustForJson(this.items[i].galleries[j].url);
						media.caption = this.setAdjustForJson(this.items[i].galleries[j].caption);
						media.credit = this.setAdjustForJson(this.items[i].galleries[j].credit);
						media.thumbnail = this.setAdjustForJson(this.items[i].galleries[j].thumbnail);
						media.featured = this.items[i].galleries[j].featured;
						media.gid = this.items[i].galleries[j].uid;
						item.media.push(media);
					}
				}
				item.unique = this.items[i].id;
				timelineJSON.timeline.date.push(item);
			}

			if(true){
				timelineJSON.timeline.asset.cover_background_image = jQuery('#asset_cover_background_image').val();
				timelineJSON.timeline.extra.cover_background_color = jQuery('#extra_cover_background_color').val();
				timelineJSON.timeline.extra.cover_title_color = jQuery('#extra_cover_title_color').val();
				timelineJSON.timeline.extra.cover_body_color = jQuery('#extra_cover_body_color').val();

				timelineJSON.timeline.extra.slide_background_color = jQuery('#extra_slide_background_color').val();
				timelineJSON.timeline.extra.slide_title_color = jQuery('#extra_slide_title_color').val();
				timelineJSON.timeline.extra.slide_body_color = jQuery('#extra_slide_body_color').val();

				timelineJSON.timeline.asset.back_background_image = jQuery('#asset_back_background_image').val();
				timelineJSON.timeline.extra.back_background_color = jQuery('#extra_back_background_color').val();
				timelineJSON.timeline.extra.back_title_color = jQuery('#extra_back_title_color').val();
				timelineJSON.timeline.extra.back_body_color = jQuery('#extra_back_body_color').val();

				timelineJSON.timeline.extra.css = jQuery('#extra_css').val();
				//console.log(timelineJSON);
			}

			var replaceURI = false;
			if(this.Root.find('#eid').val() != '') {
				var url = base_uri + jQuery.trim(jQuery('.timeline_properties #taogi_permalink').text())+"/"+jQuery('#nickname').val()+"/save";
				if(this.Root.find('#vid').val())
					url = url + '?vid='+this.Root.find('#vid').val();
			} else {
				var url = base_uri + "create/save";
				replaceURI = true;
			}

			timelineJSON.timeline.extra.published = this.Root.find('input#is_public').val();
			jQuery.ajaxSettings.traditional = true;
			jQuery.ajax({
				url:  url,
				type: 'POST',
				data: {content: JSON.stringify(timelineJSON)},
				contentType: 'application/x-www-form-urlencoded',
				beforeSend: function() {
					jfe_Block_onRequest('타임라인을 저장하고 있습니다. 잠시만 기다려주세요.');
				},
				success: function(json) {
					var error = parseInt(json.error);
					var message = json.message;
					jfe_unBlock_afterRequest();
					if(error == -1) {
						alert("로그인한 시간이 오래되어 자동 로그아웃 되었습니다. 다시 로그인 해주세요");
						return false;
					} else if(error == -2 || error == -4) {
						return self.checkField('#timeline_url',message);
					} else if(error == -3) {
						alert(message);
						return false;
					} else if(!error) {
						var eid = json.eid;
						var vid = json.vid;
						var nickname = json.nickname;
						var s_uri = json.src_uri;
						var t_uri = json.tar_uri;
						self.Root.find('#eid').val(eid);
						self.Root.find('#vid').val(vid);
						if(self.Root.find('#nickname').val() != nickname) {
							replaceURI = true;
						}
						self.Root.find('#nickname').val(nickname);
						if(replaceURI) {
							if (window.history.replaceState) {
								window.history.pushState(null, '따오기 타임라인:'+jQuery('#timeline_headline').text(), base_uri + jQuery.trim(jQuery('.timeline_properties #taogi_permalink').text())+"/"+jQuery('#nickname').val()+"/modify");
							}
							if(self.settings.hasGNB) {
								self.updateGNB();
							}
						}
						self.isChanged = 0;
						if(s_uri && t_uri) {
							self.replaceMediaUri(s_uri,t_uri);
						}
					}
				},
				complete: function() {
				},
				error: function(jqXHR, textStatus, errors) {
					jfe_unBlock_afterRequest();
					alert(errors);
					return false;
				}
			});
		},

		checkField: function(selector,message) {
			jQuery(selector).addClass('focus').attr('error-content',message);
			this.focus(jQuery(selector));
			return false;
		},

		setAdjustForJson: function(value) {
			var out = '';
			if(typeof(value) == 'undefined') return out;
			if(typeof(value) == 'none') return out;
			if(typeof(value) == undefined) return out;
			if(!value) return out;
			out = jQuery.trim(value).replace(/"/g,"&quot;");
			return out;
		},

		/*
		 * show Error Message when error occurring at saving Content
		 */
		saveError: function(title,message,scroll_id) {
			var self = this;
			var markup = jQuery(this.errorMarkup);
			markup.find('h3.error-title').text(title);
			markup.find('div.error-message').text(message);
			markup.find('.close').attr('data-target',markup.attr('id'));
			jQuery('body').append(markup);
			markup.css({'left': parseInt((this.Width - markup.width())/2)+'px', 'top': parseInt((this.Height - markup.height())/2)+'px'});
			markup.find('.close').bind('click.taogi',function(e) {
				e.preventDefault();
				var id = jQuery(this).attr('data-target');
				jQuery('#'+id).remove();
			});
			setTimeout(function() {
				markup.css('opacity',0);
				setTimeout(function() {
					markup.remove();
				},600);
			},2000);
			if(scroll_id) {
				this.scrollTo(scroll_id);
			}
		},

		replaceMediaUri: function(s_uri,t_uri) {
			var self = this;
			for(var i=0; i<this.items.length; i++) {
				var f = this.items[i].item.find('.figure');
				if(f.length > 0) {
					if(f.attr('href'))
						f.attr('href',f.attr('href').replace(s_uri,t_uri));
					if(f.attr('thumbnail'))
						f.attr('thumbnail',f.attr('thumbnail').replace(s_uri,t_uri));
					f.find('img').each(function() {
						var $this = jQuery(this);
						if($this.attr('src'))
							$this.attr('src',$this.attr('src').replace(s_uri,t_uri));
					});
				}

				for(var j=0; j<this.items[i].galleries.length; j++) {
					this.items[i].galleries[j].url = this.items[i].galleries[j].url.replace(s_uri,t_uri);
					this.items[i].galleries[j].thumbnail = this.items[i].galleries[j].thumbnail.replace(s_uri,t_uri);
				}
				this.items[i].item.find('.media-nav .thumbnails .thumbnail').each(function(l) {
					var $this = jQuery(this);
					if($this.attr('href'))
						$this.attr('href',$this.attr('href').replace(s_uri,t_uri));
					if($this.attr('thumbnail'))
						$this.attr('thumbnail',$this.attr('thumbnail').replace(s_uri,t_uri));
					$this.find('img').each(function() {
						var $t = jQuery(this);
						if($t.attr('src'))
							$t.attr('src',$t.attr('src').replace(s_uri,t_uri));
					});
				});
			}
		},

		updateGNB: function() {
			var self = this;
			var url = base_uri + jQuery.trim(jQuery('.timeline_properties #taogi_permalink').text())+"/"+jQuery('#nickname').val()+"/gnb";
			jQuery.ajax({
				url:  url,
				type: 'GET',
				contentType: 'application/x-www-form-urlencoded',
				beforeSend: function() {
					jfe_Block_onRequest('헤더정보를 수정하고 있습니다. 잠시만 기다려주세요.');
				},
				success: function(json) {
					jfe_unBlock_afterRequest();
					var error = parseInt(json.error);
					var message = json.message;
					if(!error) {
						jQuery('#'+self.settings.hasGNB).replaceWith(message);
					} else {
						alert(message);
						return false;
					}
				},
				error: function(jqXHR, textStatus, errors) {
					jfe_unBlock_afterRequest();
					alert(errors);
					return false;
				}
			});
		},

		updateStatus: function() {
			var self = this;
			var eid = parseInt(this.Root.find('#eid').val());
			if(!eid) {
				this.save();
			} else {
				var s = this.Root.find('input#is_public').val();
				var url = base_uri + jQuery.trim(jQuery('.timeline_properties #taogi_permalink').text())+"/"+jQuery.trim(jQuery('.timeline_properties #timeline_url').text())+"/status";
				var params = "status="+s;
				jQuery.ajax({
					url:  url,
					type: 'POST',
					data: params,
					contentType: 'application/x-www-form-urlencoded',
					beforeSend: function() {
						jfe_Block_onRequest((!s ? '비' : '')+'공개문서로 전환하고 있습니다. 잠시만 기다려주세요.');
					},
					success: function(json) {
						var error = parseInt(json.error);
						var message = json.message;
						jfe_unBlock_afterRequest();
						if(error == -1) {
							alert("로그인한 시간이 오래되어 자동 로그아웃 되었습니다. 다시 로그인 해주세요");
							return false;
						} else if(error == -3) {
							alert(message);
						} else if(!error) {
							var eid = json.eid;
							var vid = json.vid;
							self.Root.find('#eid').val(eid);
							self.Root.find('#vid').val(vid);
						}
					},
					error: function(jqXHR, textStatus, errors) {
						jfe_unBlock_afterRequest();
						alert(errors);
						return false;
					}
				});
			}
		},

		unlock: function() {
			if(this.Root.find('#eid').val() != '') {
				var url = base_uri + jQuery.trim(jQuery('.timeline_properties #taogi_permalink').text())+"/"+jQuery.trim(jQuery('.timeline_properties #timeline_url').text())+"/status";
				var params = "lock=off";
			} else {
				var url = base_uri + 'create/cancel';
				var params = '';
			}
			jQuery.ajax({
				url:  url,
				type: 'POST',
				data: params,
				contentType: 'application/x-www-form-urlencoded',
				success: function(json) {
					var error = parseInt(json.error);
					var message = json.message;
					if(error == -1) {
						alert("로그인한 시간이 오래되어 자동 로그아웃 되었습니다. 다시 로그인 해주세요");
					} else if(error == -3) {
						alert('test1 '+message);
					} else if(!error) {
						alert("편집중인 타임라인의 락을 해제했습니다");
					}
				},
				error: function(jqXHR, textStatus, errors) {
//					alert('오류 '+errors);
				}
			});
		},

		configureSetting: function() {
			var self = this;
			this.configure.find('.tabs a').on('click',function(e){
				e.preventDefault();
				var $trigger = jQuery(this);
				var $tab = $trigger.closest('.tab');
				var $content = jQuery($trigger.attr('href'));

				$tab.siblings().removeClass('active');
				$tab.addClass('active');

				$content.siblings().removeClass('active');
				$content.addClass('active');
			});

			this.configureBasic();
			this.configurePreset();
			this.configureAdvanced();
		},

		configureBasic: function() {
			var self = this;
			var $basicEditor = this.configure.find('#'+this.settings.configure+'_basic');
			$basicEditor.find('a.cover_background_image_uploader').on('click',function(e){
				e.preventDefault();
				var $trigger = jQuery(this);
				var options = jQuery.extend({},self.settings.fancyboxFilemanagerOptions,{
					href: $trigger.attr('href'),
					afterClose  : function() {
						var inp = $trigger.next('input.cover_background_image');
						$trigger.find('img').attr('src',inp.val());
					}
				});
				jQuery.fancybox.open(options);
			});
			this.spectrum($basicEditor);
		},

		configurePreset: function() {
			var self = this;
			var $presetEditor = this.configure.find('#'+this.settings.configure+'_preset');
			$presetEditor.find('.preset input').on('change',function(e){
				$trigger = jQuery(this);
				$preset = jQuery(this).closest('.preset');

				if($trigger.prop('checked')!='checked'){
					if(!confirm('이 프리셋을 적용하면 기존 모양 설정을 덮어씁니다. 진행하시겠습니까?')){
						$trigger.prop('checked','');
						return;
					}

					$preset.siblings().removeClass('current');
					$preset.addClass('current');

					if($preset.attr('data-settings')!=''){
						var l1,d1,l2,d2,l3,d3;
						jQuery.getJSON($preset.attr('data-settings'))
							.done(function(data){
								jQuery.each(data,function(l1,d1){
									jQuery.each(d1,function(l2,d2){
										if(l2=='background_image'){
											l3 = 'asset['+l1+'_'+l2+']';
											d3 = d2!=''?$preset.attr('data-directory')+d2:'';
											jQuery('input[name="'+l3+'"]').val(d3);
										}else{
											l3 = 'extra['+l1+'_'+l2+']';
											d3 = d2;
											jQuery('input[name="'+l3+'"]').spectrum('set',d3);
										}
										console.log('PRESET: '+$preset.attr('data-name')+' => '+l3+' updated. ('+d3+')');
									});
								});
							})
							.fail(function(data){
								console.log('PRESET: '+$preset.attr('data-name')+' has no settings'+data);
							})
							.always(function(data){
							});
					}

					if($preset.attr('data-stylesheet')!=''){
						var sl;
						jQuery.ajax($preset.attr('data-stylesheet'))
							.done(function(data){
								sl = 'extra[css]';	
								jQuery('textarea[name="'+sl+'"]').val(data);
								console.log('PRESET: '+$preset.attr('data-name')+' => '+sl+' updated. ('+data+')');
							})
							.fail(function(data){
								console.log('PRESET: '+$preset.attr('data-name')+' has no stylesheet => '+data);
							})
							.always(function(data){
							});
					}
				}else{
					$preset.removeClass('current');
				}
			});
		},

		configureAdvanced: function() {
		}
	}

	jQuery.fn.taogiEditor = function(options) {
		return this.each(function() {
			var taogiEditor = new TaogiEditor(jQuery(this),options);
		});
	};

	jQuery.fn.taogiEditor.defaults = {
		scrollBody: '.taogi-model-wrap',
		sortspeed: 500,
		menubar: 'editor_config_advanced',
		configure: 'editor_config_exterior',
		richeditorOptions: {
			//anchorInputPlaceholder: 'Type a link',
			buttons: ['bold','italic','underline','strikethrough','anchor'],
			diffLeft: 0,
			diffTop: -10,
			updateOnEmptySelection: false,
			firstHeader: 'h1',
			secondHeader: 'h2',
			delay: 1000,
			targetBlank: true	
		},
		fancyboxFilemanagerOptions: {
			width       : 900,
			height      : 600,
			type        : 'iframe',
			fitToView   : true,
			autoSize    : false,
			autoResize  : true
		},
	 	spectrumOptions: {
			chooseText: '선택',
			cancelText: '취소',
			preferredFormat: 'hex',
			allowEmpty: true,
			//showAlpha: true,
			showPalette: true,
			palette: [['black','white','gray','red','green','blue','gold']],
			hideAfterPaletteSelect: true,
			showSelectionPalette: true,
			showInitial: true,
			showInput: true
		}
	}

	jQuery.fn.taogiEditor.setttings = {};
})(jQuery);

jQuery(document).ready(function(e){
// BEGIN CODE

	/***********************************************************************************************
	 * INIT
	 ***********************************************************************************************/
	function str_replace( str, si, mi ){
		str = si != NaN ? str.replace( /__SLIDE_ID__/g, si ) : str;
		str = mi != NaN ? str.replace( /__MEDIA_ID__/g, mi ) : str;
		return str;
	}

	jQuery('#timeline_editor').taogiEditor({
		scrollBody: '.taogi-model-wrap',
		sortspeed: 600,
		hasGNB: 'taogi-gnb',
		menubar: 'taogi-create-menu-bar',
		configure: 'editor_config_exterior'
	});

// END CODE
});
