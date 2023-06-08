<?php
/**
 * Dump and Die Function
 *
 * This function is used for debugging purposes to quickly inspect the value of a variable and terminate the script execution.
 *
 * @param mixed $val The variable to be dumped and displayed.
 * @return void
 */
function dd($val)
{
    echo '<pre>';
    die(var_dump($val));
    echo '</pre>';
}
