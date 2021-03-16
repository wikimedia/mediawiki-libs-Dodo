<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLElement32.js.
class HTMLElement32Test extends DomTestCase
{
    public function testHTMLElement32()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLElement32') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vtitle = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'element');
        $nodeList = $doc->getElementsByTagName('sup');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $vtitle = $testNode->title;
        $this->assertEqualsData('titleLink', 'SUP Element', $vtitle);
    }
}