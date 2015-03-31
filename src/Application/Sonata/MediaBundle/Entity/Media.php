<?php
/**
 * This file is part of the EspaceMembers project.
 *
 * (c) Stanislav Stepanenko <dsazztazz@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\MediaBundle\Entity;

use Sonata\MediaBundle\Entity\BaseMedia as BaseMedia;

/**
 * Media
 *
 * @author Stanislav Stepanenko <dsazztazz@gmail.com>
 */
class Media extends BaseMedia
{
    protected $id;
    protected $playtime;

    /**
     * Get id
     *
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set playtime
     *
     * @param  string $playtime
     * @return Media
     */
    public function setPlaytime($playtime)
    {
        $this->playtime = $playtime;

        return $this;
    }

    /**
     * Get playtime
     *
     * @return string
     */
    public function getPlaytime()
    {
        return $this->playtime;
    }
}
