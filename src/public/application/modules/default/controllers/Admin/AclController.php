<?php

class Admin_AclController extends Zend_Controller_Action
{

    public function init()
    {
        if(!hasAccess("acp")) // or !hasAccess("acp_default_acl"))
		{
			$this->redirect($this->view->Url(Array(),"NoAccess",true));
		}

		$this->obj_ares = new Default_Model_Acl_Resource();
		$this->obj_arole = new Default_Model_Acl_Role();
		$this->obj_arr = new Default_Model_Acl_Resource_Role();
    }

    public function indexAction()
    {
		$cache = Zend_Registry::get("Zend_Cache");
		$cache_id = "global_acl_data";
		//$cache->remove($cache_id);

		$select = $this->obj_arole->select();
		$select->order("name");
        $roles = $this->obj_arole->fetchAll($select);

		$this->view->roles = $roles->toArray();

    }

	public function editAction()
	{
		$request = $this->getRequest();

		$role_id = $request->getParam("role_id",0);
		if($role_id>0)
		{
			$role = $this->obj_arole->fetchRow("role_id=".$role_id);
			$this->view->role = $role->name;

			if($request->isPost())
			{
				$post = $request->getPost();
				$this->obj_arr->delete("role_id=".$role_id);
				foreach($post['res_id'] as $id=>$value)
				{
					$arrData = Array(
					"role_id"=>$role_id,
					"resource_id"=>$id,
					"value"=>$value
					);
					$this->obj_arr->insert($arrData);
				}

				$cache = Zend_Registry::get("Zend_Cache");
				$cache_id = "global_acl_data";
				$cache->remove($cache_id);

				$url = $this->view->url(Array("module"=>"default","controller"=>"admin_acl","action"=>"index","lang"=>Settings::get("S_lang")),null,true);
				header("Location: ".$url);
				exit;

			}
			/*** start ****/

			$select = $this->obj_ares->select();
			$select->where("type = ?", "frontend");
			$select->order("category");
			$select->order("name");

			$select2 = $this->obj_ares->select();
			$select2->where("type = ?", "backend");
			$select2->order("category");
			$select2->order("name");

			$roles = Array(
			'frontend'	=> $this->obj_ares->fetchAll($select)->toArray(),
			'backend'	=> $this->obj_ares->fetchAll($select2)->toArray()
			);

		//	print_r($roles);exit;
			$this->view->resources = $roles;

			/**** End

			$select = $this->obj_ares->select();
			$select->order("name");
			$resources = $this->obj_ares->fetchAll($select);
			$this->view->resources = $resources->toArray();
***/
			$select = $this->obj_arr->select();
			$select->where("role_id=?",$role_id);
			$list = $this->obj_arr->fetchAll($select);
			if($list->count()>0)
			{
				foreach($list as $item)
				{
					$roleRes [$item->resource_id]=$item->value;
				}
			} else {
				if($resources->count()>0)
				{
					foreach($resources as $res)
					{
						$roleRes [$res->resource_id]=$res->default_value;
					}
				}
			}
			//print_r($roleRes);
			//exit;
			$this->view->roleRes = $roleRes;


		}
		else
		{
			exit("err 125");
		}

	}

}