<?php


namespace HappyR\Google\AnalyticsBundle\CacheServices;

use Doctrine\Common\Cache\CacheProvider;

/**
 * Class DoctrineCache
 *
 * @author Tobias Nyholm
 *
 */
class DoctrineCache implements CacheInterface
{

    /**
     * @var CacheProvider cache
     *
     *
     */
    private $cache;

    /**
     * @param CacheProvider $cache
     */
    public function __construct(CacheProvider $cache)
    {
        $this->cache = $cache;
    }

    /**
     * The namespace of the cache
     *
     * @param string $name
     *
     * @return mixed
     */
    public function setNamespace($name)
    {
        $this->cache->setNamespace($name);
    }

    /**
     * Fetch a new cache by its id
     *
     * @param string $id
     *
     * @return mixed|boolean false if nothing found
     */
    public function fetch($id)
    {
        return $this->cache->fetch($id);
    }

    /**
     * Save data into cache
     *
     * @param string $id
     * @param mixed $data
     * @param integer $lifeTime
     *
     * @return mixed
     */
    public function save($id, $data, $lifeTime = 0)
    {
        return $this->cache->save($id, $data, $lifeTime);
    }
}