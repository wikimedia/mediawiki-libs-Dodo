<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLSelectElement10.js.
class HTMLSelectElement10Test extends DomTestCase
{
    public function testHTMLSelectElement10()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLSelectElement10') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vmultiple = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'select');
        $nodeList = $doc->getElementsByTagName('select');
        $this->assertSizeData('Asize', 3, $nodeList);
        $testNode = $nodeList[0];
        $vmultiple = $testNode->multiple;
        $this->assertTrueData('multipleLink', $vmultiple);
    }
}