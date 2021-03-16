<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLFormElement04.js.
class HTMLFormElement04Test extends DomTestCase
{
    public function testHTMLFormElement04()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLFormElement04') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vacceptcharset = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'form');
        $nodeList = $doc->getElementsByTagName('form');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $vacceptcharset = $testNode->acceptCharset;
        $this->assertEqualsData('acceptCharsetLink', 'US-ASCII', $vacceptcharset);
    }
}