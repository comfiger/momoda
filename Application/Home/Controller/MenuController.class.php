<?php
/**
 *
 *
 * @author comfiger<liuqiang@wisdudu.com>
 * @version v1.0.0
 *
 **/
namespace Home\Controller;
use Think\Controller;

class MenuController extends Controller {
	public function __construct() {
        parent::__construct();
		//判断是否登陆
		if(session('user_id')){ //已登陆
			
		}else{ //未登陆，跳转道登陆界面
			$this->redirect("index/index");
		}
    }
	
	/**
	 * 菜单管理
	 **/
	public function index(){
		if(IS_GET){
			$roleid = I('roleid') ? (int)I('roleid') : 0;
			$menulist = D('Menu')->getMenuListByParentId(0);
			if($roleid){
				$menuInfo = D('Menu')->getMenuInfoByMenuId($roleid);
				$this->assign('menuinfo', $menuInfo);
			}
			//设置菜单
			$this->assign('menulist', $menulist);
			$this->display();
		}else if(IS_POST){
			$rolename = I('rolename');
			$action = I('action');
			$roleid = I('roleid');
			$parent_id = I('parent_id');
			
			if(empty($rolename)){
				$this->ajaxReturn(0, 'json');
			}else{
				if($roleid){
					$res = D('Menu')->editMenu($roleid, $parent_id, $rolename, $action);
					if($res !== false){
						$rows = $roleid;
					}else{
						$this->ajaxReturn(0, 'json');
					}
				}else{
					$rows = D('Menu')->addMenu($parent_id, $rolename, $action);
				}
				
				if($rows){
					$this->ajaxReturn(1, 'json');
				}else{
					$this->ajaxReturn(0, 'json');
				}
			}
		}
	}
	
	/**
	 *
	 **/
	public function lists(){
		$menulist = D('Menu')->getMenuManageList();
		if(is_array($menulist)){
			foreach($menulist as $menu){
				if($menu['parent_id']){
					$newArray[$menu['parent_id']]['menu_id'] = $menu['parent_id'];
					$newArray[$menu['parent_id']]['visible'] = $newArrayMenu[$menu['parent_id']]['visible'];
					$newArray[$menu['parent_id']]['menu_name'] = $newArrayMenu[$menu['parent_id']]['menu_name'];
					$newArray[$menu['parent_id']]['icon'] = $newArrayMenu[$menu['parent_id']]['icon'];
					$newArray[$menu['parent_id']]['list'][] = $menu;
				}else{
					$newArrayMenu[$menu['menu_id']]['menu_name'] = $menu['menu_name'];
					$newArrayMenu[$menu['menu_id']]['icon'] = $menu['icon'];
					$newArrayMenu[$menu['menu_id']]['visible'] = $menu['visible'];
				}
			}
		}
		
		$this->assign('list', $newArray);
		$this->display();
	}
	
	/**
	 * 删除菜单
	 **/
	public function remove(){
		if(IS_POST){
			$roleid = I('roleid');
			$type = I('type') ? (int)I('type') : 0; //0：修改状态 1：永久删除
			$visible = I('visible') ? (int)I('visible') : 0;
			
			if($roleid <= 0 ){
				$this->ajaxReturn(0, 'json');
			}else{
				if($type){
					$row = D('Menu')->editMenuVisibleByMenuId($visible, $roleid);
				}else{
					$row = D('Menu')->deleteMenu($roleid);
				}
				
				if($row !== false){
					$this->ajaxReturn(1, 'json');
				}else{
					$this->ajaxReturn(0, 'json');
				}
			}
		}
	}
	
}


   