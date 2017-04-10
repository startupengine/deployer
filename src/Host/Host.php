<?php
/* (c) Anton Medvedev <anton@medv.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Deployer\Host;

use Deployer\Configuration\Configuration;
use Deployer\Configuration\ConfigurationAccessor;

class Host
{
    use ConfigurationAccessor;

    private $hostname;
    private $user;
    private $port;
    private $configFile;
    private $identityFile;
    private $forwardAgent = true;
    private $multiplexing = null;
    private $options = [];

    /**
     * @param string $hostname
     */
    public function __construct(string $hostname)
    {
        $this->hostname = $hostname;
        $this->config = new Configuration();
    }

    /**
     * Generate options string for ssh
     *
     * @return string
     */
    public function sshOptions()
    {
        $options = '';

        if ($this->port) {
            $options .= " -p {$this->port}";
        }

        if ($this->configFile) {
            $options .= " -F {$this->configFile}";
        }

        if ($this->identityFile) {
            $options .= " -i {$this->identityFile}";
        }

        if ($this->forwardAgent) {
            $options .= " -A";
        }

        foreach ($this->options as $option) {
            $options .= " -o $option";
        }

        return $options;
    }

    /**
     * Returns pair user/hostname
     *
     * @return string
     */
    public function __toString()
    {
        $user = empty($this->user) ? '' : "{$this->user}@";
        $hostname = preg_replace('/\/.+$/', '', $this->hostname);
        return "$user$hostname";
    }

    /**
     * @return string
     */
    public function getHostname()
    {
        return $this->hostname;
    }

    /**
     * @param string $hostname
     * @return $this
     */
    public function hostname(string $hostname)
    {
        $this->hostname = $hostname;
        return $this;
    }

    /**
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param string $user
     * @return $this
     */
    public function user(string $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param int $port
     * @return $this
     */
    public function port(int $port)
    {
        $this->port = $port;
        return $this;
    }

    /**
     * @return string
     */
    public function getConfigFile()
    {
        return $this->configFile;
    }

    /**
     * @param string $configFile
     * @return $this
     */
    public function configFile(string $configFile)
    {
        $this->configFile = $configFile;
        return $this;
    }

    /**
     * @return string
     */
    public function getIdentityFile()
    {
        return $this->identityFile;
    }

    /**
     * @param string $identityFile
     * @return $this
     */
    public function identityFile(string $identityFile)
    {
        $this->identityFile = $identityFile;
        return $this;
    }

    /**
     * @return bool
     */
    public function isForwardAgent()
    {
        return $this->forwardAgent;
    }

    /**
     * @param bool $forwardAgent
     * @return $this
     */
    public function forwardAgent(bool $forwardAgent = true)
    {
        $this->forwardAgent = $forwardAgent;
        return $this;
    }

    /**
     * @return bool
     */
    public function isMultiplexing()
    {
        return $this->multiplexing;
    }

    /**
     * @param bool $multiplexing
     * @return $this
     */
    public function multiplexing(bool $multiplexing = true)
    {
        $this->multiplexing = $multiplexing;
        return $this;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param array $options
     * @return $this
     */
    public function options(array $options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @param string $option
     * @return $this
     */
    public function addOption(string $option)
    {
        $this->options[] = $option;
        return $this;
    }

    /**
     * Set stage
     *
     * @param string $stage
     * @return $this
     */
    public function stage(string $stage)
    {
        $this->config->set('stage', $stage);
        return $this;
    }

    /**
     * Set roles
     *
     * @param array ...$roles
     * @return $this
     */
    public function roles(...$roles)
    {
        $this->config->set('roles', []);

        foreach ($roles as $role) {
            $this->config->add('roles', [$role]);
        }

        return $this;
    }

    /**
     * Set become
     *
     * @param string $user
     * @return $this
     */
    public function become(string $user)
    {
        $this->config->set('become', $user);
        return $this;
    }
}