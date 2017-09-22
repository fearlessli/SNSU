/*
 * chuangkit公共js，所以页面必须引用
 * 
 * Date: 2015-12-25
 *
 */

"use strict";

define(['jquery'], function($){

// 禁止网页被iframe引用
if(top.location != self.location) {

    top.location = self.location;     

}

var b = {
    
    // 设置Cookie
    setCookie : function( c_name, value, expiredays ) {

        var exdate = new Date();

        exdate.setDate(exdate.getDate()+expiredays);

        document.cookie = c_name + "=" + escape(value) + ((expiredays==null) ? "" : ";expires=" + exdate.toGMTString());

    },

    // 取得Cookie
    getCookie : function( c_name ) {

        if (document.cookie.length > 0) {

            c_start = document.cookie.indexOf(c_name + "=");

            if (c_start != -1){ 

                c_start = c_start + c_name.length+1;
                c_end = document.cookie.indexOf(";",c_start);

                if (c_end == -1) {

                    c_end = document.cookie.length;

                }

                return unescape(document.cookie.substring(c_start,c_end));

            } 

        }

        return ""

    },

    // 空函数，函数占位
    noop : function(){},

    isNull : function(instance) {
        return instance == undefined || instance == null;
    },

    // 数组按数字排序
    sortByNum : function(arr) {

        if(typeof arr != "object") return;

        arr.sort(function(a, b){

            return a - b; 

        });

    },

    // 判断字符串的字节长度（字母一个字符，汉字两个字符）
    byteLength : function(str) {

        if(typeof str != "string") return;

        var length = 0;
        var str_length = str.length;
        var char_code;

        for(var i = 0;i < str_length;i++) {

            char_code = str.charCodeAt(i);

            if(char_code >= 0 && char_code <= 128) {
                
                length++;

            } else {
                
                length += 2;
            
            }

        }

        return length;

    },

    /**
     * 类继承   子类的prototype的prototype指向父类的prototype
     * 
     * @param   {function}  sub_class       子类 
     * @param   {function}  super_class     父类
     *
     *
     */
    classExtend : function(sub_class, super_class) {
        
        // 只传一个参数，父类为object
        if(super_class === undefined) {
           
            super_class = Object;    

        }

        sub_class.prototype.__proto__ = super_class.prototype;
        // 让子类的super_class属性指向父类的prototype，方便调用
        sub_class.prototype.super_class = super_class.prototype;

        // 让子类继承父类的对象方法
        sub_class.prototype.super_init = function() {
            
            var cur_class = this.super_class;
            if(cur_class == undefined || cur_class === Object.prototype) {
                
                return;

            }

            cur_class.constructor.apply(this, arguments);

        }

    },

    // 创建节点
    createNode : function(node_name, node_class) {

        var node = document.createElement(node_name); 

        node.setAttribute("class", node_class);

        return node;

    },

    // 创建svg节点
    createSvgNode : function(node_name) {
        
        var svg_node = document.createElementNS("http://www.w3.org/2000/svg", node_name);

        return svg_node;

    },
        
    // 获取地址栏参数
    getQueryString : function(name) {

        var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);

        if(r!=null)return  unescape(r[2]); return null;

    },

    // 当前时间
    now : function() {

        return new Date().getTime();

    },

    /**
     * 频率控制 返回函数连续调用时，func 执行频率限定为 次 / wait
     * 
     * @param  {function}   func      传入函数
     * @param  {number}     wait      表示时间窗口的间隔
     * @param  {object}     options   如果想忽略开始边界上的调用，传入{leading: false}。
     *                                如果想忽略结尾边界上的调用，传入{trailing: false}
     * @return {function}             返回客户调用函数   
     */
    throttle : function(func, wait, options) {

        var context, args, result;
        var timeout = null;
        // 上次执行时间点
        var previous = 0;

        if (!options) options = {};

        // 延迟执行函数
        var later = function() {

            // 若设定了开始边界不执行选项，上次执行时间始终为0
            previous = options.leading === false ? 0 : b.now();
            timeout = null;
            result = func.apply(context, args);

            if (!timeout) context = args = null;

        };

        return function() {

            var now = b.now();

            // 首次执行时，如果设定了开始边界不执行选项，将上次执行时间设定为当前时间。
            if (!previous && options.leading === false) previous = now;

            // 延迟执行时间间隔
            var remaining = wait - (now - previous);
            context = this;
            args = arguments;

            // 延迟时间间隔remaining小于等于0，表示上次执行至此所间隔时间已经超过一个时间窗口
            // remaining大于时间窗口wait，表示客户端系统时间被调整过
            if (remaining <= 0 || remaining > wait) {

                clearTimeout(timeout);

                timeout = null;
                previous = now;
                result = func.apply(context, args);

                if (!timeout) context = args = null;

            //如果延迟执行不存在，且没有设定结尾边界不执行选项
            } else if (!timeout && options.trailing !== false) {

                timeout = setTimeout(later, remaining);

            }

            return result;

        };
    },

}

    // ajax预处理
$.ajaxPrefilter("json jsonp", function( options, originalOptions, jqXHR ) {

    var rquestion = /\?/;
    options.url += ( rquestion.test( options.url ) ? "&" : "?" ) + "_dataType="+originalOptions.dataType;
    var client_succes = originalOptions.success;

	options.success = function(response, textStatus) {

    	response = eval(response);
    	var header = response.header;
    	var body = response.body;

    	if (header.code == 1) {

    		if ($.isFunction(client_succes))

	    		client_succes(body, textStatus);

    	} else {

        	if (originalOptions.error) {

        		originalOptions.error(response);

        	} else {

        		var msg = header.msg ? header.msg : header.EXCE;

  				if (console && console.log) {
  					// console.log(msg);
  				}

    		}

    	}

    	if (originalOptions.all) {

    		originalOptions.all(response);

    	}

    };

});

return b;

});
