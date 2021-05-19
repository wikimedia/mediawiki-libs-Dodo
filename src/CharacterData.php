<?php

declare( strict_types = 1 );

namespace Wikimedia\Dodo;

use Wikimedia\Dodo\Internal\Util;

/******************************************************************************
 * CharacterData.php
 * -----------------
 */
abstract class CharacterData extends Leaf implements \Wikimedia\IDLeDOM\CharacterData {
	// DOM mixins
	use ChildNode;
	use NonDocumentTypeChildNode;

	// Stub out methods not yet implemented.
	use \Wikimedia\IDLeDOM\Stub\CharacterData;

	// Helper functions from IDLeDOM
	use \Wikimedia\IDLeDOM\Helper\CharacterData;

	/** @var string */
	protected $_data;

	/**
	 * @inheritDoc
	 */
	final public function getNodeValue() : ?string {
		return $this->getData();
	}

	/**
	 * @inheritDoc
	 */
	final public function setNodeValue( ?string $value ) : void {
		$this->setData( $value ?? '' );
	}

	/**
	 * @inheritDoc
	 */
	final public function getTextContent() : ?string {
		return $this->getData();
	}

	/**
	 * @inheritDoc
	 */
	final public function setTextContent( ?string $value ) : void {
		$this->setData( $value ?? '' );
	}

	/**
	 * DOMString substringData(unsigned long offset,
	 *               unsigned long count);
	 * The substringData(offset, count) method must run these steps:
	 *
	 *     If offset is greater than the context object's
	 *     length, throw an INDEX_SIZE_ERR exception and
	 *     terminate these steps.
	 *
	 *     If offset+count is greater than the context
	 *     object's length, return a DOMString whose value is
	 *     the UTF-16 code units from the offsetth UTF-16 code
	 *     unit to the end of data.
	 *
	 *     Return a DOMString whose value is the UTF-16 code
	 *     units from the offsetth UTF-16 code unit to the
	 *     offset+countth UTF-16 code unit in data.
	 *
	 * PORT NOTES:
	 * In Domino.js, checking was done to ensure $offset and $count
	 * were integers and not-undefined. Here we just use type hints.
	 *
	 * @param int $offset
	 * @param int $count
	 * @return string
	 */
	public function substringData( int $offset, int $count ) : string {
		if ( $offset > strlen( $this->_data ) || $offset < 0 || $count < 0 ) {
			Util::error( "IndexSizeError" );
		}

		return substr( $this->_data, $offset, $offset + $count );
	}

	/**
	 * void appendData(DOMString data);
	 * The appendData(data) method must append data to the context
	 * object's data.
	 *
	 * PORT NOTES: Again, for the number of arguments, we can just
	 * use the function prototype to check.
	 *
	 * @param string $data
	 */
	public function appendData( string $data ) : void {
		$this->_data .= $data;
		if ( $this->getIsConnected() ) {
			$this->_nodeDocument->_mutateValue( $this );
		}
	}

	/**
	 * void insertData(unsigned long offset, DOMString data);
	 * The insertData(offset, data) method must run these steps:
	 *
	 *     If offset is greater than the context object's
	 *     length, throw an INDEX_SIZE_ERR exception and
	 *     terminate these steps.
	 *
	 *     Insert data into the context object's data after
	 *     offset UTF-16 code units.
	 *
	 * @param int $offset
	 * @param string $data
	 */
	public function insertData( int $offset, string $data ) : void {
		$this->replaceData( $offset, 0, $data );
	}

	/**
	 * void deleteData(unsigned long offset, unsigned long count);
	 * The deleteData(offset, count) method must run these steps:
	 *
	 *     If offset is greater than the context object's
	 *     length, throw an INDEX_SIZE_ERR exception and
	 *     terminate these steps.
	 *
	 *     If offset+count is greater than the context
	 *     object's length var count be length-offset.
	 *
	 *     Starting from offset UTF-16 code units remove count
	 *     UTF-16 code units from the context object's data.
	 *
	 * @param int $offset
	 * @param int $count
	 */
	public function deleteData( int $offset, int $count ) : void {
		$this->replaceData( $offset, $count, '' );
	}

	/**
	 * void replaceData(unsigned long offset, unsigned long count,
	 *          DOMString data);
	 *
	 * The replaceData(offset, count, data) method must act as
	 * if the deleteData() method is invoked with offset and
	 * count as arguments followed by the insertData() method
	 * with offset and data as arguments and re-throw any
	 * exceptions these methods might have thrown.
	 *
	 * @param int $offset
	 * @param int $count
	 * @param string $data
	 */
	public function replaceData( int $offset, int $count, string $data ) : void {
		$curtext = $this->_data;
		$len = strlen( $curtext );

		if ( $offset > $len || $offset < 0 ) {
			Util::error( "IndexSizeError" );
		}

		if ( $offset + $count > $len ) {
			$count = $len - $offset;
		}

		// Fast path
		if ( $offset === 0 && $count === $len ) {
			$this->_data = $data;
			return;
		}

		$prefix = substr( $curtext, 0, $offset );
		$suffix = substr( $curtext, $offset + $count );

		$this->_data = $prefix . $data . $suffix;
		if ( $this->getIsConnected() ) {
			$this->_nodeDocument->_mutateValue( $this );
		}
	}

	/** @inheritDoc */
	public function getLength(): int {
		return strlen( $this->_data );
	}

	/** @inheritDoc */
	public function getData() : string {
		return $this->_data;
	}

	/** @inheritDoc */
	public function setData( ?string $value ) : void {
		$this->replaceData( 0, $this->getLength(), $value ?? '' );
	}

}
