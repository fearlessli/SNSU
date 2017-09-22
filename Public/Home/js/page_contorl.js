var lrAction = {
		loginBgShow: function() {
			$('#login_area_container').show().removeClass('login_bg_hiding').addClass('login_bg_showing');
			$('.login_area').show().removeClass('to_zero').addClass('to_one');
			
			logoAction.showW();
		},
		loginBgHide: function() {
			$('#login_area_container').removeClass('login_bg_showing').addClass('login_bg_hiding');

			$('.inputBox .warning').html('');
		},
		loginFormShow: function() {
			var callback = "$('#login_area_form').show().removeClass('to_zero').addClass('to_one').find('form input').eq(0).focus()";
			//this.hidden('.login_area_form');
			this.registerFormHide();
			this.forgetHide();
			this.hidden('#change_pwd');
			i.delayHide(350, [$('.login_area_form')], callback);
		},
		loginFormHide: function() {
			$('#login_area_form').removeClass('to_one').addClass('to_zero');

			$('.inputBox .warning').html('');
		},
		registerFormShow: function() {
			var callback = "$('#register_area_form').show().removeClass('to_zero').addClass('to_one').find('form input').eq(0).focus()";
			this.loginFormHide();
			this.forgetHide();
			i.delayHide(370, [$('.login_area_form')], callback);
		},
		registerFormHide: function() {
			$('#register_area_form').removeClass('to_one').addClass('to_zero');
			$('#register1').show();
			$('#register2').hide();
			$('#register_area_form .hint2').removeClass('active');
			$('#register_area_form .hint1').addClass('active');

			$('.inputBox .warning').html('');
		},
		register1Hide: function() {
			$('#register1').removeClass('to_one').addClass('to_zero');
			$('#register_area_form .hint1').removeClass('active');

			$('.inputBox .warning').html('');
		},
		register2Show: function() {
			var callback = "$('#register2').show().removeClass('to_zero').addClass('to_one').find('input').eq(0).focus()";
			i.delayHide(370, [$('#register1')], callback);
			$('#register_area_form .hint2').addClass('active');
		},
		forgetHide: function() {
			$('#forget_pwd').removeClass('to_one').addClass('to_zero');
			$('#forget_pwd .hint1').addClass('active');
			$('#forget_pwd .hint2').removeClass('active');
			$('#get_code').show();
			$('#write_code').hide();

			$('.inputBox .warning').html('');
		},
		forgetShow: function() {
			var callback = "$('#forget_pwd').show().removeClass('to_zero').addClass('to_one').find('form input').eq(0).focus()";
			this.loginFormHide();
			i.delayHide(370, [$('#login_area_form')], callback);
		},
		writecodeShow: function() {
			var callback = "$('#write_code').show().removeClass('to_zero').addClass('to_one').find('input').eq(0).focus()";
			this.hidden('#get_code');
			i.delayHide(370, [$('#get_code')], callback);
			$('#forget_pwd .hint2').addClass('active');
			$('#forget_pwd .hint1').removeClass('active');
		},
		hidden: function(hidden) {
			$(hidden).removeClass('to_one').addClass('to_zero');

			$('.inputBox .warning').html('');
		},
		changepwdShow: function() {
			var callback = "$('#change_pwd').show().removeClass('to_zero').addClass('to_one').find('form input').eq(0).focus()";
			this.hidden('#forget_pwd');
			i.delayHide(370, [$('#forget_pwd')], callback);
		},
		closeForm: function() {
			$('.login_area').removeClass('to_one').addClass('to_zero');
			this.loginBgHide();

			logoAction.showB();
		}
	},
	sbAction = {
		sidebarShow: function() {
			$('#siteinfo').show();
			$('#sidebar_container').show().removeClass('sidebar_container_hiding').addClass('sidebar_container_showing');
			$('#nav_content').removeClass('nav_content_hiding').addClass('nav_content_showing');
			i.closeShow($('#siteinfo_close'));

			logoAction.showW();
		},
		sidebarHide: function() {
			$('#sidebar_container').removeClass('sidebar_container_showing').addClass('sidebar_container_hiding');
			$('#nav_content').removeClass('nav_content_showing').addClass('nav_content_hiding');
		},
		contentShow: function() {
			$('#siteinfo_content').show().removeClass('siteinfo_content_hiding').addClass('siteinfo_content_showing');
		},
		contentHide: function() {
			if($('#siteinfo_content').css('display') != 'none') {
				$('#siteinfo_content').removeClass('siteinfo_content_showing').addClass('siteinfo_content_hiding');
			}
		},
		wordsShow: function(index) {
			if(index == '0') {
				this.close();
			} else {
				var callback = "$('.siteinfo_words[data-index="+index+"]').show().removeClass('to_zero').addClass('to_one')";
				this.wordsHide('1');this.wordsHide('2');this.wordsHide('3');
				i.delayHide(370, [$('.siteinfo_words')], callback);
			}
		},
		wordsHide: function(index) {
			$('.siteinfo_words[data-index='+index+']').removeClass('to_one').addClass('to_zero');
		},
		close: function() {
			var objArr = [$('#siteinfo'), $('#sidebar_container'), $('#siteinfo_content')];
			this.sidebarHide();
			this.contentHide();
			this.wordsHide('1');this.wordsHide('2');this.wordsHide('3');
			i.delayHide(370, [$('.siteinfo_words')]);
			i.delayHide(570, objArr);

			logoAction.showB();
		},
	},
	ssAction = {
		siteShowShow: function() {
			$('#site_show_area').show().removeClass('site_show_hiding').addClass('site_show_showing');
			logoAction.showB();
		},
		siteShowHide: function() {
			$('#site_show_area').removeClass('site_show_showing').addClass('site_show_hiding');
			logoAction.showW();
			i.delayHide(570, [$('#site_show_area')]);
		},
		anotherBgShow: function() {
			$('#title_another_bg').addClass('title_anotherBg_showing');
		},
		anotherBgHide: function() {
			$('#title_another_bg').removeClass('title_anotherBg_showing');
		}
	},
	logoAction = {
		showW: function() {
			$('#logo_w').show().removeClass('to_zero').addClass('to_one');$('header .logo_area').css('color','#fff');
			$('#logo_b').show().addClass('to_zero').removeClass('to_one');$('header .logo_area');
			i.delayHide(370, [$('#logo_b')]);
		},
		showB: function() {
			$('#logo_b').show().removeClass('to_zero').addClass('to_one');$('header .logo_area').css('color','#333');
			$('#logo_w').show().addClass('to_zero').removeClass('to_one');$('header .logo_area');
			i.delayHide(370, [$('#logo_w')]);
		}
	},
	i = {
		delayHide: function(time, objArr, callback) {
			setTimeout(function(){
				for(var i=0; i<objArr.length; i++) {
					objArr[i].hide();
				}
				eval(callback);
			}, time);
		},
		closeShow: function(obj) {
			obj.removeClass('close_hiding').addClass('close_showing');
		},
		closeHide: function(obj) {
			obj.removeClass('close_showing').addClass('close_hiding');
		}
	};


