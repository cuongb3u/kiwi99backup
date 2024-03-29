<?php
/*
* 2007-2011 PrestaShop 
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2011 PrestaShop SA
*  @version  Release: $Revision: 6673 $
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

//
// IMPORTANT : don't forget to delete the underscore _ in the file name if you want to use it !
//

if (!class_exists('FB') and file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR.'fb.php'))
{
	if (!defined('PS_USE_FIREPHP'))
	{
		require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'fb.php';
		define('PS_USE_FIREPHP',true);
	}
}
else
	if (class_exists('FB') AND !defined('PS_USE_FIREPHP'))
		define('PS_USE_FIREPHP',true);
	else 
		define('PS_USE_FIREPHP',false);

class Tools extends ToolsCore
{

	/**
	* Redirect user to another page after 5 sec
	*
	* @param string $url Desired URL
	* @param string $baseUri Base URI (optional)
	*/
	public static function redirect($url, $baseUri = __PS_BASE_URI__)
	{
		if (strpos($url, 'http://') === FALSE && strpos($url, 'https://') === FALSE)
		{
			global $link;
			if (strpos($url, $baseUri) !== FALSE && strpos($url, $baseUri) == 0)
				$url = substr($url, strlen($baseUri));
			$explode = explode('?', $url);
			$url = $link->getPageLink($explode[0], true);
			if (isset($explode[1]))
				$url .= '?'.$explode[1];
			$baseUri = '';
		}

		if (isset($_SERVER['HTTP_REFERER']) AND ($url == $_SERVER['HTTP_REFERER']))
    	header('Refresh: 5; url='.$_SERVER['HTTP_REFERER']);
		else
			header('Refresh: 5; url='.$baseUri.$url);
		exit;

	}


	/**
	* Redirect url wich allready PS_BASE_URI after 5 sec
	*
	* @param string $url Desired URL
	*/
	public static function redirectLink($url)
	{
		if (!preg_match('@^https?://@i', $url))
		{
			global $link;
			if (strpos($url, __PS_BASE_URI__) !== FALSE && strpos($url, __PS_BASE_URI__) == 0)
				$url = substr($url, strlen(__PS_BASE_URI__));
			$explode = explode('?', $url);
			$url = $link->getPageLink($explode[0]);
			if (isset($explode[1]))
				$url .= '?'.$explode[1];
		}

		header('Refresh: 5; url='.$url);
		echo '<h1>Redirection automatique dans 5 secondes</h1><a href='.$url.'>'.$url.'</a>';
		exit;
	}
	/**
	* Redirect user to another admin page after 5 sec
	*
	* @param string $url Desired URL
	*/
	public static function redirectAdmin($url)
	{
		header('Refresh: 5; url='.$url);
		echo '<h1>Redirection automatique dans 5 secondes</h1><a href='.$url.'>'.$url.'</a>';
		exit;
	}


	/**
	* Display an error with detailed object
	* (display in firefox console if Firephp is enabled)
	*
	* @param mixed $object
	* @param boolean $kill
	* @return $object if $kill = false;
	*/
	public static function dieObject($object, $kill = true)
	{
		if(PS_USE_FIREPHP)
			FB::error($object);
		else
			return parent::dieObject($object,$kill);

		if ($kill)
			die('END');
		return $object;
	}

	/**
	* ALIAS OF dieObject() - Display an error with detailed object 
	* (display in firefox console if Firephp is enabled)
	*
	* @param object $object Object to display
	*/
	public static function d($obj, $kill = true)
	{
		if(PS_USE_FIREPHP)
			FB::error($obj);
		else
			parent::d($obj,$kill);

		if ($kill)
			die('END');
		return $object;
	}

	/**
	* ALIAS OF dieObject() - Display an error with detailed object but don't stop the execution
	* (display in firefox console if Firephp is enabled)
	*
	* @param object $object Object to display
	*/
	public static function p($object)
	{
		if(PS_USE_FIREPHP)
			FB::info($object);
		else 
			return parent::p($object);
		return $object;
	}

	/**
	* Display a warning message indicating that the method is deprecated
	* (display in firefox console if Firephp is enabled)
	*/
	public static function displayAsDeprecated()
	{

		if (_PS_DISPLAY_COMPATIBILITY_WARNING_)
		{
		$backtrace = debug_backtrace();
		$callee = next($backtrace);
		if(PS_USE_FIREPHP)
			FB::warn('Function <strong>'.$callee['function'].'()</strong> is deprecated in <strong>'.$callee['file'].'</strong> on line <strong>'.$callee['line'].'</strong><br />', 'Deprecated method');
		else
			trigger_error('Function <strong>'.$callee['function'].'()</strong> is deprecated in <strong>'.$callee['file'].'</strong> on line <strong>'.$callee['line'].'</strong><br />', E_USER_WARNING);

		$message = Tools::displayError('The function').' '.$callee['function'].' ('.Tools::displayError('Line').' '.$callee['line'].') '.Tools::displayError('is deprecated and will be removed in the next major version.');
		Logger::addLog($message, 3, $callee['class']);
	}
	}

	/**
	 * Display a warning message indicating that the parameter is deprecated
	* (display in firefox console if Firephp is enabled)
	 */
	public static function displayParameterAsDeprecated($parameter)
	{
		if (_PS_DISPLAY_COMPATIBILITY_WARNING_)
		{
		$backtrace = debug_backtrace();
		$callee = next($backtrace);
			trigger_error('Parameter <strong>'.$parameter.'</strong> in function <strong>'.$callee['function'].'()</strong> is deprecated in <strong>'.$callee['file'].'</strong> on line <strong>'.$callee['Line'].'</strong><br />', E_USER_WARNING);

			if(PS_USE_FIREPHP)
				FB::trace('Parameter <strong>'.$parameter.'</strong> in function <strong>'.$callee['function'].'()</strong> is deprecated in <strong>'.$callee['file'].'</strong> on line <strong>'.$callee['Line'].'</strong><br />', 'deprecated parameter');
			else
				$message = Tools::displayError('The parameter').' '.$parameter.' '.Tools::displayError(' in function ').' '.$callee['function'].' ('.Tools::displayError('Line').' '.$callee['Line'].') '.Tools::displayError('is deprecated and will be removed in the next major version.');

			Logger::addLog($message, 3, $callee['class']);
		}
	}

	/**
	* display debug_backtrace() 
	* (display in firefox console if Firephp is enabled)
	* 
	* @param mixed $obj 
	* @return void
	*/
	public static function trace($obj = NULL)
	{
		if(PS_USE_FIREPHP)
			FB::trace($obj);
		else{
			Tools::p($obj);
			debug_print_backtrace();
		}
	}
}
