var GLOBAL_PATH = '/Public/';

var pageControl = {
    show: function(pageName, time) {
        var a = "$('"+pageName+"').show().removeClass('hide').addClass('show').css('animation-duration', '"+(time/1000)+"s');";

        this.hide('.main_page', time);
        this.delayHide(time, [$('.main_page')], a);
    },
    hide: function(pageName, time) {
        var page = $(pageName);
        page.removeClass('show').addClass('hide');
    },
    delayHide: function(time, objArr, callback) {
        setTimeout(function(){
            for(var i=0; i<objArr.length; i++) {
                objArr[i].hide();
            }
            eval(callback);
        }, time+70);
    }
}; //控制页面的切换效果


/****



****/

function Images() { //画廊
    this.data = '';
    this.images = $('#images');
    this.layer = $('#layer');
    this.showed_img = $('#layer #showed_img');

    this.hash = new Hash();

    this.model = '<div class="img_item_box"><div class="img_item"><div class="img_info"><h1 class="img_title"></h1><p class="img_desc"></p></div></div></div>';
    // this.model = '<div class="floatItem galleryItem" ><a class="fancybox-gallery" href="{$vo.path}" data-fancybox-group="thumb" title="{$vo.name}" data-description="{$vo.description}"><div class="galleryImg" name="{$vo.id}"  alt="" style="background-image: url({$vo.path});"><div class="galleryImgCover"><div class="galleryImgTitle">{$vo.name}</div><div class="galleryImgDisc">{$vo.description}</div></div></div></a></div>';

    this.changeImage();
}

Images.prototype = {
    constructor: Images,
    init: function(data) { //初始化画廊

        this.data = data;
        this.images.html('');

        var num = this.data.length;
        
        for(var i=0; i<num; i++) {
            this.images.append(this.model);
        }

        this.showImage();
        this.launchLayer();
    },
    showImage: function() { //填充数据
        var data = this.data;
        var i;

        i=0;
        this.images.find('.img_item').each(function() {
            data[i].path = data[i].path.indexOf('Public') != -1 ? data[i].path : GLOBAL_PATH + data[i].path;
            $(this).css('background-image', 'url('+ data[i].path +')').attr('data-index', i).attr('data-src', data[i].path);
            i++;
        });
        i=0;
        this.images.find('.img_title').each(function() {
            $(this).html(data[i++].name);
        });
        i=0;
        this.images.find('.img_desc').each(function() {
            $(this).html(data[i++].description);
        });

        $('.img_info').each(function() {
            if($(this).find('.img_title').html() == '' && $(this).find('.img_desc').html() == '') {
                $(this).hide();
            }
        })
    },
    launchLayer: function() { //开启图片模态预览功能
        var _this = this;

        $('.img_item').click(function() {
            var src = $(this).attr('data-src'),
                index = $(this).attr('data-index');

            _this.deployLayerData(src, index);

            _this.layer
                .show()
                .animate({
                    'opacity': 1
                }, 300);
        });

        this.layer.find('#back').click(function() {
            _this.layer
                .animate({
                    'opacity': 0
                }, 300, function() {
                    $(this).hide();
                })
        })
    },
    deployLayerData: function(src, index) { //为弹出层填充数据
        var _this = this;

        this.showed_img.attr('src',src).attr('data-index', index);
        this.layer.find('#title').html(_this.data[index].name);
        this.layer.find('#desc').html(_this.data[index].description);
    },
    changeImage: function() { //弹出层更新数据
        var prev = $('#layer #prev_p'),
            next = $('#layer #next_p');
        var _this = this;

        prev.click(function() {
            var index = parseInt(_this.showed_img.attr('data-index')) - 1,
                src = _this.data[index] ? _this.data[index].path : 0;

            if(src) {
                _this.deployLayerData(src, index);
            } else {
                _this.warning('the first!');
            }
        });

        next.click(function() {
            var index = parseInt(_this.showed_img.attr('data-index')) + 1,
                src = _this.data[index] ? _this.data[index].path : 0;

            if(src) {
                _this.deployLayerData(src, index);
            } else {
                _this.warning('the last!');
            }
        });
    },
    warning: function(warning) { //弹出层警告（当图片为第一张或最后一张）
        $('#warning')
            .html(warning)
            .show()
            .animate({
                'opacity': 1
            }, 1000, function() {
                $(this).animate({
                    'opacity': 0
                }, 1000, function() {
                    $(this).hide();
                });
            });
    },
    readHash: function(url) { //读取hash以拉取数据（只有读到了hash才能拉取数据）
        this.hash.read(request, this, url, this.init, imgData);
        this.hash.read(request, this, imgInfoRequestURL, this.imgInfoCtrl, {})
    },
    imgInfoCtrl: function(data) {
        var data = data[0];

        if(data.pic_name_visible == '0') {
            $('.img_title').hide();
        }
        if(data.pic_des_visible == '0') {
            $('.img_desc').hide();
        }
    }
}


