<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Nodes;
use Wikimedia\Dodo\Node;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Document-createTreeWalker.html.
class DocumentCreateTreeWalkerTest extends WPTTestHarness
{
    public function testDocumentCreateTreeWalker()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Document-createTreeWalker.html');
        $this->assertTest(function () {
            $this->wptAssertThrowsJs($this->type_error, function () {
                $this->doc->createTreeWalker();
            });
        }, 'Required arguments to createTreeWalker should be required.');
        $this->assertTest(function () {
            $tw = $this->doc->createTreeWalker($this->doc->body);
            $this->wptAssertEquals($tw->root, $this->doc->body);
            $this->wptAssertEquals($tw->currentNode, $this->doc->body);
            $this->wptAssertEquals($tw->whatToShow, 0xffffffff);
            $this->wptAssertEquals($tw->filter, null);
        }, 'Optional arguments to createTreeWalker should be optional (1 passed).');
        $this->assertTest(function () {
            $tw = $this->doc->createTreeWalker($this->doc->body, 42);
            $this->wptAssertEquals($tw->root, $this->doc->body);
            $this->wptAssertEquals($tw->currentNode, $this->doc->body);
            $this->wptAssertEquals($tw->whatToShow, 42);
            $this->wptAssertEquals($tw->filter, null);
        }, 'Optional arguments to createTreeWalker should be optional (2 passed).');
        $this->assertTest(function () {
            $tw = $this->doc->createTreeWalker($this->doc->body, 42, null);
            $this->wptAssertEquals($tw->root, $this->doc->body);
            $this->wptAssertEquals($tw->currentNode, $this->doc->body);
            $this->wptAssertEquals($tw->whatToShow, 42);
            $this->wptAssertEquals($tw->filter, null);
        }, 'Optional arguments to createTreeWalker should be optional (3 passed, null).');
        $this->assertTest(function () {
            $fn = function () {
            };
            $tw = $this->doc->createTreeWalker($this->doc->body, 42, $fn);
            $this->wptAssertEquals($tw->root, $this->doc->body);
            $this->wptAssertEquals($tw->currentNode, $this->doc->body);
            $this->wptAssertEquals($tw->whatToShow, 42);
            $this->wptAssertEquals($tw->filter, $fn);
        }, 'Optional arguments to createTreeWalker should be optional (3 passed, function).');
    }
}
