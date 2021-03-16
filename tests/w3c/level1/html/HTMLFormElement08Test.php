<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLFormElement08.js.
class HTMLFormElement08Test extends DomTestCase
{
    public function testHTMLFormElement08()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLFormElement08') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vtarget = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'form2');
        $nodeList = $doc->getElementsByTagName('form');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $vtarget = $testNode->target;
        $this->assertEqualsData('targetLink', 'dynamic', $vtarget);
    }
}