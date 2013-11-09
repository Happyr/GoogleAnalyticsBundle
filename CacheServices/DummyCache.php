<?php


namespace HappyR\Google\AnalyticsBundle\CacheServices;

/**
 * Class DummyCache, This class does nothing...
 *
 * @author Tobias Nyholm
 *
 */
class DummyCache implements CacheInterface
{
    /**
     * The namespace of the cache
     *
     * @param string $name
     *
     * @return mixed
     */
    public function setNamespace($name)
    {
    }

    /**
     * Fetch a new cache by its id
     *
     * @param string $id
     *
     * @return boolean false
     */
    public function fetch($id)
    {
        return false;
    }

    /**
     * Save data into cache
     *
     * @param string $id
     * @param mixed $data
     * @param integer $lifeTime
     *
     */
    public function save($id, $data, $lifeTime = 0)
    {
    }
}