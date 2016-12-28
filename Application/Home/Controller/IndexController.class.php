<?php
/**
 * 登陆操作
 **/
namespace Home\Controller;
use Think\Controller;

class IndexController extends Controller {
	/**
	 * 用户登录操作
	 **/
	public function index(){
		$action = I('get.ac') ? (int)I('get.ac') : 0;
		if(IS_GET){
			if(session('user_id')){
				if(!$action){
					$this->redirect("index/welcome");
				}else{
					session('user_id', null);
					session('role_id', null);
					session('nickname', null);
					session('faces', null);
					session('role_name', null);
					$this->display('index');
				}
			}else{
				$this->display();
			}
		}else if(IS_POST){
			$this->isCheckLogin();
		}
	}
	
	/**
	 * 退出登陆
	 *
	 **/
	public function logout(){
		if(IS_GET){
			session('user_id', null);
			session('role_id', null);
			session('nickname', null);
			session('faces', null);
			session('role_name', null);
			
			$this->ajaxReturn(1, 'json');
			
		}else if(IS_POST){
			$this->isCheckLogin();
		}
	}
	
	/**
	 * 欢迎界面
	 *
	 **/
	public function welcome(){
		if(session('user_id')){
			$this->display();
		}else{
			$this->redirect("index/index");
		}
	}
	
	private function isCheckLogin(){
		$username = I('uname');
		$password = I('pwds');
		
		if(empty($username) || empty($password)){
			$this->display();
		}
		
		$userinfo = D('Users')->getUserInfoByName($username);
		$data = array();
		if(is_array($userinfo) && count($userinfo)){
			if($userinfo['password'] == md5($password)){
				//保存登陆信息
				session('user_id', $userinfo['user_id']);
				session('role_id', $userinfo['role_id']);
				session('nickname', $userinfo['nickname']);
				session('faces', $userinfo['faces']);
				session('role_name', $userinfo['role_name']);
				session('company_id', $userinfo['company_id']);
				$data['error'] = 2;
			}else{
				$data['error'] = 1;
				$data['message'] = '登陆密码错误';
			}
		}else{
			$data['error'] = 0;
			$data['message'] = '用户不存在';
		}
		
		echo json_encode($data);
		exit;
	}
	
	public function test(){
		echo D('Jpush')->pushMessageBody('', array('102'), '测试哦', array('key'=>1), 0);
	}
}

