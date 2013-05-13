<?php return array
	(
		#
		# Form standards are used to autoconfigure form objects into a specific
		# use cases.
		#

		'form.standards' => array
			(
				'demo:barebone' => function (\mjolnir\types\HTMLForm $form)
					{
						return $form
							->addfieldtemplate(':field');
					},
				'demo:general' => function (\mjolnir\types\HTMLForm $form)
					{
						return $form
							->adderrorrenderer
								(
									function (array $errors = null)
									{
										if ($errors)
										{
											$out = '<ul>';
											foreach ($errors as $error)
											{
												$out .= "<li>$error</li>";
											}

											return $out.'</ul>';
										}
										else # no errors
										{
											return '';
										}
									}
								)
							->addfieldtemplate
								(
									'<div class="control-group"><label class="control-label" for=":id">:label</label><div class="controls">:field :errors</div></div>'
								);
					},
			),
	);
