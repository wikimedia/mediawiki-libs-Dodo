<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom;
use Wikimedia\Dodo\Element;
use Wikimedia\Dodo\Attr;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/getElementsByClassName-whitespace-class-names.html.
class GetElementsByClassNameWhitespaceClassNamesTest extends WPTTestHarness
{
    public function testGetElementsByClassNameWhitespaceClassNames()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/getElementsByClassName-whitespace-class-names.html');
        $spans = $this->doc->querySelectorAll('span');
        foreach ($spans as $span => $___) {
            $this->assertTest(function () use(&$span) {
                $className = $span->getAttribute('class');
                $this->assertEqualsData(count($className), 1, 'Sanity check: the class name was retrieved and is a single character');
                $shouldBeSpan = $this->doc->getElementsByClassName($className);
                $this->assertArrayEqualsData($shouldBeSpan, [$span]);
            }, "Passing a {$span->textContent} to getElementsByClassName still finds the span");
        }
    }
}
