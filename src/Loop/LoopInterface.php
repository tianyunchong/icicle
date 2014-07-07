<?php
namespace Icicle\Loop;

use Icicle\Event\EventEmitterInterface;
use Icicle\Socket\ReadableSocketInterface;
use Icicle\Socket\SocketInterface;
use Icicle\Socket\WritableSocketInterface;
use Icicle\Timer\ImmediateInterface;
use Icicle\Timer\TimerInterface;

interface LoopInterface extends EventEmitterInterface
{
    /**
     * Determines if the necessary components for the loop class are available.
     *
     * @return  bool
     *
     * @api
     */
    public static function enabled();
    
    /**
     * Executes a single tick, processing callbacks and handling any available I/O.
     *
     * @param   bool $blocking Determines if the tick should block and wait for I/O if no other tasks are scheduled.
     *
     * @api
     */
    public function tick($blocking = true);
    
    /**
     * Starts the loop event loop.
     * Emits: run
     *
     * @api
     */
    public function run();
    
    /**
     * Stops the loop event loop.
     * Emits: stop
     *
     * @api
     */
    public function stop();
    
    /**
     * Determines if the loop event loop is running.
     *
     * @return  bool
     *
     * @api
     */
    public function isRunning();
    
    /**
     * Removes all events (I/O, timers, callbacks, signal handlers, etc.) from the loop.
     *
     * @api
     */
    public function clear();
    
    /**
     * Performs any reinitializing necessary after forking.
     *
     * @api
     */
    public function reInit();
    
    /**
     * Sets the maximum number of callbacks set with schedule() that will be executed per tick.
     *
     * @param   int $depth
     *
     * @api
     */
    public function maxScheduleDepth($depth);
    
    /**
     * Define a callback function to be run after all I/O has been handled in the current tick.
     * Callbacks are called in the order defined.
     *
     * @param   callable $callback
     * @param   array $args Array of arguments to be passed to the callback function.
     *
     * @api
     */
    public function schedule(callable $callback, array $args = []);
    
    /**
     * Adds the socket to the loop and begins listening for data.
     *
     * @param   ReadableSocketInterface $socket
     */
    public function addReadableSocket(ReadableSocketInterface $socket);
    
    /**
     * Pauses listening for data on the socket.
     *
     * @param   ReadableSocketInterface $socket
     *
     * @return  bool
     */
    public function pauseReadableSocket(ReadableSocketInterface $socket);
    
    /**
     * Resumes listening for data on the socket.
     *
     * @param   ReadableSocketInterface $socket
     *
     * @return  bool
     */
    public function resumeReadableSocket(ReadableSocketInterface $socket);
    
    /**
     * Determines if the given socket is pending (listneing for data).
     *
     * @param   ReadableSocketInterface $socket
     *
     * @return  bool
     */
    public function isReadableSocketPending(ReadableSocketInterface $socket);
    
    /**
     * Adds the socket to the queue waiting to write.
     *
     * @param   WritableSocketInterface $socket
     */
    public function scheduleWritableSocket(WritableSocketInterface $socket);
    
    /**
     * Removes the socket from the queue waiting to write.
     *
     * @param   WritableSocketInterface $socket
     */
    public function unscheduleWritableSocket(WritableSocketInterface $socket);
    
    /**
     * Determines if the socket is waiting to write.
     *
     * @param   WritableSocketInterface $socket
     *
     * @return  bool
     */
    public function isWritableSocketScheduled(WritableSocketInterface $socket);
    
    /**
     * @param   SocketInterface $socket
     *
     * @return  bool
     */
    public function containsSocket(SocketInterface $socket);
    
    /**
     * Completely removes the socket from the loop (stops listening for data or writing data).
     *
     * @param   SocketInterface $socket
     */
    public function removeSocket(SocketInterface $socket);
    
    /**
     * Adds the given timer to the loop.
     *
     * @param   TimerInterface $timer
     */
    public function addTimer(TimerInterface $timer);
    
    /**
     * Removes the timer from the loop.
     *
     * @param   TimerInterface $timer
     */
    public function cancelTimer(TimerInterface $timer);
    
    /**
     * Determines if the timer is active in the loop.
     *
     * @param   TimerInterface $timer
     *
     * @return  bool
     */
    public function isTimerActive(TimerInterface $timer);
    
    /**
     * Adds the given timer to the loop.
     *
     * @param   TimerInterface $timer
     */
    public function addImmediate(ImmediateInterface $immediate);
    
    /**
     * Removes the timer from the loop.
     *
     * @param   TimerInterface $timer
     */
    public function cancelImmediate(ImmediateInterface $immediate);
    
    /**
     * Determines if the timer is active in the loop.
     *
     * @param   TimerInterface $timer
     *
     * @return  bool
     */
    public function isImmediatePending(ImmediateInterface $immediate);
}
