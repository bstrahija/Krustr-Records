
// usage: log('inside coolFunc', this, arguments);
// paulirish.com/2009/log-a-lightweight-wrapper-for-consolelog/
window.log = function(){
  log.history = log.history || [];   // store logs to an array for reference
  log.history.push(arguments);
  if(this.console) console.log( Array.prototype.slice.call(arguments) );
};



// place any jQuery/helper plugins in here, instead of separate, slower script files.




function str_replace (search, replace, subject, count) {
    // Replaces all occurrences of search in haystack with replace  
    // 
    // version: 1102.614
    // discuss at: http://phpjs.org/functions/str_replace
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Gabriel Paderni
    // +   improved by: Philip Peterson
    // +   improved by: Simon Willison (http://simonwillison.net)
    // +    revised by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
    // +   bugfixed by: Anton Ongson
    // +      input by: Onno Marsman
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +    tweaked by: Onno Marsman
    // +      input by: Brett Zamir (http://brett-zamir.me)
    // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   input by: Oleg Eremeev
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // +   bugfixed by: Oleg Eremeev
    // %          note 1: The count parameter must be passed as a string in order
    // %          note 1:  to find a global variable in which the result will be given
    // *     example 1: str_replace(' ', '.', 'Kevin van Zonneveld');
    // *     returns 1: 'Kevin.van.Zonneveld'
    // *     example 2: str_replace(['{name}', 'l'], ['hello', 'm'], '{name}, lars');
    // *     returns 2: 'hemmo, mars'
    var i = 0,
        j = 0,
        temp = '',
        repl = '',
        sl = 0,
        fl = 0,
        f = [].concat(search),
        r = [].concat(replace),
        s = subject,
        ra = r instanceof Array,
        sa = s instanceof Array;
    s = [].concat(s);
    if (count) {
        this.window[count] = 0;
    }
 
    for (i = 0, sl = s.length; i < sl; i++) {
        if (s[i] === '') {
            continue;
        }
        for (j = 0, fl = f.length; j < fl; j++) {
            temp = s[i] + '';
            repl = ra ? (r[j] !== undefined ? r[j] : '') : r[0];
            s[i] = (temp).split(f[j]).join(repl);
            if (count && s[i] !== temp) {
                this.window[count] += (temp.length - s[i].length) / f[j].length;
            }
        }
    }
    return sa ? s : s[0];
}





// !===> Live URL title
/** ------------------------------------
  /**  Live URL Title Function
  /** -------------------------------------*/

  function liveUrlTitle(title)
  {
  	var foreign_chars = {
  		 223: "ss"
		,224: "a"
		,225: "a"
		,226: "a"
		,229: "a"
		,227: "ae"
		,230: "ae"
		,228: "ae"
		,231: "c"
		,232: "e"
		,233: "e"
		,234: "e"
		,235: "e"
		,236: "i"
		,237: "i"
		,238: "i"
		,239: "i"
		,241: "n"
		,242: "o"
		,243: "o"
		,244: "o"
		,245: "o"
		,246: "oe"
		,249: "u"
		,250: "u"
		,251: "u"
		,252: "ue"
		,255: "y"
		,257: "aa"
		,269: "c" // č
		,275: "ee"
		,291: "gj"
		,299: "ii"
		,311: "kj"
		,316: "lj"
		,326: "nj"
		,353: "s"
		,363: "uu"
		,382: "z" // ž
		,256: "aa"
		,268: "ch"
		,274: "ee"
		,290: "gj"
		,298: "ii"
		,310: "kj"
		,315: "lj"
		,325: "nj"
		,352: "sh"
		,362: "uu"
		,381: "zh"
		,273: "dj" // đ
		,263: "c" // ć
  	};
  	
	var defaultTitle = '';
	var newText = title;

	if (defaultTitle != '')
	{
		if (newText.substr(0, defaultTitle.length) == defaultTitle)
		{
			newText = newText.substr(defaultTitle.length);
		}
	}

	newText = newText.toLowerCase();
	var separator = "-";

	if (separator != "_")
	{
		newText = newText.replace(/\_/g, separator);
	}
	else
	{
		newText = newText.replace(/\-/g, separator);
	}

	// Foreign Character Attempt

	var newTextTemp = '';
	for(var pos=0; pos<newText.length; pos++)
	{
		var c = newText.charCodeAt(pos);

		if (c >= 32 && c < 128)
		{
			newTextTemp += newText.charAt(pos);
		}
		else
		{
			console.log(c);
			if (c in foreign_chars) {
				newTextTemp += foreign_chars[c];
			}
		}
	}

	var multiReg = new RegExp(separator + '{2,}', 'g');

	newText = newTextTemp;

	newText = newText.replace('/<(.*?)>/g', '');
	newText = newText.replace(/\s+/g, separator);
	newText = newText.replace(/\//g, separator);
	newText = newText.replace(/[^a-z0-9\-\._]/g,'');
	newText = newText.replace(/\+/g, separator);
	newText = newText.replace(multiReg, separator);
	newText = newText.replace(/^[-_]|[-_]$/g, '');
	newText = newText.replace(/\.+$/g,'');

	/*if (document.getElementById("slug"))
	{
		document.getElementById("slug").value = "" + newText;
	}*/
	
	return newText
}



function function_exists(function_name) {
 
	// Original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	if (typeof function_name == 'string'){
		return (typeof this.window[function_name] == 'function');
	} else{
		return (function_name instanceof Function);
	}
}




