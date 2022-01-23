<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('hello', [$this, 'formatPrice']),
        ];
    }

    public function formatPrice(): string
    {
        return "1";
    }
}