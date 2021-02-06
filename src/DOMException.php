<?php

declare( strict_types = 1 );
// phpcs:disable Generic.Files.LineLength.TooLong
// phpcs:disable Generic.NamingConventions.UpperCaseConstantName.ClassConstantNotUpperCase
// phpcs:disable MediaWiki.Commenting.FunctionComment.MissingDocumentationPrivate
// phpcs:disable MediaWiki.Commenting.FunctionComment.MissingDocumentationPublic
// phpcs:disable MediaWiki.Commenting.FunctionComment.MissingParamTag
// phpcs:disable MediaWiki.Commenting.FunctionComment.MissingReturn
// phpcs:disable MediaWiki.Commenting.FunctionComment.SpacingAfter
// phpcs:disable MediaWiki.Commenting.FunctionComment.WrongStyle
// phpcs:disable MediaWiki.Commenting.PropertyDocumentation.MissingDocumentationPrivate
// phpcs:disable MediaWiki.Commenting.PropertyDocumentation.MissingDocumentationProtected
// phpcs:disable MediaWiki.Commenting.PropertyDocumentation.MissingDocumentationPublic
// phpcs:disable MediaWiki.Commenting.PropertyDocumentation.WrongStyle
// phpcs:disable MediaWiki.NamingConventions.LowerCamelFunctionsName.FunctionName
// phpcs:disable PSR12.Properties.ConstantVisibility.NotFound
// phpcs:disable PSR2.Classes.PropertyDeclaration.Underscore

namespace Wikimedia\Dodo;

/******************************************************************************
 * DOMException.php
 * ----------------
 * (Mostly) implements the WebIDL-1 DOMException interface
 * https://www.w3.org/TR/WebIDL-1/#idl-DOMException*
 */
class DOMException extends \Exception {

	const ERR_CODE_DOES_NOT_EXIST = -1;	/* [Dodo] Errors without Legacy code */
	const INDEX_SIZE_ERR = 1;
	const DOMSTRING_SIZE_ERR = 2;		/* [WEB-IDL-1] No longer present */
	const HIERARCHY_REQUEST_ERR = 3;
	const WRONG_DOCUMENT_ERR = 4;
	const INVALID_CHARACTER_ERR = 5;
	const NO_DATA_ALLOWED_ERR = 6;		/* [WEB-IDL-1] No longer present */
	const NO_MODIFICATION_ALLOWED_ERR = 7;
	const NOT_FOUND_ERR = 8;
	const NOT_SUPPORTED_ERR = 9;
	const INUSE_ATTRIBUTE_ERR = 10;
	const INVALID_STATE_ERR = 11;
	const SYNTAX_ERR = 12;
	const INVALID_MODIFICATION_ERR = 13;
	const NAMESPACE_ERR = 14;
	const INVALID_ACCESS_ERR = 15;
	const VALIDATION_ERR = 16;
	const TYPE_MISMATCH_ERR = 17;		/* [WEB-IDL-1] No longer present */
	const SECURITY_ERR = 18;
	const NETWORK_ERR = 19;
	const ABORT_ERR = 20;
	const URL_MISMATCH_ERR = 21;
	const QUOTA_EXCEEDED_ERR = 22;
	const TIMEOUT_ERR = 23;
	const INVALID_NODE_TYPE_ERR = 24;
	const DATA_CLONE_ERR = 25;

	const ERROR_NAME_TO_CODE = [
		'IndexSizeError' => self::INDEX_SIZE_ERR,
		'HierarchyRequestError' => self::HIERARCHY_REQUEST_ERR,
		'WrongDocumentError' => self::WRONG_DOCUMENT_ERR,
		'InvalidCharacterError' => self::INVALID_CHARACTER_ERR,
		'NoModificationAllowedError' => self::NO_MODIFICATION_ALLOWED_ERR,
		'NotFoundError' => self::NOT_FOUND_ERR,
		'NotSupportedError' => self::NOT_SUPPORTED_ERR,
		'InUseAttributeError' => self::INUSE_ATTRIBUTE_ERR,
		'InvalidStateError' => self::INVALID_STATE_ERR,
		'SyntaxError' => self::SYNTAX_ERR,
		'InvalidModificationError' => self::INVALID_MODIFICATION_ERR,
		'NamespaceError' => self::NAMESPACE_ERR,
		'InvalidAccessError' => self::INVALID_ACCESS_ERR,
		'SecurityError' => self::SECURITY_ERR,
		'NetworkError' => self::NETWORK_ERR,
		'AbortError' => self::ABORT_ERR,
		'URLMismatchError' => self::URL_MISMATCH_ERR,
		'QuotaExceededError' => self::QUOTA_EXCEEDED_ERR,
		'TimeoutError' => self::TIMEOUT_ERR,
		'InvalidNodeTypeError' => self::INVALID_NODE_TYPE_ERR,
		'DataCloneError' => self::DATA_CLONE_ERR,
		'EncodingError' => self::ERR_CODE_DOES_NOT_EXIST,
		'NotReadableError' => self::ERR_CODE_DOES_NOT_EXIST,
		'UnknownError' => self::ERR_CODE_DOES_NOT_EXIST,
		'ConstraintError' => self::ERR_CODE_DOES_NOT_EXIST,
		'DataError' => self::ERR_CODE_DOES_NOT_EXIST,
		'TransactionInactiveError' => self::ERR_CODE_DOES_NOT_EXIST,
		'ReadOnlyError' => self::ERR_CODE_DOES_NOT_EXIST,
		'VersionError' => self::ERR_CODE_DOES_NOT_EXIST,
		'OperationError' => self::ERR_CODE_DOES_NOT_EXIST
	];

