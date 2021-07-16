<?php 
namespace Wikimedia\Dodo\Tests\WPT\Dom\Nodes;
use Wikimedia\Dodo\Text;
use Wikimedia\Dodo\Tests\Harness\WPTTestHarness;
// @see vendor/web-platform-tests/wpt/dom/nodes/Document-createEvent.https.html.
class DocumentCreateEventHttpsTest extends WPTTestHarness
{
    public function testAlias($arg, $iface)
    {
        $ev = null;
        $this->assertTest(function () use(&$arg, &$iface) {
            $ev = $this->doc->createEvent($arg);
            $this->wptAssertEquals(get_class($ev), $this->window[$iface]->prototype);
        }, $arg . ' should be an alias for ' . $iface . '.');
        $this->assertTest(function () use(&$ev) {
            $this->wptAssertEquals($ev->type, '', 'type should be initialized to the empty string');
            $this->wptAssertEquals($ev->target, null, 'target should be initialized to null');
            $this->wptAssertEquals($ev->currentTarget, null, 'currentTarget should be initialized to null');
            $this->wptAssertEquals($ev->eventPhase, 0, 'eventPhase should be initialized to NONE (0)');
            $this->wptAssertEquals($ev->bubbles, false, 'bubbles should be initialized to false');
            $this->wptAssertEquals($ev->cancelable, false, 'cancelable should be initialized to false');
            $this->wptAssertEquals($ev->defaultPrevented, false, 'defaultPrevented should be initialized to false');
            $this->wptAssertEquals($ev->isTrusted, false, 'isTrusted should be initialized to false');
        }, "createEvent('" . $arg . "') should be initialized correctly.");
    }
    public function testDocumentCreateEventHttps()
    {
        $this->doc = $this->loadHtmlFile('vendor/web-platform-tests/wpt/dom/nodes/Document-createEvent.https.html');
        foreach ($aliases as $alias) {
            $iface = $aliases[$alias];
            $this->testAlias($alias, $iface);
            $this->testAlias(strtolower($alias), $iface);
            $this->testAlias(strtoupper($alias), $iface);
            if ($alias[count($alias) - 1] != 's') {
                $plural = $alias . 's';
                if (!isset($aliases[$plural])) {
                    $this->assertTest(function () use(&$plural) {
                        $this->wptAssertThrowsDom('NOT_SUPPORTED_ERR', function () use(&$plural) {
                            $evt = $this->doc->createEvent($plural);
                        });
                    }, 'Should throw NOT_SUPPORTED_ERR for pluralized legacy event interface "' . $plural . '"');
                }
            }
        }
        $this->assertTest(function () {
            $this->wptAssertThrowsDom('NOT_SUPPORTED_ERR', function () {
                $evt = $this->doc->createEvent('foo');
            });
            $this->wptAssertThrowsDom('NOT_SUPPORTED_ERR', function () {
                // 'LATIN CAPITAL LETTER I WITH DOT ABOVE' (U+0130)
                $evt = $this->doc->createEvent("UİEvent");
            });
            $this->wptAssertThrowsDom('NOT_SUPPORTED_ERR', function () {
                // 'LATIN SMALL LETTER DOTLESS I' (U+0131)
                $evt = $this->doc->createEvent("UıEvent");
            });
        }, 'Should throw NOT_SUPPORTED_ERR for unrecognized arguments');
        /*
         * The following are event interfaces which do actually exist, but must still
         * throw since they're absent from the table in the spec for
         * document.createEvent().  This list is not exhaustive, but includes all
         * interfaces that it is known some UA does or did not throw for.
         */
        $someNonCreateableEvents = [
            'AnimationEvent',
            'AnimationPlaybackEvent',
            'AnimationPlayerEvent',
            'ApplicationCacheErrorEvent',
            'AudioProcessingEvent',
            'AutocompleteErrorEvent',
            'BeforeInstallPromptEvent',
            'BlobEvent',
            'ClipboardEvent',
            'CloseEvent',
            'CommandEvent',
            'DataContainerEvent',
            'ErrorEvent',
            'ExtendableEvent',
            'ExtendableMessageEvent',
            'FetchEvent',
            'FontFaceSetLoadEvent',
            'GamepadEvent',
            'GeofencingEvent',
            'IDBVersionChangeEvent',
            'InstallEvent',
            'KeyEvent',
            'MIDIConnectionEvent',
            'MIDIMessageEvent',
            'MediaEncryptedEvent',
            'MediaKeyEvent',
            'MediaKeyMessageEvent',
            'MediaQueryListEvent',
            'MediaStreamEvent',
            'MediaStreamTrackEvent',
            'MouseScrollEvent',
            'MutationEvent',
            'NotificationEvent',
            'NotifyPaintEvent',
            'OfflineAudioCompletionEvent',
            'OrientationEvent',
            'PageTransition',
            // Yes, with no "Event"
            'PageTransitionEvent',
            'PointerEvent',
            'PopStateEvent',
            'PopUpEvent',
            'PresentationConnectionAvailableEvent',
            'PresentationConnectionCloseEvent',
            'ProgressEvent',
            'PromiseRejectionEvent',
            'PushEvent',
            'RTCDTMFToneChangeEvent',
            'RTCDataChannelEvent',
            'RTCIceCandidateEvent',
            'RelatedEvent',
            'ResourceProgressEvent',
            'SVGEvent',
            'SVGZoomEvent',
            'ScrollAreaEvent',
            'SecurityPolicyViolationEvent',
            'ServicePortConnectEvent',
            'ServiceWorkerMessageEvent',
            'SimpleGestureEvent',
            'SpeechRecognitionError',
            'SpeechRecognitionEvent',
            'SpeechSynthesisEvent',
            'SyncEvent',
            'TimeEvent',
            'TouchEvent',
            'TrackEvent',
            'TransitionEvent',
            'WebGLContextEvent',
            'WebKitAnimationEvent',
            'WebKitTransitionEvent',
            'WheelEvent',
            'XULCommandEvent',
        ];
        foreach ($someNonCreateableEvents as $eventInterface) {
            $this->assertTest(function () use(&$eventInterface) {
                $this->wptAssertThrowsDom('NOT_SUPPORTED_ERR', function () use(&$eventInterface) {
                    $evt = $this->doc->createEvent($eventInterface);
                });
            }, 'Should throw NOT_SUPPORTED_ERR for non-legacy event interface "' . $eventInterface . '"');
            // SVGEvents is allowed, other plurals are not
            if ($eventInterface !== 'SVGEvent') {
                $this->assertTest(function () use(&$eventInterface) {
                    $this->wptAssertThrowsDom('NOT_SUPPORTED_ERR', function () use(&$eventInterface) {
                        $evt = $this->doc->createEvent($eventInterface . 's');
                    });
                }, 'Should throw NOT_SUPPORTED_ERR for pluralized non-legacy event interface "' . $eventInterface . 's"');
            }
        }
        $aliases = ['BeforeUnloadEvent' => 'BeforeUnloadEvent', 'CompositionEvent' => 'CompositionEvent', 'CustomEvent' => 'CustomEvent', 'DeviceMotionEvent' => 'DeviceMotionEvent', 'DeviceOrientationEvent' => 'DeviceOrientationEvent', 'DragEvent' => 'DragEvent', 'Event' => 'Event', 'Events' => 'Event', 'FocusEvent' => 'FocusEvent', 'HashChangeEvent' => 'HashChangeEvent', 'HTMLEvents' => 'Event', 'KeyboardEvent' => 'KeyboardEvent', 'MessageEvent' => 'MessageEvent', 'MouseEvent' => 'MouseEvent', 'MouseEvents' => 'MouseEvent', 'StorageEvent' => 'StorageEvent', 'SVGEvents' => 'Event', 'TextEvent' => 'CompositionEvent', 'UIEvent' => 'UIEvent', 'UIEvents' => 'UIEvent'];
    }
}
