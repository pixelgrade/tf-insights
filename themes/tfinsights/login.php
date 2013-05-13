<?
	namespace app;

	/* @var $theme   ThemeView */
	/* @var $lang    Lang */
	/* @var $context Controller_Login */
	/* @var $control Controller_Login */
?>

<? if (\app\Auth::role() === \app\Auth::Guest): ?>

	<h2><?= $lang->idx('title:signin') ?></h2>

	<?= $f = HTML::form($control->action('signin'), 'demo:general')
		->errors_are($errors['mjolnir:access/signin.errors']) ?>

	<div class="form-horizontal">

		<? if (isset($errors['mjolnir:access/signin.errors'], $errors['mjolnir:access/signin.errors']['form'])): ?>
			<ul>
				<? foreach ($errors['mjolnir:access/signin.errors']['form'] as $error): ?>
				<li><?= $error ?></li>
				<? endforeach; ?>
			</ul>
		<? endif; ?>

		<?= $f->text($lang->idx('username'), 'identity') ?>
		<?= $f->password($lang->idx('password'), 'password') ?>
		<?= $f->checkbox($lang->idx('rememberme'), 'rememberme') ?>

		<div class="form-actions">
			<button class="btn btn-primary" <?= $f->mark() ?>>
				<?= $lang->idx('submit') ?>
			</button>
		</div>

	</div>

<? else: # signed in ?>

	<p><?= $lang->idx('signed-in-as', ['name' => Auth::nickname()]) ?></p>

<? endif; ?>