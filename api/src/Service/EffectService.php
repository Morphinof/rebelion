<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 15/12/2017
 * Time: 22:08
 */

namespace Rebelion\Service;

use Rebelion\Abstracts\ServiceAbstract;
use Rebelion\Annotations\EffectAnnotation;
use Rebelion\Entity\Action\PlayCard;
use Rebelion\Entity\Container\Card;
use Rebelion\Entity\Effect\Effect;
use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Rebelion\Entity\Effect\ProxyEffect;
use Rebelion\Entity\Target;
use Rebelion\Event\Combat\ProxyEffectResolved;
use Rebelion\Exceptions\EffectResolveException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Translation\TranslatorInterface;

class EffectService extends ServiceAbstract
{
    /** @var TranslatorInterface $translator */
    private $translator;

    /** @var LoggerInterface $logger */
    private $logger;

    /** @var EntityManagerInterface */
    private $em;

    /** @var Reader */
    private $annotationReader;

    /** @var EffectResolverService */
    private $effectResolver;

    /** @var EventDispatcherInterface */
    private $dispatcher;

    /**
     * EffectService constructor.
     *
     * @param TranslatorInterface      $translator
     * @param LoggerInterface          $logger
     * @param EntityManagerInterface   $entityManager
     * @param Reader                   $annotationReader
     * @param EffectResolverService    $effectResolver
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(
        TranslatorInterface $translator,
        LoggerInterface $logger,
        EntityManagerInterface $entityManager,
        Reader $annotationReader,
        EffectResolverService $effectResolver,
        EventDispatcherInterface $dispatcher
    ) {
        $this->translator       = $translator;
        $this->logger           = $logger;
        $this->em               = $entityManager;
        $this->annotationReader = $annotationReader;
        $this->effectResolver   = $effectResolver;
        $this->dispatcher       = $dispatcher;
    }

    /**
     * @param PlayCard $action
     *
     * @return bool
     * @throws EffectResolveException
     */
    public function resolvePlayCardAction(PlayCard $action): bool
    {
        $card          = $action->getCard();
        $turn          = $action->getTurn();
        $actionTargets = $action->getTargets();

        /**@var ProxyEffect $effect */
        foreach ($card->getEffects() as $index => $effect) {
            $numberOfTargets = $effect->getParent()->getTargetMode(true);
            $targets         = [];

            for ($i = 0; $i < $numberOfTargets; $i++) {
                if (!isset($actionTargets[$index + $i])) {
                    throw new EffectResolveException($effect, sprintf('Effect missing target'));
                }

                $targetId = $actionTargets[$index + $i];
                $target   = $this->em->getRepository(Target::class)->find($targetId);

                if (!$target instanceof Target) {
                    throw new EffectResolveException($effect, sprintf('Unknown target #%s', $targetId));
                }

                $targets[] = $target;
            }

            if (!$this->effectResolver->resolve($turn->getCombat(), $effect, $targets)) {
                throw new EffectResolveException(
                    $effect,
                    sprintf(
                        'Failed to resolve proxy effect #%d (%s)',
                        $effect->getId(),
                        $effect->getParent()->getName()
                    )
                );
            }

            $event = new ProxyEffectResolved($effect, $targets);
            $this->dispatcher->dispatch(ProxyEffectResolved::NAME, $event);
        }

        return true;
    }

    /**
     * @return array|Effect[]
     */
    public function getEffects()
    {
        return $this->em->getRepository('Rebelion:Effect')->findAll();
    }

    /**
     * Search in Rebelion\Effect for effect base classes
     *
     * @param bool $formChoices
     *
     * @return array|null
     */
    public function getEffectsClasses($formChoices = false): ?array
    {
        $classes = [];
        $finder  = new Finder();
        $finder->files()->in(__DIR__ . '/../Effect');

        foreach ($finder as $effectClassFile) {
            $className = $effectClassFile->getBasename('.php');
            try {
                $fullClassName = 'Rebelion\\Effect\\' . $className;
                $object        = new \ReflectionClass($fullClassName);

                /** @var EffectAnnotation $apiMetaAnnotation */
                $apiMetaAnnotation = $this->annotationReader->getClassAnnotation(
                    $object,
                    'Rebelion\\Annotations\\EffectAnnotation'
                );

                # TODO: transform this in unit test

                #if (!$apiMetaAnnotation) {
                #    throw new \Exception(sprintf('EffectClass file %s does not have required annotation EffectAnnotation', $effectClassFile));
                #}

                if (!$formChoices) {
                    $classes[$this->translator->trans($fullClassName, 'X')] = $className;
                } else {
                    $classes[$className] = $fullClassName;
                }

            } catch (\ReflectionException $exception) {
                $classes = null;
            }
        }

        return $classes;
    }

    /**
     * @param string $class
     *
     * @return array|null
     *
     * @throws \ReflectionException
     */
    public function getEffectClassParameters(string $class): ?array
    {
        $object = new \ReflectionClass($class);

        /** @var EffectAnnotation $apiMetaAnnotation */
        $apiMetaAnnotation = $this->annotationReader->getClassAnnotation(
            $object,
            'Rebelion\\Annotations\\EffectAnnotation'
        );

        $parameters = null;
        if ($apiMetaAnnotation !== null) {
            $parameters = $apiMetaAnnotation->__toArray();
        }

        return $parameters;
    }

    /**
     * @param Card          $card
     * @param FormInterface $form
     */
    public function saveCardEffects(Card $card, FormInterface $form): void
    {
        $parameters = [];
        foreach ($form->all() as $child) {
            if ($child instanceof Form) {
                $matches = [];
                if (preg_match('/^proxy_effect_(.*)/', $child->getConfig()->getName(), $matches)) {
                    $proxyEffectId = $matches[1];

                    /** @var ProxyEffect $proxyEffect */
                    $proxyEffect = $this->em->getRepository('Rebelion:Effect\ProxyEffect')->find($proxyEffectId);

                    $parameters['value'] = $child->getData();

                    $proxyEffect->setParameters($parameters);

                    $this->em->persist($proxyEffect);
                    $this->em->flush();
                }
            }
        }
    }
}
