<?php

declare(strict_types=1);

namespace Pest\Factories\Attributes;

use Pest\Factories\Covers\CoversClass;
use Pest\Factories\Covers\CoversFunction;
use Pest\Factories\TestCaseMethodFactory;

/**
 * @internal
 */
final class Uses extends Attribute
{
    /**
     * Determine if the attribute should be placed above the class instead of above the method.
     */
    public static bool $above = true;

    /**
     * Adds attributes regarding the "covers" feature.
     *
     * @param  array<int, string>  $attributes
     * @return array<int, string>
     */
    public function __invoke(TestCaseMethodFactory $method, array $attributes): array
    {
        foreach ($method->uses as $uses) {
            if ($uses instanceof CoversClass) {
                // Prepend a backslash for FQN classes
                if (str_contains($uses->class, '\\')) {
                    $uses->class = '\\'.$uses->class;
                }

                $attributes[] = "#[\PHPUnit\Framework\Attributes\UsesClass({$uses->class}::class)]";
            } elseif ($uses instanceof CoversFunction) {
                $attributes[] = "#[\PHPUnit\Framework\Attributes\UsesFunction('{$uses->function}')]";
            }
        }

        return $attributes;
    }
}
