<?php

namespace Wikimedia\Dodo;

use Wikimedia\IDLeDOM\Node;

class AbstractRange implements \Wikimedia\IDLeDOM\AbstractRange {
	use \Wikimedia\IDLeDOM\Helper\AbstractRange;

	/**
	 * @var Node
	 */
	private $_startContainer;
	/**
	 * @var int
	 */
	private $_startOffset;
	/**
	 * @var int
	 */
	private $_endOffset;
	/**
	 * @var Node
	 */
	private $_endContainer;
	/**
	 * @var bool
	 */
	private $_collapsed;

	/**
	 * @return Node
	 */
	public function getStartContainer() : Node {
		return $this->_startContainer;
	}

	/**
	 * @return int
	 */
	public function getStartOffset() : int {
		return $this->_startOffset;
	}

	/**
	 * @return Node
	 */
	public function getEndContainer() : Node {
		return $this->_endContainer;
	}

	/**
	 * @return int
	 */
	public function getEndOffset() : int {
		return $this->_endOffset;
	}

	/**
	 * @return bool
	 */
	public function getCollapsed() : bool {
		return $this->_collapsed;
	}
}
