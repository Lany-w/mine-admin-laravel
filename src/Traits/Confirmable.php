<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/8 16:44
 */

namespace Lany\MineAdmin\Traits;

use Closure;
use Composer\InstalledVersions;


trait Confirmable
{
    /**
     * Confirm before proceeding with the action.
     *
     * This method only asks for confirmation in production.
     */
    public function confirmToProceed(string $warning = 'Application In Production!', null|bool|Closure $callback = null): bool
    {
        $callback ??= $this->isShouldConfirm();

        $shouldConfirm = value($callback);

        if ($shouldConfirm) {
            if ($this->input->getOption('force')) {
                return true;
            }

            $this->alert($warning);

            $confirmed = $this->confirm('Do you really wish to run this command?');

            if (! $confirmed) {
                $this->comment('Command Cancelled!');

                return false;
            }
        }

        return true;
    }

    protected function isShouldConfirm(): bool
    {
        return is_callable(['Composer\InstalledVersions', 'getRootPackage'])
            && (InstalledVersions::getRootPackage()['dev'] ?? false) === false;
    }
}