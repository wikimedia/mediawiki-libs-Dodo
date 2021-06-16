<?php 
namespace Wikimedia\Dodo\Tests\Wpt\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Range;
use Wikimedia\Dodo\Tests\Wpt\Harness\WptTestHarness;
// @see vendor/web-platform-tests/wpt/dom/ranges/Range-adopt-test.html.
class RangeAdoptTestTest extends WptTestHarness
{
    public function createRangeWithUnparentedContainerOfSingleElement()
    {
        $range = $this->doc->createRange();
        $container = $this->doc->createElement('container');
        $element = $this->doc->createElement('element');
        $container->appendChild($element);
        $range->selectNode($element);
        return $range;
    }
    public function nestRangeInOuterContainer($range)
    {
        $range->startContainer->ownerDocument->createElement('outer')->appendChild($range->startContainer);
    }
    public function moveNodeToNewlyCreatedDocumentWithAppendChild($node)
    {
        $this->doc->implementation->createDocument(null, null)->appendChild($node);
    }
    public function testRangeAdoptTest()
    {
        $this->doc = $this->loadWptHtmlFile('vendor/web-platform-tests/wpt/dom/ranges/Range-adopt-test.html');
        //Tests removing only element
        $this->assertTest(function () {
            $range = $this->createRangeWithUnparentedContainerOfSingleElement();
            $range->startContainer->removeChild($range->startContainer->firstChild);
            $this->assertEqualsData($range->endOffset, 0);
        }, 'Range in document: Removing the only element in the range must collapse the range');
        //Tests removing only element after parented container moved to another document
        $this->assertTest(function () {
            $range = $this->createRangeWithUnparentedContainerOfSingleElement();
            $this->nestRangeInOuterContainer($range);
            $this->moveNodeToNewlyCreatedDocumentWithAppendChild($range->startContainer);
            $this->assertEqualsData($range->endOffset, 0);
        }, 'Parented range container moved to another document with appendChild: Moving the element to the other document must collapse the range');
        //Tests removing only element after parentless container moved oo another document
        $this->assertTest(function () {
            $range = $this->createRangeWithUnparentedContainerOfSingleElement();
            $this->moveNodeToNewlyCreatedDocumentWithAppendChild($range->startContainer);
            $range->startContainer->removeChild($range->startContainer->firstChild);
            $this->assertEqualsData($range->endOffset, 0);
        }, 'Parentless range container moved to another document with appendChild: Removing the only element in the range must collapse the range');
        //Tests removing only element after parentless container of container moved to another document
        $this->assertTest(function () {
            $range = $this->createRangeWithUnparentedContainerOfSingleElement();
            $this->nestRangeInOuterContainer($range);
            $this->moveNodeToNewlyCreatedDocumentWithAppendChild($range->startContainer->parentNode);
            $range->startContainer->removeChild($range->startContainer->firstChild);
            $this->assertEqualsData($range->endOffset, 0);
        }, "Range container's parentless container moved to another document with appendChild: Removing the only element in the range must collapse the range");
    }
}
