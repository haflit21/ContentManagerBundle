<?php

namespace ContentManagerBundle\ContentManagerBundle\Twig;

use Twig_Extension;
use Twig_Filter_Method;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\TwigBundle\Loader\FilesystemLoader;

class HtmlExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            'cutWord' => new Twig_Filter_Method($this, 'cutWordFilter'),
        );
    }

    public function cutWordFilter($content, $length = 30, $after = '...', $cutOn = ' ')
    {
        if (strlen($content)>$length)
            $content = substr((strip_tags($content)), 0, strrpos((substr((strip_tags($content)), 0, $length)), $cutOn)).$after;

        return $content;
    }

    public function getName()
    {
        return 'cutWord_extension';
    }
}
