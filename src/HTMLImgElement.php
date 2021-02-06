<?php

declare( strict_types = 1 );
// phpcs:disable MediaWiki.Commenting.FunctionComment.MissingDocumentationPublic
// phpcs:disable MediaWiki.Commenting.PropertyDocumentation.MissingDocumentationPrivate
// phpcs:disable MediaWiki.NamingConventions.LowerCamelFunctionsName.FunctionName
// phpcs:disable PSR12.Properties.ConstantVisibility.NotFound
// phpcs:disable PSR2.Classes.PropertyDeclaration.Underscore
// phpcs:disable Squiz.Scope.MethodScope.Missing

namespace Wikimedia\Dodo;

class HTMLImgElement extends Element {

	/** @var ReflectedAttribute[] */
	private $attribs = [];

	const REFERRER = [
		'type' => [
			"",
			"no-referrer",
			"no-referrer-when-downgrade",
			"same-origin",
			"origin",
			"strict-origin",
			"origin-when-cross-origin",
			"strict-origin-when-cross-origin",
			"unsafe-url"
		],
		'missing' => ''
	];

	/**
	 * TODO make this non-static, only caller is __construct
	 *
	 * @param HTMLImgElement $owner
	 * @param array $spec_array
	 * @return ReflectedAttribute[]
	 */
	private static function buildAttributes( $owner, $spec_array ) {
		$ret = [];

		foreach ( $spec_array as $name => $spec ) {
			if ( !is_array( $spec ) ) {
				$spec = [ 'type' => $spec, 'name' => $name ];
			}
			if ( !isset( $spec['name'] ) ) {
				$spec['name'] = $name;
			}
			$ret[$name] = ReflectedAttributes::getReflectedAttribute( $owner, $spec );
		}

		return $ret;
	}

	private $_prop;

	public function __construct( $doc, $lname, $prefix ) {
		parent::__construct( $doc, $lname, $prefix );

		$this->attribs = self::buildAttributes( $this, [
			'alt' => 'string',
			'src' => 'URL',
			'srcset' => 'string',
			'crossOrigin' => 'CORS',
			'useMap' => 'string',
			'isMap' => 'boolean',
			'height' => [ 'type' => 'unsigned long', 'default' => 0 ],
			'width' => [ 'type' => 'unsigned long', 'default' => 0 ],
			'referrerPolicy' => self::REFERRER,
			/* obsolete */
			'name' => 'string',
			'lowsrc' => 'URL',
			'align' => 'string',
			'hspace' => [ 'type' => 'unsigned long', 'default' => 0 ],
			'vspace' => [ 'type' => 'unsigned long', 'default' => 0 ],
			'longDesc' => 'URL',
			'border' => [ 'type' => 'string', 'is_nullable' => true ]
		] );
	}

	public function __get( $name ) {
		if ( isset( $this->attribs[$name] ) ) {
			return $this->attribs[$name]->get();
		}
	}

	public function __set( $name, $value ) {
		if ( isset( $this->attribs[$name] ) ) {
			$this->attribs[$name]->set( $value );
		}
	}
}

// class HTMLIFrameElement extends HTMLElement
//{
//private $_attr;

//public function __construct ($doc, $lname, $prefix)
//{
//parent::__construct($doc, $lname, $prefix);
//$this->_attr = build_attributes(array(
//'src' => 'URL',
//'srcdoc' => 'string',
//'name' => 'string',
//'width' => 'string',
//'height' => 'string',
//'seamless' => 'boolean',
//'allowFullscreen' => 'boolean',
//'allowUserMedia' => 'boolean',
//'allowPaymentRequest' => 'boolean',
//'referrerPolicy' => REFERRER,
//'align' => 'string',
//'scrolling' => 'string',
//'frameBorder' => 'string',
//'longDesc' => 'URL',
//'marginHeight' => array('type'=>'string', 'is_nullable'=>true),
//'marginWidth' => array('type'=>'string', 'is_nullable'=>true)
//));
//}

//public function __get($name)
//{
//if (isset($this->_attr[$name])) {
//return $this->_attr[$name]->get();
//}
//}
//public function __set($name, $value)
//{
//if (isset($this->_attr[$name])) {
//$this->_attr[$name]->set($value);
//}
//}
//}