var check = {
		emailFormat: function(obj, error) {
			var re = /^\w+(\.\w+)*@(\w)+((\.\w+)+)$/;
			var is = re.test(obj.value);
			
			if(is) {
				error.call(obj, 1);
				return true;
			} else {
				error.call(obj);
				return false;
			}
		},
		pwdFormat: function(obj, error) {
			var re = /^[^\s*].{5,18}$/;
			var is = re.test(obj.value);

			if(is) {
				error.call(obj, 1);
				return true;
			} else {
				error.call(obj);
				return false;
			}
		},
		emailExist: function(obj, error) {
			/*ajax*/
			error.call(obj);
//				error.call(obj, 1);
		},
		login: function(obj, email, pwd, error) {
			/*ajax*/
			var email = $('#login_email').val();
			var password = $('#login_password').val();
			$.post('/User/doLogin', {email: email, password: password}, function (data) {

				console.log(email + ' ' + password)
				console.log(data)

				if (data == 1) {
					location.href = '/User/index';	
				} else {
					error.call(obj);
				}
			})
		},
		authCode: function(obj) {
			/*ajax*/
			var email = $('#reg_email').val();
			var password = $('#reg_password').val();
			var verify_code = $('#reg_verify_code').val();
			console.log(email+' '+password+' '+verify_code)

			$.post('/User/doReg', {email: email, password: password, verify_code: verify_code}, function (data) {

				console.log(data)
				$('.inputBox .warning').html('');

				if (data == 2) {
					error.authCodeError.call(obj[1]);
				} else if (data.status == 1) {
					lrAction.register1Hide();
					lrAction.register2Show();
				} else if(data.status == 0) {
					error.emailRepeat.call(obj[0]);
				}
			})
		},
		eAuthCode: function(obj, error) {
			/*ajax*/
			error.call(obj);
		},
		pwdSame: function(pwd1, pwd2, error) {
			if(pwd1.value == pwd2.value) {
				error.callback(pwd2, 1);
				return true;
			} else {
				error.call(pwd2);
				return false;
			}
		},
		username: function(obj, error) {
			var re = /^\w{2,15}$/g;
			var is = re.test(obj.value);

			if(is) {
				error.call(obj, 1);
				return true;
			} else {
				error.call(obj);
				return false;
			}
		},
		userdesc: function(obj, error) {
			var re = /^\w{2,15}$/g;
			var is = re.test(obj.value);

			if(is) {
				error.call(obj, 1);
				return true;
			} else {
				error.call(obj);
				return false;
			}
		}
	},
	error = {
		username: function(e) {
			if(e) {
				$(this).parent().find('.warning').empty();
			} else {
				$(this).parent().find('.warning').html('用户名格式不正确');
				console.log('username is not correct.')
			}
		},
		userdesc: function(e) {
			if(e) {
				$(this).parent().find('.warning').empty();
			} else {
				$(this).parent().find('.warning').html('请填写个人描述');
				console.log('userdesc is not correct.')
			}
		},
		emailFormat: function(e) {
			if(e) {
				$(this).parent().find('.warning').html('');
				console.log('email is ok.')
			} else {
				$(this).parent().find('.warning').html('邮箱格式不正确');
				console.log('this is not a email addr.')
			}
		},
		pwdFormat: function(e) {
			if(e) {
				$(this).parent().find('.warning').html('');
			} else {
				$(this).parent().find('.warning').html('密码格式不正确');				
				console.log('password isn\'t up to the standard.')
			}
		},
		authCodeError: function(e) {
			if(e) {
				$(this).parent().find('.warning').html('');
			} else {
				$(this).parent().find('.warning').html('验证码不正确');
				console.log('auth code error.')
			}
		},
		loginError: function(e) {
			if(e) {
				$(this).parent().find('.warning').html('');
			} else {
				$(this).parent().find('.warning').html('邮箱或密码错误');
				console.log('username or password is not correct.')
			}
		},
		emailNotExist: function(e) {
			if(e) {
				$(this).parent().find('.warning').html('');
			} else {
				$(this).parent().find('.warning').html('该邮箱不存在');
				console.log('the email is not exist.')
			}
		},
		pwdNotSame: function(e) {
			if(e) {
				$(this).parent().find('.warning').html('');
			} else {
				$(this).parent().find('.warning').html('两次输入密码不一致');
				console.log('the two passwords are not same.')
			}
		},
		eAuthCodeIncorrect: function(e) {
			if(e) {
				$(this).parent().find('.warning').html('');
			} else {
				$(this).parent().find('.warning').html('验证码不正确');
				console.log('eAuthCode is incorrect.')
			}
		},
		emailRepeat: function(e) {
			if(e) {
				$(this).parent().find('.warning').html('');
			} else {
				$(this).parent().find('.warning').html('这个邮箱已经被注册过');
			}
		}
	},
	input = {
		login: {
			email: document.forms.loginForm.email,
			pwd: document.forms.loginForm.pwd
		},
		register: {
			email: document.forms.registerForm1.email,
			pwd: document.forms.registerForm1.pwd,
			authcode: document.forms.registerForm1.authcode,
			username: document.forms.registerForm2.username,
			userdesc: document.forms.registerForm2.userdesc
		},
		fetchPwd: {
			email: document.forms.getCode.email,
			authcode: document.forms.writeCode.authcode
		},
		changePwd: {
			first: document.forms.changePwd.first,
			second: document.forms.changePwd.second
		}
	};


