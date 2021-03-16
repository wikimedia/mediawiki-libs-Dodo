<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLAnchorElement11.js.
class HTMLAnchorElement11Test extends DomTestCase
{
    public function testHTMLAnchorElement11()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLAnchorElement11') != null) {
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
        $doc = $this->load($docRef, 'doc', 'anchor2');
        $nodeList = $doc->getElementsByTagName('a');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $vtarget = $testNode->target;
        $this->assertEqualsData('targetLink', 'dynamic', $vtarget);
    }
}