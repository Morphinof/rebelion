<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 16/12/2017
 * Time: 21:06
 */

namespace Rebelion\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class AbstractRebelionController
 *
 * Contains common services between controllers
 *
 * @package Rebelion\Controller
 */
abstract class AbstractRebelionController extends AbstractController
{
    /** @var TranslatorInterface $translator */
    protected $translator;

    /**
     * CombatController constructor.
     *
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }
}