<?php namespace app; return array
/////// Access Protocol Configuration //////////////////////////////////////////
(
	'whitelist' => array # allow
		(
			/**
			 * All access within the application is dictated by this (and
			 * related) configuration files. If checkbox style access control is
			 * required then simply create a protocol that implements something
			 * like an access list or some other structure and pass in here as
			 * to the apropriate role.
			 *
			 * Since protocols are programatic any type of access control logic
			 * can be done. Including "has access if is member of and X happens
			 * while Y is false", which is tricky if not sometimes impossible
			 * with ACLs.
			 *
			 * Allow::relays, Allow::backend, or Ban::relays, etc can be used to
			 * create basic protocols for common access requirements.
			 */
		
			 Auth::Guest => array
				(
					Allow::relays
						(
							'v1-items.api'
						)
						->unrestricted(),
				),
		),

	'blacklist' => array # disallow! (no matter what)
		(
			// empty
		),

	'aliaslist' => array # alias list
		(
			/**
			 * If something is allowed for the alias it will be allowed for
			 * the permission category as well. Does not apply for
			 * exceptions. If there is an exception for an alias the
			 * exception will not apply for the permission category.
			 */

			// examples
			Auth::Guest => [ '+common' ],
			'member'    => [ '+common', '+member' ],
			'admin'     => [ '+common', '+member', '+admin' ],
		),

	'roles' => array # roles in system
		(
			'admin' => 1,
			'member' => 2,
		),

); # config