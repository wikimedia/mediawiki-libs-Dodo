<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLModElement04.js.
class HTMLModElement04Test extends W3CTestHarness
{
    public function testHTMLModElement04()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'HTMLModElement04') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vdatetime = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'mod');
        $nodeList = $doc->getElementsByTagName('del');
        $this->w3cAssertSize('Asize', 1, $nodeList);
        $testNode = $nodeList->item(0);
        $vdatetime = $testNode->dateTime;
        $this->w3cAssertEquals('dateTimeLink', 'January 2, 2002', $vdatetime);
    }
}
