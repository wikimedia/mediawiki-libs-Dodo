<?php 
namespace Wikimedia\Dodo\Tests;
use Wikimedia\Dodo\DomException;
use Exception;
// @see vendor/fgnass/domino/test/w3c/level1/html/HTMLAreaElement04.js.
class HTMLAreaElement04Test extends DomTestCase
{
    public function testHTMLAreaElement04()
    {
        $builder = $this->getBuilder();
        if ($this->checkInitialization($builder, 'HTMLAreaElement04') != null) {
            return;
        }
        $nodeList = null;
        $testNode = null;
        $vhref = null;
        $doc = null;
        $docRef = null;
        if (gettype($this->doc) != NULL) {
            $docRef = $this->doc;
        }
        $doc = $this->load($docRef, 'doc', 'area');
        $nodeList = $doc->getElementsByTagName('area');
        $this->assertSizeData('Asize', 1, $nodeList);
        $testNode = $nodeList[0];
        $vhref = $testNode->href;
        $this->assertURIEqualsData('hrefLink', null, null, null, 'dletter.html', null, null, null, null, $vhref);
    }
}