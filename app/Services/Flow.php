<?php

namespace App\Services;

use App\Models\UserCheckpoint;
use App\Services\Operation;

class Flow
{
    protected $flow = [];
    private $name;

    public function __construct(String $name, array $flow)
    {
        $this->name = $name;
        foreach($flow as $key => $state) {
            $this->addState([$key => $state]);
        }
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function setFlow(array $flow)
    {
        foreach($flow as $key => $state) {
            $this->addState([$key => $state]);
        }
    }

    public function getFlow() : array
    {
        return $this->flow;
    }

    public function addState(array $state, string $after = null)
    {
        $key = array_keys($state)[0];
        $value = array_values($state)[0];
        if(is_numeric($key) or $key === null) { $key = $value; $value = []; }

        if($after) {
            $afterOrder = $this->isExist($after);
            if ($afterOrder >= 0) {
                $firstSlice = array_slice($this->flow, 0, $afterOrder + 1);
                $secondSlice = array_slice($this->flow, $afterOrder + 1, count($this->flow));
                $this->flow = $firstSlice;
                $this->flow[$key] = $value;
                $this->flow = array_merge($this->flow, $secondSlice);
            }
            else {
                $this->flow[$key] = $value;
            }
        }
        else {
            $this->flow[$key] = $value;
        }
    }

    private function isExist(string $state) : int
    {
        $flowKey = array_keys($this->flow);
        $stateKey = array_search($state, $flowKey);
        if($stateKey < 0 or $stateKey === false) { return -1; }
        return $stateKey;
    }

    public function addAccessory(Flow $accessory)
    {
        $prefix = $accessory->getName();
        foreach($accessory->getFlow() as $key => $state) {
            $this->flow['/' . $prefix . $key] = $state;
        }
    }

    public function getNextState(array $arguments, bool $saveCheckpoint = true): array
    {
        $next = '';
        $response = '';
        $userCheckpoint = UserCheckpoint::where('user_id',$arguments['user_id'])->first();
        if( ! empty($userCheckpoint->checkpoint)) {
            $currentStateName = $userCheckpoint->checkpoint;
            $currentStateName = substr($currentStateName, strpos($currentStateName, '/') + 1);

            $currentStateIndex = $this->isExist($currentStateName);
            if ($currentStateIndex < 0) {
                abort(404);
            }

            if (isset(array_keys($this->flow)[$currentStateIndex + 1])) {
                $next = array_keys($this->flow)[$currentStateIndex + 1];
            } else {
                abort(404);
            }

            if (!$this->flow[$currentStateName] == null) {
                foreach ($this->flow[$currentStateName] as $operation) {
                    $response = $operation->getNextState($arguments);
                    if (!empty($response['error'])) {
                        return ['next' => '', 'error' => $response['error']];
                    } elseif (!empty($response['next'])) {
                        $next = $response['next'];
                        break;
                    }
                }
            }
        }
        else {
            $next = array_keys($this->flow)[0];
        }

        $next = $this->name . "/" . $next;
        if($next and $saveCheckpoint) { UserCheckpoint::updateOrCreate(['user_id'=>$arguments['user_id']],['checkpoint'=>$next]); }
        return ['next' => $next, 'error' => ''];
    }

}
