<?php
/*********************************************************************************
 * Developed by Maxx Ng'ang'a
 * (C) 2017 Crysoft Dynamics Ltd
 * Karbon V 2.1
 * User: Maxx
 * Date: 6/1/2018
 * Time: 12:00 PM
 ********************************************************************************/

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
 * @ORM\Entity
 * @ORM\Table(name="profile")
 * @Vich\Uploadable
 * @ORM\HasLifecycleCallbacks
 */
class Profile implements \Serializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="string")
     */
    private $id;
    /**
     * @ORM\Column(type="date")
     */
    private $dateOfBirth;
    /**
     * @ORM\Column(type="string")
     */
    private $levelOfEducation;
    /**
     * @ORM\Column(type="string")
     */
    private $lifeStatus="Student";
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $fb;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $twitter;

    /**
     * @Vich\UploadableField(mapping="product_image", fileNameProperty="imageName", size="imageSize")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $imageName;
    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $imageSize;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User",inversedBy="profile")
     */
    private $user;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $createdAt;
    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * @param mixed $dateOfBirth
     */
    public function setDateOfBirth($dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;
    }

    /**
     * @return mixed
     */
    public function getLevelOfEducation()
    {
        return $this->levelOfEducation;
    }

    /**
     * @param mixed $levelOfEducation
     */
    public function setLevelOfEducation($levelOfEducation)
    {
        $this->levelOfEducation = $levelOfEducation;
    }

    /**
     * @return mixed
     */
    public function getLifeStatus()
    {
        return $this->lifeStatus;
    }

    /**
     * @param mixed $lifeStatus
     */
    public function setLifeStatus($lifeStatus)
    {
        $this->lifeStatus = $lifeStatus;
    }

    /**
     * @return mixed
     */
    public function getFb()
    {
        return $this->fb;
    }

    /**
     * @param mixed $fb
     */
    public function setFb($fb)
    {
        $this->fb = $fb;
    }

    /**
     * @return mixed
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * @param mixed $twitter
     */
    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;
    }
    /**
     * @return mixed
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    /**
     * @param mixed $imageName
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;
    }

    /**
     * @return File|null
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $imageFile
     * @return Profile
     */
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;
        if ($image) {
            //Lets make sure at least one field changes so Doctrine can process the file
            $this->updatedAt = new \DateTimeImmutable();
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getImageSize()
    {
        return $this->imageSize;
    }

    /**
     * @param integer $imageSize
     * @return Profile
     */
    public function setImageSize($imageSize)
    {
        $this->imageSize = $imageSize;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }


    /**
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->levelOfEducation,
            $this->lifeStatus,
            $this->twitter,
            $this->fb,
            $this->dateOfBirth

        ));
    }

    /**
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        // TODO: Implement unserialize() method.
    }
}