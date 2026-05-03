<?php

/**
 * Determine if a cabinet menu item should be visible based on config.
 */
if (! function_exists('isMenuItemVisible')) {
    function isMenuItemVisible(string $key): bool
    {
        return ! in_array($key, config('cabinet.menu.items_hidden', []), true);
    }
}
