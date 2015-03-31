<?php
namespace EspaceMembers\MainBundle\Twig;

class FunctionExtension extends \Twig_Extension
{
    public function getFunctions()
    {
        $optionsForRaw = ['is_safe' => ['all']];

        return array(
            'preg_match_result' => new \Twig_SimpleFunction(
                'preg_match_result', [$this, 'getPregMatchResult']
            ),

            'dump' => new \Twig_SimpleFunction(
                'dump', [$this, 'preDump'], $optionsForRaw
            ),
        );
    }

    public function getPregMatchResult($pattern = '', $string = '')
    {
        preg_match($pattern, $string, $matches);

        return $matches;
    }

    public function pre($stringable)
    {
        return "<pre>" . (string) $stringable . "</pre>";
    }

    public function preDump($values)
    {
        return $this->pre(print_r($values, 1));
    }

    public function getName()
    {
        return 'espace_members_function_extension';
    }
}
