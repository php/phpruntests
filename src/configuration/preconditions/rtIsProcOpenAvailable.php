<?php

/**
 * Class for checking whether proc_open() is available
 *
 */
class rtIsProcOpenAvailable extends rtPreCondition
{
    /**
     * Return the message associated with missing proc_open();
     *
     * @return text
     */
    public function getMessage()
    {
        return rtText::get('procOpenNotAvailable');
    }

    /**
     * Check that proc_open() is available
     *   
     * @param  rtRuntestsConfiguration $config
     * @return boolean
     */
    public function check(rtRuntestsConfiguration $config)
    {
    	return function_exists('proc_open');
    }
}
?>
