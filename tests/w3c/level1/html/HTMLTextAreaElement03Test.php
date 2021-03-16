<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLTextAreaElement03.js.
class HTMLTextAreaElement03Test extends DomTestCase
{
    public function testHTMLTextAreaElement03()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLTextAreaElement03') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vform = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'textarea');
        $nodeList = $doc->getElementsByTagName('textarea');
        $this->assertSizeData('Asize', 3, $nodeList);
        $testNode = $nodeList->item(1);
        $vform = $testNode->form;
        $this->assertNullData('formNullLink', $vform);
    }
}