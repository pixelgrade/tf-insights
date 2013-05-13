<?
	namespace app;

	/* @var $theme ThemeView */
	/* @var $lang  Lang */
?>

<div class="container">

	<div class="masthead">
		<ul class="nav nav-pills pull-right">

			<? if (Access::can('dashboard.public')): ?>
				<li>
					<a href="<?= URL::href('dashboard.public') ?>">
						<?= $lang->idx('title:dashboard') ?>
					</a>
				</li>
			<? endif; ?>

			<li>
				<a href="<?= URL::href('home.public') ?>">
					<?= $lang->idx('title:home') ?>
				</a>
			</li>

			<li>
				<? if (\app\Auth::role() === \app\Auth::Guest): ?>
					<a href="<?= \app\URL::href('login.public') ?>">
						<?= $lang->idx('singin') ?>
					</a>
				<? else: # signed in ?>
					<a href="<?= \app\URL::href('login.public', ['action' => 'signout']) ?>">
						<?= $lang->idx('signout') ?>
					</a>
				<? endif; ?>
			</li>

		</ul>
		<h1 class="muted">
			<?= $lang->idx('title:site') ?>
		</h1>
	</div>

	<hr/>

	<?= $entrypoint->render() ?>

</div>
