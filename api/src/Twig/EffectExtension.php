<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 30/01/2018
 * Time: 20:18
 */

namespace Rebelion\Twig;

use Rebelion\Entity\Container\Card;
use Rebelion\Entity\Effect\ProxyEffect;
use Rebelion\Service\EffectService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Class CardExtension
 *
 * @package Rebelion\Twig
 */
class EffectExtension extends AbstractExtension
{
    /** @var EffectService $effectService */
    private $effectService;

    /**
     * CardExtension constructor.
     *
     * @param EffectService $effectService
     */
    public function __construct(EffectService $effectService)
    {
        $this->effectService = $effectService;
    }

    /**
     * @return array
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('class_name', [$this, 'className', ['needs_context' => true]]),
        ];
    }

    /**
     * @param $class
     *
     * @return string
     */
    public function className($class): string
    {
        $last = explode($class, '\\');

        return ($last[count($last)]);
    }
}