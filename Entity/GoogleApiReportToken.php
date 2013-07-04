<?php

namespace HappyR\Google\AnalyticsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HappyR\Google\AnalyticsBundle\Entity\GoogleApiReportToken
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="HappyR\Google\AnalyticsBundle\Entity\GoogleApiReportTokenRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class GoogleApiReportToken
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $token
     *
     * @ORM\Column(name="token", type="string", length=255)
     */
    private $token;

    /**
     * @var datetime $createdAt
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var datetime $updatedAt
     *
     * @ORM\Column(name="updatedAt", type="datetime")
     */
    private $updatedAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set token
     *
     * @param string $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Get createdAt
     *
     * @return datetime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function touchUpdatedAt()
    {
        $this->updatedAt = new \DateTime();
    }

    /**
     * Get updatedAt
     *
     * @return datetime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}
