<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLElement27.js.
class HTMLElement27Test extends DomTestCase
{
    public function testHTMLElement27()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLElement27') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vid = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'element');
        $nodeList = $doc->getElementsByTagName('noscript');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $vid = $testNode->id;
        $this->assertEqualsData('idLink', 'Test-NOSCRIPT', $vid);
    }
}