//site show
/*$('#title_area').click(function() {
	ssAction.siteShowShow();
	ssAction.anotherBgShow();
});
$('#close_site_show').click(function() {
	ssAction.siteShowHide();
	ssAction.anotherBgHide()
});*/
/*$('#play_btn').click(function() {
	var videoObj = document.getElementById('the_video');
	videoObj.play();
	$('#video_layer').hide();
	$(this).hide();
	videoObj.onended = function() {
		$('#video_layer').show();
		$('#play_btn').show();
	}
	return false;
});*/

//site info
$('#logo_area').click(function() {
	sbAction.sidebarShow();
	
});
$('#nav_content a').click(function() {
	if($(this).attr('data-index') != '0') {
		sbAction.contentShow();
	}
	sbAction.wordsShow($(this).attr('data-index'));
	return false;
});
$('#siteinfo_close').click(function() {
	sbAction.close();
	i.closeHide($(this));
	
});

//login && register
$('form').submit(function() {
	return false;
});
$('#login_btn, #login_switch').click(function() {
	if($('#login_area_container').hasClass('login_bg_hiding'))
		$('.login_area_form').hide();
	lrAction.loginBgShow();
	lrAction.loginFormShow();

	return false;
});
$('#register_btn, #register_switch').click(function() {
	if($('#login_area_container').hasClass('login_bg_hiding'))
		$('.login_area_form').hide();
	lrAction.loginBgShow();
	lrAction.registerFormShow();
	
	return false;
});

