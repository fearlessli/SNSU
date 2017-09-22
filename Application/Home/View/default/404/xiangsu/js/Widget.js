/*
 * 设计页所有类的基类
 *
 * Date : 2016-2-29
 */

"use strict"

define(['b'], function(b) {

var Widget = function() {

    console.log("init Widget");
    console.log(this);
    this._node = this.createWidget();

}

Widget.prototype = {

    constructor : Widget, 

    options : {},

    _dom_object : null,

    // 初始化dom，需要子类去实现
    createWidget : b.noop,

    // 取得当前对象节点
    getNode : function() {
        
        return this._node;

    }

}

return Widget;

// 私有方法
function init() {
    console.log("init");
}

});
