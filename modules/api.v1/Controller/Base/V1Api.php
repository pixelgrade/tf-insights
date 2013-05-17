<?php namespace tfinsights\api\v1;

class Controller_Base_V1Api extends \app\Puppet implements \mjolnir\types\Controller
{
	use \app\Trait_Controller;
	
	/**
	 * @return array
	 */
	function api_index()
	{
		$method = \app\Server::request_method();
		
		switch ($method)
		{
			case 'GET':
				return $this->get($_GET);
			case 'POST':
				return $this->post($this->payload());
			case 'PUT':
				return $this->put($this->payload());
			case 'PATCH':
				return $this->patch($this->payload());
			case 'DELETE':
				return $this->delete($this->payload());
			default:
				throw new \app\Exception_NotApplicable('Unsuported Request Type');
		}
	}
	
	/**
	 * @return array
	 */
	function get($conf)
	{
		throw new \app\Exception_NotImplemented();
	}
	
	/**
	 * @return array
	 */
	function put($req)
	{
		throw new \app\Exception_NotImplemented();
	}
	
	/**
	 * @return array
	 */
	function patch($req)
	{
		throw new \app\Exception_NotImplemented();
	}
	
	/**
	 * @return array
	 */
	function post($req)
	{
		throw new \app\Exception_NotImplemented();
	}
	
	/**
	 * @return array
	 */
	function delete($req)
	{
		throw new \app\Exception_NotImplemented();
	}
	
	/**
	 * Retrieve payload. If input is provided, it must be valid json, otherwise
	 * an exception will be thrown.
	 * 
	 * @return array|null
	 */
	protected function payload()
	{
		$input = \file_get_contents('php://input');
		
		if (empty($input))
		{
			return null;
		}
		
		$payload = \json_decode($input, true);
		
		// were we able to decode the payload?
		if ($payload !== null)
		{
			return $payload;
		}
		else # failed to decode
		{
			throw new \app\Exception('Invalid JSON payload passed. Decoding failed.');
		}
	}

} # class
