<?php

/**
 * Auth/Acl helper which handles the current user permissions.
 * Responsible for showing or hiding elements of page.
 *
 * @property SessionHelper $Session
 * @property FormHelper $Form
 */
class AuthHelper extends AppHelper
{
	var $helpers = array('Session', 'Form');

	var $__cached = array();
	var $__permsCache = null;
	var $__userCache = null;

	/**
	 * Action map, copied from AuthComponent.
	 *
	 * @var array
	 * @todo move this to bootstrap somewhere - to be in sync with AclComponent
	 */
	var $actionMap = array();

	function __construct()
	{
		parent::__construct();

		$this->actionMap = NEUTRINO_CONFIG::$ACL_ACTION_MAP;
	}

	function user()
	{
		if (!is_null($this->__userCache))
		{
			return $this->__userCache;
		}

		$this->__userCache = $this->Session->read('Auth.User');
		return $this->__userCache;
	}

	function permissions()
	{
		if (!is_null($this->__permsCache))
		{
			return $this->__permsCache;
		}

		$this->__permsCache = $this->Session->read('Auth.Permissions');
		return $this->__permsCache;
	}

	/**
	 * Returns true if a user is logged in.
	 *
	 * @return bool
	 */
	function isValid()
	{
		if (!$this->user())
		{
			return false;
		}

		return true;
	}

	/**
	 * Checks if the current user is the built-in admin account
	 *
	 * @return bool
	 */
	function isAdmin()
	{
		$user = $this->user();

		if (!$user)
		{
			return false;
		}

		return $user['is_root'];
	}

	/**
	 * Check for permissions on a controller/action pair, with the optional owner.
	 *
	 * @param string $controller Controller or null for current controller.
	 * @param string $action Action name or null for current action.
	 * @param int $owner Owner of the row in question or null if not needed.
	 * @return bool True when permission is allowed.
	 */
	function check($controller = null, $action = null, $owner = null)
	{
		if (!$this->user())
		{
			return false;
		}

		if (!Configure::read('debug')) // @todo: remove
		{
			if ($this->isAdmin())
			{
				return true;
			}
		}

		if (is_array($controller))
		{
			extract($controller, EXTR_OVERWRITE);
		}

		if (empty($controller))
		{
			$controller = $this->params['controller'];
		}

		$controller = Inflector::camelize($controller);

		if (empty($action))
		{
			$action = $this->action;
		}

		if (isset($this->actionMap[$action]))
		{
			$action = $this->actionMap[$action];
		}

		$cacheKey = sprintf('%s%s%s', $controller, $action, $owner);

		if (isset($this->__cached[$cacheKey]))
		{
			return $this->__cached[$cacheKey];
		}

		$allowed = $this->__check($controller, $action, $owner);
		$this->__cached[$cacheKey] = ($allowed == 1) ? true : false;

		return $this->__cached[$cacheKey];
	}

	/**
	 *
	 * @param string $controller Controller or null for current controller.
	 * @param string $action Action name or null for current action.
	 * @param int $owner Owner of the row in question or null if not needed.
	 * @return bool True when permission is allowed.
	 */
	function __check($controller = null, $action = null, $owner = null)
	{
		$user = $this->user();

		$checkOwn =
			(
				!empty($owner) &&
				$user['id'] == $owner &&
				in_array($action, array('read', 'update', 'delete'))
			);

		$permissions = $this->permissions();

		if (empty($user) && empty($permissions))
		{
			return true; // @todo: wtf?
		}

		foreach ($permissions as $aroNode)
		{
			// check for "own" permissions on specific controller
			if ($checkOwn)
			{
				$access = Set::extract
					(
						sprintf
						(
							'/Aco[alias=/%1$s/]/Permission[_%2$s_own!=0]/_%2$s_own',
							$controller,
							$action
						),
						$aroNode
					);

				if (!empty($access))
				{
					if ($access[0] == 1)
					{
						return true;
					}
					else if ($access[0] == -1)
					{
						return false;
					}
				}

				// check for "own" permissions on Everything
				$access = Set::extract
					(
						sprintf
						(
							'/Aco[alias=/Everything/]/Permission[_%1$s_own!=0]/_%1$s_own',
							$action
						),
						$aroNode
					);

				if (!empty($access))
				{
					if ($access[0] == 1)
					{
						return true;
					}
					else if ($access[0] == -1)
					{
						return false;
					}
				}
			} // if ($checkOwn)

			// check for regular permissions on specific controller
			$access = Set::extract
				(
					sprintf
					(
						'/Aco[alias=/%1$s/]/Permission[_%2$s!=0]/_%2$s',
						$controller,
						$action
					),
					$aroNode
				);

			if (!empty($access))
			{
				if ($access[0] == 1)
				{
					return true;
				}
				else if ($access[0] == -1)
				{
					return false;
				}
			}

			// check for regular permissions on Everything
			$access = Set::extract
				(
					sprintf
					(
						'/Aco[alias=/Everything/]/Permission[_%1$s!=0]/_%1$s',
						$action
					),
					$aroNode
				);

			if (!empty($access))
			{
				if ($access[0] == 1)
				{
					return true;
				}
				else if ($access[0] == -1)
				{
					return false;
				}
			}
		} // foreach ($permissions as $aroNode)

		// if everything failed, you have bad luck
		return false;
	}

	function permissionToInput($params)
	{
		$fieldName = null;
		$options = array
			(
				1 => __('Allow', true),
				0 => __('Inherit', true),
				-1 => __('Deny', true)
			);
		$value = 0;
		$attributes = array();

		extract($params, EXTR_OVERWRITE);

		if (empty($fieldName))
		{
			trigger_error(__('$fieldName cannot be empty in AuthHelper::permissionToInput()', true));
			return false;
		}

		return $this->output($this->Form->select($fieldName, $options, $value, $attributes, false));
	}
}

?>