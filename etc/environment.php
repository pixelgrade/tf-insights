<?php return array
	(
		# $modpath, $vdrpath, $syspath ensured by initialization

		'modules' => array
			(
				// project modules
				$modpath.'api.v1' => 'tfinsights\api\v1',
				$modpath.'tfitems'    => 'tfinsights\items',

			// ---- Plugins ---------------------------------------------------

				// no plugins

			// ---- Libraries -------------------------------------------------

				// legacy code support
				$vdrpath.'mjolnir/legacy'     => 'mjolnir\legacy',

				// profiling
				$vdrpath.'mjolnir/profile'    => 'mjolnir\profile',

				// mjolnir modules
				$vdrpath.'mjolnir/access'     => 'mjolnir\access',
				$vdrpath.'mjolnir/base'       => 'mjolnir\base',
				$vdrpath.'mjolnir/foundation' => 'mjolnir\foundation',
				$vdrpath.'mjolnir/html'       => 'mjolnir\html',
				$vdrpath.'mjolnir/database'   => 'mjolnir\database',
				$vdrpath.'mjolnir/cache'      => 'mjolnir\cache',
				$vdrpath.'mjolnir/theme'      => 'mjolnir\theme',
				$vdrpath.'mjolnir/backend'    => 'mjolnir\backend',
				$vdrpath.'mjolnir/cfs'        => 'mjolnir\cfs',
				$vdrpath.'mjolnir/types'      => 'mjolnir\types',
				$vdrpath.'mjolnir/librarian'  => 'mjolnir\librarian',
				$vdrpath.'mjolnir/testing'    => 'mjolnir\testing',
			),

		'namespaces' => array
			(
				// empty
			),

		'themes' => array
			(
				// explicit themes; themes can also just be embeded in modules,
				// in which case there's no need for them to appear here.

				'main-theme' => $syspath.'themes/tfinsights/',
			),

	); # config
