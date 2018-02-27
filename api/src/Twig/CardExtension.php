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
use Symfony\Component\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Class CardExtension
 *
 * @package Rebelion\Twig
 */
class CardExtension extends AbstractExtension
{
    /** @var TranslatorInterface $translator */
    private $translator;

    /** @var EffectService $effectService */
    private $effectService;

    /**
     * CardExtension constructor.
     *
     * @param TranslatorInterface $translator
     * @param EffectService       $effectService
     */
    public function __construct(TranslatorInterface $translator, EffectService $effectService)
    {
        $this->translator    = $translator;
        $this->effectService = $effectService;
    }

    /**
     * @return array
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('dynamic_description', [$this, 'dynamicDescription', ['needs_context' => true]]),
            new TwigFilter('iconize', [$this, 'iconize', ['needs_context' => true]]),
        ];
    }

    /**
     * @param Card $card
     *
     * @return string
     */
    public function dynamicDescription(Card $card): string
    {
        $trans       = $this->translator;
        $description = [];

        /** @var ProxyEffect $proxy */
        foreach ($card->getEffects() as $proxy) {
            $effect = $proxy->getParent();

            try {
                $defaults = $this->effectService->getEffectClassParameters($effect->getClass());
            } catch (\ReflectionException $e) {
                return sprintf('Error : %s', $e->getMessage());
            }

            if (($proxy->getParameters()['value'] ?? null) !== null) {
                $description[] = sprintf($trans->trans($effect->getClassName() . '_description'), (int)$proxy->getParameters()['value']);
            } else {
                if ($defaults !== null && ($defaults['default'] ?? null) !== null) {
                    $description[] = sprintf($trans->trans($effect->getClassName() . '_description'), (int)$defaults['default']);
                }
            }
        }

        if (empty($description)) {
            return 'card_has_no_effect';
        }

        return implode(', ', $description);
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public function iconize(string $string): string
    {
        return preg_replace_callback(
            '/(@iconize:\w*)/ui',
            function ($matches) {
                $icon = explode(':', $matches[0]);

                return sprintf('<i class="material-icons tiny dp48">%s</i>', $icon[1]);
            },
            $string
        );
    }
}