<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLButtonElement03.js.
class HTMLButtonElement03Test extends DomTestCase
{
    public function testHTMLButtonElement03()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLButtonElement03') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vaccesskey = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'button');
        $nodeList = $doc->getElementsByTagName('button');
        $this->assertSizeData('Asize', 2, $nodeList);
        $testNode = $nodeList[0];
        $vaccesskey = $testNode->accessKey;
        $this->assertEqualsData('accessKeyLink', 'f', $vaccesskey);
    }
}