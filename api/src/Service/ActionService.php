<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 10/12/2017
 * Time: 13:02
 */

namespace Rebelion\Service;

use Rebelion\Abstracts\ServiceAbstract;
use Rebelion\Entity\Turn;
use Rebelion\Enum\ActionTypeEnum;

class ActionService extends ServiceAbstract
{
    /**
     * Skip the combat current turn
     *
     * @param Turn $turn
     *
     * @return Action
     */
    public function skip(Turn $turn): Action
    {
        return self::create($turn, ActionTypeEnum::SKIP_TURN);
    }

    /**
     * End the combat current turn
     *
     * @param Turn $turn
     *
     * @return Action
     */
    public function end(Turn $turn): Action
    {
        return self::create($turn, ActionTypeEnum::END_TURN);
    }

    /**
     * Create a new action for the given turn
     *
     * @param Turn   $turn
     * @param string $type
     *
     * @return Action
     */
    public static function create(Turn $turn, $type = ActionTypeEnum::END_TURN): Action
    {
        $action = new Action();
        $action->setTurn($turn);
        $action->setType($type);

        return $action;
    }
}