<?php
/**
 * @namespace
 */
namespace User\Model;

use Engine\Acl\AuthModelInterface,
    Engine\Acl\Tools\User as UserAcl;

/**
 * Class Users
 * @package User\Model
 */
class Users extends \Engine\Mvc\Model implements AuthModelInterface
{
    use UserAcl;

    /**
     * Login model name
     * @var string
     */
    protected static $_loginCredential = 'email';

    /**
     * Password model name
     * @var string
     */
    protected static $_passwordCredential = 'password';

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var string
     */
    public $password;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var integer
     */
    public $core_acl_role_id;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     * Primary model columns
     * @var array|string
     */
    protected $_primary = 'id';

    /**
     * Name of column like dafault name column
     * @var string
     */
    protected $_nameExpr = 'name';

    /**
     * Default order column
     * @var string
     */
    protected $_orderExpr = 'id';

    /**
     * Order is asc order direction
     * @var bool
     */
    protected $_orderAsc = true;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
		$this->belongsTo('core_acl_role_id', '\ExtjsCms\Model\Acl\Role', 'id', array('alias' => 'Role'));
    }

    public function getSource()
    {
        return 'user_users';
    }

}
