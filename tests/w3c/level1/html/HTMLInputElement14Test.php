<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLInputElement14.js.
class HTMLInputElement14Test extends DomTestCase
{
    public function testHTMLInputElement14()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLInputElement14') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vsrc = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'input');
        $nodeList = $doc->getElementsByTagName('input');
        $this->assertSizeData('Asize', 9, $nodeList);
        $testNode = $nodeList->item(7);
        $vsrc = $testNode->src;
        $this->assertURIEqualsData('srcLink', null, null, null, 'submit.gif', null, null, null, null, $vsrc);
    }
}