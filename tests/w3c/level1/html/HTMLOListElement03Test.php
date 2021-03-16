<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLOListElement03.js.
class HTMLOListElement03Test extends DomTestCase
{
    public function testHTMLOListElement03()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLOListElement03') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vtype = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'olist');
        $nodeList = $doc->getElementsByTagName('ol');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $vtype = $testNode->type;
        $this->assertEqualsData('typeLink', '1', $vtype);
    }
}