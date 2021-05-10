<?php

declare( strict_types=1 );

namespace Wikimedia\Dodo;

use Wikimedia\Dodo\Internal\Util;

/******************************************************************************
 * DOMTokenList.php
 * -----------
 *
 * @link https://dom.spec.whatwg.org/#domtokenlist
 * @package Wikimedia\Dodo
 */
class DOMTokenList {
	/**
	 * Tokens list
	 *
	 * @var bool[]
	 */
	private $tokens = [];

	/**
	 * @param string|string[]|null $tokens List of token(s)
	 */
	public function __construct( $tokens = null ) {
		$this->add( $tokens );
	}

	/**
	 * @param string|string[]|null $tokens
	 *
	 * @return self
	 */
	public function add( $tokens ) : DOMTokenList {
		if ( empty( $tokens ) ) {
			Util::error( "SyntaxError" );
		}

		foreach ( $this->normalize( $tokens ) as $name ) {
			$this->tokens[$name] = true;
		}

		return $this;
	}

	/**
	 *
	 * @param string|string[] $tokens
	 *
	 * @return string[]
	 */
	protected function normalize( $tokens ) : array {
		if ( $tokens === null ) {
			return [];
		}

		if ( is_string( $tokens ) ) {
			$tokens = explode( ' ',
				$tokens );
		}

		$_tokens = [];
		foreach ( $tokens as $name ) {
			$name = trim( $name );
			if ( !empty( $name ) ) {
				$_tokens[] = $name;
			}
		}

		return $_tokens;
	}

	/**
	 * @return string
	 */
	public function toString() : string {
		return $this->value();
	}

	/**
	 * @return string
	 */
	public function value() : string {
		return implode( ' ',
			$this->values() );
	}

	/**
	 * Get all tokens
	 *
	 * @return string[]
	 */
	public function values() : array {
		return array_keys( $this->tokens );
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return $this->value();
	}

	/**
	 * Count the names
	 *
	 * @return int
	 */
	public function length() : int {
		return count( $this->tokens );
	}

	/**
	 * @param int $index Index
	 *
	 * @return string|null
	 */
	public function item( int $index ) {
		$names = $this->values();

		return $names[$index] ?? null;
	}

	/**
	 *
	 * @param string $token
	 * @param string $new_token
	 *
	 * @return bool
	 */
	public function replace( string $token, string $new_token ) : bool {
		$old_token = trim( $token );
		$new_token = trim( $new_token );

		if ( !$this->contains( $old_token ) ) {
			return false;
		}

		$_tokens = $this->tokens;
		$this->reset();

		foreach ( $_tokens as $token_name => $val ) {
			$this->tokens[$token_name === $old_token ? $new_token : $token_name] = true;
		}

		return true;
	}

	/**
	 * @param string $token
	 *
	 * @return bool
	 */
	public function contains( string $token ) : bool {
		return isset( $this->tokens[trim( $token )] );
	}

	/**
	 *
	 * @param string|string[]|null $tokens Name(s)
	 *
	 * @return self
	 */
	public function reset( $tokens = null ) : DOMTokenList {
		$this->tokens = [];

		return $this;
	}

	/**
	 *
	 * @param string $name Name
	 * @param bool|null $force
	 *
	 * @return bool
	 */
	public function toggle( string $name, bool $force = null ) : bool {
		if ( isset( $force ) ) {
			$force ? $this->add( $name ) : $this->remove( $name );

			return $force;
		}

		if ( $this->contains( $name ) ) {
			$this->remove( $name );

			return false;
		}

		$this->add( $name );

		return true;
	}

	/**
	 *
	 * @param string|string[] $tokens
	 *
	 * @return self
	 */
	public function remove( $tokens ) : DOMTokenList {
		foreach ( $this->normalize( $tokens ) as $name ) {
			unset( $this->tokens[$name] );
		}

		return $this;
	}

	/**
	 * TODO implement
	 *
	 * @param string $token
	 *
	 * @return bool
	 */
	public function supports( string $token ) : bool {
		return true;
	}
}