$('#forget_pwd_link').click(function() {
	lrAction.forgetShow();
});

$('.login_area_header .close').click(function() {
	closeTheTopArea();
});

function closeTheTopArea() {
	var objArr = [$('#login_area_container'), $('.login_area')];

	lrAction.closeForm();
	i.delayHide(370, objArr);
}


/* need to checkout */

function whenBlurCheck(obj, check, args) {
	$(obj).blur(function() {
		check.apply(this, args);
	})
}

whenBlurCheck(input.register.email, check.emailFormat, [input.register.email, error.emailFormat])
whenBlurCheck(input.register.pwd, check.pwdFormat, [input.register.pwd, error.pwdFormat])
// whenBlurCheck(input.register.authcode, check.authCode, [input.register.authcode, error.authCodeError])
whenBlurCheck(input.register.username, check.username, [input.register.username, error.username])
whenBlurCheck(input.register.userdesc, check.userdesc, [input.register.userdesc, error.userdesc])
whenBlurCheck(input.fetchPwd.authcode, check.eAuthCode, [input.fetchPwd.authcode, error.eAuthCodeIncorrect])
whenBlurCheck(input.changePwd.first, check.pwdFormat, [input.changePwd.first, error.pwdFormat])
whenBlurCheck(input.changePwd.second, check.pwdSame, [input.changePwd.first, input.changePwd.second, error.pwdNotSame])

$('#l_submit').click(function() {
	var e = input.login.email.value,
		p = input.login.pwd.value;

	if(check.login(input.login.email, e, p, error.loginError)) {
		closeTheTopArea();
	}
});

$('#r_submit1').click(function() {
	var e = input.register.email,
		p = input.register.pwd,
		a = input.register.authcode;

	if(check.emailFormat(e, error.emailFormat) && check.pwdFormat(p, error.pwdFormat)) {
		 check.authCode([e, a])
		
	}
});
$('#r_submit2').click(function() {
	var u = input.register.username,
		u2 = input.register.userdesc;
	var nickname = $('#nickname').val(),
		userdesc = $('#userdesc').val();

	if(check.username(u, error.username) && check.userdesc(u2, error.userdesc)) {

		$.post('/User/setNickName', {nickname: nickname}, function (data) {

			console.log(data);
			
			if (data == 1) {
				closeTheTopArea();
			} else if (data == 0) {
				alert('注册失败');
			}
		});
		$.post('/User/setIntro', {intro: userdesc}, function (data) {

			console.log(data);
			
			if (data == 1) {
				console.log('set intro success.')
			} else if (data == 0) {
				console.log('set intro fail.')
			}
		});
	}
});

$('#f_submit1').click(function() {
	var e = input.fetchPwd.email;

	if(check.emailExist(e, error.emailNotExist)) {
		lrAction.writecodeShow();
	}
});
$('#f_submit2').click(function() {
	var a = input.fetchPwd.authcode;

	if(check.eAuthCode(a, error.eAuthCodeIncorrect)) {
		lrAction.changepwdShow();
	}
})

$('#c_submit').click(function() {
	var p1 = input.changePwd.first,
		p2 = input.changePwd.second;

	if(check.pwdSame(p1, p2, error.pwdNotSame) && check.pwdFormat(p1, error.pwdFormat)) {
		closeTheTopArea();
	}
});


;(function(){

	$('#siteinfo').height($('body').height() - $('#footer_bottom').height() - parseInt($('#siteinfo').css('border-top'))*3);

	$(window).resize(function() {
		$('#siteinfo').height($('body').height() - $('#footer_bottom').height() - parseInt($('#siteinfo').css('border-top'))*3);
	});
})();


(function() {
	var colorList = [
		'#ff5100',
		'#ff9200',
		'#ffff00',
		'#5cff00',
		'#00ffef',
		'#00d2ff',
		'#0067ff',
		'#0021ff',
		'#8900ff',
		'#e200ff',
		'#ff006e',
		'#ff0000'
	]
	var zero = document.getElementById('zero');
	var moveTimer;
	var b, c;
	
	isStop(300);
	$(document).mousemove(function(e){
		b = e.pageX;
	});	

	function changeColor() {
		clearTimeout(moveTimer);
		moveTimer = setTimeout(function() {
			zero.style.fill = colorList[Math.floor(Math.random()*colorList.length)];
			changeColor();
		}, 1000);
	}

	function isStop(time) {
		c = b;
		setTimeout(function() {
			if(c == b) {
				changeColor()
			}
			isStop(time);
		}, time)
	}
})()