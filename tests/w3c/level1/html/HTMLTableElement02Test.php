<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLTableElement02.js.
class HTMLTableElement02Test extends DomTestCase
{
    public function testHTMLTableElement02()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLTableElement02') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vcaption = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'table');
        $nodeList = $doc->getElementsByTagName('table');
        $this->assertSizeData('Asize', 3, $nodeList);
        $testNode = $nodeList[0];
        $vcaption = $testNode->caption;
        $this->assertNullData('captionLink', $vcaption);
    }
}