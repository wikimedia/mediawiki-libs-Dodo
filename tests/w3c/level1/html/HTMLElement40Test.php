<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLElement40.js.
class HTMLElement40Test extends DomTestCase
{
    public function testHTMLElement40()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLElement40') != null) {
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
        $nodeList = $doc->getElementsByTagName('strike');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $vtitle = $testNode->title;
        $this->assertEqualsData('titleLink', 'STRIKE Element', $vtitle);
    }
}