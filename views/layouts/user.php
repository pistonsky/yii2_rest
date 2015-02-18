<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta charset="utf-8"/>
	<?= \yii\helpers\Html::csrfMetaTags() ?>
	<title>WoofGame | Admin</title>
	<link rel="stylesheet" href="<?php echo Yii::$app->request->baseUrl;?>/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo Yii::$app->request->baseUrl;?>/css/admin.css">
	<link rel="stylesheet" href="<?php echo Yii::$app->request->baseUrl;?>/css/jquery.dataTables.min.css">
	<link href='//fonts.googleapis.com/css?family=Open+Sans:600,300&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
</head>
<div id="page">
<body>
	<script src="<?php echo Yii::$app->request->baseUrl;?>/js/jquery.min.js" type="text/javascript"></script>
	<script src="<?php echo Yii::$app->request->baseUrl;?>/js/underscore-min.js" type="text/javascript"></script>
	<script src="<?php echo Yii::$app->request->baseUrl;?>/js/backbone-min.js" type="text/javascript"></script>
	<script src="<?php echo Yii::$app->request->baseUrl;?>/js/bootstrap.min.js"></script>
	<script src="<?php echo Yii::$app->request->baseUrl;?>/js/jquery-ui.min.js"></script>
	<script src="<?php echo Yii::$app->request->baseUrl;?>/js/jquery.dataTables.min.js"></script>

	<div class="container-fluid">

		<?php if (!empty(Yii::$app->controller->links)) { ?>
		<ol class="breadcrumb">

			<li><a href="<?php echo Yii::$app->request->baseUrl; ?>/admin">Главная</a></li>

			<?php foreach (Yii::$app->controller->links as $link => $title) { ?>
				<?php if (is_numeric($link)) { ?>
					<li class="active"><?= $title ?></li>
				<?php } else { ?>
					<li><a href="<?php echo Yii::$app->request->baseUrl; ?><?= $link ?>"><?= $title ?></a></li>
			<?php }} ?>
		</ol>
		<?php } ?>
		
		<?php echo $content;?>

	</div>
	
</body>
</div>
</html>