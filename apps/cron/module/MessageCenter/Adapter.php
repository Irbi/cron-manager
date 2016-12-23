<?php
/**
 * @namespace
 */
namespace MessageCenter;

use MessageCenter\Model\Exchange,
    MessageCenter\Model\Queue,
    MessageCenter\Model\QueueToExchange;

/**
 * Class Adapter
 * @package MessageCenter
 */
/**
 * Class Adapter
 *
 * @package MessageCenter
 */
class Adapter
{
    /**
     * Queue adapter
     * @var string
     */
    private $_adapter;

    /**
     * Host adress
     * @var string
     */
    private $_host;

    /**
     * Port
     * @var string
     */
    private $_port;

    /**
     * User name for auth
     * @var string
     */
    private $_username;

    /**
     * Password
     * @var string
     */
    private $_password;

    /**
     * Virtual host
     * @var string
     */
    private $_vhost;

    /**
     * Connection type
     * @var string
     */
    private $_type;

    /**
     * Config object
     * @var array
     */
    private $_config;

    /**
     * Construct
     *
     * @param \stdClass|\Phalcon\Config $options
     * @throws \InvalidArgumentException
     */
    public function __construct($options)
    {
        if (!($options instanceof \stdClass) && !($options instanceof \Phalcon\Config)) {
            throw new \InvalidArgumentException('Allow only instance of stdClass or \Phalcon\Config');
        }

        $this->_config = array();
        $this->_config['adapter'] = $options->adapter;
        $this->_config['host'] = $options->host;
        $this->_config['port'] = $options->port;
        $this->_config['username'] = $options->username;
        $this->_config['password'] = $options->password;
        $this->_config['vhost'] = $options->vhost;
        $this->_config['type'] = $options->type;
        $this->_config['exchangeType'] = $options->exchangeType;
        $this->_config['exchangePrefix'] = $options->exchangePrefix;
        $this->_config['queuePrefix'] = $options->queuePrefix;

        $this->_config['storageQueue'] = (isset($options->queueModel)) ? new $options->queueModel : new Queue();
        $this->_config['storageQueueRouters'] = (isset($options->queueToExchangeModel)) ? new $options->queueToExchangeModel : new QueueToExchange();
        $this->_config['storageExchange'] = (isset($options->exchangeModel)) ? new $options->exchangeModel : new Exchange();

        if (isset($options->connection)) {
            $this->_config['storageQueue']->setConnectionService($options->connection);
            $this->_config['storageQueueRouters']->setConnectionService($options->connection);
            $this->_config['storageExchange']->setConnectionService($options->connection);
        }
    }

    /**
     * Return exchange adapter
     *
     * @param string $name
     * @return \QueueCenter\Exchange
     */
    public function getExchange($name)
    {
        return new \QueueCenter\Exchange($name, $this->_config);
    }

    /**
     * Return storage exchange adapter
     *
     * @return \QueueCenter\Storage\Exchange
     */
    public function getStorageExchange()
    {
        return new \QueueCenter\Storage\Exchange($this->_config);
    }

    /**
     * Add new user uxchange to queue
     *
     * @param integer $userId
     * @param string $name
     * @return boolean
     */
    public function addUserExchange($userId, $name)
    {
        return \QueueCenter\Exchange::addUserExchange($this->_config, $userId, $name);
    }

    /**
     * Publish new user message to exchange
     *
     * @param integer $userId
     * @param string $name
     * @param array|string $message
     * @param string $handler
     * @return boolean
     */
    public function publishUserMessage($userId, $name, $message, $handler, $routingKey = "*")
    {
        return \QueueCenter\Exchange::publishToUserExchange($this->_config, $userId, $name, $message, $handler, $routingKey);
    }

    /**
     * Return user exchange name by id and name
     *
     * @param integer $userId
     * @param string $name
     * @return string
     */
    public function generateUserExchangeName($userId, $name)
    {
        return \QueueCenter\Exchange::generateUserExchangeName($userId, $name);
    }

    /**
     * Return queue adapter
     *
     * @param string $name
     * @return \QueueCenter\Queue
     */
    public function getQueue($name)
    {
        return new \QueueCenter\Queue($name, $this->_config);
    }

    /**
     * Return queue storage
     *
     * @return \QueueCenter\Storage\Queue
     */
    public function getStorageQueue()
    {
        return new \QueueCenter\Storage\Queue($this->_config);
    }

