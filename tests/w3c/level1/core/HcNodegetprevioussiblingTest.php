<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/core/hc_nodegetprevioussibling.js.
class HcNodegetprevioussiblingTest extends DomTestCase
{
    public function testHcNodegetprevioussibling()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'hc_nodegetprevioussibling') != null) {
            return;
        }
        $doc = null;
        $elementList = null;
        $nameNode = null;
        $psNode = null;
        $psName = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'hc_staff');
        $elementList = $doc->getElementsByTagName('strong');
        $nameNode = $elementList->item(1);
        $psNode = $nameNode->previousSibling;
        $psName = $psNode->nodeName;
        $this->assertEqualsData('whitespace', '#text', $psName);
    }
}