// Noise
(function(c){c.fn.noisy=function(b){b=c.extend({},c.fn.noisy.defaults,b);var d,a;if(JSON&&localStorage.getItem)a=localStorage.getItem(JSON.stringify(b));if(a)d=a;else{a=document.createElement("canvas");if(a.getContext){a.width=a.height=b.size;for(var h=a.getContext("2d"),e=h.createImageData(a.width,a.height),i=b.intensity*Math.pow(b.size,2),j=255*b.opacity;i--;){var f=(~~(Math.random()*a.width)+~~(Math.random()*a.height)*e.width)*4,g=i%255;e.data[f]=g;e.data[f+1]=b.monochrome?g:~~(Math.random()*255);
e.data[f+2]=b.monochrome?g:~~(Math.random()*255);e.data[f+3]=~~(Math.random()*j)}h.putImageData(e,0,0);d=a.toDataURL("image/png");if(d.indexOf("data:image/png")!=0||c.browser.msie&&c.browser.version.substr(0,1)<9&&d.length>32E3)d=b.fallback}else d=b.fallback;JSON&&localStorage&&localStorage.setItem(JSON.stringify(b),d)}return this.each(function(){c(this).css("background-image","url('"+d+"'),"+c(this).css("background-image"))})};c.fn.noisy.defaults={intensity:0.9,size:200,opacity:0.08,fallback:"",
monochrome:false}})(jQuery);





/**
 * Cookie plugin
 *
 * Copyright (c) 2006 Klaus Hartl (stilbuero.de)
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 */

/**
 * Create a cookie with the given name and value and other optional parameters.
 *
 * @example $.cookie('the_cookie', 'the_value');
 * @desc Set the value of a cookie.
 * @example $.cookie('the_cookie', 'the_value', { expires: 7, path: '/', domain: 'jquery.com', secure: true });
 * @desc Create a cookie with all available options.
 * @example $.cookie('the_cookie', 'the_value');
 * @desc Create a session cookie.
 * @example $.cookie('the_cookie', null);
 * @desc Delete a cookie by passing null as value. Keep in mind that you have to use the same path and domain
 *       used when the cookie was set.
 *
 * @param String name The name of the cookie.
 * @param String value The value of the cookie.
 * @param Object options An object literal containing key/value pairs to provide optional cookie attributes.
 * @option Number|Date expires Either an integer specifying the expiration date from now on in days or a Date object.
 *                             If a negative value is specified (e.g. a date in the past), the cookie will be deleted.
 *                             If set to null or omitted, the cookie will be a session cookie and will not be retained
 *                             when the the browser exits.
 * @option String path The value of the path atribute of the cookie (default: path of page that created the cookie).
 * @option String domain The value of the domain attribute of the cookie (default: domain of page that created the cookie).
 * @option Boolean secure If true, the secure attribute of the cookie will be set and the cookie transmission will
 *                        require a secure protocol (like HTTPS).
 * @type undefined
 *
 * @name $.cookie
 * @cat Plugins/Cookie
 * @author Klaus Hartl/klaus.hartl@stilbuero.de
 */

/**
 * Create multiple cookies at once stored in an object, with optional parameters affecting them all.
 *
 * @example $.cookie({ 'the_cookie' : 'the_value' });
 * @desc Set/delete multiple cookies at once.
 * @example $.cookie({ 'the_cookie' : 'the_value' }, { expires: 7, path: '/', domain: 'jquery.com', secure: true });
 * @desc Set/delete multiple cookies with options.
 *
 * @param Object name An object with multiple cookie name-value pairs.
 * @param Object options An object literal containing key/value pairs to provide optional cookie attributes.
 * @option Number|Date expires Either an integer specifying the expiration date from now on in days or a Date object.
 *                             If a negative value is specified (e.g. a date in the past), the cookie will be deleted.
 *                             If set to null or omitted, the cookie will be a session cookie and will not be retained
 *                             when the the browser exits.
 * @option String path The value of the path atribute of the cookie (default: path of page that created the cookie).
 * @option String domain The value of the domain attribute of the cookie (default: domain of page that created the cookie).
 * @option Boolean secure If true, the secure attribute of the cookie will be set and the cookie transmission will
 *                        require a secure protocol (like HTTPS).
 * @type undefined
 *
 * @name $.cookie
 * @cat Plugins/Cookie
 * @author Klaus Hartl/klaus.hartl@stilbuero.de
 */

