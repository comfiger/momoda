<?php
/**
 * ½ÇÉ«¿ØÖÆÀà
 *
 * @author comfiger<liuqiang@wisdudu.com>
 * @version v1.0.0
 *
 **/
namespace Home\Controller;
use Think\Controller;

class RoleController extends Controller {
	public function __construct() {
        parent::__construct();
		//判断是否登陆
		if(session('user_id')){ //已登陆
			
		}else{ //未登陆，跳转道登陆界面
			$this->redirect("index/index");
		}
    }
	
	/**
	 * 角色权限管理
	 **/
	public function index(){
		if(IS_GET){
			$roleid = I('roleid') ? (int)I('roleid') : 0;
			$menulist = D('Menu')->getMenuList();
			
			if(is_array($menulist)){
				foreach($menulist as $menu){
					if($menu['parent_id']){
						$newArray[$menu['parent_id']]['menu_id'] = $menu['parent_id'];
						$newArray[$menu['parent_id']]['menu_name'] = $newArrayMenu[$menu['parent_id']]['menu_name'];
						$newArray[$menu['parent_id']]['icon'] = $newArrayMenu[$menu['parent_id']]['icon'];
						$newArray[$menu['parent_id']]['list'][] = $menu;
					}else{
						$newArrayMenu[$menu['menu_id']]['menu_name'] = $menu['menu_name'];
						$newArrayMenu[$menu['menu_id']]['icon'] = $menu['icon'];
					}
				}
			}
			//根据权限ID获取菜单信息
			if($roleid){
				$roleinfo = D('Role')->getRoleInfo($roleid);
				if(!empty($roleinfo)){
					$this->assign('roleinfo', $roleinfo);
				}
			}
			
			$this->assign('roleid', $roleid);
			//设置菜单
			$this->assign('menulist', $newArray);
			$this->display();
		}else if(IS_POST){
			$rolename = I('rolename');
			$list = I('list');
			$roleid = I('roleid');
			
			if(empty($rolename) || empty($list)){
				$this->ajaxReturn(0, 'json');
			}else{
				if($roleid){
					$res = D('Role')->editRoleInfo($roleid, $rolename, $list);
					if($res !== false){
						$rows = $roleid;
					}else{
						$this->ajaxReturn(0, 'json');
					}
				}else{
					$rows = D('Role')->addRoleInfo($rolename, $list);
				}
				
				if($rows){
					$menulist = D('Menu')->getMenuListByRoleId($list);
					
					if(is_array($menulist)){
						foreach($menulist as $menu){
							if($menu['parent_id']){
								$newArray[$menu['parent_id']]['menu_id'] = $menu['parent_id'];
								$newArray[$menu['parent_id']]['menu_name'] = $newArrayMenu[$menu['parent_id']]['menu_name'];
								$newArray[$menu['parent_id']]['icon'] = $newArrayMenu[$menu['parent_id']]['icon'];
								$newArray[$menu['parent_id']]['list'][] = $menu;
							}else{
								$newArrayMenu[$menu['menu_id']]['menu_name'] = $menu['menu_name'];
								$newArrayMenu[$menu['menu_id']]['icon'] = $menu['icon'];
							}
						}
					}
					
					$this->createMenu($newArray, $rows);
					
					$this->ajaxReturn(1, 'json');
				}else{
					$this->ajaxReturn(0, 'json');
				}
			}
		}
	}
	
	/**
	 * 权限列表
	 *
	 **/
	public function lists(){
		//角色列表
		$rolelist = D('Role')->getRoleList();
		$this->assign('rolelist', $rolelist);
		$this->display();
	}
	
	public function remove(){
		if(IS_POST){
			$roleid = I('roleid');
			if($roleid <= 0 ){
				$this->ajaxReturn(0, 'json');
			}else{
				if(D('Role')->deleteRoleInfo($roleid) !== false){
					$this->ajaxReturn($roleid, 'json');
				}else{
					$this->ajaxReturn(0, 'json');
				}
			}
		}
	}
	
	/**
	 * 动态创建菜单
	 **/
	private function createMenu($newArray, $roleid){
		//测试缓存
		$html = '';
		foreach($newArray as $menu){
			if(!empty($html)){
				$html .= '<div class="collapsed"><span>'. $menu['menu_name'] .'</span>';
			}else{
				$html .= '<div style="margin-top: 30px;" ><span>'. $menu['menu_name'] .'</span>';
			}
			foreach($menu['list'] as $mm){
				$href = empty($mm['action']) ? 'javascript:void(0);' : __ROOT__.'/'.$mm['action'];
				$html .= '<a href="'. $href .'">'. $mm['menu_name'] .'</a>';
			}
			$html .= '</div>';
		}
		
		F('menu/menu_'. $roleid, null);
		F('menu/menu_'. $roleid, $html);
	}
	
}


   