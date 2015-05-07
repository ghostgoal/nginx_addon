<?php

class system
{
	protected $m_class_config;
	protected $m_class_query;
	protected $m_class_session;
	protected $m_class_cache;
	

	protected $m_class_model_factory;
	protected $m_class_cache_session;
	
	//
	protected $m_current_user;
	
	function getClassQuery()
	{
		return $this->m_class_query;
	}
	
	function getClassSession()
	{
		return $this->m_class_session;
	}
	function getClassCache()
	{
		return $this->m_class_cache;
	}
	
	function getCacheSession()
	{
		return $this->m_class_cache_session;
	}
	
	
	function getModel($name)
	{
		return $this->m_class_model_factory->createModel($name);
	}
	
	function getClassConfig()
	{
		return $this->m_class_config;
	}
	
	function getRequest()
	{
		$act = isset($_REQUEST['act']) ? $_REQUEST['act'] : 'index';
		
		$map = $this->getClassConfig()->getRequestMap();
		
		
		if(isset($map[$act]))
		{
			$request = $map[$act];
			return new class_request($act,$request['mask'],$request['data'],$request['handler']);
		}
		
		return false;
	
		
		
		
		
		
	}
	function dispatchRequest($class_request)
	{
		return $this->invokeRequestHandler($class_request);
	}
	
	function invokeRequestHandler($class_request)
	{
		$handler = $class_request.getRequestHandler();
		
		if(is_string($handler) )
		{
			
			if(method_exists($this,$handler))
			{
				return call_user_func_array(array($this,$handler),array($this,$class_request);
			}
			
		}
		
		if(is_object($handler) && (get_class($hanler) == 'Closure'))
		{
			return $handler($this,$class_request);
		}
		
		return false;
		
		
	}
	
	function sendResponse($class_response)
	{
		
	}
	
	
	function getCurrentUser()
	{
		$user = $this->getModel('user');
		
		if(isset($_SESSION['user']))
		{
			
			$user->fromJSON($_SESSION['user']);
			
		}else
		{
			
			$this->getCacheSession()->load($user,GUEST_ID);
		}
		
		
		return $user;
		
	}
	
	
	function init()
	{
		$config = new class_config; //
		$query = new class_query($username,$password);//
		$session = new class_session($query);
		$cache = new class_cache($session,$config);
		$modelFactory = new class_modelFactory($mp,$query);//
		$cacheSession = new class_cacheSession($cache,$session);
		
		$this->m_class_config = $config;
		$this->m_class_query = $query;
		$this->m_class_session = $session;
		$this->m_class_cache  = $cache;
		$this->m_class_model_factory =$modelFactory;
		$this->m_class_cache_session = $cacheSession;
		
		//
		
		if(isset($_SESSION['ssid']))
		{
			$ssid = $_SESSION['ssid'];
			
			SESSION_ID($ssid);
		}
		
		
		session_start();
		
		
		
/* 		$this->m_current_user = $this->getCurrentUser(); */
		
		
	}
	function loop()
	{
		$user = $this->getCurrentUser();
		$request = $this->getRequest();
		$cache = $this->getClassCache();
		
		
		
		if($request)
		{
			$json = $cache->getRequestCache($user,$request);
			
			if($json)
			{
				$response  = new class_response();
				
				if(!$response.fromJSON($json))
				{
					echo '无效的缓存！';
				}
			}
			else
			{
			
				if($user->['GID'] >= $request->getRequestMask())
				{
					$json  = $this->dispatchRequest($request)
					
					if($json)
					{
						$cache->cacheRequest($user,$request,$response);
						
					}else
					{
						'无效的处理！'
					}
				}else
				{
					echo '权限不足！';
				}
			}
		}else
		{
			echo '无效的请求！';
		}
		
		$this->sendResponse($response);
	}
}

?>