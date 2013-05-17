<?php return array
	(
		'api' => function ($relay, $target)
			{
				$json = \app\CFS::config('mjolnir/layer-stacks')['json'];
				return $json($relay, $target);
			},
	
	); # config