function Blog() { //博客
    this.data = '';
    this.blog = $('#blog');
    this.blog_main = $('#blog_main');
    this.cid = '';

    this.prev = $('#blog #prev_b');
    this.next = $('#blog #next_b');

    this.hash = new Hash();
    this.PREFIX = 'blog';

    this.model = '<div class="blog_header"><h1 id="article_title"></h1><span id="article_time"></span></div><div id="article_content" class="blog_content"></div>';
}
Blog.prototype = {
    constructor: Blog,
    init: function(data) { //初始化
        this.blog_main.html(this.model);

        this.prev.unbind('click');
        this.next.unbind('click');

        this.showArticle(data);
        this.changeArticle();
    },
    readHash: function(url) { //读取hash以拉取数据（只有读到了hash）才能拉取数据，且应自动拉取最新一篇日志，只需要传栏目id，无须传日志id
        this.hash.read(request, this, url, this.init, blogData)
    },
    showArticle: function(data) { //部署数据
        this.data = data[0];

        var date = new Date(parseInt(this.data.time) * 1000),
            time = date.getFullYear() + '/' + (date.getMonth() + 1)+ '/' + date.getDate();

        this.cid = this.data.column_id;

        this.blog_main.find('#article_title').html(this.data.title);
        this.blog_main.find('#article_time').html(time);
        this.blog_main.find('#article_content').html(this.data.content);
        this.blog_main.attr('data-id', this.data.id);

        this.hash.change(this.PREFIX, this.data.column_id, this.data.id);

    },
    changeArticle: function() { //切换文章（请求时应发送文章id，请求成功后要更改hash）
        var _this = this;
        var data;

        this.prev.click(function() {
            var id = parseInt(_this.blog_main.attr('data-id')) - 1;

            data = {
                "column_id": _this.cid,
                "id": id.toString(),
                "btn": -1
            }
            /*** ajax ***/
            request(_this, blogRequestURL, data, _this.showArticle, blogData2)
        });

        this.next.click(function() {
            var id = parseInt(_this.blog_main.attr('data-id')) + 1;

            data = {
                "column_id": _this.cid,
                "id": id.toString(),
                "btn": 1
            }
            /*** ajax ***/
            request(_this, blogRequestURL, data, _this.showArticle, blogData3)
        });
    },
    warning: function() { //文章在第一篇或最后一篇时的警告

    }
}


function Index() { //首页
    this.data = '';
    this.item_group = $('#index #item_group');

    this.config = {
            auto: true,
            speed: 5000 
        };

    this.hash = new Hash();

    this.model = '<div class="vp_item"><a class="vp_link" href=""><div class="vp_img"></div></a><div class="vp_words"><h1 class="vp_title">This is the first picture.</h1><p class="vp_desc">This is a paragraph.</p></div></div>';
}
Index.prototype = {
    constructor: Index,
    init: function(data) {
        this.data = data;

        if(this.item_group.find('.vp_item').length == 0) {

            for(var i=0; i<this.data.length; i++) {
                this.item_group.append(this.model);
            }

            this.showImage();
            this.launch(this.config);
        }

    },
    readHash: function(url) { //读取hash拉取数据
        this.hash.read(request, this, url, this.init, indexData)
    },
    showImage: function() { //部署数据
        var data = this.data;
        var i;

        i=0;
        this.item_group.find('.vp_img').each(function() {
            $(this).css('background-image', 'url('+ (data[i].path.indexOf('Public') != -1 ? data[i].path : GLOBAL_PATH + data[i].path) +')');
            i++;
        });

        i=0;
        this.item_group.find('.vp_title').each(function() {
            if (data[i].name != '') {
                $(this).html(data[i].name);
            } else {
                $(this).html('<em>暂未添加标题</em>')
            }
            i++;
        });

        i=0;
        this.item_group.find('.vp_desc').each(function() {
            $(this).html(data[i++].description);
        });

        i=0;
        this.item_group.find('.vp_link').each(function() {
            if(data[i++].link != '') {
                $(this).attr('herf', data[i].link);
            } else {
                $(this).css('cursor', 'default').click(function() {
                    return false;
                });
            }
        })
    },
    launch: function(config) { //开启轮播
        $().viewpager(config);
    }
}


