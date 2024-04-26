<?php

/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/26 14:02
 */
namespace Lany\MineAdmin\Interfaces;
interface IdGeneratorInterface
{
    /**
     * Generate an ID by meta, if meta is null, then use the default meta.
     */
    public function generate(?Meta $meta = null): int;

    /**
     * Degenerate the meta by ID.
     */
    public function degenerate(int $id): Meta;
}