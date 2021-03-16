<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLAreaElement08.js.
class HTMLAreaElement08Test extends DomTestCase
{
    public function testHTMLAreaElement08()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLAreaElement08') != null) {
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
        $doc = $this->load($docRef, 'doc', 'area2');
        $nodeList = $doc->getElementsByTagName('area');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $vtarget = $testNode->target;
        $this->assertEqualsData('targetLink', 'dynamic', $vtarget);
    }
}