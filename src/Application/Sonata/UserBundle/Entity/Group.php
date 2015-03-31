<?php
/**
 * This file is part of the EspaceMembers project.
 *
 * (c) Stanislav Stepanenko dsazztazz@gmail.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Entity\Group as BaseGroup;

/**
 * Represents a Base Group Entity
 */
class Group extends BaseGroup
{
    protected $events;

    public function __toString()
    {
        return $this->getName() ?: '';
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
     * Constructor
     */
    public function __construct()
    {
        $this->addRole('ROLE_USER');
    }

    public function addRole($role)
    {
        if (is_array($this->roles)) {
            if (!in_array($role, $this->roles, true)) {
                $this->roles[] = $role;
            }
        } else {
            $this->roles[] = $role;
        }

        return $this;
    }
}
