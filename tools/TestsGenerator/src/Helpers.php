<?php

namespace Wikimedia\Dodo\Tools\TestsGenerator;

trait Helpers {
	/**
	 * @param string $input
	 *
	 * @return string
	 */
	protected function snakeToCamel( string $input ) : string {
		$patterns = [ '_' => ' ',
			'-' => ' ',
			'.' => ' ' ];

		return lcfirst( str_replace( ' ',
			'',
			ucwords( strtr( $input,
				$patterns ) ) ) );
	}
}
