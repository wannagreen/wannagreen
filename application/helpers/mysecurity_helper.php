<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Generate a special hash from a sha1 hashed string
 *
 * @access	public
 * @return	the 'special' hash
 */
if ( ! function_exists('crypt_password'))
{
	function crypt_password($password)
	{
		$hash = sha1($password);
		$inverted = '';
		$j = 0;
		for($i=0 ; $i < strlen($hash) ; ++$i)
		{
			$inverted .= substr($hash,$j+1,1);
			$inverted .= substr($hash,$j,1);
			$j += 2;
		}
		return $inverted;
	}
}