	const ERROR_NAME_TO_MESSAGE = [
		'IndexSizeError' => 'INDEX_SIZE_ERR (1): the index is not in the allowed range',
		'HierarchyRequestError' => 'HIERARCHY_REQUEST_ERR (3): the operation would yield an incorrect nodes model',
		'WrongDocumentError' => 'WRONG_DOCUMENT_ERR (4): the object is in the wrong Document, a call to importNode is required',
		'InvalidCharacterError' => 'INVALID_CHARACTER_ERR (5): the string contains invalid characters',
		'NoModificationAllowedError' => 'NO_MODIFICATION_ALLOWED_ERR (7): the object can not be modified',
		'NotFoundError' => 'NOT_FOUND_ERR (8): the object can not be found here',
		'NotSupportedError' => 'NOT_SUPPORTED_ERR (9): this operation is not supported',
		'InUseAttributeError' => 'INUSE_ATTRIBUTE_ERR (10): setAttributeNode called on owned Attribute',
		'InvalidStateError' => 'INVALID_STATE_ERR (11): the object is in an invalid state',
		'SyntaxError' => 'SYNTAX_ERR (12): the string did not match the expected pattern',
		'InvalidModificationError' => 'INVALID_MODIFICATION_ERR (13): the object can not be modified in this way',
		'NamespaceError' => 'NAMESPACE_ERR (14): the operation is not allowed by Namespaces in XML',
		'InvalidAccessError' => 'INVALID_ACCESS_ERR (15): the object does not support the operation or argument',
		'SecurityError' => 'SECURITY_ERR (18): the operation is insecure',
		'NetworkError' => 'NETWORK_ERR (19): a network error occurred',
		'AbortError' => 'ABORT_ERR (20): the user aborted an operation',
		'URLMismatchError' => 'URL_MISMATCH_ERR (21): the given URL does not match another URL',
		'QuotaExceededError' => 'QUOTA_EXCEEDED_ERR (22): the quota has been exceeded',
		'TimeoutError' => 'TIMEOUT_ERR (23): a timeout occurred',
		'InvalidNodeTypeError' => 'INVALID_NODE_TYPE_ERR (24): the supplied node is invalid or has an invalid ancestor for this operation',
		'DataCloneError' => 'DATA_CLONE_ERR (25): the object can not be cloned.',
		'EncodingError' => 'The encoding operation (either encoding or decoding) failed.',
		'NotReadableError' => 'The I/O read operation failed.',
		'UnknownError' => 'The operation failed for an unknown transient reason (e.g. out of memory)',
		'ConstraintError' => 'A mutation operation in a transaction failed because a constraint was not satisfied.',
		'DataError' => 'Provided data is inadequate',
		'TransactionInactiveError' => 'A request was placed against a transaction which is currently not active, or which is finished.',
		'ReadOnlyError' => 'The mutating operation was attempted in a readonly transaction.',
		'VersionError' => 'An attempt was made to open a database using a lower version than the existing version.',
		'OperationError' => 'The operation failed for an operation-specific reason.'
	];

	private $_name;
	private $_err_msg;
	private $_err_code;
	private $_usr_msg;

	/*
	* [WEB-IDL-1] This is the actual constructor prototype.
	* I think the invocation is ridiculous, so we wrap it
	* in an error() function (see utilities.php).
	*/
	public function __construct( ?string $message, ?string $name ) {
		$this->_name = $name ?? "";
		$this->_err_msg  = self::ERROR_NAME_TO_MESSAGE[$this->_name] ?? "";
		$this->_err_code = self::ERROR_NAME_TO_CODE[$this->_name] ?? -1;
		$this->_usr_msg  = $message ?? $this->_err_msg;

		parent::__construct( $this->_err_msg, $this->_err_code );
	}

	public function __toString(): string {
		return __CLASS__ . ': [' . $this->_name . '] ' . $this->_usr_msg;
	}
}
