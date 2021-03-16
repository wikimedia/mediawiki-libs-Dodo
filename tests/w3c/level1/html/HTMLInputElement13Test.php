<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLInputElement13.js.
class HTMLInputElement13Test extends DomTestCase
{
    public function testHTMLInputElement13()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLInputElement13') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vsize = null;
        $doc = null;
        $domImpl = null;
        $hasHTML2 = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'input');
        $domImpl = $doc->implementation;
        $hasHTML2 = $domImpl->hasFeature('HTML', '2.0');
        if (!$hasHTML2) {
            $nodeList = $doc->getElementsByTagName('input');
            $this->assertSizeData('Asize', 9, $nodeList);
            $testNode = $nodeList[0];
            $vsize = $testNode->size;
            $this->assertEqualsData('sizeLink', '25', $vsize);
        }
    }
}