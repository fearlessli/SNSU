/*
 * DataWidget.js，负责所有类的数据操作
 *
 * Date: 2016-3-4
 *
 */

"use strict";

define([
    "b",
    "Chuangkit",
    "Widget",
    ],function(
    b,
    Chuangkit,
    Widget,
    $
){

var DataWidget = function(/*Json*/ data_widget_json) {
    
    // console.log("init DataWidget");
    var _this = this;

    // 初始化
    // 继承上一级的对象方法
    Widget.call(_this);
    init.call(_this, data_widget_json);

}

// prototype
DataWidget.prototype = {
    
    constructor : DataWidget,

    _json : {},

    getJson : function() {

        return this._json;

    },

}

// DataWidget继承于Widget
b.classExtend(DataWidget, Widget);

return DataWidget;

// 私有方法，初始化
function init(data_widget_json) {
    

}

});
