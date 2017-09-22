 //参数分别为 用户头像，用户最新上传的轮播图，要生成的快照的高度，快照生成完毕后所要执行的回调函数，[用户名，用户描述]，[导航]。最后两个参数是可选的。

 //回调函数里面应写ajax，createSnapshoot返回的二进制图像储存在this中。

 function createSnapshot(avatarSrc, bigimgSrc, H, callback, userInfo, nav) {
     var WH = 28 / 19,
         H = H,
         W = H * WH,
         PADDING = W / 100;

     var avatarW, avatarH, bigimgW, bigimgH;

     preLoadImg([avatarSrc, bigimgSrc]).done(function(imgs) {
         imgs[0].width = avatarW = H / 14;
         imgs[0].height = avatarH = H / 14;

         bigimgW = imgs[1].width;
         bigimgH = imgs[1].height;

         var canvas = drawImg(imgs[0], imgs[1], userInfo, nav);
         var dt = canvas.toDataURL('image/jpeg');

         if (callback) {
             callback.call(dt);
         }

     });

     function preLoadImg(imgArr) {
         var imgArr = typeof imgArr == 'object' ? imgArr : [imgArr];
         var imgObjArr = [];
         var loadedImg = 0;
         var callback = function() {};

         for (var i = 0; i < imgArr.length; i++) {
             imgObjArr[i] = new Image();
             imgObjArr[i].crossOrigin = 'Anonymous';
             imgObjArr[i].src = imgArr[i];
             imgObjArr[i].onload = function() {
                 imgLoaded();
             }
             imgObjArr[i].onerror = function() {
                 imgLoaded();
             }
         }

         function imgLoaded() {
             loadedImg++;
             if (loadedImg == imgArr.length) {
                 callback(imgObjArr);
             }
         }

         return {
             done: function(f) {
                 callback = f || callback;
             }
         }
     }

     function drawImg(avatar, bigimg, userInfo, nav) {
         var snapshot = document.createElement('canvas');
         snapshot.height = H;
         snapshot.width = W;
         var sW = snapshot.width,
             sH = snapshot.height;

         var ctx = snapshot.getContext('2d');

         ctx.fillStyle = '#fff';
         ctx.fillRect(0, 0, sW, sH);

         var PADDING2 = PADDING * 3;

         var bsWH = bigimgW / bigimgH,
             bdW = sW - PADDING * 2,
             bdH = sH - PADDING * 2 - avatarH - PADDING2,
             bdWH = bdW / bdH;

         var bsX, bsY, bsW, bsH;

         if (bsWH > bdWH) {
             bsH = bigimgH;
             bsW = bsH * bdWH;
             bsX = (bigimgW - bsW) / 2;
             bsY = 0;
         } else {
             bsW = bigimgW;
             bsH = bsW / bdWH;
             bsX = 0;
             bsY = (bigimgH - bsH) / 2;
         }

         console.log(bsH)

         ctx.drawImage(avatar, PADDING, PADDING, PADDING + avatarW, PADDING + avatarH);
         ctx.drawImage(bigimg, bsX, bsY, bsW, bsH, PADDING, PADDING + PADDING2 + avatarH, bdW, bdH);

         writeWords(ctx, sW, sH, userInfo, nav);
/*         var body = document.getElementsByTagName('body')[0];
         body.insertBefore(snapshot, body.childNodes[0]);*/
         return snapshot;
     }

     function writeWords(ctx, sW, sH, userInfo, nav) {
         var fontSizeB = avatarH / 3,
             fontSizeS = avatarH / 5;

         ctx.fillStyle = '#000';

         ctx.font = 'bold ' + fontSizeB + 'px Microsoft YaHei Light';
         ctx.fillText(userInfo ? userInfo[0] : 'Username', PADDING * 3 + avatarW, PADDING * 3 + (avatarH - fontSizeB - fontSizeS - PADDING) / 2);

         ctx.font = fontSizeS + 'px Microsoft YaHei Light';
         ctx.fillText(userInfo ? userInfo[1] || 'infomation' : 'infomation', PADDING * 3 + avatarW, PADDING * 5 + (avatarH - fontSizeB - fontSizeS - PADDING) / 2);

         ctx.textAlign = 'right';
         if (nav) {
             var navStr = '';
             for (var i = 0; i < nav.length; i++) {
                 navStr += ('     ' + nav[i]);
             }
             ctx.fillText(navStr, sW - PADDING, PADDING * 5 + (avatarH - fontSizeB - fontSizeS - PADDING) / 2);
         } else {
             ctx.fillText('Index Gallery Blog', sW - PADDING, PADDING * 5 + (avatarH - fontSizeB - fontSizeS - PADDING) / 2);
         }
     }
 }