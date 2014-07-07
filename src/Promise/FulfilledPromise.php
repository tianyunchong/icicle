<?php
namespace Icicle\Promise;

use Exception;
use Icicle\Loop\Loop;
use Icicle\Promise\Exception\TypeException;
use Icicle\Timer\Timer;

class FulFilledPromise extends ResolvedPromise
{
    /**
     * @var     mixed
     */
    private $value;
    
    /**
     * @param   mixed $value Anything other than a PromiseInterface or PromisorInterface object.
     *
     * @throws  TypeException Thrown if a PromiseInterface or PromisorInterface is given as the value.
     */
    public function __construct($value = null)
    {
        if ($value instanceof PromiseInterface || $value instanceof PromisorInterface) {
            throw new TypeException('Cannot use a PromiseInterface or PromisorInterface as a fulfilled promise value.');
        }
        
        $this->value = $value;
    }
    
    /**
     * {@inheritdoc}
     */
    public function then(callable $onFulfilled = null, callable $onRejected = null)
    {
        if (null === $onFulfilled) {
            return $this;
        }
        
        return new Promise(function ($resolve, $reject) use ($onFulfilled) {
            Loop::schedule(function () use ($resolve, $reject, $onFulfilled) {
                try {
                    $resolve($onFulfilled($this->value));
                } catch (Exception $exception) {
                    $reject($exception);
                }
            });
        });
    }
    
    /**
     * {@inheritdoc}
     */
    public function done(callable $onFulfilled = null, callable $onRejected = null)
    {
        if (null !== $onFulfilled) {
            Loop::schedule($onFulfilled, $this->value);
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function delay($time)
    {
        return new Promise(
            function ($resolve) use (&$timer, $time) {
                $timer = Timer::once(function () use ($resolve) {
                    $resolve($this->value);
                }, $time);
            },
            function () use (&$timer) {
                $timer->cancel();
            }
        );
    }
    
    /**
     * {@inheritdoc}
     */
    public function isFulfilled()
    {
        return true;
    }
    
    /**
     * {@inheritdoc}
     */
    public function isRejected()
    {
        return false;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getResult()
    {
        return $this->value;
    }
}