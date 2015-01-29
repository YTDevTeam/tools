<?php
/**
 * 这个是常用的一些方法合集
 *
 * @author  Qi Weiyu <wqi@yetang.com>
 * @last    2014-12-12
 */
class Util
{
	/**
	 * 获取数组的某一项
	 * 例子：
	 * $array = array('a'=>array('m'=>2, 'n'=>3), 'b'=>array('x'=>8, 'y'=>9));
	 * Util::getArray($array, 'a') ==> array('m'=>2, 'n'=>3)
	 * Util::getArray($array, 'b.x') ==> 8
	 * Util::getArray($array, 'b.z', 100) ==> 100
	 * Util::getArray($array, 'b+y', 0, '+') ==> 9
	 *
	 * @param array $array          将要获取项的数组
	 * @param string $key           要获取的项的键值，如果包含$delimiter，则可以取多维数组
	 * @param null $default         如果取不到值，那么返回此默认值
	 * @param string $delimiter     如果在多维数组中使用，那么使用此分隔符来分隔键值
	 * @return null|mixed
	 *
	 * @author  Qi Weiyu <wqi@yetang.com>
	 * @last    2014-12-11
	 */
	public static function getArray(array $array, $key, $default = null, $delimiter = '.')
	{
		if(!is_array($array))
		{
			return $default;
		}
		if(array_key_exists($key, $array))
		{
			return $array[$key];
		}
		foreach(explode($delimiter, $key) as $subKey)
		{
			if(!is_array($array) || !array_key_exists($subKey, $array))
			{
				return $default;
			}
			$array = $array[$subKey];
		}
		return $array;
	}

	/**
	 * 根据配置的链接参数，创建页面链接
	 * 此方法是封装了Controller的CreateUrl，更多用法请参考Controller
	 * 例子：
	 * Util::createUrl('/user/myOrder') ==> /user/myOrder
	 * Util::createUrl('/collection/view', array('id'=>2)) ==> /collection/2
	 * Util::createUrl('/user/doAdd', array('pid'=>3, 'time'=>'1319802299')) ==> /user/doAdd?pid=3&time=1319802299
	 *
	 * @param array|string $route       /controller/action 或者 array(/controller/action, params)
	 * @param array $params             query参数
	 * @param string $ampersand         query的连接符，默认'&'
	 * @return string
	 *
	 * @author  Qi Weiyu <wqi@yetang.com>
	 * @last    2014-12-11
	 */
	public static function createUrl($route,$params=array(),$ampersand='&')
	{
		if(is_array($route))
		{
			$params = $route;
			$route = array_shift($route);
		}
		return Yii::app()->controller->createUrl($route, $params, $ampersand);
	}

	/**
	 * 获取Yii的配置参数
	 *
	 * @param string $key
	 * @return null|mixed
	 *
	 * @author  Qi Weiyu <wqi@yetang.com>
	 * @last    2014-12-11
	 */
	public static function getYiiParam($key = '')
	{
		return Yii::app()->params[$key];
	}

	/**
	 * 获取cdn的域名
	 *
	 * @param string $type         要获取的类型，可选为 img uimg cj
	 * @return null|string
	 *
	 * @author  Qi Weiyu <wqi@yetang.com>
	 * @last    2014-12-11
	 */
	public static function getCdn($type)
	{
		$cdnList = self::getArray(self::getYiiParam('cdn'), $type);
		if(is_array($cdnList))
		{
			$cdnDomain = self::getArray($cdnList, array_rand($cdnList));
			return 'http://'.$cdnDomain.'.'.SITE_DOMAIN;
		}
		return null;
	}

	/**
	 * @var string  CSS Version
	 */
	protected static $_cssVersion = '';

	/**
	 * @var string  Js  Version
	 */
	protected static $_jsVersion = '';

	/**
	 * 获取css版本号
	 *
	 * @return string
	 *
	 * @author  Qi Weiyu <wqi@yetang.com>
	 * @last    2014-12-11
	 */
	protected static function _getCssVersion()
	{
		if(self::$_cssVersion == '')
		{
			self::$_cssVersion = Config::getCJVersion('css');
		}
		return self::$_cssVersion;
	}

	/**
	 * 获取js版本号
	 *
	 * @return string
	 *
	 * @author  Qi Weiyu <wqi@yetang.com>
	 * @last    2014-12-11
	 */
	protected static function _getJsVersion()
	{
		if(self::$_jsVersion == '')
		{
			self::$_jsVersion = Config::getCJVersion('js');
		}
		return self::$_jsVersion;
	}

	/**
	 * 获取加上版本号的css文件路径
	 *
	 * @param string $path  Css文件路径
	 * @return string
	 *
	 * @author  Qi Weiyu <wqi@yetang.com>
	 * @last    2014-12-11
	 */
	public static function getCssResourceUrl($path = '')
	{
		if(strpos($path, '/') != 0)
		{
			$path = '/'.$path;
		}
		return self::getCdn('cj').$path.'?version='.self::_getCssVersion();
	}

	/**
	 * 获取加上版本号的js文件路径
	 *
	 * @param string $path  Js文件路径
	 * @return string
	 *
	 * @author  Qi Weiyu <wqi@yetang.com>
	 * @last    2014-12-11
	 */
	public static function getJsResourceUrl($path = '')
	{
		if(strpos($path, '/') != 0)
		{
			$path = '/'.$path;
		}
		return self::getCdn('cj').$path.'?version='.self::_getJsVersion();
	}

	/**
	 * 生成随机字符串
	 *
	 * @param string $length    要生成字符串的长度
	 * @param bool $onlyNumber  是否只生成随机数字(默认否)
	 * @return string
	 *
	 * @author  Qi Weiyu <wqi@yetang.com>
	 * @last    2014-12-12
	 */
	public static function generateRandomString($length, $onlyNumber = false)
	{
		$str = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		if($onlyNumber)
		{
			$str = '0123456789';
		}
		$count = strlen($str)-1;
		$res = '';
		for($i = 0; $i < $length; $i++)
		{
			$res = $res.$str[rand(0, $count)];
		}
		return $res;
	}
}
