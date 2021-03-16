<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLScriptElement03.js.
class HTMLScriptElement03Test extends DomTestCase
{
    public function testHTMLScriptElement03()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLScriptElement03') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vdefer = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'script');
        $nodeList = $doc->getElementsByTagName('script');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $vdefer = $testNode->defer;
        $this->assertTrueData('deferLink', $vdefer);
    }
}