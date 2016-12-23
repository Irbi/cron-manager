<?php
/**
 * @namespace
 */
namespace MessageCenter\Model;

use QueueCenter\Storage\AdapterInterface as QCSAdapterInterface;

/**
 * Class Queue
 * @package MessageCenter\Model
 */
class Queue extends \Phalcon\Mvc\Model implements QCSAdapterInterface
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
    public $user_id;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $created;

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return [
            'id' => 'id',
            'user_id' => 'user_id',
            'name' => 'name',
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
		return 'qc_queue';
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
        $queue = new self();
        foreach($params as $key => $value) {
            $queue->{$key} = $value;
        }
        $queue->created = date('Y-m-d H:i:s');

        return $queue->save();
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