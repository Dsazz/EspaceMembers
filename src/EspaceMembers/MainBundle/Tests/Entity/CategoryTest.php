<?php
/**
 * This file is part of the EspaceMembers project.
 *
 * (c) Stanislav Stepanenko <dsazztazz@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EspaceMembers\MainBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use EspaceMembers\MainBundle\Entity\Category;

class CategoryTest extends KernelTestCase
{
    public function testGetName()
    {
        $employee = $this->getMock('\EspaceMembers\MainBundle\Entity\Category');
        $employee->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('Dsazz'));

        $this->assertEquals('Dsazz', $employee->getName());
    }
}
