;(function(factory){
    if(typeof define === "function" && define.amd) {
        // AMD 模式
        define([ "jquery" ], factory);
    } else {
        // 全局模式
        factory(jQuery);
    }

}(function($){

	function Viewpager(options){  //公用变量的钩子
		this.options = options;
		this.speed = options.speed ? options.speed : 2000;
		this.wordsIsOpen = 0;

		this.vp_body = $('#index');
		this.vp_img = $('.vp_img');
		this.index = 0,  //item索引

		this.resetStyle();
		this.bindBtn();
	}

	Viewpager.prototype.launchImgScroll = function(index) {
		var _this = this;

		if(this.vp_body.css('display') != 'none') {

			if(this.isHeight(this.vp_img.eq(index))) {

				this.vp_img.eq(index).each(function() {
					_this.imgScrollX($(this));
				});
			} else {
				this.vp_img.eq(index).each(function() {
					_this.imgScrollY($(this));
				});
			}
		}
	}

	Viewpager.prototype.imgScrollX = function(obj) {
		var vp_bodyWidth = this.vp_body.width();
		var vp_imgWidth = obj.width();
		var _this = this;

		obj.animate({
			marginLeft: vp_bodyWidth - vp_imgWidth,
		}, _this.speed, function() {
			$(this).animate({
				marginLeft: 0
			}, _this.speed)
		});
	}

	Viewpager.prototype.imgScrollY = function(obj) {
		var vp_bodyHight = this.vp_body.height();
		var vp_imgHeight = obj.height();
		var _this = this;

		obj.animate({
			marginTop: vp_bodyHight - vp_imgHeight,
		}, _this.speed, function() {
			$(this).animate({
				marginTop: 0
			}, _this.speed)
		});
	}

	Viewpager.prototype.bindBtn = function() {
		var _this = this;

		$('#show_info_btn').click(function() {
			if(!$(this).hasClass('btn_toblack')) {
				_this.openWords().open();
			} else {
				_this.openWords().close();
				_this.autoSwitch(_this.speed);
			}
				
		});
	}

	Viewpager.prototype.openWords = function() {
		var _this = this;

		return {
			open: function() {
				clearTimeout(_this.timer)
				_this.wordsIsOpen = 1;
				$('.vp_item .vp_words').eq(_this.index).addClass('words_open').removeClass('words_close');
				$('#show_info_btn').addClass('btn_toblack').removeClass('btn_towhite');
			},
			close: function() {
				$('.vp_item .vp_words').eq(_this.index).removeClass('words_open').addClass('words_close');
				_this.wordsIsOpen = 0;
				_this.autoSwitch(this.speed);	
				$('#show_info_btn').removeClass('btn_toblack').addClass('btn_towhite');
			}
		}
		
	}

	Viewpager.prototype.resetStyle = function() {
		var body = $('body'),
			header = $('header');
		var _this = this;

		this.vp_body.height($(window).height()-header.height()-parseInt(body.css('padding-bottom'))*2 - parseInt(header.css('margin-bottom')));

		$(window).resize(function() {
			_this.vp_body.height($(window).height()-header.height()-parseInt(body.css('padding-bottom'))*2 - parseInt(header.css('margin-bottom')));
		});
		var bodyWidth = this.vp_body.width(),
			bodyHeight = this.vp_body.height();

		this.vp_img.each(function() {
			$(this).css({
				'background-position': 'center',
				'height': '100%',
				'width': '100%',
				'background-repeat': 'no-repeat',
				'background-size': 'cover'
			});
		})
	}

	Viewpager.prototype.isHeight = function(obj) {
		var _this = this;
		var a = _this.vp_body.width() / _this.vp_body.height();

		var src = obj.attr('src');
		var img = new Image();
			img.src = src;
		var imgWidth = img.width,
			imgHeight = img.height;

		var b = imgWidth / imgHeight;

		if(b > a) {
			return 'true';
		} else {
			return false;
		}
	}

	Viewpager.prototype.autoSwitch = function(time){  //自动播放
		var _this = this;

		if(time){
			this._time = time;
			this.timer = setTimeout(function(){
				if(_this.index == _this.vp_img.length-1){
					_this.index = -1;
				}
				_this.index++;
				showContent(_this.index);  //切换item
				changeActiveBtn(_this.index);  //点亮当前item对应的下方圆点
				_this.autoSwitch(time)
			}, time)
		}
	}

	Viewpager.prototype.vpControl = function(){ 
		var _this = this;
		var btnNum = _this.vp_img.length;

		_this.createItemBtn(btnNum); //生成圆点
		showContent(0);  //打开页面后显示第一个item

		this.vp_body.find('#pre').click(function(){  //显示前一个item

			if($('.vp_item .vp_words').eq(_this.index).hasClass('words_open')) {
				_this.openWords().close();
			}
			clearTimeout(_this.timer);  //点击后清除定时器
			if(_this.index == 0){
				_this.index = _this.vp_img.length;
			}
			_this.index--;
			showContent(_this.index);
			changeActiveBtn(_this.index);

			_this.autoSwitch(_this._time); //重新启动定时器
		}) 
		this.vp_body.find('#next').click(function(){  //显示后一个item

			if($('.vp_item .vp_words').eq(_this.index).hasClass('words_open')) {
				_this.openWords().close();
			}
			clearTimeout(_this.timer);
			if(_this.index == _this.vp_img.length-1){
				_this.index = -1;
			}
			_this.index++;
			showContent(_this.index);
			changeActiveBtn(_this.index);

			_this.autoSwitch(_this._time);
		})

		changeActiveBtn(_this.index);
	}

	Viewpager.prototype.createItemBtn = function(btnNum){  //生成下方圆点
		var _this = this;

		for(var i=0; i<btnNum; i++){
			document.getElementById('vp_item_btn_group').innerHTML += '<a class="vp_item_btn" data-num="'+i+'" href="#"></a>';
		}
		$('.vp_item_btn').click(function(){  //点击圆点跳转至其所对应item
			clearTimeout(_this.timer);

			_this.index = this.dataset.num;
			changeActiveBtn(_this.index);
			showContent(_this.index);

			_this.autoSwitch(_this._time);
		})
	}

	function showContent(index){  //切换item
		var itemList = $('.vp_item');

		itemList.fadeOut(500);
		itemList.eq(index).fadeIn(500);
	}

	function changeActiveBtn(index){  //切换圆点状态
		var _this = this;
		var itemBtnList = $('.vp_item_btn');

		itemBtnList.removeClass('active');
		itemBtnList.eq(index).addClass('active');
	}

	$.fn.extend({ viewpager : function(options){

		var vp = new Viewpager(options);
		vp.vpControl(); 

		if(options.auto == true){
			if(options.speed){
				vp.autoSwitch(options.speed);
			} else {
				vp.autoSwitch(2000);
			}
		}

	}});

}));