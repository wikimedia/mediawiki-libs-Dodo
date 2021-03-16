<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLAnchorElement04.js.
class HTMLAnchorElement04Test extends DomTestCase
{
    public function testHTMLAnchorElement04()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLAnchorElement04') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vhref = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'anchor');
        $nodeList = $doc->getElementsByTagName('a');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $vhref = $testNode->href;
        $this->assertURIEqualsData('hrefLink', null, null, null, 'submit.gif', null, null, null, null, $vhref);
    }
}