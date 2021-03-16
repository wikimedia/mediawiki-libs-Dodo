<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLButtonElement04.js.
class HTMLButtonElement04Test extends DomTestCase
{
    public function testHTMLButtonElement04()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLButtonElement04') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vdisabled = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'button');
        $nodeList = $doc->getElementsByTagName('button');
        $this->assertSizeData('Asize', 2, $nodeList);
        $testNode = $nodeList[0];
        $vdisabled = $testNode->disabled;
        $this->assertTrueData('disabledLink', $vdisabled);
    }
}