<?php require 'library/extras.php';

$complete = array
	(
		'base'
	);

$common = array
	(
		'unsorted'
	);

return array
	(
		'root' => 'root/',
		'sources' => 'src/',
		'mode' => 'complete',

	# Complete mode

		'complete-mapping' => \app\index
			(
				$complete,
				$common
			),

	# Targetted mode

		// common files used in targeted mapping
		'targeted-common' => $common,

		// mapping targets to files; if a target is not mapped it won't have
		// any style associated
		'targeted-mapping' => array
			(
				'home' => array
					(
						// empty (just inherit common)
					),
			),

	); # config
