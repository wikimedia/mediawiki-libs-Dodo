<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLElement20.js.
class HTMLElement20Test extends DomTestCase
{
    public function testHTMLElement20()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLElement20') != null) {
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
        $nodeList = $doc->getElementsByTagName('var');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $vid = $testNode->id;
        $this->assertEqualsData('idLink', 'Test-VAR', $vid);
    }
}