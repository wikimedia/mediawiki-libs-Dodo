<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLImageElement09.js.
class HTMLImageElement09Test extends DomTestCase
{
    public function testHTMLImageElement09()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLImageElement09') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vsrc = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'img');
        $nodeList = $doc->getElementsByTagName('img');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $vsrc = $testNode->src;
        $this->assertURIEqualsData('srcLink', null, null, null, 'dts.gif', null, null, null, null, $vsrc);
    }
}