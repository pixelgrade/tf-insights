<?
	namespace app;

	/* @var $theme   ThemeView */
	/* @var $lang    Lang */
	/* @var $context Controller_Tfinsights */
	/* @var $control Controller_Tfinsights */

	$syspath = Env::key('sys.path');
?>

<div>
	<h2><?= $lang->idx('home-title') ?></h2>
	<?= $lang->idx('home', ['theme' => $syspath.'themes', 'modules' => $syspath.'modules']) ?>
</div>
