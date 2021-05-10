<?php

namespace App\Flow;


use App\Models\UserCheckpoint;

class Flow
{
    private static $flow = [];
    private static $mainFlowName;

    public static function getMainFlow()
    {
        return self::$mainFlowName;
    }

    public static function setMainFlow(generalFlowClass $mainFlow)
    {
        self::$mainFlowName = $mainFlow->getName();
    }

    private static function isExist(string $state) : int
    {
        $flowKeys = array_keys(self::$flow);
        $stateKey = array_search($state, $flowKeys);
        if($stateKey < 0 or $stateKey === false) { return -1; }
        return $stateKey;
    }

    public static function separateCheckpoint(string $checkpoint): array
    {
        $flowName = '';
        $state = '';
        $slashPosition = strpos($checkpoint, '/',1);
        if ($slashPosition > 0) {
            $flowName = substr($checkpoint, 1, $slashPosition -1);
            $state = substr($checkpoint, $slashPosition);
        }
        return array($flowName, $state);
    }

    public static function getNextState(array $arguments, bool $saveCheckpoint = true): array
    {
        $next = '';
        $userCheckpoint = UserCheckpoint::where('user_id', $arguments['user_id'])->first();
        if (empty($userCheckpoint->checkpoint)) {
            $currentFlowName = self::$mainFlowName;  // don't set yet!
            dd($currentFlowName);
//            $checkpoint = array_keys(self::$flow)[0];     incorrect: there is not any state in flow
        } else {
            $checkpoint = $userCheckpoint->checkpoint;
            [$currentFlowName, $currentState] = self::separateCheckpoint($checkpoint);
        }

        $flowClassName = __NAMESPACE__ .'\\'. $currentFlowName . 'Flow';
        $flowObj = new $flowClassName;
        self::$flow = call_user_func(array($flowObj,'getFlow'));

        $currentStateIndex = self::isExist($checkpoint);

        if ($currentStateIndex < 0) {
            abort(404);
        }

        if (isset(array_keys(self::$flow)[$currentStateIndex + 1])) {
            $next = array_keys(self::$flow)[$currentStateIndex + 1];
        } else {
            abort(404);
        }

        if ( ! self::$flow[$checkpoint] == null) {
            foreach (self::$flow[$checkpoint] as $operation) {
                $response = $operation->getNextState($arguments);
                if ( ! empty($response['error'])) {
                    return ['next' => '', 'error' => $response['error']];
                } elseif (!empty($response['next'])) {
                    $next = $response['next'];
                    break;
                }
            }
        }

        if ($next and $saveCheckpoint) {
            UserCheckpoint::updateOrCreate(['user_id' => $arguments['user_id']], ['checkpoint' => $next]);
        }
        return ['next' => $next, 'error' => ''];
    }
}
