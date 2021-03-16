<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLInputElement04.js.
class HTMLInputElement04Test extends DomTestCase
{
    public function testHTMLInputElement04()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLInputElement04') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vaccept = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'input');
        $nodeList = $doc->getElementsByTagName('input');
        $this->assertSizeData('Asize', 9, $nodeList);
        $testNode = $nodeList->item(8);
        $vaccept = $testNode->accept;
        $this->assertEqualsData('acceptLink', 'GIF,JPEG', $vaccept);
    }
}