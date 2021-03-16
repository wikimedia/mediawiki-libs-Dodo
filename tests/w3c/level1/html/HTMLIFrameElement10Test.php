<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLIFrameElement10.js.
class HTMLIFrameElement10Test extends DomTestCase
{
    public function testHTMLIFrameElement10()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLIFrameElement10') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vwidth = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'iframe');
        $nodeList = $doc->getElementsByTagName('iframe');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $vwidth = $testNode->width;
        $this->assertEqualsData('widthLink', '60', $vwidth);
    }
}