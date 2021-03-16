<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLMetaElement01.js.
class HTMLMetaElement01Test extends DomTestCase
{
    public function testHTMLMetaElement01()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLMetaElement01') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vcontent = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'meta');
        $nodeList = $doc->getElementsByTagName('meta');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $vcontent = $testNode->content;
        $this->assertEqualsData('contentLink', 'text/html; CHARSET=utf-8', $vcontent);
    }
}