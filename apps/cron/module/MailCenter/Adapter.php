<?php
namespace MailCenter;

class Adapter
{
	
	/**
	 * Cron config
	 * @var \stdClass
	 */
	private $_config;
			
	public function __construct($config)
	{
		$this->_config = $config;
	}
	
	/**
	 * Return configuration object for MailCenter
	 * 
	 * @return \stdClass
	 */
	public function getConfig()
	{
		$config = new \stdClass();
		$config->siteurl = $this->_config->siteurl;
		$config->host = $this->_config->host;
		$config->port = $this->_config->port;
		$config->dbname = $this->_config->dbname;
		$config->username = $this->_config->username;
		$config->password = $this->_config->password;
		$config->path = $this->_config->path;
		$config->options = $this->_config->options;
		$config->affiliateId = $this->_config->affiliateId;

		return $config;
	}

	/**
	 *
	 * @param string $type
	 * @param array $options
	 * @param array $emails
	 * @return void
	 */
	public function send($type, $options = null, $emails = null)
	{
		$mailing = new \MailCenter\Mailing($this->getConfig(), $type, $options, $emails);
		$mailing->run();
	}
}