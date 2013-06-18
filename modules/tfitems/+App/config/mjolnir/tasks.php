<?php return array
	(
		'grab:items' => array
			(
				'category' => 'Task',
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
				'category' => 'Task',
				'description' => array
					(
						'Grab sales from TF for today.'
					),
				'flags' => array
					(
						
					),
			),
		'grab:ratings' => array
			(
				'category' => 'Task',
				'description' => array
					(
						'Grab ratings from TF for today.'
					),
				'flags' => array
					(
						
					),
			),
		'grab:authors' => array
			(
				'category' => 'Task',
				'description' => array
					(
						'Update the authors information.'
					),
				'flags' => array
					(
						
					),
			),
	);
