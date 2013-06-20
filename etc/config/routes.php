<?php

	$target_regex = '[0-9A-Za-z]+';
	$target = ['target' => $target_regex ];
	$id_regex = '[0-9]+';
	$id = ['id' => $id_regex];
	$apimethods = ['GET'];

return array
	(

		'/'
			=> [ 'home.public' ],
	
		// ---- API ---------------------------------------------------------------
	
		# v1
	
		'/api/v1/items'
			=> [ 'v1-items.api', [], $apimethods],
	
		'/api/v1/authors'
			=> [ 'v1-authors.api', [], $apimethods],
	
	);
