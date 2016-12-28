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

class UserController extends Controller {
	public function __construct() {
        parent::__construct();
		//判断是否登陆
		if(session('user_id')){ //已登陆
			
		}else{ //未登陆，跳转道登陆界面
			$this->redirect("index/index");
		}
    }
	
	/**
	 * 用户管理
	 **/
	public function index(){
		if(IS_GET){
			$user_id = I('user_id') ? (int)I('user_id') : 0;
			$rolelist = D('Role')->getRoleList();
			$companylist = D('Company')->getAllCompanyList();
			if($user_id){
				$userInfo = D('Users')->getUsersInfoById($user_id);
				$this->assign('userinfo', $userInfo);
			}
			$this->assign('companylist', $companylist);
			$this->assign('rolelist', $rolelist);
			$this->display();
		}else if(IS_POST){
			$user_id = I('user_id');
			$username = I('username');
			$password = I('password');
			$nickname = I('nickname');
			$role_id = I('role_id');
			$company_id = I('company_id');
			if(empty($username) || empty($password) || empty($nickname) || empty($company_id)){
				$this->ajaxReturn(0, 'json');
			}else{
				if($user_id){
					$userInfo = D('Users')->getUsersInfoById($user_id);
					$data = array(
						'username' => $username,
						'nickname' => $nickname,
						'role_id' => $role_id,
						'company_id' => $company_id
					);
					if($userInfo['password'] != $password){
						$data['password'] = MD5($password);
					}
					$res = D('Users')->editUserInfo($user_id, $data);
					if($res !== false){
						$rows = $user_id;
					}else{
						$this->ajaxReturn(0, 'json');
					}
				}else{
					$rows = D('Users')->addUsersInfo($username, MD5($password), $nickname, $role_id, $company_id);
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
	 * 用户列表
	 *
	 **/
	public function lists(){
		$page = I('get.page') ? (int)I('get.page') : 1;
		$ajax = I('get.ajax') ? (int)I('get.ajax') : 0;
		$count = D('Users')->getUsersCount();
		//用户列表
		$userlist = D('Users')->getUsersList($page);
		$this->assign('userlist', $userlist);
		$this->assign('count', $count);
		$this->assign('page', $page);
		//判断请求方式
		if(!$ajax){
			$this->display('lists');
		}else{
			$this->display('lists_page', 'utf-8', 'html');
		}
	}
	
	public function remove(){
		if(IS_POST){
			$user_id = I('user_id');
			if($user_id <= 0 ){
				$this->ajaxReturn(0, 'json');
			}else{
				if(D('Users')->deleteUserInfoByUserId($user_id) !== false){
					$this->ajaxReturn($user_id, 'json');
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


   