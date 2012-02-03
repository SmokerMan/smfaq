var SmFaq = {
	slideInProgress: false,
	activeId: false,
	objectIdToSlideDown: false,
	FormProgress: false,
	timer: 10,
	slideSpeed: 10
};
SmFaq.jx = {
	getHTTPObject: function () {
		var http = false;
		if (typeof ActiveXObject != 'undefined') {
			try {
				http = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e) {
				try {
					http = new ActiveXObject("Microsoft.XMLHTTP");
				} catch (E) {
					http = false;
				}
			}
		} else if (window.XMLHttpRequest) {
			try {
				http = new XMLHttpRequest();
			} catch (e) {
				http = false;
			}
		}
		return http;
	},
	get: function (url, callback, format) {
		var http = this.init();
		if (!http || !url) return;
		if (http.overrideMimeType) http.overrideMimeType('text/xml');
		if (!format) var format = "text";
		format = format.toLowerCase();
		var now = "uid=" + new Date().getTime();
		url += (url.indexOf("?") + 1) ? "&" : "?";
		url += now;
		http.open("GET", url, true);
		http.onreadystatechange = function () {
			if (http.readyState == 4) {
				if (http.status == 200) {
					var result = "";
					if (http.responseText) result = http.responseText;
					if (callback) callback(result);
				} else {
					alert(http.status);
				}
			}
		}
		http.send(null);
	},
	post: function (url, params, callback, format) {
		var http = this.init();
		if (!http || !url) return;
		if (http.overrideMimeType) http.overrideMimeType('text/xml');
		if (!format) var format = "text";
		format = format.toLowerCase();
		var now = "uid=" + new Date().getTime();
		url += (url.indexOf("?") + 1) ? "&" : "?";
		url += now;
		http.open("POST", url);
		http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		http.setRequestHeader("Content-length", params.length);
		http.setRequestHeader("Connection", "close");
		http.onreadystatechange = function () {
			if (http.readyState == 4) {
				if (http.status == 200) {
					var result = "";
					if (http.responseText) result = http.responseText;
					if (format.charAt(0) == "j") {
						result = result.replace(/[\n\r]/g, "");
						result = eval('(' + result + ')');
					}
					if (callback) callback(result);
				} else {
					alert(http.status);
				}
			}
		}
		http.send(params);
	},
	init: function () {
		return this.getHTTPObject();
	}
}
SmFaq.$ = function (id) {
	return document.getElementById(id)
}
SmFaq.init = function () {
	var patern = /#p(.*[0-9])/;
	var t = patern.exec(location.href);
	if (t) {
		return SmFaq.answer(t[1]);
	} else {
		return;
	}
}
SmFaq.answer = function (inputId) {
	if (SmFaq.slideInProgress) return;
	SmFaq.slideInProgress = true;
	inputId = inputId + '';
	var numericId = inputId.replace(/[^0-9]/g, '');
	var answerDiv = SmFaq.$('a' + numericId);
	if (!answerDiv) return;
	SmFaq.objectIdToSlideDown = false;
	if (!answerDiv.style.display || answerDiv.style.display == 'none') {
		if (SmFaq.activeId && SmFaq.activeId != numericId) {
			SmFaq.$('q' + SmFaq.activeId).className = 'question';
			SmFaq.$('q' + numericId).className = 'question active';
			SmFaq.objectIdToSlideDown = numericId;
			SmFaq.slider(SmFaq.activeId, (SmFaq.slideSpeed * -1));
		} else {
			SmFaq.$('q' + numericId).className = 'question active';
			answerDiv.style.display = 'block';
			SmFaq.slider(numericId, (SmFaq.slideSpeed));
		}
	} else {
		SmFaq.$('q' + numericId).className = 'question';
		SmFaq.slider(numericId, (SmFaq.slideSpeed * -1));
		SmFaq.activeId = false;
	}
}
SmFaq.slider = function (inputId, direction) {
	var obj = SmFaq.$('a' + inputId);
	var contentObj = SmFaq.$('ac' + inputId);
	height = obj.clientHeight + direction;
	rerunFunction = true;
	if (height > contentObj.offsetHeight) {
		height = contentObj.offsetHeight;
		rerunFunction = false;
	}
	if (height <= 1) {
		height = 1;
		rerunFunction = false;
	}
	obj.style.height = height + 'px';
	var topPos = height - contentObj.offsetHeight;
	if (topPos > 0) topPos = 0;
	contentObj.style.top = topPos + 'px';
	if (rerunFunction) {
		setTimeout('SmFaq.slider(' + inputId + ',' + direction + ')', SmFaq.timer);
	} else {
		if (height <= 1) {
			obj.style.display = 'none';
			if (SmFaq.objectIdToSlideDown && SmFaq.objectIdToSlideDown != inputId) {
				SmFaq.$('a' + SmFaq.objectIdToSlideDown).style.display = 'block';
				this.slider(SmFaq.objectIdToSlideDown, SmFaq.slideSpeed);
			} else {
				SmFaq.slideInProgress = false;
			}
		} else {
			SmFaq.activeId = inputId;
			SmFaq.slideInProgress = false;
		}
	}
}
SmFaq.showform = function (show, b) {
	if (b) this.b = b;
	if (show == true) {
		if (this.form) {
			this.form.style.display = 'block';
			this.b.style.display = 'none';
			return;
		}
		var url = this.url + 'showform';
		this.form = document.createElement('div');
		this.form.className = 'loader';
		SmFaq.b.style.display = 'none';
		SmFaq.b.parentNode.insertBefore(this.form, SmFaq.b);

		function response(data) {
			SmFaq.form.className = '';
			SmFaq.form.innerHTML = data;
		}
		SmFaq.jx.get(url, response);
	} else {
		if (SmFaq.FormProgress) return;
		this.form.style.display = 'none';
		SmFaq.b.style.display = 'inline-block';
	}
}
SmFaq.unpublished = function (itemid) {
	var loader = this.$('smfaq-unpub');
	if (loader.className == 'active') {
		loader.removeChild(loader.firstChild);
	}
	loader.className = 'loader';
	var url = this.url + 'admin.show_unpublished&Itemid=' + encodeURIComponent(itemid);

	function response(data) {
		loader.className = 'active';
		loader.innerHTML = data;
	}
	SmFaq.jx.get(url, response);
}
SmFaq.sendform = function (f) {
	if (this.FormProgress) return;
	SmFaq.FormProgress = true;
	this.boxmsg = document.createElement('div');
	this.boxmsg.className = 'loader';
	f.parentNode.parentNode.insertBefore(this.boxmsg, f.parentNode);
	var params = SmFaq.DataForm(f)
	var url = this.url + 'send';

	function response(data) {
		SmFaq.boxmsg.className = '';
		setTimeout("SmFaq.FormProgress = false;", 6000);
		if (data.valid == false) {
			for (fail in data.items) {
				if (f[data.items[fail].name]) {
					if (data.items[fail].name == 'token') {
						f.token.value = data.items[fail].t;
					}
					var msg = SmFaq.boxmsg.appendChild(document.createElement('div'))
					msg.className = 'err-msg';
					msg.innerHTML = data.items[fail].msg;
					if (data.items[fail].name) {
						f[data.items[fail].name].className = 'error';
					}
				}
			}
			SmFaq.msg(SmFaq.boxmsg);
			SmFaq.UnclassError(f);
		} else {
			SmFaq.boxmsg.className = 'ok-msg';
			SmFaq.boxmsg.innerHTML = data.msg;
			SmFaq.msg(SmFaq.boxmsg);
			if (!SmFaq.b) {
				f.style.display = 'none';
				f.question.value = '';
				if (data.captcha) SmFaq.ReloadCapthca();
				SmFaq.f = f;
				SmFaq.Count(f);
				setTimeout("SmFaq.f.style.display = 'block';delete SmFaq.f", 6000);
			} else {
				f.parentNode.parentNode.removeChild(f.parentNode);
				setTimeout("SmFaq.b.style.display = 'inline-block';delete SmFaq.form;", 6000);
			};
		}
	}
	SmFaq.jx.post(url, params, response, 'j');
}
SmFaq.UnclassError = function (f) {
	for (var i = 0; i < f.elements.length; i++) {
		if (f.elements[i].className == 'error') f.elements[i].onchange = function () {
			this.className = ''
		}
	}
}
SmFaq.DataForm = function (f) {
	var P = new Array();
	for (var i = 0; i < f.elements.length; i++) {
		var sP = encodeURIComponent(f.elements[i].name);
		sP += "=";
		if (f.elements[i].type == 'checkbox') {
			sP += encodeURIComponent(f.elements[i].checked == true ? 1 : 0);
		} else {
			sP += encodeURIComponent(f.elements[i].value);
		}
		P.push(sP);
	}
	var params = P.join("&");
	return params;
}
SmFaq.ReloadCapthca = function () {
	this.$('smfaq-captcha').src = this.$('smfaq-captcha').src.replace(/&ac=\d+/g, '&ac=' + String(Math.floor(Math.random() * 100000)));
	this.$('smfaq-form').captcha.value = '';
}
SmFaq.Vote = function (f, v, h) {
	if (typeof (this.voted) !== 'undefined') return;
	var url = SmFaq.url + 'storevote';
	var params = SmFaq.DataForm(f);
	params += '&vote=' + encodeURIComponent(v);
	var hx = f.clientHeight;
	this.voted = SmFaq.$('a' + f.id.value);
	function response(data) {
		f.innerHTML = data;
		if (v == 0) {
			if (h) {
				SmFaq.voted.style.height = SmFaq.voted.clientHeight + 'px';
			} else {
				SmFaq.voted.style.height = SmFaq.voted.clientHeight + f.clientHeight - hx + 'px';
			}
			
			delete SmFaq.voted;
		} else {
			SmFaq.h = f.offsetHeight;
			SmFaq.msg(f);
			if (h) {
				setTimeout("SmFaq.voted.style.height = SmFaq.voted.offsetHeight + 'px';delete SmFaq.voted; delete SmFaq.h", 6000);
			} else {
				setTimeout("SmFaq.voted.style.height = SmFaq.voted.offsetHeight - SmFaq.h + 'px';delete SmFaq.voted; delete SmFaq.h", 6000);
			}
			
			
			return;
		}
	}
	SmFaq.jx.post(url, params, response);
}
SmFaq.Comment = function (f) {
	if (this.FormProgress) return;
	var url = this.url + 'comment';
	var params = this.DataForm(f);
	this.FormProgress = true;
	function response(data) {
		setTimeout("SmFaq.FormProgress = false;", 6000);
		if (data.valid == false) {
			var msg = f.appendChild(document.createElement('div'));
			msg.className = 'err-msg';
			msg.innerHTML = data.msg;
			if (data.s) f.token.value = data.t;
			SmFaq.msg(msg);
			return;
		} else {
			var h = f.offsetHeight;
			SmFaq.a = SmFaq.$('a' + f.id.value);
			f.innerHTML = data.msg;
			SmFaq.h = f.offsetHeight
			h = h - SmFaq.h;
			SmFaq.f = f;
			SmFaq.a.style.height = SmFaq.a.clientHeight - h + 'px';
			setTimeout("SmFaq.Fade('SmFaq.f')", 5000);
			setTimeout("SmFaq.a.style.height = SmFaq.a.clientHeight-SmFaq.h +'px';", 6000);
			return;
		}
	}
	SmFaq.jx.post(url, params, response, 'j');
}
SmFaq.Count = function (f) {
	var c = f.count.value;
	var a = f.question.value.length;
	var counter = this.$('smfaq-counter');
	if (a > c) f.question.value = f.question.value.substring(0, c);
	if (counter) {
		counter.innerHTML = c - a;
		counter.className = c - a > 10 ? '' : 'lastcount';
	}
}
SmFaq.Opacity = function (level, el) {
	el.style.opacity = level;
	el.style.MozOpacity = level;
	el.style.KhtmlOpacity = level;
	el.style.filter = "alpha(opacity=" + (level * 100) + ");";
	if (level < 0.05) {
		el.parentNode.removeChild(el);
		return;
	}
}
SmFaq.msg = function (e) {
	this.m = e;
	setTimeout("SmFaq.Fade('SmFaq.m')", 5000);
}
SmFaq.Fade = function (el) {
	var duration = 1000;
	var steps = 20;
	for (i = 0; i <= 1; i += (1 / steps)) {
		setTimeout("SmFaq.Opacity(" + (1 - i) + ',' + el + ")", i * duration);
	}
}

window.onload = SmFaq.init;