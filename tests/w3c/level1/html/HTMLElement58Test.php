<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLElement58.js.
class HTMLElement58Test extends DomTestCase
{
    public function testHTMLElement58()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLElement58') != null) {
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
        $nodeList = $doc->getElementsByTagName('center');
        $this->assertSizeData('Asize', 2, $nodeList);
        $testNode = $nodeList[0];
        $vtitle = $testNode->title;
        $this->assertEqualsData('titleLink', 'CENTER Element', $vtitle);
    }
}