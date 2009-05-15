<?php

/**
 * Class for setting the PHP CGI executable
 *
 */
class rtPhpCgiExecutableSetting extends rtSetting
{
    const SAPI_CGI = "/sapi/cgi/php-cgi";

    private $phpCgiExecutable;

    private $configuration;

    /**
     * Sets the PHP CGI executable. Note the dependency on having a working directory setting
     *
     */
    public function init(rtRuntestsConfiguration $configuration)
    {
        $this->configuration = $configuration;

        if ($configuration->hasEnvironmentVariable('TEST_PHP_CGI_EXECUTABLE')) {
            if($configuration->getEnvironmentVariable('TEST_PHP_CGI_EXECUTABLE') != null) {
                if ($configuration->getEnvironmentVariable('TEST_PHP_CGI_EXECUTABLE') == 'auto') {

                    $rtWorkingDirectorySetting = new rtWorkingDirectorySetting($configuration);
                    $this->phpCgiExecutable = $rtWorkingDirectorySetting->get() . self::SAPI_CGI;
                } else {
                    $this->phpCgiExecutable = $configuration->getEnvironmentVariable('TEST_PHP_CGI_EXECUTABLE');
                }
            }
        }
    }

    /**
     * @todo spriebsch: does this method need to be public, is it only called from get()?
     * @todo zoe:This method only works if we are running from a PHP source tree, do we need to
     * cope with /usr/local/bin/php for example?
     */
    public function guessFromPhpCli($phpCli)
    {
        if(substr(dirname($phpCli),-3) == 'cli') {
            $pathLength = strlen(dirname($phpCli)) - 3;
            $sapiDir = substr(dirname($phpCli), 0, $pathLength);
            $this->phpCgiExecutable = $sapiDir."cgi/php-cgi";
        }
    }

    /**
     * Returns path to PHP CGI executable.
     * If not set, we guess based on the path to the PHP CLI executable.
     *
     * @return string
     */
    public function get()
    {
        if (is_null($this->phpCgiExecutable)) {

            // We ask rtPhpExecutableSetting for the path to the PHP executable.
            $rtPhpExecutableSetting = new rtPhpExecutableSetting($this->configuration);
            $this->guessFromPhpCli($rtPhpExecutableSetting->get());
        }

        return $this->phpCgiExecutable;
    }
}
?>