/**
 * Get the value of a cookie with the given name.
 *
 * @example $.cookie('the_cookie');
 * @desc Get the value of a cookie.
 *
 * @param String name The name of the cookie.
 * @return The value of the cookie.
 * @type String
 *
 * @name $.cookie
 * @cat Plugins/Cookie
 * @author Klaus Hartl/klaus.hartl@stilbuero.de
 */

/**
 * Get the names and values of all cookies for the page.
 *
 * @example $.cookie();
 * @desc Get all the cookies for the page
 *
 * @return an object with the name-value pairs of all available cookies.
 * @type Object
 *
 * @name $.cookie
 * @cat Plugins/Cookie
 * @author Klaus Hartl/klaus.hartl@stilbuero.de
 */


jQuery.cookie = function(name, value, options) {
    if (typeof value != 'undefined'  ||  (name  &&  typeof name != 'string')) { // name and value given, set cookie
        if (typeof name == 'string') {
            options = options || {};
            if (value === null) {
                value = '';
                options.expires = -1;
            }
            var expires = '';
            if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
                var date;
                if (typeof options.expires == 'number') {
                    date = new Date();
                    date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
                } else {
                    date = options.expires;
                }
                expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
            }
            // CAUTION: Needed to parenthesize options.path and options.domain
            // in the following expressions, otherwise they evaluate to undefined
            // in the packed version for some reason...
            var path = options.path ? '; path=' + (options.path) : '';
            var domain = options.domain ? '; domain=' + (options.domain) : '';
            var secure = options.secure ? '; secure' : '';
            document.cookie = name + '=' + encodeURIComponent(value) + expires + path + domain + secure;
        } else { // `name` is really an object of multiple cookies to be set.
          for (var n in name) { jQuery.cookie(n, name[n], value||options); }
        }
    } else { // get cookie (or all cookies if name is not provided)
        var returnValue = {};
        if (document.cookie) {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = jQuery.trim(cookies[i]);
                // Does this cookie string begin with the name we want?
                if (!name) {
                    var nameLength = cookie.indexOf('=');
                    returnValue[ cookie.substr(0, nameLength)] = decodeURIComponent(cookie.substr(nameLength+1));
                } else if (cookie.substr(0, name.length + 1) == (name + '=')) {
                    returnValue = decodeURIComponent(cookie.substr(name.length + 1));
                    break;
                }
            }
        }
        return returnValue;
    }
};




/*
 * jQuery Notify UI Widget 1.4
 * Copyright (c) 2010 Eric Hynds
 *
 * http://www.erichynds.com/jquery/a-jquery-ui-growl-ubuntu-notification-widget/
 *
 * Depends:
 *   - jQuery 1.4
 *   - jQuery UI 1.8 widget factory
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 *
*/
(function(d){d.widget("ech.notify",{options:{speed:500,expires:5E3,stack:"below",custom:false},_create:function(){var a=this;this.templates={};this.keys=[];this.element.addClass("ui-notify").children().addClass("ui-notify-message ui-notify-message-style").each(function(b){b=this.id||b;a.keys.push(b);a.templates[b]=d(this).removeAttr("id").wrap("<div></div>").parent().html()}).end().empty().show()},create:function(a,b,c){if(typeof a==="object"){c=b;b=a;a=null}a=this.templates[a||this.keys[0]];if(c&&c.custom)a=d(a).removeClass("ui-notify-message-style").wrap("<div></div>").parent().html();return(new d.ech.notify.instance(this))._create(b,d.extend({},this.options,c),a)}});d.extend(d.ech.notify,{instance:function(a){this.parent=a;this.isOpen=false}});d.extend(d.ech.notify.instance.prototype,{_create:function(a,b,c){this.options=b;var e=this;c=c.replace(/#(?:\{|%7B)(.*?)(?:\}|%7D)/g,function(f,g){return g in a?a[g]:""});c=this.element=d(c);var h=c.find(".ui-notify-close");typeof this.options.click==="function"&&c.addClass("ui-notify-click").bind("click",function(f){e._trigger("click",f,e)});h.length&&h.bind("click",function(){e.close();return false});this.open();typeof b.expires==="number"&&window.setTimeout(function(){e.close()},b.expires);return this},close:function(){var a=this,b=this.options.speed;this.isOpen=false;this.element.fadeTo(b,0).slideUp(b,function(){a._trigger("close")});return this},open:function(){if(this.isOpen||this._trigger("beforeopen")===false)return this;var a=this;this.isOpen=true;this.element[this.options.stack==="above"?"prependTo":"appendTo"](this.parent.element).css({display:"none",opacity:""}).fadeIn(this.options.speed,function(){a._trigger("open")});return this},widget:function(){return this.element},_trigger:function(a,b,c){return this.parent._trigger.call(this,a,b,c)}})})(jQuery);

