<?

class Application_Plugin_Acl extends Zend_Controller_Plugin_Abstract
{
	
	public function preDispatch(Zend_Controller_Request_Abstract $request) 
	{		
		// for testing
		//$acl = new Zend_Acl();
		//Zend_Registry::set("Zend_Acl",$acl);
		//return true;
		
		if(defined("isCron"))
		{
			return true;
		}
		
		$acl = new Zend_Acl();

		$obj_ares = new Default_Model_Acl_Resource();
		$obj_arole = new Default_Model_Acl_Role();
		$obj_arr = new Default_Model_Acl_Resource_Role();
		$obj_aru = new Default_Model_Acl_Resource_User();
		
		$auth = Zend_Auth::getInstance();
		if ( $auth->hasIdentity() )
		{
			$auth = Zend_Auth::getInstance();
			$cur_role = $auth->getIdentity()->role;			
		} else {
			$cur_role = "guest";
		}
		
		$cache = Zend_Registry::get("Zend_Cache");
		
		$cache_id = "global_acl_data";
		
		if($cache->test($cache_id.""))
		{
			$arrBaseAclData = $cache->load($cache_id);
			
			$resources = $arrBaseAclData ['resources'];
			$roles = $arrBaseAclData['roles'];
			$roleResources = $arrBaseAclData ['role_resources'];

		} else {
			
			$resources = $obj_ares->fetchAll();
			$roles = $obj_arole->fetchAll();
			$roleResources = 		$obj_arr->fetchAll();
			
			$arrBaseAclData ['resources']=$resources->toArray();
			$arrBaseAclData['roles'] = $roles->toArray();
			$arrBaseAclData ['role_resources'] = $roleResources->toArray();
			
			$cache->save( $arrBaseAclData,$cache_id,Array(),86400);
		}
		
		// set permission list		
		if( count($resources)>0 )
		{
			foreach($resources as $resource)
			{				
				$acl->add(new Zend_Acl_Resource( $resource['name'] ));
				$resourceList[$resource['resource_id']]=$resource['name'];
			}
		}
		
		// roles 		
		if( count($roles)>0 )
		{
			foreach($roles as $role)
			{
				$acl->addRole(new Zend_Acl_Role($role['name']));
				$roleList[$role['role_id']]=$role['name'];
			}
		}
		
		// set role permissions
		if( count($roleResources)>0 )
		{				
			foreach($roleResources as $perm)
			{
			
				$res = $resourceList[$perm['resource_id']];
				$role = $roleList[$perm['role_id']];
				
				if($perm['value']==1)
				{
					$acl->allow($role,$res);
				} else {
					$acl->deny($role,$res);
				}
			}
		}
				
		Zend_Registry::set("Zend_Acl",$acl);				
		return true;
		
	}

}
?>