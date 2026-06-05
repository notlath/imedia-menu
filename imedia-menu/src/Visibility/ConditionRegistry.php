<?php

declare(strict_types=1);

namespace IMedia\Menu\Visibility;

final class ConditionRegistry
{
    private ConditionEvaluator $evaluator;

    public function __construct()
    {
        $this->evaluator = new ConditionEvaluator();
    }

    public function getConditions(): array
    {
        $conditions = $this->evaluator->getAll();

        $result = [];

        foreach ($conditions as $condition) {
            $result[$condition->type()] = [
                'type'  => $condition->type(),
                'label' => $condition->label(),
            ];
        }

        return $result;
    }

    public function getEvaluator(): ConditionEvaluator
    {
        return $this->evaluator;
    }
}
