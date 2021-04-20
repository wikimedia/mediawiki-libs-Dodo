<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLSelectElement09.js.
class HTMLSelectElement09Test extends DomTestCase
{
    public function testHTMLSelectElement09()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLSelectElement09') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vdisabled = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'select');
        $nodeList = $doc->getElementsByTagName('select');
        $this->assertSizeData('Asize', 3, $nodeList);
        $testNode = $nodeList->item(2);
        $vdisabled = $testNode->disabled;
        $this->assertTrueData('disabledLink', $vdisabled);
    }
}