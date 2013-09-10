<?php


namespace HappyR\Google\AnalyticsBundle\CacheServices;


/**
 * Class CacheInterface
 *
 * @author Tobias Nyholm
 *
 */
interface CacheInterface
{
    /**
     * The namespace of the cache
     *
     * @param $name
     *
     * @return mixed
     */
    public function setNamespace($name);

    /**
     * Fetch a new cache by its id
     *
     * @param $id
     *
     * @return mixed|boolean false if nothing found
     */
    public function fetch($id);

    /**
     * Save data into cache
     *
     * @param $id
     * @param $data
     * @param int $lifeTime
     *
     */
    public function save($id, $data, $lifeTime = 0);
}