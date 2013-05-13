<?php return array
	(
		'version' => '1.0',

		'loaders' => array # null = default configuration
			(
//				'dart' => [ 'head' => [ 'loader' ] ],
				'style' => [ 'default.style' => 'base-style' ],
				'javascript' => null,
				'bootstrap' => null,
			),

		// target-to-file mapping
		'mapping' => array
			(
				'home' => array
					(
						'base/foundation',
						'tfinsights'
					),
				'dashboard' => array
					(
						'base/foundation',
						'dashboard'
					),
				'login' => array
					(
						'base/foundation',
						'login'
					),
			),

	); # theme
