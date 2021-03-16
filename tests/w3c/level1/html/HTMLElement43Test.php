<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLElement43.js.
class HTMLElement43Test extends DomTestCase
{
    public function testHTMLElement43()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLElement43') != null) {
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
        $nodeList = $doc->getElementsByTagName('em');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $vtitle = $testNode->title;
        $this->assertEqualsData('titleLink', 'EM Element', $vtitle);
    }
}