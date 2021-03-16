<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLModElement02.js.
class HTMLModElement02Test extends DomTestCase
{
    public function testHTMLModElement02()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLModElement02') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vdatetime = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'mod');
        $nodeList = $doc->getElementsByTagName('ins');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $vdatetime = $testNode->dateTime;
        $this->assertEqualsData('dateTimeLink', 'January 1, 2002', $vdatetime);
    }
}