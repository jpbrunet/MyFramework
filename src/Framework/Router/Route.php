<?php
/**
 * Project Name:  MyFramework.
 * User: BRUNET JP
 * Date: 04/11/2019
 * Time: 21:34
 */

namespace Framework\Router;

/**
 * Class Route
 * Register and match routes
 * @package Framework\Router
 */
class Route
{

    /**
     * @var string
     */
    private $name;
    /**
     * @var callable
     */
    private $callback;
    /**
     * @var array
     */
    private $params;

    /**
     * Route constructor.
     * @param string $name
     * @param string|callable $callback
     * @param array $params
     */
    public function __construct(string $name, $callback, array $params)
    {
        $this->name = $name;
        $this->callback = $callback;
        $this->params = $params;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return callable|string
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * Retrieves the parameters from the URL
     * @return string[]
     */
    public function getParams(): array
    {
        return $this->params;
    }
}
