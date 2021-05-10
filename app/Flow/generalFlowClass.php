<?php

namespace App\Flow;

abstract class generalFlowClass
{
    protected $flow = [];
    protected $name = '';

    abstract public function __construct();

    public function getName() : string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getFlow() : array
    {
        return $this->flow;
    }

    public function setFlow(array $flow)
    {
        foreach($flow as $key => $state) {
            $this->addStates([$key => $state]);
        }
    }

    private function isExist(string $state) : int
    {
        $flowKey = array_keys($this->flow);
        $stateKey = array_search($state, $flowKey);
        if($stateKey < 0 or $stateKey === false) { return -1; }
        return $stateKey;
    }

    public function addStates(array $states, string $after = null)
    {
        for ($index=0; $index < count($states); $index++) {
            $key = array_keys($states)[$index];
            $value = array_values($states)[$index];

            if (is_numeric($key) or $key === null) {
                $key = $value;
                $value = [];
            }

            $key = '/' . $this->name . $key;

            if ($after) {
                $afterOrder = $this->isExist($after);
                if ($afterOrder >= 0) {
                    $firstSlice = array_slice($this->flow, 0, $afterOrder + 1);
                    $secondSlice = array_slice($this->flow, $afterOrder + 1, count($this->flow));
                    $this->flow = $firstSlice;
                    $this->flow[$key] = $value;
                    $this->flow = array_merge($this->flow, $secondSlice);
                    $after = $key;
                } else {
                    $this->flow[$key] = $value;
                }
            } else {
                $this->flow[$key] = $value;
            }
        }
    }

    public function addAccessory(generalFlowClass $accessory, string $after = null)
    {
        $prefix = $this->name;
        $this->addStates($accessory->getFlow(), $after);

//        foreach($accessory->getFlow() as $key => $state) {
//            $this->flow['/' . $prefix . $key] = $state;
//        }
    }
}
