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
     * @param $name
     *
     * @return mixed
     */
    public function setNamespace($name)
    {

    }

    /**
     * Fetch a new cache by its id
     *
     * @param $id
     *
     * @return mixed
     */
    public function fetch($id)
    {
        return false;
    }

    /**
     * Save data into cache
     *
     * @param $id
     * @param $data
     * @param int $lifeTime
     *
     */
    public function save($id, $data, $lifeTime = 0)
    {

    }

}