    /**
     * Add user quque
     *
     * @param integer $userId
     * @param string $name
     * @return boolean
     */
    public function addUserQueue($userId, $name)
    {
        return \QueueCenter\Queue::addUserQueue($this->_config, $userId, $name);
    }

    /**
     * Return user queue name by id and name
     *
     * @param integer $userId
     * @param string $name
     * @return string
     */
    public function generateUserQueueName($userId, $name)
    {
        return \QueueCenter\Queue::generateUserQueueName($userId, $name);
    }

    /**
     * Bind user queue to exchange
     *
     * @param integer $userId
     * @param string $name
     * @param integer $exchangeId
     * @return boolean
     */
    public function bindUserQueue($userId, $name, $exchangeId, $routingKey = "*")
    {
        return \QueueCenter\Queue::bindUserQueue($this->_config, $userId, $name, $exchangeId, $routingKey);
    }

    /**
     * Unbind user queue from exchange
     *
     * @param integer $userId
     * @param string $name
     * @param integer $exchangeId
     * @return boolean
     */
    public function unbindUserQueue($userId, $name, $exchangeId, $routingKey = "*")
    {
        return \QueueCenter\Queue::unbindUserQueue($this->_config, $userId, $name, $exchangeId, $routingKey);
    }

    /**
     * Return queue handler adapter
     *
     * @return \QueueCenter\Queue\Handler
     */
    public function getHandler()
    {
        return new \QueueCenter\Queue\Handler($this->_config);
    }

    /**
     * @return array|bool
     */
    public function getDefaultApplicationExchange()
    {
        $exchangeName = $this->generateUserExchangeName(0, 'application');
        $storage = $this->getStorageExchange();
        $exchange = $storage->getByName($exchangeName);
        if (!$exchange) {
            $this->addUserExchange(0, 'application');
            $exchange = $storage->getByName($exchangeName);
        }

        return $exchange;
    }

    /**
     * Publish to apppicker default application exchange
     *
     * @param $message
     * @param $handler
     * @param string $routingKey
     * @return mixed
     */
    public function publishToDefaultApplicationExchange($message, $routingKey = "*")
    {
        return $this->publishUserMessage('0', 'application', $message, 'mail-notification', $routingKey);
    }

    /**
     * Publish to apppicker default application exchange
     *
     * @param $message
     * @param $handler
     * @param string $routingKey
     * @return mixed
     */
    public function publishToDefaultExchange($message, $handler = 'default', $routingKey = "*")
    {
        return $this->publishUserMessage('0', 'default', $message, $handler, $routingKey);
    }

    /**
     * @return array|bool
     */
    public function getDefaultIosExchange()
    {
        $exchangeName = $this->generateUserExchangeName(0, 'ios');
        $storage = $this->getStorageExchange();
        $exchange = $storage->getByName($exchangeName);
        if (!$exchange) {
            $this->addUserExchange(0, 'ios');
            $exchange = $storage->getByName($exchangeName);
        }

        return $exchange;
    }

    /**
     * Publish to apppicker default IOs exchange
     *
     * @param mixed $message
     * @param string $routingKey
     * @return boolean
     */
    public function publishToDefaultIOsExchange($message, $routingKey = "*")
    {
        return $this->publishUserMessage('0', 'ios', $message, 'push-notification', $routingKey);
    }

    /**
     * @return array|bool
     */
    public function getDefaultExchange()
    {
        $exchangeName = $this->generateUserExchangeName(0, 'default');
        $storage = $this->getStorageExchange();
        $exchange = $storage->getByName($exchangeName);
        if (!$exchange) {
            $this->addUserExchange(0, 'default');
            $exchange = $storage->getByName($exchangeName);
        }

        return $exchange;
    }

    /**
     * @param $details
     */
    public function publishAppsalepublishConfirmation($details)
    {
        $this->publishToDefaultExchange(
            array(
                'template' => 'appsalepublish',
                'article_id' => $details['article_id'],
                'username' => $details['username'],
                'email' => $details['email'],
                'title' => $details['title'],
                'published_at' => $details['published_at'],
                'article_title' => $details['articleTitle'],

            ),
            'confirmation',
            'confirmation.*'
        );
    }

    /**
     * @param $email
     * @param null $details
     */
    public function publishAutoAppsaleCreationConfirmation($email, $details = null)
    {
        $this->publishToDefaultExchange(
            array(
                'type' => 4,
                'email' => $email,
                'username' => 'tester',
                'notification' => $details,
            ),
            'confirmation',
            'confirmation.*'
        );
    }
}