<?php 
namespace Wikimedia\Dodo\Tests\W3C;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\DOMException;
use Wikimedia\Dodo\Tests\Harness\W3CTestHarness;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_documentcreatetextnode.js.
class HcDocumentcreatetextnodeTest extends W3CTestHarness
{
    public function testHcDocumentcreatetextnode()
    {
        $docsLoaded = -1000000;
        $builder = $this->getBuilder();
        $success = null;
        if ($this->checkInitialization($builder, 'hc_documentcreatetextnode') != null) {
            return;
        }
        $doc = null;
        $newTextNode = null;
        $newTextName = null;
        $newTextValue = null;
        $newTextType = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $newTextNode = $doc->createTextNode('This is a new Text node');
        $newTextValue = $newTextNode->nodeValue;
        $this->w3cAssertEquals('value', 'This is a new Text node', $newTextValue);
        $newTextName = $newTextNode->nodeName;
        $this->w3cAssertEquals('strong', '#text', $newTextName);
        $newTextType = $newTextNode->nodeType;
        $this->w3cAssertEquals('type', 3, $newTextType);
    }
}
