<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\PropertyBundle\Entity;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use PropertyPrivately\CoreBundle\Supports\Contracts\ArrayableInterface;
use PropertyPrivately\PropertyBundle\Entity\Property;

/**
 * PropertyPrivately\PropertyBundle\Entity\Property
 *
 * @ORM\Entity(repositoryClass="PropertyPrivately\PropertyBundle\Entity\Repository\ImageRepository")
 * @ORM\Table(name="PPProperty.Image")
 * @ORM\HasLifecycleCallbacks
 */
class Image implements ArrayableInterface
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="description", type="string", length=250)
     * @Assert\Length(
     *  min="10",
     *  max="250",
     *  minMessage="Your description must be at least {{ limit }} characters long.",
     *  maxMessage="Your description cannot be longer than {{ limit }} characters long."
     * )
     * @Assert\Type(type="string", message="That description is not a valid {{ type }}.")
     *
     * @Assert\Blank(groups={"POST"})
     * @Assert\NotBlank(groups={"PATCH"})
     */
    protected $description;

    /**
     * @ORM\Column(name="path", type="string", length=250)
     */
    protected $path;

    /**
     * @ORM\Column(name="displayOrder", type="integer")
     */
    protected $displayOrder;

    /**
     * @ManyToOne(targetEntity="Property", fetch="EAGER", inversedBy="images")
     * @JoinColumn(name="propertyId", referencedColumnName="id")
     */
    protected $property;

    /**
     * @Assert\File(
     *  maxSize="5242880",
     *  mimeTypes={"image/jpeg"},
     *  mimeTypesMessage="Please upload a valid JPEG."
     * )
     *
     * @Assert\NotBlank(groups={"POST"})
     * @Assert\Blank(groups={"PATCH"})
     */
    protected $file;

    /**
     * @var UploadedFile
     */
    protected $temp;


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
     * Set description
     *
     * @param string $description
     * @return Image
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Image
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set display order
     *
     * @param integer $displayOrder
     * @return Image
     */
    public function setDisplayOrder($displayOrder)
    {
        $this->displayOrder = $displayOrder;

        return $this;
    }

    /**
     * Get display order
     *
     * @return integer
     */
    public function getDisplayOrder()
    {
        return $this->displayOrder;
    }

    /**
     * Set property
     *
     * @param Property $property
     * @return Image
     */
    public function setProperty(Property $property = null)
    {
        $this->property = $property;

        return $this;
    }

    /**
     * Get property
     *
     * @return Property
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * Sets file.
     *
     * @param  UploadedFile $file
     * @return Image
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;

        if (isset($this->path)) {
            $this->temp = $this->path;
            $this->path = null;
        } else {
            $this->path = 'initial';
        }

        return $this;
    }

    /**
     * Gets file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Get bundle directory
     *
     * @return string
     */
    protected function getBundleDir()
    {
        return 'bundles/propertyprivatelyproperty';
    }

    /**
     * Get public directory
     *
     * @return string
     */
    protected function getPublicDir()
    {
        return __DIR__ . '/../Resources/public';
    }

    /**
     * Get web directory
     *
     * @return string
     */
    protected function getWebDir()
    {
        return __DIR__ . '/../../../../web';
    }

    /**
     * Get upload directory
     *
     * @return string
     */
    protected function getUploadDir()
    {
        return '/images/properties/' . $this->getProperty()->getId();
    }

    /**
     * Get upload root directory
     *
     * @return string
     */
    protected function getUploadRootDir()
    {
        return $this->getPublicDir() . $this->getUploadDir();
    }

    /**
     * Get absolute path
     *
     * @return string
     */
    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir() . '/' . $this->path;
    }

    /**
     * Get web root path
     *
     * @return string
     */
    public function getWebPath()
    {
        return null === $this->path
            ? null
            : $this->getBundleDir() . $this->getUploadDir() . '/' . $this->path;
    }

    /**
     * Upload file
     *
     * @return Image
     */
    private function upload()
    {
        if (null !== $this->getFile()) {
            $filename   = sha1(uniqid(mt_rand(), true));
            $this->path = $filename . '.' . $this->getFile()->guessExtension();

            $this->getFile()->move($this->getUploadRootDir(), $this->path);

            if (isset($this->temp)) {
                unlink($this->getUploadRootDir() . '/' . $this->temp);

                $this->temp = null;
            }

            $this->file = null;
        }

        return $this;
    }

    /**
     * Clean up this entities files
     *
     * @return Image
     */
    private function removeUpload()
    {
        $fs = new Filesystem();

        if ($fs->exists($this->getAbsolutePath())) {
            $fs->remove($this->getAbsolutePath());
        }

        return $this;
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->upload();
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
        $this->upload();
    }

    /**
     * @ORM\PostRemove()
     */
    public function postRemove()
    {
        $this->removeUpload();
    }

    /**
     * @see ArrayableInterface::toArray()
     */
    public function toArray()
    {
        return array(
            'id'           => $this->id,
            'description'  => $this->description,
            'path'         => $this->path,
            'displayOrder' => $this->displayOrder
        );
    }
}
