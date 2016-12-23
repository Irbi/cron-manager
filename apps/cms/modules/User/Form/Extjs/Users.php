<?php
/**
 * @namespace
 */
namespace User\Form\Extjs;

use Engine\Crud\Form\Field;

/**
 * Class Users
 *
 * @category    Module
 * @package     User
 * @subpackage  Form
 */
class Users extends Base
{
    /**
     * Extjs form key
     * @var string
     */
    protected $_key = 'users';

    /**
     * Form title
     * @var string
     */
    protected $_title = 'Users';

    /**
     * Container model
     * @var string
     */
    protected $_containerModel = '\User\Model\Users';

    /**
     * Container condition
     * @var array|string
     */
    protected $_containerConditions = null;

    /**
     * Constructor
     *
     * @param integer|string $id
     * @param array $params
     * @param \Phalcon\DiInterface $di
     * @param \Phalcon\Events\ManagerInterface $eventsManager
     */
    public function __construct(
        $id = null,
        array $params = [],
        \Phalcon\DiInterface $di,
        \Phalcon\Events\ManagerInterface $eventsManager = null
    ) {
        if (!$di) {
            throw new \Engine\Exception('Dependency injectio not set');
        }
        parent::__construct($id, $params, $di, $eventsManager);
    }

    /**
     * Initialize form fields
     *
     * @return void
     */
    protected function _initFields()
    {
		$this->_fields = [
			'id'        => new Field\Primary('Id'),
            'email'     => new Field\Mail('Email'),
            'name'      => new Field\Name('Name'),
            'password'  => new Field\Password('Password', null, $this->_di->get('security')),
            'confirm'   => new Field\PasswordConfirm('Password confirm', 'password'),
			'role'      => new Field\ManyToOne('Role', '\ExtjsCms\Model\Acl\Role'),
            'status'    => new Field\ArrayToSelect("Status", 'status', ['1' => 'Active', '0' => 'Not active'])
		];
    }
}
