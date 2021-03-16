<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLElement69.js.
class HTMLElement69Test extends DomTestCase
{
    public function testHTMLElement69()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLElement69') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vlang = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'element');
        $nodeList = $doc->getElementsByTagName('strike');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $vlang = $testNode->lang;
        $this->assertEqualsData('langLink', 'en', $vlang);
    }
}