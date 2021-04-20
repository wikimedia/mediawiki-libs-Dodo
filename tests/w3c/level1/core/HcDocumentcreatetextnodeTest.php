<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_documentcreatetextnode.js.
class HcDocumentcreatetextnodeTest extends DomTestCase
{
    public function testHcDocumentcreatetextnode()
    {
        $builder = $this->getBuilder();
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
        $this->assertEqualsData('value', 'This is a new Text node', $newTextValue);
        $newTextName = $newTextNode->nodeName;
        $this->assertEqualsData('strong', '#text', $newTextName);
        $newTextType = $newTextNode->nodeType;
        $this->assertEqualsData('type', 3, $newTextType);
    }
}