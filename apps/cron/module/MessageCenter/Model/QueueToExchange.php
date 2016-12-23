<?php
/**
 * @namespace
 */
namespace MessageCenter\Model;

use QueueCenter\Storage\AdapterInterface as QCSAdapterInterface;

/**
 * Class QueueToExchange
 * @package MessageCenter\Model
 */
class QueueToExchange extends \Phalcon\Mvc\Model implements QCSAdapterInterface
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $queue_id;

    /**
     *
     * @var integer
     */
    public $exchange_id;

    /**
     *
     * @var string
     */
    public $routing_key;

    /**
     *
     * @var string
     */
    public $created;

    /**
     * Independent Field Mapping.
     */
    public function columnMap()
    {
        return [
            'id' => 'id',
            'queue_id' => 'queue_id',
            'exchange_id' => 'exchange_id',
            'routing_key' => 'routing_key',
            'created' => 'created'
        ];

    }

    /**
     * Initialize connection service
     */
    public function initialize()
    {
        $this->setConnectionService('remoteDbApppicker');
    }

	/**
	 * @return string the associated database table name
	 */
	public function getSource()
	{
		return 'qc_queue_to_exchange';
	}

    /**
     * Return data from database by params
     * @param array $params
     * @return array
     */
    public function get(array $params)
    {
        $where = [];
        foreach ($params as $key => $value) {
            $where[] = $key." = '".$value."'";
        }
        if ($result = $this->find(implode(" AND ", $where))) {
            if (count($result) == 1) {
                return $result->toArray()[0];
            } else {
                return $result->toArray();
            }
        }

        return false;
    }

    /**
     * Add new data to database
     *
     * @param array $params
     * @return boolean
     */
    public function add(array $params)
    {
        $queueToExchange = new self();
        foreach($params as $key => $value) {
            $queueToExchange->{$key} = $value;
        }
        $queueToExchange->created = date('Y-m-d H:i:s');

        return $queueToExchange->save();
    }

    /**
     * Remove data from database by id
     *
     * @param integer $id
     * @return bollean
     */
    public function remove($id)
    {
        $row = $this->findFirst($id);
        return $row->delete();
    }
}