<!DOCTYPE html>
<html lang="cn">
<head>
	<meta charset="UTF-8">
	<title>跳转提示</title>
	<script type="text/javascript" src="/Public/Home/js/jquery.min.js"></script>
	<link rel="stylesheet" type="text/css" href="/Public/Home/css/main.css">
</head>
<body>
<div class="page404">

	<div class="textTitle"><?php if(isset($message)) {?>
		<h2><?php echo($message); ?></h2>
		<?php }else{?>
		<h2><?php echo($error); ?></h2>
		<?php }?>
		<p class="detail"></p>
		<p class="jump">
			页面自动 <a id="href" href="<?php echo($jumpUrl); ?>">跳转</a> 等待时间： <b id="wait"><?php echo($waitSecond); ?></b>
		</p></div>
	<div class="textTitle"></div>
	<br>

	<div class="textTitle">向素</div>
</div>
<script type="text/javascript">
	(function () {
		var wait = document.getElementById('wait'), href = document.getElementById('href').href;
		var interval = setInterval(function () {
			var time = --wait.innerHTML;
			if (time <= 0) {
				location.href = href;
				clearInterval(interval);
			}
			;
		}, 1000);
	})();
</script>
</body>
</html>
