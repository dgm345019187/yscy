function sortByKey(array, key) {
	return array.sort(function(a, b) {
		var x = a[key];
		var y = b[key];
		return((x < y) ? -1 : ((x > y) ? 1 : 0));
	});
}

function GetQueryString(name) {
	var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
	var r = window.location.search.substr(1).match(reg);
	if(r != null) return(r[2]);
	return null;
}

function getSign(signdata, toke) {
	signdata = sortByKey(signdata, 'name');
	var buff = '';
	for(var i in signdata) {
		if(signdata[i].value == undefined) {
			continue;
		}
		buff += signdata[i].name + '=' + signdata[i].value + '&';
	}
	buff += toke;
	return md5(buff);
}

function getLocalData(arrs) {
	var arrs_ = GetQueryString(arrs);
	if(arrs_ != null) {
		return arrs_;
	}
}

//function getAPI() {
//	var api = {
//		URL: 'http://3dshow.kanuvip.com/',
//		LONG: 'ws://3dshow.kanuvip.com:1800'
//	}
//	return api;
//}
Date.prototype.format = function(format) {
	var date = {
		"M+": this.getMonth() + 1,
		"d+": this.getDate(),
		"h+": this.getHours(),
		"m+": this.getMinutes(),
		"s+": this.getSeconds(),
		"q+": Math.floor((this.getMonth() + 3) / 3),
		"S+": this.getMilliseconds()
	};
	if(/(y+)/i.test(format)) {
		format = format.replace(RegExp.$1, (this.getFullYear() + '').substr(4 - RegExp.$1.length));
	}
	for(var k in date) {
		if(new RegExp("(" + k + ")").test(format)) {
			format = format.replace(RegExp.$1, RegExp.$1.length == 1 ?
				date[k] : ("00" + date[k]).substr(("" + date[k]).length));
		}
	}
	return format;
}