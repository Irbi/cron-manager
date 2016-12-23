<?php
namespace Cron;

use Phalcon\Mvc\ModuleDefinitionInterface;

class Module implements ModuleDefinitionInterface
{

    /**
     * Register a specific autoloader for the module
     */
    public function registerAutoloaders(\Phalcon\DiInterface $dependencyInjector = NULL)
    {
        $loader = new \Phalcon\Loader();

        $namespaces = [
            'Cron\Task'	    => CRON_PATH.'/task/',
            'Engine'        => ENGINE_PATH
        ];

        $mainModuleDir = CRON_PATH.'/module';
        $moduleDirs = scandir($mainModuleDir);
        foreach($moduleDirs as $module) {
            $moduleDir = $mainModuleDir.'/'.$module;
            if ($module == '.' || $module == '..' || !is_dir($moduleDir)) {
                continue;
            }
            $namespaces[$module] = $moduleDir;
        }
        $loader->registerNamespaces($namespaces);

        $loader->register();
    }

    /**
     * Register specific services for the module
     *
     * @param $di
     */
    public function registerServices(\Phalcon\DiInterface $dependencyInjector)
    {
        //Registering a dispatcher

        $dependencyInjector->setShared('dispatcher', function() {
            $dispatcher = new \Phalcon\CLI\Dispatcher();
            $dispatcher->setDefaultNamespace("Cron\Task\\");

            return $dispatcher;
        });

        $this->registerConfiguration($dependencyInjector);
        $this->registerEnvironment($dependencyInjector);
        $this->registerCronEnvironment($dependencyInjector);
        $this->registerDatabase($dependencyInjector);
        $this->registerElastica($dependencyInjector);
        $this->registerMessageCenter($dependencyInjector);
        $this->registerMailCenter($dependencyInjector);
        $this->registerSharding($dependencyInjector);
    }

    /**
     * Set configuration to Dependency Injection
     *
     * @param $di
     * @throws \Exception
     */
    protected function registerConfiguration($di)
    {
        //Read the annotations from controllers
        $configPath = CRON_PATH.'/config';
        if (!file_exists($configPath)) {
            throw new \Exception('Config dir not found!');
        }
        $globalConfig = new \Phalcon\Config();
        $files = scandir($configPath); // get all file names
        foreach ($files as $file) { // iterate files
            if ($file == "." || $file == ".." || $file[0] == "_") {
                continue;
            }
            $config = include($configPath."/".$file);
            $globalConfig->merge($config);
        }
        $di->set('config', $globalConfig);
    }

    /**
     * Register cron environment
     *
     * @param $di
     */
    protected function registerCronEnvironment($di)
    {
        $config = $di->get('config');

        $di->set('thumperConnection', function() use ($config) {
            $connections = [
                'default' => new \PhpAmqpLib\Connection\AMQPLazyConnection(
                    $config->rabbitmq->host,
                    $config->rabbitmq->port,
                    $config->rabbitmq->username,
                    $config->rabbitmq->password,
                    $config->rabbitmq->vhost
                )
            ];
            return new \Thumper\ConnectionRegistry($connections, 'default');
        });
    }

    /**
     * Register module environments
     *
     * @param $di
     */
    protected function registerEnvironment($di)
    {
        $config = $di->get('config');

        foreach ($config->environment as $env => $options) {
            $di->set($env, function() use ($options, $di) {
                $environment = function ($service) use ($options, $di) {
                    $serviceEnv = $options[$service];
                    $key = $service."_".$serviceEnv;

                    return $key;
                };

                return $environment;
            });
        }
    }

