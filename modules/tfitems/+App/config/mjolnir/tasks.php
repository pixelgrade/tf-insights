<?php return array
	(
		'grab:items' => array
			(
				'description' => array
					(
						'Grab items from TF.'
					),
				'flags' => array
					(
						'url' => array
							(
								'type' => 'text',
								'description' => 'The start url.',
								'short' => 'u',
							),
					
						'class' => array
							(
								'type' => 'text',
								'description' => 'A div class that holds the ids (i.e. <li class="wordpress-template" data-item-id="4720197">).',
								'short' => 'c'
							),
					),
			),
	
		'grab:sales' => array
			(
				'description' => array
					(
						'Grab sales from TF for today.'
					),
				'flags' => array
					(
						
					),
			),
	);
