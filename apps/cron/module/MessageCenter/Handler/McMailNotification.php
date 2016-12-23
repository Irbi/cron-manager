<?php
/**
 * @namespace
 */
namespace MessageCenter\Handler;

/**
 * Class McMailNotification
 * @package MessageCenter\Handler
 */
class McMailNotification extends Base
{
    /**
     * Handle data by params
     *
     * @param array $params
     * @return array
     */
	public function handle(array $data)
	{
		if (
            !isset($data['publish']) ||
            !isset($data['message']) ||
            !isset($data['user_id']) ||
            !isset($data['queue_id']) ||
            !isset($data['exchange']) ||
            !isset($data['routing_key'])
		) {
			throw new \Exception('Not valid data');
		};

		var_dump($data);

        return true;
	}
}