    /**
     * Register database adapters
     *
     * @param $di
     */
    protected function registerDatabase($di)
    {
        $config =$di->get('config');

        /**
         * If the configuration specify the use of metadata adapter use it or use memory otherwise
         */
        $di->set('modelsMetadata', function() use ($config) {
            if (isset($config->models->metadata)) {
                $metadataAdapter = 'Phalcon\Mvc\Model\Metadata\\'.$config->models->metadata->adapter;
                return new $metadataAdapter();
            } else {
                return new \Phalcon\Mvc\Model\Metadata\Memory();
            }
        });

        foreach ($config->database as $env => $options) {
            $options = $options->toArray();
            if (!isset($options['port'])) {
            	$options['port'] = 3306;
            }
            $key = ($env != 'db') ? "database_".$env : $env;
            $di->set($key, function() use ($key, $options, $di) {
                $db = new \Phalcon\Db\Adapter\Pdo\Mysql([
                    "host" => $options['host'],
                    "username" => $options['username'],
                    "password" => $options['password'],
                    "dbname" => $options['name'],
                	"port" => $options['port'],
                ]);

                $result = $db->query("SHOW VARIABLES LIKE 'wait_timeout'");
                $result = $result->fetchArray();
                $db->timeout = (int) $result['Value'];
                $db->start = time();
                $eventsManager = new \Phalcon\Events\Manager();
                //Listen all the database events
                $eventsManager->attach($key, function($event, $db, $dbKey) {
                    $sql = $db->getSQLStatement();
                    if ($event->getType() == 'beforeQuery') {
                        if ($sql != 'SELECT 1+2+3') {
                            $activeTimeout = time() - $db->start;
                            if ($activeTimeout > $db->timeout) {
                                echo "Reconnect to " . $dbKey;
                                $db->connect();
                                $db->start = time();
                            }
                            try {
                                $res = $db->query('SELECT 1+2+3');
                                $resArray = $res->fetch();
                                if ($resArray[0] != 6) {
                                    echo "Reconnect to " . $dbKey;
                                    $db->connect();
                                }
                            }  catch (\PDOException $e) {
                                echo "Reconnect to " . $dbKey;
                                $db->connect();
                            }

                            return true;
                        }
                    }

                });

                //Assign the eventsManager to the db adapter instance
                $db->setEventsManager($eventsManager);

                return $db;
            });
        }
    }

    /**
     * Register MessageCenter adapters
     *
     * @param $di
     */
    protected function registerMessageCenter($di)
    {
        $config =$di->get('config');

        foreach ($config->messageCenter as $env => $options) {
            $options = $options->toArray();
            $key = "messageCenter_".$env;
            $di->set($key, function() use ($env, $key, $options, $di) {
                $environment = $di->get($env);
                $config = new \stdClass();
                $config->adapter = $options['adapter'];
                $config->host = $options['host'];
                $config->port = $options['port'];
                $config->username = $options['username'];
                $config->password = $options['password'];
                $config->vhost = $options['vhost'];
                $config->type = $options['type'];
                $config->class = $options['class'];
                $config->exchangeType = $options['exchangeType'];
                $config->exchangePrefix = $options['exchangePrefix'];
                $config->queuePrefix = $options['queuePrefix'];
                $config->connection = $environment('database');

                return new \MessageCenter\Adapter($config);
            });
        }

    }

    /**
     * Register MailCenter adapters
     *
     * @param $di
     */
    protected function registerMailCenter($di)
    {
        $config =$di->get('config');

        foreach ($config->mailCenter as $env => $options) {
            $options = $options->toArray();
            $key = "mailCenter_".$env;
            $di->set($key, function() use ($key, $options, $di) {
                $config = new \stdClass();
                $config->siteurl = $options['siteurl'];
                $config->host = $options['host'];
                $config->port = $options['port'];
                $config->dbname = $options['dbname'];
                $config->username = $options['username'];
                $config->password = $options['password'];
                $config->path = $options['path'];
                $config->options = $options['options'];
                $config->affiliateId = $options['affiliateId'];

                return new \Cron\Models\MessageCenter\Adapter($config);
            });
        }

    }

    /**
     * Register Elastica adapters
     *
     * @param $di
     */
    protected function registerElastica($di)
    {
        $config =$di->get('config');

        foreach ($config->elastic as $env => $options) {
            $options = $options->toArray();
            $key = "elastic_".$env;
            $di->set($key, function() use ($key, $options, $di) {
                $config = [
                    'index' => $options['index'],
                    'connections' => $options['connections']
                ];
                return new \Engine\Search\Elasticsearch\Client($config);
            });
        }

    }

    /**
     * Register Sharding config
     *
     * @param $di
     */
    protected function registerSharding($di)
    {
        $config =$di->get('config');

        $shardingConfig = $config->shardingConfig;
        $di->set('shardingConfig', function() use ($shardingConfig) {
            return $shardingConfig;
        });

        $shardingServiceConfig = $config->shardingConfigServise;
        $di->set('shardingServiceConfig', function() use ($shardingServiceConfig) {
            return $shardingServiceConfig;
        });

    }

}