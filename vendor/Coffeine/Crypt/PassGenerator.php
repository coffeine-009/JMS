<?php

class Coffeine_Crypt_PassGenerator
{
	///	***	Properties	***	///
	protected $length;
	protected $password;

	///	***	Methods		***///
	public function __construct( /*int*/$Length )
	{
		$this -> length = (int)$Length;
	}

	public function __destruct()
	{
		$this -> length = null;
		$this -> password = null;
	}

	public function getPassword()// : string
	{
		return $this -> password;
	}

	public function generate()
	{
		// set password length
		$pw_length = $this -> length;
		$password = '';
		// set ASCII range for random character generation
		$lower_ascii_bound = 50;          // "2"
		$upper_ascii_bound = 122;       // "z"
			
		// Exclude special characters and some confusing alphanumerics
		// o,O,0,I,1,l etc
		$notuse = array (58,59,60,61,62,63,64,73,79,91,92,93,94,95,96,108,111);
		$i = 0;
		while ($i < $pw_length) {
			mt_srand ((double)microtime() * 1000000);
			// random limits within ASCII table
			$randnum = mt_rand ($lower_ascii_bound, $upper_ascii_bound);
			if (!in_array ($randnum, $notuse)) {
				$password = $password . chr($randnum);
				$i++;
			}
		}

		$this -> password =  $password;
	}
}