function Hash() { //hash控制器
    this.cid = '';
    this.id = '';
    this.lo = window.location;
    this.CONNECTER = '-';
}
Hash.prototype = {
    constructor: Hash,
    change: function(prefix, cid, id) { //改变hash（前缀（前缀没什么卵用，只是用来标识这个页面的类型），页面id，日志id）
        this.cid = cid;
        this.id = id;
        this.prefix = prefix;

        var hash;

        if(this.id) {
            hash = this.prefix + this.cid + this.CONNECTER + this.id;
        } else {
            hash = this.prefix + this.cid;
        }

        this.lo.hash = hash;
    },
    read: function(callback, _this, url, callback2, jiadata) { //读取hash （读取hash后执行的函数（这里全部为ajax请求函数），调用这个函数的方法所在的类（因为在ajax请求函数中要用到），ajax请求地址，ajax请求成功后的回调函数，用于测试的假数据
        var cid = this.lo.hash.replace(/#|-.*|\D/g, ''),
            id = this.lo.hash.replace(/#\w*-?|[^\d*]/g, ''),
			pageClass = this.lo.hash.replace(/#|-|\d*|/g, '');

        var data;

        if(id) { //如果id不是空字符串便判定为请求日志
            data = {
                column_id: cid,
                id: id
            };
            console.log('cid-id')
            callback(_this, url, data, callback2, jiadata);

        } else if (cid != '' && pageClass == 'images') { //如果id为空，cid不为空，且页面类型为images，则判定为请求图片
            data = {gid: cid};
            console.log('cid')
            callback(_this, url, data, callback2, jiadata);

		} else if(cid != '' && pageClass == 'blog') { //如果id为空，cid不为空，且页面类型为blog，则判定为请求最新一篇日志
			data = {column_id: cid};
			callback(_this, url, data, callback2, jiadata);
        } else if(cid == '') { //如果id和cid均为空，则判定为请求首页轮播图
            callback(_this, url, {}, callback2, jiadata);
        }

    }
}


function Nav() { //导航
    this.data = '';
    this.nav = $('#user_nav');
}
Nav.prototype = {
    constructor: Nav,
    request: function(url) { //请求导航数据
        request(this, url, {}, this.init, navData);
    },
    init: function(data) { //初始化导航
        this.data = data;

        this.show();

        this.bind();

		initSite();

        this.responsive(600);
    },
    show: function() { //部署数据
        var data = this.data;
        
        for(var i=0; i<data.length; i++) {
            if(data[i].status == '1' && data[i].visible == '1') {

                this.nav.append('<a data-page="'+type(data[i].type)+'" href="#'+data[i].id+'">'+data[i].name+'</a>');
            }
            //导航里每个链接里所包含的信息有：页面类型，页面id，页面名称。这些必须是第一组被渲染的数据。
        } 

        function type(a) {
            switch(a) {
                case '1': return 'images';
                case '2': return 'blog';
            }
        }
    },
    bind: function() { //绑定导航条链接事件
        $('.user_nav a').click(function() {
            var page = $(this).attr('data-page'); //获取当前链接所对应的页面的类型

            $('.user_nav a').removeClass('active'); //移除导航某链接的active状态
            $(this).addClass('active'); //为当前被点击的链接添加active状态
            pageControl.show('#'+page, 300); //调用页面显示的方法，显示当前被点击的链接所对应的类型的页面

            aHash.change(page, $(this).attr('href').replace(/#/g, '')) //改变hash，因为页面数据的拉取是由hash所驱动的

            if(page == 'images') { //如果是images类型的页面，使用image类的readHash方法请求并部署数据

                aImages.readHash(imgRequestURL);

            } else if(page == 'blog') { //依此类推

                aBlog.readHash(blogRequestURL);

            } else if(page == 'index') {
                aIndex.readHash(indexRequestURL);
            }

            return false;
        });
    },
    responsive: function(width) {
        if($(window).width() <= width) {
            $('#user_nav').addClass('small_nav');
        } else {
            $('#user_nav').removeClass('small_nav');
        }
        $(window).resize(function() {
            if($(this).width() <= width) {
                $('#user_nav').addClass('small_nav').hide();
            } else {
                $('#user_nav').removeClass('small_nav').show();
            }
        });
        $('#nav_btn').click(function() {
            if($('#user_nav').hasClass('small_nav')) {
                $('#user_nav').fadeToggle(400);
            }     
        });
        $('#user_nav a').on('click', function() {
            if($('#user_nav').hasClass('small_nav')) {
                $('#user_nav').fadeOut(400);
            }
        });
    }
}


function Info() {
    this.data = '';

    this.title = $('title');
    this.siteName = $('#site_name');
    this.siteInfo = $('#site_info');
    this.siteLogo = $('#site_logo');
}
Info.prototype = {
    constructor: Info,
    request: function() {
        request(this, infoRequestURL, {}, this.init, {});
    },
    init: function(data) {
        this.data = data[0];
        this.show();
    },
    show: function() {
        var data = this.data;
        var defaultLogo = '/Public/Home/svg/demoAvator.svg';

        this.title.html(data.name);

        this.siteName.html(data.name);

        if(data.sub_title) {
            this.siteInfo.html(data.sub_title);
        } else {
            this.siteInfo.hide();
        }

        if(data.logo_path) {
            this.siteLogo.attr('src', GLOBAL_PATH + data.logo_path);
        } else {
            this.siteLogo.attr('src', defaultLogo);
        }
    }
}


function request(_this, url, _data, callback, jiadata) { //ajax请求函数（使用该函数的类的this，请求路径，发送至后端的数据，请求到数据后的回调函数，测试用假数据）

    console.log(_data)
    var request = $.ajax({
        url: url,
        method: "POST",
        data: _data,
        dataType: "json"
    });

    request.success(function(data) {
        if(data == 0) { //非法请求处理。这里的非法请求返回的数据是什么还不确定，暂时使用0代替。
            ifError();
        } else { //如果请求合法，则调用回调函数
            console.log(data)
            callback.call(_this, data);
        }
    });

    request.error(function(jqXHR, textStatus) { //如果请求失败，则填充假数据，并抛出错误
        callback.call(_this, jiadata);
        throw new Error('Request failed! This is the test data.');
    });
}

function initSite() { //在页面刚刚打开时对页面进行初始化
    var hash = window.location.hash
    var pageClass = hash.replace(/#|-|\d*|/g, ''),
        cid = hash.replace(/#|-.*|\D/g, ''),
        id = hash.replace(/#\w*-?|[^\d*]/g, '');

    var a = $('.user_nav a[href=#'+cid+']'); //读取hash中的cid来选择要进行模拟点击的导航链接
    console.log(pageClass.length);
    console.log(pageClass)
    console.log(cid)
    if (pageClass == 'index' || pageClass == '') { //如果页面类型为index或者空字符串，则将a更换为首页的链接
        cid = '';
        a = $('.user_nav a[data-page=index]');
        console.log(a)
    } 
    a.trigger('click');

}

function ifError() { //错误处理，一般为非法请求或者请求不到数据时显示的信息，以后需要改写成错误页面切换
    alert('404');
}


var aImages = new Images(),
    aBlog = new Blog(),
    aHash = new Hash(),
    aIndex = new Index(),
    aNav = new Nav(),
    aInfo = new Info();

var imgRequestURL = '/ajaxGetGalleryList',
    imgInfoRequestURL = '/ajaxGetOneColumnInfo',
    blogRequestURL = '/ajaxGetOneBlog',
    indexRequestURL = '/ajaxGetIndexPic',
    navRequestURL = '/ajaxGetAllColumn',
    infoRequestURL = '/getCurWebsiteInfo';

aInfo.request(infoRequestURL);
aNav.request(navRequestURL); //初始化导航

setTimeout(function() {
    $('body').animate({
        opacity: 1
    }, 1000);
}, 500);

/*
 *
 * */
