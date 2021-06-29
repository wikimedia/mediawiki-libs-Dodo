<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Document-createTreeWalker.html.
class DocumentCreateTreeWalkerTest extends WPTTestHarness
{
    public function testDocumentCreateTreeWalker()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Document-createTreeWalker.html');
        $this->assertTest(function () {
            $this->assertThrowsJsData($this->type_error, function () {
                $this->doc->createTreeWalker();
            });
        }, 'Required arguments to createTreeWalker should be required.');
        $this->assertTest(function () {
            $tw = $this->doc->createTreeWalker($this->doc->body);
            $this->assertEqualsData($tw->root, $this->doc->body);
            $this->assertEqualsData($tw->currentNode, $this->doc->body);
            $this->assertEqualsData($tw->whatToShow, 0xffffffff);
            $this->assertEqualsData($tw->filter, null);
        }, 'Optional arguments to createTreeWalker should be optional (1 passed).');
        $this->assertTest(function () {
            $tw = $this->doc->createTreeWalker($this->doc->body, 42);
            $this->assertEqualsData($tw->root, $this->doc->body);
            $this->assertEqualsData($tw->currentNode, $this->doc->body);
            $this->assertEqualsData($tw->whatToShow, 42);
            $this->assertEqualsData($tw->filter, null);
        }, 'Optional arguments to createTreeWalker should be optional (2 passed).');
        $this->assertTest(function () {
            $tw = $this->doc->createTreeWalker($this->doc->body, 42, null);
            $this->assertEqualsData($tw->root, $this->doc->body);
            $this->assertEqualsData($tw->currentNode, $this->doc->body);
            $this->assertEqualsData($tw->whatToShow, 42);
            $this->assertEqualsData($tw->filter, null);
        }, 'Optional arguments to createTreeWalker should be optional (3 passed, null).');
        $this->assertTest(function () {
            $fn = function () {
            };
            $tw = $this->doc->createTreeWalker($this->doc->body, 42, $fn);
            $this->assertEqualsData($tw->root, $this->doc->body);
            $this->assertEqualsData($tw->currentNode, $this->doc->body);
            $this->assertEqualsData($tw->whatToShow, 42);
            $this->assertEqualsData($tw->filter, $fn);
        }, 'Optional arguments to createTreeWalker should be optional (3 passed, function).');
    }
}
