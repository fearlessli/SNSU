/*模态框重置 clearContent*/
var clearContent = function() {
        $(".ui-dialog").empty();
    }
/*置为编辑状态 designNavMenuEdit*/
var MenuEdit = function() {
    $(".buttonConfirm").hide();
    $(".buttonSave").fadeIn(400);
    $(".designNavMenuConfirm").hide();
    $(".designNavMenuEdit").fadeIn(400);
};
/*置为删除确认状态 designNavMenuConfirm*/
var MenuConfirm = function() {
    $(".buttonSave").hide();
    $(".buttonConfirm").fadeIn(400);
    $(".designNavMenuEdit").hide();
    $(".designNavMenuConfirm").fadeIn(400);
};
/*点击保存执行 buttonSave*/
$(".buttonSave").click(function() {
    $(".designNavModal").fadeOut(400);
    $(".designNavMenu").animate({
        left: '-400px',
    }, 400);
});
/*点击确认执行 buttonConfirm*/
$(".buttonConfirm").click(function() {
    $(".designNavModal").fadeOut(400);
    $(".designNavMenu").animate({
        left: '-400px',
    }, 400);
});
/*点击取消执行 buttonCancel*/
$(".buttonCancel").click(function() {
    $(".designNavModal").fadeOut(400);
    $(".designNavMenu").animate({
        left: '-400px',
    }, 400);
});