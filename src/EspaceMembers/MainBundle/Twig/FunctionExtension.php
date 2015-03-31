<?php
namespace EspaceMembers\MainBundle\Twig;

class FunctionExtension extends \Twig_Extension
{
    public function getFunctions()
    {
        return array(
            'preg_match_result' => new \Twig_SimpleFunction('preg_match_result', array($this, 'getPregMatchResult'))
        );
    }

    public function getPregMatchResult($pattern = '', $string = '')
    {
        preg_match($pattern, $string, $matches);

        return $matches;
    }

    public function getName()
    {
        return 'espace_members_function_extension';
    }
}
