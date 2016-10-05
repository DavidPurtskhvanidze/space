<?php

namespace {
function getparentcwd()
{
	return dirname(getcwd());
}
class Installer
{
	private $config;
	private static $instance;
	private $log = array();
	private $completedActionNames = array();
	private $lastFormName;
	private $replacementVars = array();
	private $currentActionData = array();
	private $currentStep = 0;
	private $siteUrl;
	private $adminSiteUrl;
	private $pathToApplicationRoot;
	private $id;
	private $dataForRemoteLogging = array();

	/**
	 * @static
	 * @return Installer;
	 */
	public static function getInstance()
	{
		if (empty(self::$instance))
		{
			self::$instance = new Installer();
		}
		return self::$instance;
	}

	public function init($config)
	{
		spl_autoload_register(array($this, 'autoload'));

		if ($this->getRequestValue('action') == 'file')
		{
			$this->sendFile();
			exit;
		}
		session_start();
		//todo:refactor here
		if (isset($_REQUEST['downloadLicense']) && ! empty($_SESSION['license']))
		{
			header("Content-type:");
			header("Content-Disposition: attachment; filename=license");
			echo  $_SESSION['license'];
			exit;
		}

		$this->config = $config;
		$this->initSiteUrls();


		if (!isset($_REQUEST['restore']))
		{
			session_unset();
			$this->id = uniqid();
			$this->setData('id', $this->id);
		}
		else
		{
			$this->id = $this->getData('id');
		}

		$this->pathToApplicationRoot = isset($config['pathToApplicationRoot']) ? $config['pathToApplicationRoot'] : "./";
		$this->completedActionNames = $this->getData('completedActionNames');
		$this->log = $this->getData('setupLog');


		$this->setReplacementVar('product', $config['product']);
		$this->setReplacementVar('version', $config['version']);
		$this->setReplacementVar('siteUrl', 'http://' . $_SERVER['HTTP_HOST'] . ':' . $_SERVER['SERVER_PORT'] . pathinfo(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), PATHINFO_DIRNAME));
	}

	public function __destruct()
	{
		$this->setData('completedActionNames', $this->completedActionNames);
		$this->setData('setupLog', $this->log);

		$data = $this->getDataForRemoteLogging();
		$data['action_statuses'] = $this->completedActionNames;
		$data['log_data'] = $this->log;

		$this->remoteLog($data);
	}

	public function autoload($classname)
	{
		$pathToFile = str_replace("\\", "/", $classname) . ".php";
		include_once($pathToFile);
	}

	public function run()
	{
		try
		{
			ob_start();
			foreach ($this->config['build'] as $action => $actionData)
			{
				++$this->currentStep;
				if (!array_key_exists($action, $this->completedActionNames))
				{
					list($taskName) = array_keys($actionData);
					$this->remoteLogAction($action);
					$this->remoteLogStatus('ACTION_STARTED');
					try
					{
						$this->currentActionData = $actionData;
						$this->doTask($taskName, $actionData[$taskName]);
						$this->completedActionNames[$action] = 'completed';
					}
					catch (SkipActionException $e)
					{
						$this->writeLog($actionData['caption'] ." skipped.", 'warning');
						$this->completedActionNames[$action] = 'skipped';
						$this->unsetDataForLastForm();
					}
					$this->clearRequest();
				}
			}
			$this->showMainTemplate(ob_get_clean());
		}
		catch (WaitForUserInputException $e)
		{
			$this->remoteLogStatus('USER_INPUT');
			$this->showMainTemplate(ob_get_clean());
		}
		catch (StepFailedException $e)
		{
			$this->remoteLogStatus('STEP_FAILED');
			$this->writeLog($e->getMessage(), 'warning');
			$this->writeMessage($e->getMessage(), 'error');
			$this->unsetDataForLastForm();
			header("Location: {$_SERVER['PHP_SELF']}?restore");
		}
		catch (InstallationFailedException $e)
		{
			$this->remoteLogStatus('INSTALLATION_FAILED');
			$this->writeLog($e->getMessage(), 'error');
			$m = ob_get_contents();
			ob_end_clean();

			if ($e->displayBuffer) {
				$content = $m;
			} else {
				$content = "<div class='alert alert-danger' role='alert'>" . $e->getMessage() . "</div>";
			}

			$t = new \TemplateProcessor\Template();
			$this->showMainTemplate($content . $t->fetch('files/retry_control.tpl'));
		}
	}

	private function remoteLogAction($action)
	{
		$this->setDataForRemoteLog('current_action', $action);
	}

	private function remoteLogStatus($status)
	{
		$this->setDataForRemoteLog('current_status', $status);
	}

	private function remoteLog($params)
	{
		if (empty($this->config['remoteLogHandlerUrl']))
		{
			return;
		}
		$url = $this->config['remoteLogHandlerUrl'];

		$params['id'] = $this->id;
		$params['site_url'] = $this->siteUrl;
		$params['product_name'] = $this->config['product'];
		$params['product_version'] = $this->config['version'];
		$params['php_version'] = PHP_VERSION;
		$params['time'] = time();
		// change log version if the log structure has been changed
		$params['log_version'] = '1';

		// post without waiting for response
		$post_string = http_build_query($params);
		$parts = parse_url($url);

		@$fp = fsockopen($parts['host'],
			isset($parts['port']) ? $parts['port'] : 80,
			$errNo, $errStr, 30);
		if (false === $fp) return;

		$out = "POST " . $parts['path'] . " HTTP/1.1\n";
		$out .= "Host: " . $parts['host'] . "\n";
		$out .= "Content-Type: application/x-www-form-urlencoded\n";
		$out .= "Content-Length: " . strlen($post_string) . "\n";
		$out .= "Connection: Close\n\n";
		if (!empty($post_string))
		{
			$out .= $post_string;
		}
		fwrite($fp, $out);
		fclose($fp);
	}

	private function showMainTemplate($content)
	{
		$pageTemplate = new \TemplateProcessor\Template();
		$pageTemplate->MAIN_CONTENT = $content;
		$pageTemplate->LOG = $this->getLog();
		$pageTemplate->ACTIONS = $this->getActionsList();
		$pageTemplate->CURRENT_STEP = $this->currentStep;
		$pageTemplate->All_STEPS = count($this->config['build']);
		$pageTemplate->product_name = $this->config['product'];
		$pageTemplate->product_version = $this->config['version'];
		$template = isset($this->config['templateFileName']) ? $this->config['templateFileName'] : "files/default_index.tpl";
		$pageTemplate->display($template);
	}

	private function getActionsList()
	{
		$result = array();
		$currentFound = false;
		foreach ($this->config['build'] as $action => $actionData)
		{
			if (!array_key_exists($action, $this->completedActionNames))
			{
				if (!$currentFound)
				{
					$result[$actionData['caption']] = 'current';
					$currentFound = true;
				}
				else
				{
					$result[$actionData['caption']] = 'incomplete';
				}
			}
			else
			{
				$result[$actionData['caption']] = $this->completedActionNames[$action];
			}
		}
		return $result;
	}

	private function sendFile()
	{
		$filename = $_REQUEST['file'];
		$file = new \TemplateProcessor\File($filename);
		header("Content-type:" . $file->getContentType());
		$file->flushContent();
	}

	public function doTask($taskName, $taskData)
	{
		$taskClassName = "\\Tasks\\$taskName";
		$task = new $taskClassName($taskData);
		$task->setConfig($this->config);
		$task();

		if (isset($taskData['log']))
		{
			$this->writeLog($taskData['log']['message'], $taskData['log']['type']);
		}

	}

	public function getFormsData()
	{
		return $this->config['forms'];
	}

	public function getRequestValue($name)
	{
		return isset($_REQUEST[$name]) ? $_REQUEST[$name] : null;
	}

	public function writeLog($message, $type = 'info')
	{
		$this->log[] = array('content' => $message, 'type' => $type, 'datetime' => date("Y-m-d H:i:s"));
	}
	public function writeMessage($message, $type = 'info', $messageId = null)
	{
		$messages = $this->getData('messages');
		if ($messageId !== null)
		{
			$messages[$messageId] = array('message' => $message, 'type' => $type);
		}
		else
		{
			$messages[] = array('message' => $message, 'type' => $type);
		}
		$this->setData('messages', $messages);
	}

	private function getLog()
	{
		$result = "";
		foreach ($this->log as $log)
		{
			$logType = strtoupper($log['type']);

			switch ($log['type']) {

				case 'info':
				case 'warning':
				case 'success':
					$cssClass = 'text-' . $log['type'];
					break;
				case 'error':
					$cssClass = 'text-danger';
					break;
			}

			$result .= "<div><span>[{$log['datetime']}]</span> <span class='{$cssClass}'><span>{$logType}:</span> {$log['content']}</span></div>";
		}
		return $result;
	}
	public function getMessages()
	{
		$messages = $this->getData('messages');
		$this->unsetData('messages');
		return $messages;
	}

	public function getDataForForm($formName)
	{
		if (!empty($_SESSION['Data'][$formName]))
		{
			return $_SESSION['Data'][$formName];
		}
		$this->doTask("RequestUserInput", array('formName' => $formName, 'skipable' => isset($this->currentActionData['skipable'])));
		$this->lastFormName = $formName;
		return $_SESSION['Data'][$formName];
	}
	public function setDataForForm($formName, $data)
	{
		$_SESSION['Data'][$formName] = $data;
	}
	public function unsetDataForLastForm()
	{
		if (isset($this->lastFormName))
			unset($_SESSION['Data'][$this->lastFormName]);
	}

	public function getData($id, $default = array())
	{
		return isset($_SESSION[$id]) ? $_SESSION[$id] : $default;
	}
	public function setData($id, $data)
	{
		$_SESSION[$id] = $data;
	}
	public function unsetData($id)
	{
		unset($_SESSION[$id]);
	}

	private function clearRequest()
	{
		$_REQUEST = array();
	}

	public function replaceMessageVarsToValues($message)
	{
		foreach ($this->replacementVars as $name => $value)
		{
			$message = str_replace("\${{$name}}", $value, $message);
		}
		return $message;
	}

	public function setReplacementVar($name, $value)
	{
		$this->replacementVars[$name] = $value;
	}

	public function getReplacementVar($name)
	{
		return $this->replacementVars[$name];
	}

	public function getBaseUrl()
	{
		return $this->siteUrl;
	}

	public function getConfig($key)
	{
		return $this->config[$key];
	}

	public function getFtpClient($host, $port, $user, $password, $dir = null)
	{
		$ftpClient = new FtpClient();
		$ftpClient->connect($host, $port);
		$ftpClient->login($user, $password);
		if (!is_null($dir)) $ftpClient->chdir($dir);
		return $ftpClient;
	}

	public function getCurlSession($id)
	{
		return new CurlSession($id);
	}

	public function getAdminPanelUrl()
	{
		return $this->adminSiteUrl;
	}

	private function initSiteUrls()
	{
		$siteUrlDefineMethod = isset($this->config['siteUrlDefineMethod']) ? $this->config['siteUrlDefineMethod'] : "useSelfUrl";
		if ($siteUrlDefineMethod == "useSelfUrl")
		{
			$pathInfo = pathinfo($_SERVER['SCRIPT_NAME']);
			$pathInfo['dirname'] = str_replace ("\\", "/", $pathInfo['dirname']);
			if ($pathInfo['dirname'] == "/") $pathInfo['dirname'] = "";
			$this->siteUrl = 'http://' . $_SERVER['HTTP_HOST'] . $pathInfo['dirname'];
			$this->adminSiteUrl = $this->siteUrl . "/admin/";
		}
		elseif ($siteUrlDefineMethod == "readFromConfigFile")
		{
			$this->siteUrl = $this->getSiteUrlFromConfigFile($this->pathToApplicationRoot . "/apps/FrontEnd/config/local.ini");
			$this->adminSiteUrl = $this->getSiteUrlFromConfigFile($this->pathToApplicationRoot . "/apps/AdminPanel/config/local.ini");
		}
		else
		{
			throw new InstallationFailedException("Undefined method '{$siteUrlDefineMethod}' for defining Site Urls");
		}
	}

	private function getSiteUrlFromConfigFile($pathToFile)
	{
		$parsed = parse_ini_file($pathToFile);
		return $parsed['SiteUrl'];
	}

	/**
	 * @return mixed
	 */
	public function getPathToApplicationRoot()
	{
		return $this->pathToApplicationRoot;
	}

	private function getDataForRemoteLogging()
	{
		return $this->dataForRemoteLogging;
	}

	private function setDataForRemoteLog($id, $value)
	{
		$this->dataForRemoteLogging[$id] = $value;
	}
}

class StepFailedException extends \Exception
{
}

class WaitForUserInputException extends \Exception
{
}

class InstallationFailedException extends \Exception
{
	public $displayBuffer;

	public function __construct($message = "", $displayBuffer = false)
	{
		$this->displayBuffer = $displayBuffer;
		parent::__construct($message);
	}
}

class SkipActionException extends \Exception
{
}

class UndefinedActionRequestedException extends \Exception
{
	private $actionId;

	public function __construct($actionId)
	{
		$this->actionId = $actionId;
		$this->message = "Undefined action $actionId";
	}
}

class UndefinedRequirementTypeRequestedException extends \Exception
{
	private $requirementType;

	public function __construct($requirementType)
	{
		$this->requirementType = $requirementType;
		$this->message = "Undefined requirement type $requirementType";
	}
}

class FtpClient
{
	private $ftpResource;

	public function connect($ftpHost, $ftpPort)
	{
		$this->ftpResource = @ftp_connect($ftpHost, $ftpPort);
	}

	public function login($ftpUser, $ftpPassword)
	{
		return @ftp_login($this->ftpResource, $ftpUser, $ftpPassword);
	}

	public function chdir($ftpDirectory)
	{
		return @ftp_chdir($this->ftpResource, $ftpDirectory);
	}

	public function delete($pathToFile)
	{
		return @ftp_delete($this->ftpResource, $pathToFile);
	}

	public function rmdir($path)
	{
		return @ftp_rmdir($this->ftpResource, $path);
	}

	public function rename($from, $to)
	{
		return @ftp_rename($this->ftpResource, $from, $to);
	}
}

class CurlSession
{
	private $curl;

	public function __construct($id)
	{
		$this->curl = curl_init();
		curl_setopt($this->curl, CURLOPT_HEADER, 0);
		curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($this->curl, CURLOPT_TIMEOUT, 60);
		curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($this->curl, CURLOPT_COOKIEJAR, tempnam("", $id));
		if (isset($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']))
		{
			curl_setopt($this->curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
			curl_setopt($this->curl, CURLOPT_USERPWD, "{$_SERVER['PHP_AUTH_USER']}:{$_SERVER['PHP_AUTH_PW']}");
		}
	}

	public function request($url, $requestVars = array(), $method = 'GET')
	{
		$queryString = http_build_query($requestVars, '', '&');
		switch ($method)
		{
			case 'GET' :
				curl_setopt($this->curl, CURLOPT_HTTPGET, true);
				$url .= (strpos($url, '?')) ? '&' : '?';
				$url .= $queryString;
				break;
			case 'POST' :
				curl_setopt($this->curl, CURLOPT_POST, true);
				curl_setopt($this->curl, CURLOPT_POSTFIELDS, $queryString);
				break;
			default :
				throw new \Exception('Unknown method: ' . $method);
		}
		curl_setopt($this->curl, CURLOPT_URL, $url);
		curl_exec($this->curl);

		return curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
	}

	public function __destruct()
	{
		curl_close($this->curl);
	}
}
}

namespace Requirements {

class PhpExtension extends Requirement
{
	public function isValid()
	{
		\Installer::getInstance()->writeLog("Checking PHP \"{$this->data['name']}\" extension...");
		return extension_loaded($this->data['name']);
	}

	public function getCaption()
	{
		return $this->data['name'];
	}
}
}

namespace Requirements {

class PhpIniSetting extends Requirement
{
	public function isValid()
	{
		\Installer::getInstance()->writeLog("Checking php.ini \"{$this->data['name']}\" setting...");
		return ini_get($this->data['name']) == $this->data['value'];
	}

	public function getCaption()
	{
		return $this->data['name'];
	}
}
}

namespace Requirements {

class PhpTimeZone extends Requirement
{
	public function isValid()
	{
		\Installer::getInstance()->writeLog("Checking php.ini \"date.timezone\" setting...");
		$timezone = ini_get('date.timezone');
		return !empty($timezone);
	}

	public function getCaption()
	{
		return 'Time Zone';
	}
}
}

namespace Requirements {

class PhpVersion extends Requirement
{
	public function __construct($data)
	{
		\Installer::getInstance()->setReplacementVar('php_version', phpversion());
		parent::__construct($data);
	}

	public function isValid()
	{
		\Installer::getInstance()->writeLog("Checking PHP Version...");
		return version_compare($this->data['min'], phpversion(), "<=") &&
				version_compare($this->data['max'], phpversion(), ">");
	}

	public function getCaption()
	{
		return 'PHP Version';
	}
}
}

namespace Requirements {

class RebuildInterfaceCacheCheck extends Requirement
{
	public function isValid()
	{
		\Installer::getInstance()->writeLog("Checking Possibility To Rebuild Interface Cache...");
		$url = \Installer::getInstance()->getBaseUrl() . '/?SYSCOMMAND=REBUILD_INTERFACE_CACHE_CHECK';
		$serverResponse = $this->curlRequest($url);
		if ($serverResponse['status'] < 200 || $serverResponse['status'] >= 300)
		{
			\Installer::getInstance()->writeLog($serverResponse['response'], "error");
			return false;
		}
		return true;
	}

	private function curlRequest($url)
	{
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, 60);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		if (isset($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']))
		{
			curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
			curl_setopt($curl, CURLOPT_USERPWD, "{$_SERVER['PHP_AUTH_USER']}:{$_SERVER['PHP_AUTH_PW']}");
		}
		$response = curl_exec($curl);
		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);
		return array('status' => $status, 'response' => $response);
	}

	public function getCaption()
	{
		return 'Cache Build';
	}
}
}


namespace Requirements {

abstract class Requirement
{
	protected $data;

	public function __construct($data)
	{
		$this->data = $data;
	}
	public function getFailureMessage()
	{
		return \Installer::getInstance()->replaceMessageVarsToValues($this->data['message']);
	}
	public function isMandatory()
	{
		return $this->data['mandatory'];
	}

	abstract public function isValid();
	abstract public function getCaption();
}
}

namespace Tasks
{
	abstract class AbstractTask
	{
		protected $data;
		protected $config;

		public function __construct($data)
		{
			$this->data = $data;
		}

		public abstract function __invoke();

		public function setConfig($config)
		{
			$this->config = $config;
		}

		protected function getFiles()
		{
			$files = array();
			if (isset($this->data['fileset']))
			{
				if (isset($this->data['fileset']['includesfile']))
				{
					$files = array_merge($files, array_map('trim', file($this->data['fileset']['includesfile'])));
				}
				else
				{
					foreach ($this->data['fileset'] as $filesetRecord)
					{
						if (isset($filesetRecord['pattern']))
						{
							$currentDir = getcwd();
							if (isset($this->data['fileset']['basedir']))
							{
								chdir($this->data['fileset']['basedir']);
							}
							$it = new \GlobIterator($filesetRecord['pattern']);
							foreach ($it as $file)
							{
								$files[] = (string)$file;
							}
							chdir($currentDir);

						}
					}
				}
			}
			if (isset($this->data['file']))
			{
				$files[] = $this->data['file'];
			}
			return $files;
		}
	}
}

namespace Tasks {

class ApplyPermissionsViaFTP extends AbstractTask
{
	public function __invoke()
	{
		if (isset($this->data['message']))
		{
			$message = \Installer::getInstance()->replaceMessageVarsToValues($this->data['message']);
			\Installer::getInstance()->writeMessage($message, 'info', 'description');
		}
		$data = \Installer::getInstance()->getDataForForm($this->data['dataSet']);
		$dirsData = isset($this->data['dirset']) ? $this->data['dirset'] : array();
		$filesData = isset($this->data['fileset']) ? $this->data['fileset'] : array();
		$mode = $this->data['mode'];

		$ftpResource = @ftp_connect($data['ftpHost'], $data['ftpPort']);
		@ftp_login($ftpResource, $data['ftpUser'], $data['ftpPassword']);

		$ftpDirectory = $data['ftpDirectory'];
		if (!empty($ftpDirectory) && $ftpDirectory{strlen($ftpDirectory) - 1} != '/') $ftpDirectory .= '/';
		if ($ftpDirectory{0} != '/') $ftpDirectory = './' . $ftpDirectory;

		$failedDirs = array();
		$failedFiles = array();

		if (!empty($dirsData))
		{
			\Installer::getInstance()->writeLog("Changing directory permissions via FTP...");
			foreach ($dirsData as $dirData)
			{
                if (filter_var($dirData['optional'], FILTER_VALIDATE_BOOLEAN) && !file_exists($dirData['path']))
                {
                    \Installer::getInstance()->writeLog("Optional directory \"{$dirData['path']}\" does not exist. Skipped", "warning");
                }
                elseif (!file_exists($dirData['path']))
                {
					\Installer::getInstance()->writeLog("Directory \"{$dirData['path']}\" does not exist", "error");
					$failedDirs[] = $dirData['path'];
                }
				elseif (@ftp_site($ftpResource, "CHMOD $mode " . $ftpDirectory . "{$dirData['path']}") === false)
				{
					\Installer::getInstance()->writeLog("Cannot change mode of directory \"{$dirData['path']}\"", "error");
					$failedDirs[] = $dirData['path'];
				}
			}
			if (empty($failedDirs))
			{
				\Installer::getInstance()->writeLog("Changed directory permissions successfully...", "success");
			}
			else
			{
				\Installer::getInstance()->writeMessage("The specified FTP user does not have enough rights to change permissions to {$mode} for the following directories: \n<ul><li>" . implode("</li><li>", $failedDirs) . "</li><ul>\n", "error");
			}
		}

		if (!empty($filesData))
		{
			\Installer::getInstance()->writeLog("Changing file permissions via FTP...");
			foreach ($filesData as $fileData)
			{
                if (filter_var($fileData['optional'], FILTER_VALIDATE_BOOLEAN) && !file_exists($fileData['path']))
                {
                    \Installer::getInstance()->writeLog("Optional file \"{$fileData['path']}\" does not exist. Skipped", "warning");
                }
                elseif (!file_exists($fileData['path']))
                {
					\Installer::getInstance()->writeLog("File \"{$fileData['path']}\" does not exist", "error");
					$failedFiles[] = $fileData['path'];
                }
				elseif (@ftp_site($ftpResource, "CHMOD $mode " . $ftpDirectory . "{$fileData['path']}") === false)
				{
					\Installer::getInstance()->writeLog("Cannot change mode of file \"{$fileData['path']}\"", "error");
					$failedFiles[] = $fileData['path'];
				}
			}
			if (empty($failedFiles))
			{
				\Installer::getInstance()->writeLog("Changed file permissions successfully...", "success");
			}
			else
			{
				\Installer::getInstance()->writeMessage("The specified FTP user does not have enough rights to change permissions to {$mode} for the following files: \n<ul><li>" . implode("</li><li>", $failedFiles) . "</li><ul>\n", "error");
			}
		}

		ftp_close($ftpResource);

		if (!empty($failedDirs) || !empty($failedFiles))
		{
			throw new \StepFailedException("Please specify an FTP user which has enough rights to change permissions or change them manually. Alternatively, you can skip this step and set the correct permissions later.");
		}
	}
}
}

namespace Tasks {

class CheckExtraModuleVersionCompatibilities extends AbstractTask
{
    public function __construct($data)
    {
        parent::__construct($data);
        $this->data = $data;
        $upgradePatchDirBaseName = basename(dirname(__FILE__));
    }

    public function __invoke()
    {
		if (isset($_REQUEST['actionSkip']))
		{
			throw new \SkipActionException();
		}
        
        $patchInfo = $this->readPatchPackageInfo();
        $extraModuleDataList = $this->getProductVersionExtraModuleDataList($patchInfo['product'], $patchInfo['version']);
        
        $incompatibleExtraModules = array();
        
        foreach($extraModuleDataList as $extraModuleData)
        {
            if ($this->moduleExists($extraModuleData['name']))
            {
                $installedExtraModuleInfo = $this->readModulePackageInfo($extraModuleData['name']);
                if ($installedExtraModuleInfo['version'] < $extraModuleData['version'])
                {
                    $incompatibleExtraModules[] = array(
                        'name' => $extraModuleData['name'],
                        'caption' => $extraModuleData['caption'],
                        'currentVersion' => $installedExtraModuleInfo['version'],
                        'requiredVersion' => $extraModuleData['version'],
                    );
                }
            }
        }
        
        if (empty($incompatibleExtraModules))
        {
            \Installer::getInstance()->writeLog('Existing extra module versions are compatible with patch version.', 'success');
        }
        else
        {
            \Installer::getInstance()->writeLog("Existing extra module versions are incompatible with patch version.", 'error');
            
            $log = array();
            foreach($incompatibleExtraModules as $incompatibleExtraModule)
            {
                $log[] = $incompatibleExtraModule['name'] . '-' . $incompatibleExtraModule['currentVersion'] . '. Required version is '. $incompatibleExtraModule['requiredVersion'];
            }
            
			$template = new \TemplateProcessor\Template();
			$template->caption = "Checking Extra Module Compabilities";
			$template->skipable = isset($this->data['skipable']) ? $this->data['skipable'] : false;
			$template->message = "The following extra module versions are incompatible with patch version and must be upgraded.  \n<ul><li>" . implode("</li><li>", $log) . "</li><ul>\n";

			$template->display('files/retry_skip_confirmation.tpl');
            
            throw new \WaitForUserInputException();
        }
    }
    
    private function moduleExists($moduleName)
    {
        $ftpDataSet = \Installer::getInstance()->getDataForForm($this->data['FtpDataSet']);
        $dir = $ftpDataSet['ftpDirectory'] . '/modules/' . $moduleName;
        
        return @is_dir($dir);
    }
    
    private function readModulePackageInfo($moduleName)
    {
        $ftpDataSet = \Installer::getInstance()->getDataForForm($this->data['FtpDataSet']);
        $dir = $ftpDataSet['ftpDirectory'] . '/modules/' . $moduleName;
        $fileContent = file_get_contents($dir . '/packageinfo.txt');
        preg_match('/\Module: (?P<module>\w+) v\.(?P<version>[\w\.\-]+), \(c\) WorksForWeb 2005 - 20.*/', $fileContent, $matches);
        if (empty($matches))
        {
            throw new \InstallationFailedException("Could not locate file 'packageinfo.txt' of module '{$moduleName}' in directory '{$dir}'");
        }
        
        return array(
            'module' => $matches['module'],
            'version' => $matches['version'],
        );
    }

    private function readPatchPackageInfo()
    {
        $dir = dirname(__FILE__);
        $fileContent = file_get_contents($dir . '/packageinfo.txt');
        preg_match('/(?P<product>\w+) v\.(?P<version>[\w\.\-]+), \(c\) WorksForWeb 2005 - 20.*/', $fileContent, $matches);
        if (empty($matches))
        {
            throw new \InstallationFailedException("Could not locate file 'packageinfo.txt' of upgrade patch in directory '{$dir}'");
        }
        
        return array(
            'product' => $matches['product'],
            'version' => $matches['version'],
        );
    }

    private function getProductVersionExtraModuleDataList($product, $version)
    {
        return array_merge(
            $this->getExtraModuleDataByUrl("http://www.worksforweb.com/modules_data/{$version}/common.xml"),
            $this->getExtraModuleDataByUrl("http://www.worksforweb.com/modules_data/{$version}/{$product}.xml")
        );
    }

    private function getExtraModuleDataByUrl($url) 
    {
        $xml = @simplexml_load_file($url);
        if (empty($xml->module))
        {
            throw new \InstallationFailedException("Could not fetch modules data");
        }
        
        $extraModules = array();

        foreach ($xml->module as $module)
        {
            if (strtolower($module['extra']) == 'true')
            {
                $extraModule['name'] = (string) $module['name'];
                $extraModule['caption'] = (string) $module['caption'];
                $extraModule['version'] = (string) $module->version['value'];
                $extraModules[] = $extraModule;
            }
        }

        return $extraModules;
    }
    
    }
}

namespace Tasks
{
	class CheckFileNotExists extends AbstractTask
	{
		public function __invoke()
		{
			$files = array_filter($this->getFiles(), array($this, 'fileExists'));
			if (!empty($files))
			{
				$this->displayConfirmationPage("Checking Deleted Files", "Delete the files listed below and click retry.", $files);
				throw new \WaitForUserInputException();
			}
		}

		private function displayConfirmationPage($caption, $message, $failedFiles)
		{
			$template = new \TemplateProcessor\Template();
			$template->caption = $caption;
			$template->skipable = isset($this->data['skipable']) ? $this->data['skipable'] : false;
			$template->message = $message;
			$template->failedFiles = $failedFiles;

			$template->display('files/default_confirmation.tpl');
		}

		private function fileExists($file)
		{
			return !empty($file) && file_exists(\Installer::getInstance()->getPathToApplicationRoot(). $file);
		}
	}
}


namespace Tasks
{
	class CheckFilePermission extends AbstractTask
	{
		public function __invoke()
		{
			$expectedMode = isset($this->data['expectedMode']) ? $this->data['expectedMode'] : true;
			$messagesData = isset($this->data['messages']) ? $this->data['messages'] : array();
			$log = isset($messagesData['log']) ? $messagesData['log'] : false;
			$failureLog = isset($messagesData['failureLog']) ? $messagesData['failureLog'] : false;
			$failureLogType = $messagesData['failureLogType'];
			$failureMessage = $messagesData['failureMessage'];
			$dirSet = isset($this->data['dirset']) ? $this->data['dirset'] : array();
			$fileSet = isset($this->data['fileset']) ? $this->data['fileset'] : array();
						
			if ($log)
				\Installer::getInstance()->writeLog($log);

			$failedDirs = $this->getWriteFailedItems($dirSet, $expectedMode);
			$failedFiles = $this->getWriteFailedItems($fileSet, $expectedMode);

			if (!empty($failedDirs) || !empty($failedFiles))
			{
				if ($failureLog)
					\Installer::getInstance()->writeLog($failureLog, $failureLogType);
				
				$message = str_replace('${expectedPermissionMode}', $expectedMode, $failureMessage);
				
				if (isset($_REQUEST['actionNext']))
				{
					throw new \SkipActionException();
				}
				else
				{
					$this->displayConfirmationPage($log, $message, $failedDirs, $failedFiles);
					throw new \WaitForUserInputException();
				}
			}
		}

		private function getWriteFailedItems($items, $expectedMode)
		{
			$failedItems = array();
			foreach ($items as $item)
			{
				clearstatcache();
				// do not check if optional is set to 'true' and file does not exist
				if (filter_var($item['optional'], FILTER_VALIDATE_BOOLEAN) && !file_exists($item['path']))
				{
					continue;
				}

                if (substr(decoct(fileperms($item['path'])), -3) != $expectedMode)
                {
                    $failedItems[] = $item['path'];
                }
			}
			return $failedItems;
		}
		
		private function displayConfirmationPage($caption, $message, $failedDirs, $failedFiles)
		{
			$template = new \TemplateProcessor\Template();
			$template->caption = $caption;
			$template->skipable = isset($this->data['skipable']) ? $this->data['skipable'] : false;
			$template->message = $message;
			$template->failedDirs = $failedDirs;
			$template->failedFiles = $failedFiles;
			
			$template->display('files/default_confirmation.tpl');
		}
	}
}

namespace Tasks
{
	class CheckFileWritable extends AbstractTask
	{
		public function __invoke()
		{
			$messagesData = isset($this->data['messages']) ? $this->data['messages'] : array();
			$log = isset($messagesData['log']) ? $messagesData['log'] : false;
			$failureLog = isset($messagesData['failureLog']) ? $messagesData['failureLog'] : false;
			$failureLogType = $messagesData['failureLogType'];
			$failureMessage = $messagesData['failureMessage'];
			$dirSet = isset($this->data['dirset']) ? $this->data['dirset'] : array();
			$fileSet = isset($this->data['fileset']) ? $this->data['fileset'] : array();
						
			if ($log)
				\Installer::getInstance()->writeLog($log);

			$failedDirs = $this->getWriteFailedItems($dirSet);
			$failedFiles = $this->getWriteFailedItems($fileSet);

			if (!empty($failedDirs) || !empty($failedFiles))
			{
				if ($failureLog)
					\Installer::getInstance()->writeLog($failureLog, $failureLogType);
				
				if (isset($_REQUEST['actionNext']))
				{
					throw new \SkipActionException();
				}
				else
				{
					$this->displayConfirmationPage($log, $failureMessage, $failedDirs, $failedFiles);
					throw new \WaitForUserInputException();
				}
			}
		}

		private function getWriteFailedItems($items)
		{

			$failedItems = array();
			foreach ($items as $item)
			{
				clearstatcache();
				// do not check if optional is set to 'true' and file does not exist
				if (filter_var($item['optional'], FILTER_VALIDATE_BOOLEAN) && !file_exists($item['path']))
				{
					continue;
				}

				if (!is_writable($item['path']))
				{
					$failedItems[] = $item['path'];
				}
			}
			return $failedItems;
		}

		private function displayConfirmationPage($caption, $message, $failedDirs, $failedFiles)
		{
			$template = new \TemplateProcessor\Template();
			$template->caption = $caption;
			$template->skipable = isset($this->data['skipable']) ? $this->data['skipable'] : false;
			$template->message = $message;
			$template->failedDirs = $failedDirs;
			$template->failedFiles = $failedFiles;
			
			$template->display('files/default_confirmation.tpl');
		}
	}
}


namespace Tasks {
	class CheckLicense extends AbstractTask
	{

		public function __invoke()
		{
			if (file_exists('license') || $_SERVER['HTTP_HOST'] == 'localhost')
				throw new \SkipActionException();

			if (isset($_REQUEST['actionNext']) && ! file_exists('license'))
			{
			   throw new \StepFailedException("No License");
			}

			if (isset($_REQUEST['actionNext']) && file_exists('license'))
			{
				\Installer::getInstance()->writeLog("License successfully uploaded", "success");
				return;
			}

			$template = new \TemplateProcessor\Template();
			$template->message = $this->data['message'];
			$template->display('files/show_license.tpl');

			$waitForConfirmation = isset($this->data['waitForConfirmation']) ? $this->data['waitForConfirmation'] : false;
			if ($waitForConfirmation)
			{
				$template->actionNext = true;
				$template->display('files/default_button_set.tpl');

				throw new \WaitForUserInputException();
			}
		}
	}
}

namespace Tasks {

class CheckRequirements extends AbstractTask
{
	public function __invoke()
	{
		if (isset($_REQUEST['actionNext']))
		{
			return;
		}

		$requirements = $this->data['requirements'];

		\Installer::getInstance()->writeLog("Checking minimal server requirements...");

		$hasError = false;
		// clear messages
		\Installer::getInstance()->getMessages();

		$result = [];

		foreach ($requirements as $requirementData)
		{
			$requirementClassName = "\\Requirements\\{$requirementData['type']}";
			if (!class_exists($requirementClassName))
			{
				throw new \UndefinedRequirementTypeRequestedException($requirementData['type']);
			}
			/**
			 * @var \Requirements\Requirement $requirement
			 */
			$requirement = new $requirementClassName($requirementData);
			if ($requirement->isValid())
			{
				$status = 'success';
			}
			elseif ($requirement->isMandatory())
			{
				\Installer::getInstance()->writeLog($requirement->getFailureMessage(), "error");
				$hasError = true;
				$status = 'error';
			}
			else
			{
				\Installer::getInstance()->writeLog($requirement->getFailureMessage(), "warning");
				\Installer::getInstance()->writeMessage($requirement->getFailureMessage(), "warning");
				$status = 'warning';
			}

			$result[$requirement->getCaption()] = [
				'status' => $status,
				'message' => $requirement->getFailureMessage()
			];
		}

		$template = new \TemplateProcessor\Template();
		$template->result = $result;
		$template->display('files/requirements.tpl');

		if ($hasError) {
			throw new \InstallationFailedException('Hosting is not compatible.', true);
		}

		$template->actionNext = true;
		$template->display('files/default_button_set.tpl');

		throw new \WaitForUserInputException();
	}
}
}

namespace Tasks {

	class DBApply extends AbstractTask
	{
		public function __invoke()
		{
			$files = $this->getFiles();
			if (empty($files))
			{
				\Installer::getInstance()->writeLog("There is no sql file to apply", "info");
				return true;
			}

			$data = \Installer::getInstance()->getDataForForm($this->data['dataSet']);

			try
			{
				$dbh = new \PDO("mysql:dbname={$data['dbName']};host={$data['dbHost']};charset=utf8", $data['dbUser'], $data['dbPassword']);
			}
			catch (\PDOException $e)
			{
				throw new \InstallationFailedException("Connection failed: " . $e->getMessage());
			}

			$commands = array();
			set_time_limit(0);

			foreach ($files as $file)
			{
				\Installer::getInstance()->writeLog("Applying DB dump file: " . $file);
				if (!$sqlFile = @fopen($file, "r"))
				{
					\Installer::getInstance()->writeLog("Cannot read sql file: " . $file, "error");
					throw new \InstallationFailedException("Cannot read sql file: " . $file);
				}
				$sqlQuery = fread($sqlFile, filesize($file));
				fclose($sqlFile);
				$this->PMA_splitSqlFile($commands, $sqlQuery);
			}

			foreach ($commands as $command)
			{
				if ($command['empty'] || empty ($command['query']))
					continue;
				$command['query'] = trim($command['query']);

				if ($dbh->exec($command['query']) === false)
				{
					list(, $dbErrorCode, $dbErrorMessage) = $dbh->errorInfo();
					$errorMessage = "Cannot execute MySQL query: #{$dbErrorCode} - {$dbErrorMessage}";
					\Installer::getInstance()->writeLog($errorMessage, "error");
					throw new \InstallationFailedException($errorMessage);
				}
			}

			\Installer::getInstance()->writeLog("Applied dump file successfully...", "success");
		}

		private function PMA_splitSqlFile(&$ret, $sql, $release = 3)
		{
			// do not trim, see bug #1030644
			//$sql          = trim($sql);
			$sql = rtrim($sql, "\n\r");
			$sql_len = strlen($sql);
			$char = '';
			$string_start = '';
			$in_string = FALSE;
			$nothing = TRUE;
			$time0 = time();

			for ($i = 0; $i < $sql_len; ++$i)
			{
				$char = $sql[$i];

				// We are in a string, check for not escaped end of strings except for
				// backquotes that can't be escaped
				if ($in_string)
				{
					for (; ;)
					{
						$i = strpos($sql, $string_start, $i);
						// No end of string found -> add the current substring to the
						// returned array
						if (!$i)
						{
							$ret[] = array('query' => $sql, 'empty' => $nothing);
							return TRUE;
						}
						// Backquotes or no backslashes before quotes: it's indeed the
						// end of the string -> exit the loop
						else {
							if ($string_start == '`' || $sql[$i - 1] != '\\')
							{
								$string_start = '';
								$in_string = FALSE;
								break;
							}
							// one or more Backslashes before the presumed end of string...
							else
							{
								// ... first checks for escaped backslashes
								$j = 2;
								$escaped_backslash = FALSE;
								while ($i - $j > 0 && $sql[$i - $j] == '\\')
								{
									$escaped_backslash = !$escaped_backslash;
									$j++;
								}
								// ... if escaped backslashes: it's really the end of the
								// string -> exit the loop
								if ($escaped_backslash)
								{
									$string_start = '';
									$in_string = FALSE;
									break;
								}
								// ... else loop
								else
								{
									$i++;
								}
							}
						} // end if...elseif...else
					} // end for
				} // end if (in string)

				// lets skip comments (/*, -- and #)
				else if (($char == '-' && $sql_len > $i + 2 && $sql[$i + 1] == '-' && $sql[$i + 2] <= ' ') || $char == '#' || ($char == '/' && $sql_len > $i + 1 && $sql[$i + 1] == '*'))
				{
					$i = strpos($sql, $char == '/' ? '*/' : "\n", $i);
					// didn't we hit end of string?
					if ($i === FALSE)
					{
						break;
					}
					if ($char == '/') $i++;
				}

				// We are not in a string, first check for delimiter...
				else if ($char == ';')
				{
					// if delimiter found, add the parsed part to the returned array
					$ret[] = array('query' => substr($sql, 0, $i), 'empty' => $nothing);
					$nothing = TRUE;
					$sql = ltrim(substr($sql, min($i + 1, $sql_len)));
					$sql_len = strlen($sql);
					if ($sql_len)
					{
						$i = -1;
					}
					else
					{
						// The submited statement(s) end(s) here
						return TRUE;
					}
				} // end else if (is delimiter)

				// ... then check for start of a string,...
				else if (($char == '"') || ($char == '\'') || ($char == '`'))
				{
					$in_string = TRUE;
					$nothing = FALSE;
					$string_start = $char;
				} // end else if (is start of string)

				elseif ($nothing)
				{
					$nothing = FALSE;
				}

				// loic1: send a fake header each 30 sec. to bypass browser timeout
				$time1 = time();
				if ($time1 >= $time0 + 30)
				{
					$time0 = $time1;
					header('X-pmaPing: Pong');
				} // end if
			} // end for

			// add any rest to the returned array
			if (!empty($sql) && preg_match('@[^[:space:]]+@', $sql))
			{
				$ret[] = array('query' => $sql, 'empty' => $nothing);
			}

			return TRUE;
		}
	}
}

namespace Tasks
{
	class DefineAdminCredentials extends \Tasks\AbstractTask
	{
		public function __invoke()
		{
			if (isset($this->data['message']))
			{
				\Installer::getInstance()->writeMessage($this->data['message'], 'info', 'description');
			}
			$data = \Installer::getInstance()->getDataForForm($this->data['dataSet']);
			$dbData = \Installer::getInstance()->getDataForForm($this->data['dbDataSet']);

			try
			{
				$dbh = new \PDO("mysql:dbname={$dbData['dbName']};host={$dbData['dbHost']};charset=utf8", $dbData['dbUser'], $dbData['dbPassword']);
			}
			catch (\PDOException $e)
			{
				throw new \InstallationFailedException("Connection failed: " . $e->getMessage());
			}

			\Installer::getInstance()->writeLog("Writing administrator configuration...");

			$this->dbQuery($dbh, "TRUNCATE TABLE `core_administrator`");
			$this->dbQuery($dbh, "INSERT INTO `core_administrator`(`username`, `password`, `group`) VALUES(?, PASSWORD(?), 'admin')", array($data['adminUsername'], $data['adminPassword']));
			$this->dbQuery($dbh, "UPDATE `core_settings` SET value = ? WHERE name = 'system_email'", array($data['systemEmail']));

			\Installer::getInstance()->writeLog("Defined admin credentials successfully...", "success");
		}

		/**
		 * @param \PDO $dbh
		 * @param string $query
		 * @param array $params
		 * @throws \InstallationFailedException
		 */
		private function dbQuery($dbh, $query, $params = array())
		{
			$sth = $dbh->prepare($query);
			if (!$sth->execute($params))
			{
				list(, $dbErrorCode, $dbErrorMessage) = $sth->errorInfo();
				$errorMessage = "Cannot execute MySQL query: #{$dbErrorCode} - {$dbErrorMessage}";
				\Installer::getInstance()->writeLog($errorMessage, "error");
				throw new \InstallationFailedException($errorMessage);
			}

		}
	}
}

namespace Tasks
{
	class DefineLocalSettings extends AbstractTask
	{
		public function __invoke()
		{
			$data = \Installer::getInstance()->getDataForForm($this->data['dataSet']);

			\Installer::getInstance()->writeLog("Defining local settings...");

			$config = array
			(
				'DBHost' => $data['dbHost'],
				'DBUser' => $data['dbUser'],
				'DBPassword' => $data['dbPassword'],
				'DBName' => $data['dbName'],
				'MySQLCharset' => $this->data['charset'],
			);
			$baseUrl = \Installer::getInstance()->getBaseUrl();
			$frontEndConfig = array_merge($config, array('SiteUrl' => $baseUrl));
			$adminPanelConfig = array_merge($config, array('SiteUrl' => $baseUrl . '/admin'));

			$this->rewriteConfigFile($frontEndConfig, 'apps/FrontEnd/config/local.ini');
			$this->rewriteConfigFile($adminPanelConfig, 'apps/AdminPanel/config/local.ini');

            $mobileConfigIniPath = 'apps/MobileFrontEnd/config/local.ini';
            $mobileFrontEndUrl = $this->getMobileFrontendUrlFromLicense();
            if (file_exists($mobileConfigIniPath))
            {
                if (!$mobileFrontEndUrl)
                {
                    $mobileFrontEndUrl = $baseUrl . '/m';
                }
                $mobileFrontEndConfig = array_merge($config, array('SiteUrl' => $mobileFrontEndUrl));
                $this->rewriteConfigFile($mobileFrontEndConfig, 'apps/MobileFrontEnd/config/local.ini');
            }
            
			\Installer::getInstance()->writeLog("Defined local settings successfully...", "success");
		}

		private function rewriteConfigFile($config, $fileName)
		{
			$fileContent = "";
			foreach ($config as $key => $value)
			{
				$fileContent .= $key . " = \"" . addcslashes($value, '"\\') . "\"\n";
			}
			if (file_put_contents($fileName, $fileContent) === false)
			{
				\Installer::getInstance()->writeLog("Cannot rewrite \"{$fileName}\" file...", "error");
				throw new \InstallationFailedException("Cannot rewrite \"{$fileName}\" file!");
			}
		}
        
		private function getMobileFrontendUrlFromLicense()
		{
			if (!file_exists('license')) 
            {
                return false;
            }
			$data = parse_ini_file('license');
            
			return isset($data['MobileFrontEnd_site_url']) ? $data['MobileFrontEnd_site_url'] : false;
		}
	}
}

namespace Tasks
{
	class DeleteFilesViaFtp extends AbstractTask
	{
		/**
		 * @var \FtpClient
		 */
		private $ftpClient;

		public function __invoke()
		{
			$data = \Installer::getInstance()->getDataForForm($this->data['dataSet']);

			$this->ftpClient = \Installer::getInstance()->getFtpClient($data['ftpHost'], $data['ftpPort'], $data['ftpUser'], $data['ftpPassword'], $data['ftpDirectory']);

			$files = $this->getFiles();
			$files = array_filter($files, array($this, 'fileExists'));

			$deletedFiles = array();
			$failedFiles = array();

			foreach ($files as $file)
			{
				if ($this->deleteFilesRecursively($file))
				{
					$deletedFiles[] = $file;
				}
				else
				{
					$failedFiles[] = $file;
				}
			}
			if (!empty($deletedFiles))
			{
				$list = sprintf("'%s'", join("', '", $deletedFiles));
				\Installer::getInstance()->writeLog("Files listed below were successfully deleted: " . $list, "success");
			}
			if (!empty($failedFiles))
			{
				\Installer::getInstance()->writeMessage("The specified FTP user does not have enough rights to delete the following files: \n<ul><li>" . implode("</li><li>", $failedFiles) . "</li><ul>\n", "error");
				throw new \StepFailedException("Please specify an FTP user which has enough rights to delete files or delete them manually.");
			}
		}

		private function fileExists($file)
		{
			return !empty($file) && file_exists(\Installer::getInstance()->getPathToApplicationRoot() . $file);
		}

		private function deleteFilesRecursively($pathToFile)
		{
			if (is_file(\Installer::getInstance()->getPathToApplicationRoot() . $pathToFile))
			{
				return $this->ftpClient->delete($pathToFile);
			}
			if (is_dir(\Installer::getInstance()->getPathToApplicationRoot() . $pathToFile))
			{
				return $this->deleteDirectory($pathToFile);
			}
		}

		private function deleteDirectory($pathToFile)
		{
			$currentDir = getcwd();
			chdir(\Installer::getInstance()->getPathToApplicationRoot());
			/**
			 * @var \SplFileInfo[] $iterator
			 */
			$iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($pathToFile, \FilesystemIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST);

			$dirs = array();
			$files = array();
			foreach ($iterator as $currentFile)
			{
				if ($currentFile->isDir())
				{
					$dirs[] = (string) $currentFile;
				}
				else
				{
					$files[] = (string) $currentFile;
				}
			}
			$dirs[] = $pathToFile;
			$dirs = array_unique($dirs);

			usort($dirs, function ($a, $b) {
				return strlen($b) - strlen($a);
			});

			chdir($currentDir);

			$filesDeletionResult = array_map(array($this->ftpClient, 'delete'), $files);
			$dirsDeletionResult = array_map(array($this->ftpClient, 'rmdir'), $dirs);

			return !in_array(false, $filesDeletionResult) && !in_array(false, $dirsDeletionResult);
		}
	}
}


namespace Tasks {

class DisplayTemplate extends AbstractTask
{
	public function __invoke()
	{
		if (isset($_REQUEST['actionNext']))
		{
			return;
		}
		
		$template = new \TemplateProcessor\Template();
		$template->baseUrl = \Installer::getInstance()->getBaseUrl() . "/";
		$template->product_name = \Installer::getInstance()->getReplacementVar('product');
		$template->site_url = \Installer::getInstance()->getReplacementVar('siteUrl');
		$template->display('files/' . $this->data['templateFileName']);
		
		$waitForConfirmation = isset($this->data['waitForConfirmation']) ? $this->data['waitForConfirmation'] : false;
		if ($waitForConfirmation)
		{
			$template->actionNext = true;
			$template->display('files/default_button_set.tpl');
			
			throw new \WaitForUserInputException();
		}
	}
}
}

namespace Tasks {
	class GenerateLicense extends AbstractTask
	{

		public function __invoke()
		{
			if (file_exists('license') || $_SERVER['HTTP_HOST'] == 'localhost')
				throw new \SkipActionException();

			$this->check();

			if (isset($this->data['message'])) {
				\Installer::getInstance()->writeMessage($this->data['message'], 'info', 'description');
			}

			$data = \Installer::getInstance()->getDataForForm($this->data['dataSet']);

			$data['FrontEnd_site_url'] = \Installer::getInstance()->getBaseUrl();
			$data['product_name'] = $this->config['product'];
			$data['product_version'] = $this->config['version'];
			$data['action'] = 'GenerateLicense';
			$this->generate($data);
		}

		private function generate($data)
		{

			\Installer::getInstance()->writeLog("Try License generate");
			$post_string = http_build_query($data);
			$parts = parse_url(\Installer::getInstance()->getConfig('licenseWorksforweb'));

			@$fp = fsockopen($parts['host'],
				isset($parts['port']) ? $parts['port'] : 80,
				$errNo, $errStr, 30);

			if (false === $fp) {
				\Installer::getInstance()->writeLog("Can not connect to remote server", "error");
				return;
			}

			$out = "POST " . $parts['path'] . " HTTP/1.1\n";
			$out .= "Host: " . $parts['host'] . "\n";
			$out .= "Content-Type: application/x-www-form-urlencoded\n";
			$out .= "Content-Length: " . strlen($post_string) . "\n";
			$out .= "Connection: Close\n\n";
			if (!empty($post_string)) {
				$out .= $post_string;
			}
			fwrite($fp, $out);
			fclose($fp);
			\Installer::getInstance()->writeLog("License was generated successful and send to your E-mail");
		}

		private function check()
		{
			$post_string = http_build_query(array(
				'FrontEnd_site_url' => \Installer::getInstance()->getBaseUrl(),
				'action' => 'check',
			));
			$parts = parse_url(\Installer::getInstance()->getConfig('licenseWorksforweb') . 'system/licenses/installation_license/');

			@$fp = fsockopen($parts['host'],
				isset($parts['port']) ? $parts['port'] : 80,
				$errNo, $errStr, 30);

			if (false === $fp) {
				\Installer::getInstance()->writeLog("Can not connect to remote server", "error");
				return;
			}

			$out = "POST " . $parts['path'] . " HTTP/1.1\n";
			$out .= "Host: " . $parts['host'] . "\n";
			$out .= "Content-Type: application/x-www-form-urlencoded\n";
			$out .= "Content-Length: " . strlen($post_string) . "\n";
			$out .= "Connection: Close\n\n";
			if (!empty($post_string)) {
				$out .= $post_string;
			}
			fwrite($fp, $out);

			$isBody = false;
			$message = '';
			while (!feof($fp)) {
				$buf = fgets($fp, 1024);

				if ($isBody) {
					$message .= $buf;
				}

				if ($buf == "\r\n") {
					$isBody = true;
				}
			}
			fclose($fp);
			if (trim($message) == 'Yes')
				throw new \SkipActionException();
		}
	}
}

namespace Tasks
{
	class InstallAllModules extends \Tasks\AbstractTask
	{
		public function __invoke()
		{
			$curl = \Installer::getInstance()->getCurlSession("installAllModules");

			if (isset($this->data['message']))
			{
				$message = \Installer::getInstance()->replaceMessageVarsToValues($this->data['message']);
				\Installer::getInstance()->writeMessage($message, 'info', 'description');
			}
			$adminData = \Installer::getInstance()->getDataForForm($this->data['adminDataSet']);
			$adminPanelUrl = \Installer::getInstance()->getBaseUrl() . "/admin/";

			\Installer::getInstance()->writeLog("Installation of all modules step:");
			try
			{
				$validator = new \Validators\LicenseExistAndValid();
				$validator->validate(array());
			}
			catch (\StepFailedException $e)
			{
				\Installer::getInstance()->writeLog($e->getMessage(), "error");
				throw new \InstallationFailedException($e->getMessage() . ' Please obtain the license from <a href="http://license.worksforweb.com/">http://license.worksforweb.com/</a> to continue with the module installation.');
			}

			$curl->request(
				$adminPanelUrl,
				array(
					'action' => 'login',
					'admin_username' => $adminData['adminUsername'],
					'admin_password' => $adminData['adminPassword']
				),
				'POST'
			);
			$curl->request(
				$adminPanelUrl . 'system/module_manager/manage_modules/',
				array(
					'action' => 'installAll'
				)
			);

            $curl->request(
                \Installer::getInstance()->getBaseUrl(),
                array(
                    'SYSCOMMAND'=>'CLEAR_MODULE_FUNCTION_INFO_CACHE'
                )
            );

			\Installer::getInstance()->writeLog("All modules successfully installed", "success");
		}

	}
}


namespace Tasks
{
	class MoveDirectoryContentToApplicationRootViaFtp extends AbstractTask
	{
		/**
		 * @var \FtpClient
		 */
		private $ftpClient;

		public function __invoke()
		{
			$data = \Installer::getInstance()->getDataForForm($this->data['dataSet']);
			$this->ftpClient = \Installer::getInstance()->getFtpClient($data['ftpHost'], $data['ftpPort'], $data['ftpUser'], $data['ftpPassword'], $data['ftpDirectory']);

			$upgradePatchDirBaseName = basename(dirname(__FILE__));

			// to be on the same dir in php and ftp
			chdir(\Installer::getInstance()->getPathToApplicationRoot());

			$from = $upgradePatchDirBaseName . "/" . $this->data['dir'] . "/";
			$to = "./";

			if (!empty($this->data['to']))
			{
				$to .= $this->data['to'] . "/";
				\Installer::getInstance()->writeLog(sprintf("Moving '%s' directory files to the '%s' dir of the application", $this->data['dir'], $this->data['to']));
			}
			else
			{
				\Installer::getInstance()->writeLog(sprintf("Moving '%s' directory files to the root of the application", $this->data['dir']));
			}

			$it = new \DirectoryIterator($from);
			/**
			 * @var \DirectoryIterator $file
			 */
			foreach ($it as $file)
			{
				if ($file->isDot()) continue;
				if ($file->isFile())
				{
					$this->moveFile((string) $file, $from, $to);
				}
				elseif ($file->isDir())
				{
					$this->moveDir((string) $file, $from, $to);
				}
			}

			// return to upgrade patch directory
			chdir($upgradePatchDirBaseName);
		}

		private function moveFile($file, $from, $to)
		{
			if (file_exists($file))
			{
				$this->ftpClient->delete($to . $file);
			}
			$this->ftpClient->rename($from . $file, $to . $file);

		}
		private function moveDir($dir, $from, $to)
		{
			if (!file_exists($to . $dir))
			{
				$this->ftpClient->rename($from . $dir, $to . $dir);
			}
			else
			{
				$it = new \DirectoryIterator($from . $dir);
				/**
				 * @var \DirectoryIterator $file
				 */
				foreach ($it as $file)
				{
					if ($file->isDot()) continue;
					if ($file->isFile())
					{
						$this->moveFile($dir . "/" . $file, $from, $to);
					}
					elseif ($file->isDir())
					{
						$this->moveDir($dir . "/" . $file, $from, $to);
					}
				}
				$this->ftpClient->rmdir($from . $dir);
			}
		}
	}
}


namespace Tasks {
	class PlaceLicense extends AbstractTask
	{

		public function __invoke()
		{
			if (file_exists('license') || $_SERVER['HTTP_HOST'] == 'localhost')
				throw new \SkipActionException();

			if (isset($this->data['message'])) {
				\Installer::getInstance()->writeMessage($this->data['message'], 'info', 'description');
			}

			$ftpdata = \Installer::getInstance()->getDataForForm($this->data['dataSet']);

			$this->placeLicense($ftpdata);
		}

		private function placeLicense($ftpData)
		{
			$license = $this->getLicense();
			$tmpLicense = tmpfile();
			fwrite($tmpLicense, $license);
			fseek($tmpLicense, 0);

			$ftpResource = @ftp_connect($ftpData['ftpHost'], $ftpData['ftpPort']);
			@ftp_login($ftpResource, $ftpData['ftpUser'], $ftpData['ftpPassword']);
			$ftpDirectory = $ftpData['ftpDirectory'];
			if (!empty($ftpDirectory) && $ftpDirectory{strlen($ftpDirectory) - 1} != '/') {
				$ftpDirectory .= '/';
			}
			if ($ftpDirectory{0} != '/') {
				$ftpDirectory = './' . $ftpDirectory;
			}
			if (@ftp_fput($ftpResource, $ftpDirectory . 'license', $tmpLicense, FTP_BINARY) === false) {
				throw new \StepFailedException("License Upload Failed");
			}
			fclose($tmpLicense);
			if (@ftp_site($ftpResource, "CHMOD {$this->data['mode']} " . $ftpDirectory . 'license') === false) {
				\Installer::getInstance()->writeLog("Cannot change mode of license", "error");
			}

			\Installer::getInstance()->writeLog("Placed License to root", "success");
		}

		private function getLicense()
		{
			if (isset($this->data['LicenseVerificationCodeMessage']))
			{
				$message = \Installer::getInstance()->replaceMessageVarsToValues($this->data['LicenseVerificationCodeMessage']);
				\Installer::getInstance()->writeMessage($message, 'info', 'description');
			}
			$verificationData = \Installer::getInstance()->getDataForForm($this->data['verificationData']);
			$post_string = http_build_query(array(
				'action' => 'getLicense',
				'site_url' => \Installer::getInstance()->getBaseUrl(),
				'code' => $verificationData['code'],
			));
			$parts = parse_url(\Installer::getInstance()->getConfig('licenseWorksforweb') . 'system/licenses/installation_license/');

			@$fp = fsockopen($parts['host'],
				isset($parts['port']) ? $parts['port'] : 80,
				$errNo, $errStr, 30);

			if (false === $fp) throw new \StepFailedException("Can not connect to remote server", "error");

			$out = "POST " . $parts['path'] . " HTTP/1.1\n";
			$out .= "Host: " . $parts['host'] . "\n";
			$out .= "Content-Type: application/x-www-form-urlencoded\n";
			$out .= "Content-Length: " . strlen($post_string) . "\n";
			$out .= "Connection: Close\n\n";
			if (!empty($post_string)) {
				$out .= $post_string;
			}
			fwrite($fp, $out);

			$isBody = false;
			$license = '';
			while (!feof($fp)) {
				$buf = fgets($fp, 1024);

				if ($isBody) {
					$license .= $buf;
				}

				if ($buf == "\r\n") {
					$isBody = true;
				}
			}
			fclose($fp);
			if (empty($license)) throw new \StepFailedException('Not loaded License try again');
			return $license;
		}
	}
}

namespace Tasks
{
	class RequestFrontEnd extends AbstractTask
	{
		const REQUIRED_MINIMAL_MEMORY_LIMIT = "64M";

		public function __invoke()
		{
			\Installer::getInstance()->writeLog("Requesting FrontEnd via cURL...");

			$rebuildInterfacesCachePartial = $this->needToRebuildInterfacesCachePartial();
			if ($rebuildInterfacesCachePartial)
			{
				\Installer::getInstance()->writeLog("PHP memory_limit is lower than the required. The system will try to rebuild interfaces cache partially.", "warning");
			}

			$url = \Installer::getInstance()->getBaseUrl() . '/?SYSCOMMAND=SETUP_ENVIRONMENT&rebuildInterfacesCachePartial=' . (int) $rebuildInterfacesCachePartial;
			$serverResponse = $this->curlRequest($url);
			if ($serverResponse['status'] != 204)
			{
				$log = "cUrl request to {$url} failed! " . \Installer::getInstance()->getReplacementVar('product') . "'s response was as follows: <br />";
				if (!empty($serverResponse['response']))
				{
					$log .= $serverResponse['response'];
				}
				else
				{
					$log .= 'UNKNOWN ERROR (server left a blank response)';
				}
				\Installer::getInstance()->writeLog($log, "error");
				throw new \InstallationFailedException($this->data['failureMessage']);
			}

			\Installer::getInstance()->writeLog("cURL request success...", "success");
		}

		private function needToRebuildInterfacesCachePartial()
		{
			$memoryLimit = ini_get("memory_limit");
			if (!empty($memoryLimit) &&
					$this->convertToBytes($memoryLimit) < $this->convertToBytes(self::REQUIRED_MINIMAL_MEMORY_LIMIT))
			{
				return true;
			}
			return false;
		}

		private function convertToBytes($value)
		{
		    $value = trim($value);
		    $last = strtolower($value[strlen($value)-1]);
		    switch($last)
		    {
		        case 'g':
		            $value *= 1024;
		        case 'm':
		            $value *= 1024;
		        case 'k':
		            $value *= 1024;
		    }
			return $value;
		}

		private function curlRequest($url)
		{
			$curl = curl_init($url);
			curl_setopt($curl, CURLOPT_HEADER, 0);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_TIMEOUT, 60);
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
			if (isset($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']))
			{
				curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
				curl_setopt($curl, CURLOPT_USERPWD, "{$_SERVER['PHP_AUTH_USER']}:{$_SERVER['PHP_AUTH_PW']}");
			}
			$response = curl_exec($curl);
			$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			curl_close($curl);
			return array('status' => $status, 'response' => $response);
		}
	}
}

namespace Tasks {

class RequestUserInput extends AbstractTask
{
	public function __invoke()
	{
		$forms = \Installer::getInstance()->getFormsData();
		$formData = $forms[$this->data['formName']];
		$action = \Installer::getInstance()->getRequestValue('action');

		if ($action == "Next")
		{

			$data = array();
			foreach ($formData['fields'] as $name => $field)
			{
				$data[$name] = \Installer::getInstance()->getRequestValue($name);
			}

			\Installer::getInstance()->setData("lastFormData", $data);

			if (isset($formData['validator']))
			{
				$validatorClassName = "\\Validators\\" . $formData['validator'];
				$validator = new $validatorClassName();
				$validator->validate($data);
			}

			\Installer::getInstance()->setDataForForm($this->data['formName'], $data);
			unset($_REQUEST['action']);
		}
		elseif ($action == "Skip")
		{
			throw new \SkipActionException();
		}
		else
		{
			$data = \Installer::getInstance()->getData("lastFormData");
			foreach ($formData['fields'] as $name => &$field)
			{
				if (isset($data[$name]))
				{
					$field['value'] = $data[$name];
				}
				elseif (strpos($field['default'], 'php_function:') === 0)
				{
					$function = substr($field['default'], 13);
					$result = $function();
					$field['value'] = $result;
				}
				else
				{
					$field['value'] = $field['default'];
				}
			}
			$this->displayForm($formData);
			throw new \WaitForUserInputException();
		}
	}

	private function displayForm($formData)
	{
		$template = new \TemplateProcessor\Template();
		$template->caption = $formData['caption'];
		$template->messages = \Installer::getInstance()->getMessages();
		$template->fields = $formData['fields'];
		$template->skipable = $this->data['skipable'];
		$templateName = !empty($formData['templateFileName']) ? "files/" . $formData['templateFileName'] : "files/default_form.tpl";
		$template->display($templateName);

	}
}
}

namespace Tasks
{
	class SetCharacterSet extends AbstractTask
	{
		/**
		 * @var \PDO
		 */
		private $dbConnection;

		public function __invoke()
		{
			if (isset($this->data['message']))
			{
				\Installer::getInstance()->writeMessage($this->data['message'], 'info', 'description');
			}
			$data = \Installer::getInstance()->getDataForForm($this->data['dataSet']);
			$charset = $this->data['charset'];

			try
			{
				$dbh = new \PDO("mysql:dbname={$data['dbName']};host={$data['dbHost']};charset=utf8", $data['dbUser'], $data['dbPassword']);
			}
			catch (\PDOException $e)
			{
				throw new \InstallationFailedException("Connection failed: " . $e->getMessage());
			}

			$this->dbConnection = $dbh;

			\Installer::getInstance()->writeLog("Setting character set to \"{$charset}\"..." );

			if ($this->sqlVersionAbove41())
			{
				if ($this->getCurrentCharacterSet() != $charset)
				{
					if ($this->isCharsetAvailable($charset))
					{
						if (!$this->setCharacterSet($data['dbName'], $charset))
						{
							\Installer::getInstance()->writeLog("Cannot set charset.", "warning");
						}
					}
					else
					{
						\Installer::getInstance()->writeLog("\"{$charset}\" charset is unavailable. Cannot set charset.", "warning");
					}
				}
			}
			else
			{
				\Installer::getInstance()->writeLog("SQL version is below 4.1. Cannot set charset.", "warning");
			}
		}

		private function sqlVersionAbove41()
		{
			$versionNumber = $this->dbConnection->query("SHOW variables LIKE 'version'", \PDO::FETCH_COLUMN, 1)->fetch();
			$parts = preg_split('[/.-]', $versionNumber);
			return !($parts[0] < 4 || $parts[0] == 4 && $parts[1] < 1);
		}

		private function isCharsetAvailable($charset)
		{
			$sth = $this->dbConnection->prepare("SHOW CHARACTER SET LIKE ?");
			$sth->execute(array($charset));
			return $sth->fetch() !== false;
		}
		private function getCurrentCharacterSet()
		{
			return $this->dbConnection->query("SHOW variables LIKE 'character_set_database'", \PDO::FETCH_COLUMN, 1)->fetch();
		}

		private function setCharacterSet($dbname, $charset)
		{
			return $this->dbConnection->exec("ALTER DATABASE `{$dbname}` CHARACTER SET '{$charset}'") !== false;
		}
	}
}

namespace Tasks
{
	class UpgradeModules extends AbstractTask
	{
		public function __invoke()
		{
			$adminData = \Installer::getInstance()->getDataForForm($this->data['dataSet']);

			\Installer::getInstance()->writeLog("Upgrading modules started...");

			$curl = \Installer::getInstance()->getCurlSession("upgradeModules");

			$adminPanelMainPageUrl = \Installer::getInstance()->getAdminPanelUrl() . "/";

			\Installer::getInstance()->writeLog("Logging in to the admin panel...");
			$curl->request(
				$adminPanelMainPageUrl,
				array(
					'action' => 'login',
					'admin_username' => $adminData['adminUsername'],
					'admin_password' => $adminData['adminPassword']
				),
				'POST'
			);

			\Installer::getInstance()->writeLog("Requesting for the upgrade all modules action...");
			$curl->request(
				$adminPanelMainPageUrl . 'system/module_manager/manage_modules/',
				array(
					'action' => 'upgradeAll'
				)
			);

			\Installer::getInstance()->writeLog("All modules are successfully upgraded.", "success");
		}
	}
}


namespace Tasks
{
	class UploadLicense extends \Tasks\AbstractTask
	{
		public function __invoke()
		{
			if (isset($this->data['FtpCredentialsMessage']))
			{
				$message = \Installer::getInstance()->replaceMessageVarsToValues($this->data['FtpCredentialsMessage']);
				\Installer::getInstance()->writeMessage($message, 'info', 'description');
			}

			if (file_exists('license'))
				throw new \SkipActionException();

            $mode = $this->data['mode'];
			$ftpData = \Installer::getInstance()->getDataForForm($this->data['FTPDataSet']);
            
			if (isset($this->data['LicenseUploadMessage']))
			{
				$message = \Installer::getInstance()->replaceMessageVarsToValues($this->data['LicenseUploadMessage']);
				\Installer::getInstance()->writeMessage($message, 'info', 'description');
			}
			\Installer::getInstance()->getDataForForm($this->data['dataSet']);
			\Installer::getInstance()->writeLog("Upload License step:");
            
			if (isset($_FILES['licenseFile']))
			{
				if ($_FILES['licenseFile']['error'] !== UPLOAD_ERR_OK)
				{
					throw new UploadException($_FILES['licenseFile']['error']);
				}
				else
				{
					$ftpResource = @ftp_connect($ftpData['ftpHost'], $ftpData['ftpPort']);
					@ftp_login($ftpResource, $ftpData['ftpUser'], $ftpData['ftpPassword']);
					$ftpDirectory = $ftpData['ftpDirectory'];
					if (!empty($ftpDirectory) && $ftpDirectory{strlen($ftpDirectory) - 1} != '/')
                    {
                        $ftpDirectory .= '/';
                    }
					if ($ftpDirectory{0} != '/')
                    {
                        $ftpDirectory = './' . $ftpDirectory;
                    }
					if (@ftp_put($ftpResource, $ftpDirectory . 'license', $_FILES['licenseFile']['tmp_name'], FTP_BINARY) === false)
					{
						throw new \StepFailedException("License Upload Failed");
					}
                    if (@ftp_site($ftpResource, "CHMOD $mode " . $ftpDirectory . 'license') === false)
                    {
                        \Installer::getInstance()->writeLog("Cannot change mode of license", "error");
                    }
				}
			}

			\Installer::getInstance()->writeLog("License successfully uploaded", "success");
		}

	}

	class UploadException extends \StepFailedException
	{
		public function __construct($code) {
			$message = $this->codeToMessage($code);
			parent::__construct($message);
		}

		private function codeToMessage($code)
		{
			switch ($code) {
				case UPLOAD_ERR_INI_SIZE:
					$message = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
					break;
				case UPLOAD_ERR_FORM_SIZE:
					$message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
					break;
				case UPLOAD_ERR_PARTIAL:
					$message = "The uploaded file was only partially uploaded";
					break;
				case UPLOAD_ERR_NO_FILE:
					$message = "No file was uploaded";
					break;
				case UPLOAD_ERR_NO_TMP_DIR:
					$message = "Missing a temporary folder";
					break;
				case UPLOAD_ERR_CANT_WRITE:
					$message = "Failed to write file to disk";
					break;
				case UPLOAD_ERR_EXTENSION:
					$message = "File upload stopped by extension";
					break;

				default:
					$message = "Unknown upload error";
					break;
			}
			return $message;
		}
	}

}


namespace Tasks
{
	class WriteHtaccess extends AbstractTask
	{
		public function __invoke()
		{
			$path = $this->data['pathToHtaccess'];

			$pathToHtaccess = "." . $path . ".htaccess";
			$rewriteBasePath = '';
			$urlParams = parse_url(\Installer::getInstance()->getBaseUrl());
			if (isset ($urlParams['path'])) $rewriteBasePath = trim($urlParams['path']);

			\Installer::getInstance()->writeLog("Writing .htaccess file in \"{$pathToHtaccess}\"...");

			if (!$strings = @file($pathToHtaccess))
			{
				$message = "Cannot find .htaccess file. Please check file existing";
				\Installer::getInstance()->writeLog($message, "error");
				throw new \InstallationFailedException($message);
			}

			if (!$htaccess = @fopen($pathToHtaccess, 'w'))
			{
				$message = "Cannot open .htaccess file to write.";
				\Installer::getInstance()->writeLog($message, "error");
				throw new \InstallationFailedException($message);
			}

			foreach ($strings as $index => $cstr)
			{
				if (strpos($strings[$index], "RewriteBase") !== false)
				{
					$strings[$index] = "RewriteBase " . $rewriteBasePath . $path . "\r\n";
				}
				fputs ($htaccess, $strings[$index]);
			}

			fclose ($htaccess);
			\Installer::getInstance()->writeLog("Written .htaccess file successfully...", "success");
		}
	}
}

namespace TemplateProcessor {

class File
{
	private $filename;

	public function __construct($filename)
	{
		global $FILES;
		$this->filename = $filename;
		$this->allFiles = &$FILES;
	}

	public function getContentType()
	{
		$extension = pathinfo($this->filename, PATHINFO_EXTENSION);
		return isset(self::$typeMap[$extension]) ? self::$typeMap[$extension] : self::defaultType;
	}

	public function flushContent()
	{
		if (isset($this->allFiles[$this->filename]))
		{
			echo base64_decode($this->allFiles[$this->filename]);
		}
		else
		{
			$fh = fopen($this->filename, 'r');
			fpassthru($fh);
			fclose($fh);
		}
	}

	public function getContent()
	{
		if (isset($this->allFiles[$this->filename])) return base64_decode($this->allFiles[$this->filename]);
		return file_get_contents($this->filename);
	}

	const defaultType = "text/plain";
	private static $typeMap = array(
									'css' => 'text/plain',
									'gif' => 'image/gif',
									'png' => 'image/png',
									'jpg' => 'image/jpeg',
									);
}
}

namespace TemplateProcessor {

class Template
{
	private $variables = array();
	private $callbacks = array();
	private $templateName = null;
	private $_TemplateSupplier;

	public function __construct()
	{
		$this->_TemplateSupplier = new TemplateSupplier();
	}

	public function registerCallback($name, $callback)
	{
		$this->callbacks[$name] = $callback;
	}

	public function __set($name, $value)
	{
		$this->variables[$name] = $value;
	}

	public function __get($name)
	{
		if (isset($this->variables[$name]))
		{
			return $this->variables[$name];
		}
		else
		{
			throw new \Exception("Reference to an unknown variable \"$name\" in $this->templateName");
		}
	}

	public function __call($name, $args)
	{
		if (isset($this->callbacks[$name]))
		{
			return call_user_func_array($this->callbacks[$name],$args);
		}
		else
		{
			throw new \Exception("Call to an unknown callback \"$name()\" in $this->templateName");
		}
	}

	public function self($vars=array())
	{
		$template = new Template($this->templateName);
		foreach($vars as $name => $value)
		{
			$template->$name = $value;
		}
        return $template->getText();
	}

	function display($template_name)
	{
		$this->templateName = $template_name;
		$templateContents = $this->_TemplateSupplier->getTemplateContents($template_name);
		extract($this->variables);
		eval(' ?>' . $templateContents . ' ');
	}
	function fetch($template_name)
	{
		ob_start();
		$this->display($template_name);
		$contents = ob_get_contents();
		ob_end_clean();
		return $contents;
	}
}
}

namespace TemplateProcessor {

class TemplateSupplier
{
	public function getTemplateContents($filename)
	{
		$file = new File($filename);
		return $file->getContent();
	}
}
}

namespace Validators
{
	class AdminCredentials
	{
		public function validate($data)
		{
			if (empty($data['adminUsername'])) throw new \StepFailedException("Admin username is not specified.");
			if (empty($data['adminPassword'])) throw new \StepFailedException("Admin password is not specified.");
			if (empty($data['systemEmail'])) throw new \StepFailedException("System Email is not specified.");
		}
	}
}

namespace Validators
{
	class CanAdminLoginValidator
	{
		public function validate($data)
		{
			$installer = \Installer::getInstance();

			$adminPanelUrl = $installer->getAdminPanelUrl() . "/";
			$curl = $installer->getCurlSession("CanAdminLoginValidator");

			\Installer::getInstance()->writeLog("Checking admin credentials...");

			$result = $curl->request(
				$adminPanelUrl,
				array(
					'action' => 'login',
					'admin_username' => $data['adminUsername'],
					'admin_password' => $data['adminPassword']
				),
				'POST'
			);

			if ($result == 401)
			{
				\Installer::getInstance()->writeLog("Could not login to the admin panel. Login failed with \"401 Unauthorized\" status code.", "error");
				throw new \StepFailedException("Authorization failed");
			}

			\Installer::getInstance()->writeLog("Admin credentials are ok.", "success");
		}
	}
}


namespace Validators
{
	class DBCredentials
	{
		public function validate($data)
		{
			extract($data);

			if (empty($dbHost)) throw new \StepFailedException("Database host is not specified.");
			if (empty($dbUser)) throw new \StepFailedException("Database user is not specified.");
			if (empty($dbPassword)) throw new \StepFailedException("Database password is not specified.");
			if (empty($dbName)) throw new \StepFailedException("Database name is not specified.");

			\Installer::getInstance()->writeLog("Trying to connect to '{$dbHost}' server... (username: '{$dbUser}', password: '{$dbPassword}')");

			try
			{
				$dbh = new \PDO("mysql:dbname={$data['dbName']};host={$data['dbHost']};charset=utf8", $data['dbUser'], $data['dbPassword']);
			}
			catch (\PDOException $e)
			{
				$message = "Cannot connect to MySQL DB: " . $e->getMessage();
				\Installer::getInstance()->writeLog($message, "error");
				throw new \StepFailedException($message);
			}

			\Installer::getInstance()->writeLog("Connected to '{$dbHost}' successfully...", "success");
			\Installer::getInstance()->writeLog("Selected the database successfully...", "success");
		}
	}
}

namespace Validators
{
	class FTPCredentials
	{
		public function validate($data)
		{
			extract($data);

			if (empty($ftpHost)) throw new \StepFailedException("FTP host is not specified.");
			if (empty($ftpPort)) throw new \StepFailedException("FTP port is not specified.");
			if (empty($ftpDirectory)) throw new \StepFailedException("FTP directory is not specified.");
			if (empty($ftpUser)) throw new \StepFailedException("FTP user is not specified.");
			if (empty($ftpPassword)) throw new \StepFailedException("FTP password is not specified.");

			\Installer::getInstance()->writeLog("Connecting to FTP-Server \"{$ftpHost}:$ftpPort\"...");

			if (!function_exists ("ftp_connect"))
			{
				$message = "Your server does not support PHP's FTP-functions!";
				\Installer::getInstance()->writeLog($message, "error");
				throw new \StepFailedException($message);
			}

			if (!$ftpResource = @ftp_connect($ftpHost, $ftpPort, 10))
			{
				$message = "Cannot connect to FTP-Server!";
				\Installer::getInstance()->writeLog($message, "error");
				throw new \StepFailedException($message);
			}
			\Installer::getInstance()->writeLog("Connected to FTP-Server successfully", "success");

			\Installer::getInstance()->writeLog("FTP-Authorization... ({$ftpUser}:{$ftpPassword}@{$ftpHost}:{$ftpPort})");
			if ($logged = @ftp_login($ftpResource, $ftpUser, $ftpPassword) === false)
			{
				$message = "FTP-Authorization failed!";
				\Installer::getInstance()->writeLog($message, "error");
				throw new \StepFailedException($message);
			}
			\Installer::getInstance()->writeLog("FTP-Authorization success", "success");

			if (!empty($ftpDirectory) && $ftpDirectory{strlen($ftpDirectory) - 1} != '/') $ftpDirectory .= '/';
			if ($ftpDirectory{0} != '/') $ftpDirectory = './' . $ftpDirectory;

			\Installer::getInstance()->writeLog("Checking FTP directory... ({$ftpDirectory})");
			if (@ftp_chdir($ftpResource, $ftpDirectory) === false)
			{
				$message = "Specified directory doesn't exist!";
				\Installer::getInstance()->writeLog($message, "error");
				throw new \StepFailedException($message);
			}

			$fileList = @ftp_nlist($ftpResource, ".");
			if (!in_array("packageinfo.txt", $fileList))
			{
				$message = "Wrong product directory specified. The directory does not contain a file 'packageinfo.txt'.";
				\Installer::getInstance()->writeLog($message, "error");
				throw new \StepFailedException($message);
			}

			ftp_close($ftpResource);
		}
	}
}

namespace Validators
{
	class LicenseExistAndValid
	{
		public function validate($data)
		{
			$this->curl = curl_init();
			curl_setopt($this->curl, CURLOPT_HEADER, 0);
			curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($this->curl, CURLOPT_TIMEOUT, 60);
			curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($this->curl, CURLOPT_COOKIEJAR, tempnam("", "licenseExistAndValid"));
			if (isset($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']))
			{
				curl_setopt($this->curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
				curl_setopt($this->curl, CURLOPT_USERPWD, "{$_SERVER['PHP_AUTH_USER']}:{$_SERVER['PHP_AUTH_PW']}");
			}

			$adminPanelUrl = \Installer::getInstance()->getBaseUrl() . "/admin/";

			$result = $this->request($adminPanelUrl);
			if ($result == 403) 
			{
				throw new \StepFailedException("License validation failed.");
			}
		}
		
		public function request($url, $requestVars = array(), $method = 'GET')
		{
			$queryString = http_build_query($requestVars, '', '&');

			switch ($method)
			{
				case 'GET' :
					curl_setopt($this->curl, CURLOPT_HTTPGET, true);
					$url .= (strpos($url, '?')) ? '&' : '?';
					$url .= $queryString;
				break;
				case 'POST' :
					curl_setopt($this->curl, CURLOPT_POST, true);
					curl_setopt($this->curl, CURLOPT_POSTFIELDS, $queryString);
				break;
				default :
					throw new \Exception('Unkown method: '. $method);
			}

			curl_setopt($this->curl, CURLOPT_URL, $url);
			curl_exec($this->curl);

			$status = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
			
			return $status;
		}
	}
}

namespace Validators {
	class LicenseVerificationCode
	{
		public function validate($data)
		{
			extract($data);

			if (empty($code)) throw new \StepFailedException("Verification Code is not specified.");

			$post_string = http_build_query(array(
				'action' => 'verificationCodeValid',
				'site_url' => \Installer::getInstance()->getBaseUrl(),
				'code' => $code,
			));
			$parts = parse_url(\Installer::getInstance()->getConfig('licenseWorksforweb') . 'system/licenses/installation_license/');

			@$fp = fsockopen($parts['host'],
				isset($parts['port']) ? $parts['port'] : 80,
				$errNo, $errStr, 30);

			if (false === $fp) throw new \StepFailedException("Can not connect to remote server", "error");

			$out = "POST " . $parts['path'] . " HTTP/1.1\n";
			$out .= "Host: " . $parts['host'] . "\n";
			$out .= "Content-Type: application/x-www-form-urlencoded\n";
			$out .= "Content-Length: " . strlen($post_string) . "\n";
			$out .= "Connection: Close\n\n";
			if (!empty($post_string)) {
				$out .= $post_string;
			}
			fwrite($fp, $out);

			$isBody = false;
			$message = '';
			while (!feof($fp)) {
				$buf = fgets($fp, 1024);

				if ($isBody) {
					$message .= $buf;
				}

				if ($buf == "\r\n") {
					$isBody = true;
				}
			}
			fclose($fp);
			if (!empty($message)) throw new \StepFailedException($message);
		}
	}
}

namespace Validators
{
	class MFAUrl
	{
		public function validate($data)
		{
			if (empty($data['url'])) throw new \StepFailedException("Mobile FrontEnd Url is not specified.");
			if (filter_var($data['url'], FILTER_VALIDATE_URL) === FALSE) throw new \StepFailedException("Mobile FrontEnd Url is invalid.");
		}
	}
}

namespace Validators
{
	class UserData
	{
		public function validate($data)
		{
			extract($data);

			if (empty($first_name)) throw new \StepFailedException("First Name is not specified.");
			if (empty($last_name)) throw new \StepFailedException("Last Name is not specified.");
			if (empty($email)) throw new \StepFailedException("The email field is empty. Please type in your email address.");
			if (! filter_var($email, FILTER_VALIDATE_EMAIL)) throw new \StepFailedException("Please specify a valid email address.");
			if (empty($terms_agree)) throw new \StepFailedException("You must agree to the terms of the license agreement in order to get a free license.");

		}
	}
}namespace { $FILES = array (
  'files/default_button_set.tpl' => 'PGZvcm0gbWV0aG9kPSJwb3N0Ij4KCTxpbnB1dCB0eXBlPSJoaWRkZW4iIG5hbWU9InJlc3RvcmUiIHZhbHVlPSIiIC8+Cgk8P3BocCBpZiAoaXNzZXQoJGFjdGlvbk5leHQpKSA6ID8+CgkJPGRpdiBjbGFzcz0icm93Ij4KCQkJPGRpdiBjbGFzcz0iY29sLXhzLW9mZnNldC00IGNvbC14cy00IHRleHQtY2VudGVyIj4KCQkJCTxpbnB1dCB0eXBlPSJzdWJtaXQiIHZhbHVlPSJOZXh0IiBuYW1lPSJhY3Rpb25OZXh0IiBjbGFzcz0iYnRuIGJ0bi1wcmltYXJ5IG5leHQiIC8+CgkJCTwvZGl2PgoJCTwvZGl2PgoJPD9waHAgZW5kaWY7ID8+CjwvZm9ybT4K',
  'files/default_confirmation.tpl' => 'PGgxPjw/cGhwIGVjaG8gJGNhcHRpb247ID8+PC9oMT4KPGRpdiBjbGFzcz0ibWVzc2FnZXMiPgoJPGRpdiBjbGFzcz0ibWVzc2FnZSI+CgkJPD9waHAgZWNobyAkbWVzc2FnZTsgPz4KCTwvZGl2PgoJCgk8P3BocCBpZiAoIWVtcHR5KCRmYWlsZWREaXJzKSkgOiA/PgoJPGRpdiBjbGFzcz0iYWxlcnQgYWxlcnQtZGFuZ2VyIiByb2xlPSJhbGVydCI+CgkJPHVsPgoJCQk8P3BocCBmb3JlYWNoKCRmYWlsZWREaXJzIGFzICRmYWlsZWREaXIpOiA/PgoJCQkJPGxpPi88P3BocCBlY2hvICRmYWlsZWREaXI7ID8+PC9saT4KCQkJPD9waHAgZW5kZm9yZWFjaDsgPz4KCQk8L3VsPgoJPC9kaXY+Cgk8P3BocCBlbmRpZjsgPz4KCgk8P3BocCBpZiAoIWVtcHR5KCRmYWlsZWRGaWxlcykpIDogPz4KCTxkaXYgY2xhc3M9ImFsZXJ0IGFsZXJ0LWRhbmdlciIgcm9sZT0iYWxlcnQiPgoJCTx1bD4KCQkJPD9waHAgZm9yZWFjaCgkZmFpbGVkRmlsZXMgYXMgJGZhaWxlZEZpbGUpOiA/PgoJCQkJPGxpPi88P3BocCBlY2hvICRmYWlsZWRGaWxlOyA/PjwvbGk+CgkJCTw/cGhwIGVuZGZvcmVhY2g7ID8+CgkJPC91bD4KCTwvZGl2PgoJPD9waHAgZW5kaWY7ID8+CjwvZGl2PgoKPGZvcm0gbWV0aG9kPSJwb3N0Ij4KCTxpbnB1dCB0eXBlPSJoaWRkZW4iIG5hbWU9InJlc3RvcmUiIHZhbHVlPSIiIC8+CgoJPGRpdiBjbGFzcz0icm93Ij4KCQk8ZGl2IGNsYXNzPSJjb2wteHMtb2Zmc2V0LTQgY29sLXhzLTQgdGV4dC1jZW50ZXIiPgoJCQk8aW5wdXQgdHlwZT0ic3VibWl0IiB2YWx1ZT0iUmV0cnkiIG5hbWU9ImFjdGlvblJldHJ5IiBjbGFzcz0iYnRuIGJ0bi1wcmltYXJ5IHJldHJ5IiAvPgoJCTwvZGl2PgoKCQk8P3BocCBpZiAoJHNraXBhYmxlKSA6ID8+CgkJCTxkaXYgY2xhc3M9ImNvbC14cy00IHRleHQtcmlnaHQiPgoJCQkJPGlucHV0IHR5cGU9InN1Ym1pdCIgdmFsdWU9IlNraXAiIGNsYXNzPSJidG4gYnRuLWxpbmsgc2tpcCIgbmFtZT0iYWN0aW9uTmV4dCIgLz4KCQkJPC9kaXY+CgkJPD9waHAgZW5kaWY7ID8+Cgk8L2Rpdj4KPC9mb3JtPgo=',
  'files/default_form.tpl' => 'PGgxPjw/cGhwIGVjaG8gJGNhcHRpb247ID8+PC9oMT4KCjw/cGhwIGZvcmVhY2ggKCRtZXNzYWdlcyBhcyAkbWVzc2FnZSkgeyA/PgoJPD9waHAgaWYgKCRtZXNzYWdlWyd0eXBlJ10gPT0gJ2Vycm9yJykgJG1lc3NhZ2VbJ3R5cGUnXSA9ICdkYW5nZXInIDs/PgoKCTxkaXYgY2xhc3M9ImFsZXJ0IGFsZXJ0LTw/cGhwIGVjaG8gJG1lc3NhZ2VbJ3R5cGUnXTsgPz4iIHJvbGU9ImFsZXJ0Ij4KCQk8P3BocCBlY2hvICRtZXNzYWdlWydtZXNzYWdlJ107ID8+Cgk8L2Rpdj4KPD9waHAgfTsgPz4KCjxmb3JtIG1ldGhvZD0icG9zdCI+Cgk8aW5wdXQgdHlwZT0iaGlkZGVuIiBuYW1lPSJyZXN0b3JlIiB2YWx1ZT0iIiAvPgoKCTw/cGhwIGZvcmVhY2ggKCRmaWVsZHMgYXMgJG5hbWUgPT4gJGZpZWxkKSB7ID8+CgkJPD9waHAgaWYgKCRmaWVsZFsndHlwZSddID09ICJyYWRpbyIpIHsgPz4KCQkJPD9waHAgZm9yZWFjaCAoJGZpZWxkWyd2YWx1ZXMnXSBhcyAkdmFsdWUgPT4gJHZhbHVlRGF0YSkgeyA/PgoJCQkJPGRpdiBjbGFzcz0iZm9ybS1ncm91cCI+CgkJCQkJPGRpdiBjbGFzcz0iY29sLXNtLW9mZnNldC0yIGNvbC1zbS0xMCI+CgkJCQkJCTxkaXYgY2xhc3M9InJhZGlvIj4KCQkJCQkJICA8bGFiZWw+CgkJCQkJCQkgIDxpbnB1dCB0eXBlPSJyYWRpbyIgbmFtZT0iPD9waHAgZWNobyAkbmFtZTsgPz4iIHZhbHVlPSI8P3BocCBlY2hvICR2YWx1ZTsgPz4iIDw/cGhwIGlmICgkZmllbGRbJ2RlZmF1bHQnXSA9PSAkdmFsdWUpIGVjaG8gJ2NoZWNrZWQ9ImNoZWNrZWQiJzsgPz4gLz4KCQkJCQkJCSAgPD9waHAgZWNobyAkdmFsdWVEYXRhWydjYXB0aW9uJ107ID8+CgkJCQkJCSAgPC9sYWJlbD4KCQkJCQkJPC9kaXY+CgkJCQkJPC9kaXY+CgkJCQk8L2Rpdj4KCQkJPD9waHAgfSA/PgoJCTw/cGhwIH0gZWxzZSB7ID8+CgkJCTxkaXYgY2xhc3M9ImZvcm0tZ3JvdXAiPgoJCQkJPGxhYmVsIGZvcj0iPD9waHAgZWNobyAkbmFtZTsgPz4iPjw/cGhwIGVjaG8gJGZpZWxkWydjYXB0aW9uJ107ID8+OjwvbGFiZWw+CgoJCQkJCTxpbnB1dCB0eXBlPSI8P3BocCBlY2hvICRmaWVsZFsndHlwZSddOyA/PiIgY2xhc3M9ImZvcm0tY29udHJvbCIgbmFtZT0iPD9waHAgZWNobyAkbmFtZTsgPz4iIGlkPSI8P3BocCBlY2hvICRuYW1lOyA/PiIgdmFsdWU9Ijw/cGhwIGVjaG8gJGZpZWxkWyd2YWx1ZSddOyA/PiIvPgoKCQkJPC9kaXY+CgkJPD9waHAgfSA/PgoJPD9waHAgfTsgPz4KCgk8ZGl2IGNsYXNzPSJyb3ciPgoJCTxkaXYgY2xhc3M9ImNvbC14cy1vZmZzZXQtNCBjb2wteHMtNCB0ZXh0LWNlbnRlciI+CgkJCTxpbnB1dCB0eXBlPSJzdWJtaXQiIHZhbHVlPSJOZXh0IiBuYW1lPSJhY3Rpb24iIGNsYXNzPSJidG4gYnRuLXByaW1hcnkgbmV4dCIgLz4KCQk8L2Rpdj4KCgkJPD9waHAgaWYgKCRza2lwYWJsZSkgeyA/PgoJCQk8ZGl2IGNsYXNzPSJjb2wteHMtNCB0ZXh0LXJpZ2h0Ij4KCQkJCTxpbnB1dCB0eXBlPSJzdWJtaXQiIHZhbHVlPSJTa2lwIiBuYW1lPSJhY3Rpb24iIGNsYXNzPSJidG4gYnRuLWxpbmsgc2tpcCIgLz4KCQkJPC9kaXY+CgkJPD9waHAgfSA/PgoJPC9kaXY+CjwvZm9ybT4K',
  'files/default_index.tpl' => 'PGh0bWwgbGFuZz0iZW4iPgo8aGVhZD4KCTxtZXRhIGNoYXJzZXQ9InV0Zi04Ij4KCTxtZXRhIGh0dHAtZXF1aXY9IlgtVUEtQ29tcGF0aWJsZSIgY29udGVudD0iSUU9ZWRnZSI+Cgk8bWV0YSBuYW1lPSJ2aWV3cG9ydCIgY29udGVudD0id2lkdGg9ZGV2aWNlLXdpZHRoLCBpbml0aWFsLXNjYWxlPTEiPgoJPCEtLSBUaGUgYWJvdmUgMyBtZXRhIHRhZ3MgKm11c3QqIGNvbWUgZmlyc3QgaW4gdGhlIGhlYWQ7IGFueSBvdGhlciBoZWFkIGNvbnRlbnQgbXVzdCBjb21lICphZnRlciogdGhlc2UgdGFncyAtLT4KCTx0aXRsZT5Xb3Jrc0ZvcldlYiBQcm9kdWN0IEluc3RhbGxlcjwvdGl0bGU+CgoJPCEtLSBCb290c3RyYXAgLS0+Cgk8bGluayBocmVmPSJodHRwczovL21heGNkbi5ib290c3RyYXBjZG4uY29tL2Jvb3RzdHJhcC8zLjMuNC9jc3MvYm9vdHN0cmFwLm1pbi5jc3MiIHJlbD0ic3R5bGVzaGVldCI+Cgk8bGluayBocmVmPSI/YWN0aW9uPWZpbGUmZmlsZT1maWxlcy9kZXNpZ24uY3NzIiByZWw9InN0eWxlc2hlZXQiIHR5cGU9InRleHQvY3NzIj4KCjwvaGVhZD4KPGJvZHkgb25sb2FkPSJkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnbG9nJykuc2Nyb2xsVG9wID0gOTk5OTk5Ij4KCjxkaXYgY2xhc3M9ImNvbnRhaW5lci1mbHVpZCI+Cgk8ZGl2IGNsYXNzPSJyb3cgaGVhZGVyIj4KCQk8ZGl2IGNsYXNzPSJjb2wtc20tNCBsb2dvIj4KCQkJPGltZyBzcmM9Ij9hY3Rpb249ZmlsZSZmaWxlPWZpbGVzL2xvZ28ucG5nIj4mbmJzcDsKCQk8L2Rpdj4KCQk8ZGl2IGNsYXNzPSJjb2wtc20tOCBpbnN0YWxsYXRpb24tcHJvZ3Jlc3MiPgoJCQk8ZGl2IGNsYXNzPSJyb3ciPgoJCQkJPGRpdiBjbGFzcz0iY29sLXNtLTgiPgoJCQkJCTxzcGFuIGNsYXNzPSJwcm9kdWN0Ij4KCQkJCQkJPD9waHAgZWNobyAkcHJvZHVjdF9uYW1lOz8+CgkJCQkJCTw/cGhwIGVjaG8gJHByb2R1Y3RfdmVyc2lvbjs/PgoJCQkJCTwvc3Bhbj4KCQkJCQlJbnN0YWxsYXRpb24gUHJvZ3Jlc3MKCQkJCTwvZGl2PgoJCQkJPGRpdiBjbGFzcz0iY29sLXNtLTQgdGV4dC1yaWdodCI+CgkJCQkJPHNwYW4gY2xhc3M9ImN1cnJlbnQtc3RlcCI+CgkJCQkJCTw/cGhwIGVjaG8gJENVUlJFTlRfU1RFUDsgPz4gLyA8P3BocCBlY2hvICRBbGxfU1RFUFM7ID8+CgkJCQkJPC9zcGFuPgoJCQkJPC9kaXY+CgkJCTwvZGl2PgoJCTwvZGl2PgoJPC9kaXY+Cgk8ZGl2IGNsYXNzPSJyb3cgbWFpbiI+CgkJPGRpdiBjbGFzcz0iY29sLXNtLTQgc3RlcHMiPgoJCQk8b2w+CgkJCQk8P3BocCBmb3JlYWNoICgkQUNUSU9OUyBhcyAkYWN0aW9uID0+ICRzdGF0dXMpIHsgPz4KCQkJCTxsaSBjbGFzcz0iPD9waHAgZWNobyAkc3RhdHVzOyA/PiI+CgoJCQkJCTxkaXYgY2xhc3M9InB1bGwtcmlnaHQiPgoJCQkJCQk8P3BocCBpZiAoJHN0YXR1cyA9PSAnc2tpcHBlZCcpOiA/PgoJCQkJCQk8c3BhbiBjbGFzcz0iZ2x5cGhpY29uIGdseXBoaWNvbi13YXJuaW5nLXNpZ24iIGFyaWEtaGlkZGVuPSJ0cnVlIiBzdHlsZT0iY29sb3I6ICM4YTZkM2IiPjwvc3Bhbj4KCQkJCQkJPD9waHAgZWxzZWlmICgkc3RhdHVzID09ICdjb21wbGV0ZWQnKTogPz4KCQkJCQkJPHNwYW4gY2xhc3M9ImdseXBoaWNvbiBnbHlwaGljb24tb2siIGFyaWEtaGlkZGVuPSJ0cnVlIiBzdHlsZT0iY29sb3I6ICMzYzc2M2QiPjwvc3Bhbj4KCQkJCQkJPD9waHAgZW5kaWYgPz4KCgkJCQkJPC9kaXY+CgoJCQkJCTw/cGhwIGVjaG8gJGFjdGlvbjsgPz4KCQkJCTwvbGk+CgkJCQk8P3BocCB9OyA/PgoJCQk8L29sPgoJCTwvZGl2PgoJCTxkaXYgY2xhc3M9ImNvbC1zbS04IGNvbnRlbnQiPgoJCQk8P3BocCBlY2hvICRNQUlOX0NPTlRFTlQ7Pz4KCQk8L2Rpdj4KCTwvZGl2PgoJPGRpdiBjbGFzcz0icm93IGxvZyI+CgkJPGRpdiBjbGFzcz0iY29sLXNtLTEyIHdpbmRvdyBwcmUtc2Nyb2xsYWJsZSIgaWQ9ImxvZyI+CgkJCTw/cGhwIGVjaG8gJExPRzs/PgoJCTwvZGl2PgoJPC9kaXY+CjwvZGl2PgoKPGRpdiBjbGFzcz0iZmFkZXIiPjxpbWcgc3JjPSI/YWN0aW9uPWZpbGUmZmlsZT1maWxlcy9zcGlubmVyLmdpZiIvPjwvZGl2PgoKPCEtLSBqUXVlcnkgKG5lY2Vzc2FyeSBmb3IgQm9vdHN0cmFwJ3MgSmF2YVNjcmlwdCBwbHVnaW5zKSAtLT4KPHNjcmlwdCBzcmM9Imh0dHBzOi8vYWpheC5nb29nbGVhcGlzLmNvbS9hamF4L2xpYnMvanF1ZXJ5LzEuMTEuMi9qcXVlcnkubWluLmpzIj48L3NjcmlwdD4KPHNjcmlwdCB0eXBlPSJ0ZXh0L2phdmFzY3JpcHQiPgoJJChkb2N1bWVudCkucmVhZHkoZnVuY3Rpb24gKCkgewoJCSQoImZvcm0iKS5zdWJtaXQoZnVuY3Rpb24gKCkgewoJCQkkKCJkaXYuZmFkZXIiKS5mYWRlSW4oNTAwLCBmdW5jdGlvbiAoKXskKHRoaXMpLmNzcygnZmlsdGVyJywgJ2FscGhhKG9wYWNpdHkgPSA4MCknKTt9KTsKCQl9KTsKCX0pOwo8L3NjcmlwdD4KPC9ib2R5Pgo8L2h0bWw+',
  'files/default_messages.tpl' => 'PGgxPjw/cGhwIGVjaG8gJGNhcHRpb247ID8+PC9oMT4KPGRpdiBjbGFzcz0ibWVzc2FnZXMiPgoJPD9waHAgZm9yZWFjaCAoJG1lc3NhZ2VzIGFzICRtZXNzYWdlKSB7ID8+Cgk8ZGl2IGNsYXNzPSI8P3BocCBlY2hvICRtZXNzYWdlWyd0eXBlJ107ID8+Ij48P3BocCBlY2hvICRtZXNzYWdlWydtZXNzYWdlJ107ID8+PC9kaXY+Cgk8P3BocCB9OyA/Pgo8L2Rpdj4K',
  'files/design.css' => 'LmhlYWRlciA+IGRpdiB7CgloZWlnaHQ6IDgwcHg7CglsaW5lLWhlaWdodDogODBweDsKfQoKLmhlYWRlciA+IGRpdiBpbWcgewoJdmVydGljYWwtYWxpZ246IG1pZGRsZTsKfQoKLmxvZ28gewoJYmFja2dyb3VuZC1jb2xvcjogIzQ5NTE1OTsKCXBhZGRpbmctbGVmdDogNTBweDsKfQoKLnN0ZXBzIHsKCWJhY2tncm91bmQtY29sb3I6ICMzNTNBM0Y7CglwYWRkaW5nOiAyMHB4IDIwcHggMjBweCA1MHB4Owp9CgouaW5zdGFsbGF0aW9uLXByb2dyZXNzLAouY29udGVudCB7CglwYWRkaW5nLWxlZnQ6IDQwcHg7CglwYWRkaW5nLXJpZ2h0OiA1MHB4Owp9CgouY29udGVudCBoMSB7CgltYXJnaW4tdG9wOiAwOwp9CgoubWFpbiA+IGRpdiB7CglwYWRkaW5nLXRvcDogNTBweDsKfQoKLmxvZyB7CglwYWRkaW5nOiAyMHB4IDUwcHg7Cglmb250LWZhbWlseTogbW9ub3NwYWNlOwoJYmFja2dyb3VuZC1jb2xvcjogI2YzZjNmMzsKfQoKLmxvZyAud2luZG93IHsKCWJvcmRlcjogMXB4IHNvbGlkICNlNmU2ZTY7CgliYWNrZ3JvdW5kLWNvbG9yOiAjZmZmZmZmOwp9Cgouc3RlcHMgb2wgewoJY291bnRlci1yZXNldDogaXRlbTsKCXBhZGRpbmc6IDA7Cn0KCi5zdGVwcyBsaSB7CglkaXNwbGF5OiBibG9jazsKCXBhZGRpbmc6IDVweCAwOwp9Cgouc3RlcHMgbGk6YmVmb3JlIHsKCWNvbnRlbnQ6IGNvdW50ZXIoaXRlbSkgIi4gIjsKCWNvdW50ZXItaW5jcmVtZW50OiBpdGVtOwoJd2lkdGg6IDJlbTsKCWRpc3BsYXk6IGlubGluZS1ibG9jazsKfQoKLnN0ZXBzIC5pbmNvbXBsZXRlIHsKCWNvbG9yOiAjZmZmZmZmOwp9Cgouc3RlcHMgLmNvbXBsZXRlZCB7Cgljb2xvcjogIzgwOEM5ODsKfQoKLnN0ZXBzIC5za2lwcGVkIHsKCWNvbG9yOiAjODA4Qzk4Owp9Cgouc3RlcHMgLmN1cnJlbnQgewoJY29sb3I6ICNmZmE2Mzk7Cglmb250LXdlaWdodDogYm9sZDsKfQoKLmluc3RhbGxhdGlvbi1wcm9ncmVzcyB7CgliYWNrZ3JvdW5kLWNvbG9yOiAjZWRlZGVkOwoJZm9udC1zaXplOiAyN3B4OwoJYm9yZGVyLWJvdHRvbTogMnB4IHNvbGlkICM3NjdjODI7Cn0KCi5pbnN0YWxsYXRpb24tcHJvZ3Jlc3MgLnByb2R1Y3QgewoJY29sb3I6ICMzMzdhYjc7Cn0KCi5pbnN0YWxsYXRpb24tcHJvZ3Jlc3MgLmN1cnJlbnQtc3RlcCB7Cgljb2xvcjogIzMzN2FiNzsKfQoKLnJlcXVpcmVtZW50cyAuaXRlbSB7CgltYXJnaW46IDEwcHggMDsKCXBhZGRpbmc6IDEwcHggMjBweDsKCWJvcmRlci1yYWRpdXM6IDVweDsKfQoKLnJlcXVpcmVtZW50cyAuaXRlbS5zdWNjZXNzIHsKCWJhY2tncm91bmQtY29sb3I6IHJnYmEoMTQwLCAxOTgsIDYzLCAwLjE1KTsKfQoKLnJlcXVpcmVtZW50cyAuaXRlbS5lcnJvciB7CgliYWNrZ3JvdW5kLWNvbG9yOiByZ2JhKDI1NSwgMjksIDM3LCAwLjEpOwp9CgoucmVxdWlyZW1lbnRzIC5pdGVtLndhcm5pbmcgewoJYmFja2dyb3VuZC1jb2xvcjogcmdiYSgyNTUsIDE2NSwgNTYsIDAuMTUpOwp9CgoucmVxdWlyZW1lbnRzIC5pdGVtIHsKCWZvbnQtZmFtaWx5OiAnT3BlbiBTYW5zJywgc2Fucy1zZXJpZjsKCWZvbnQtc2l6ZTogMTZweDsKCWNvbG9yOiAjNGI1MjU5ICFpbXBvcnRhbnQ7Cn0KCi5yZXF1aXJlbWVudHMgLml0ZW0gLmhlYWRpbmcgewoJZm9udC13ZWlnaHQ6IGJvbGQ7Cn0KCi5yZXF1aXJlbWVudHMgLml0ZW0gLm1lc3NhZ2UgewoJbWFyZ2luLXRvcDogMTBweDsKfQoKLmxvZyAuc3VjY2VzcyB7Cgp9CgpkaXYuZmFkZXIgewoJZGlzcGxheTogbm9uZTsKCXBvc2l0aW9uOiBmaXhlZDsKCWxlZnQ6IDA7Cgl0b3A6IDA7Cgl3aWR0aDogMTAwJTsKCWhlaWdodDogMTAwJTsKCWJhY2tncm91bmQ6ICMzMzM7CglvcGFjaXR5OiAwLjg7CglmaWx0ZXI6IGFscGhhKG9wYWNpdHk9ODApOwoJdGV4dC1hbGlnbjogY2VudGVyOwoJei1pbmRleDogMTsKfQoKKiBodG1sIGRpdi5mYWRlciB7Cglwb3NpdGlvbjogYWJzb2x1dGU7Cn0KCmRpdi5mYWRlciBpbWcgewoJcG9zaXRpb246IGFic29sdXRlOwoJdG9wOiA0NSU7Cn0KCiogaHRtbCBkaXYuZmFkZXIgaW1nIHsKCXRvcDogMzUlOwp9CgouYnRuIHsKCXRleHQtdHJhbnNmb3JtOiB1cHBlcmNhc2U7Cglmb250LXdlaWdodDogYm9sZDsKfQoKLmJ0bi5za2lwIHsKCWNvbG9yOiAjZmZhNjM5Owp9Cgpmb3JtIGxhYmVsIHsKCWNvbG9yOiAjODE4ZDk5OwoJdGV4dC10cmFuc2Zvcm06IHVwcGVyY2FzZTsKCWZvbnQtc2l6ZTogMTJweDsKfQo=',
  'files/file_upload_form.tpl' => 'PGgxPjw/cGhwIGVjaG8gJGNhcHRpb247ID8+PC9oMT4KPGRpdiBjbGFzcz0ibWVzc2FnZXMiPgoJPD9waHAgZm9yZWFjaCAoJG1lc3NhZ2VzIGFzICRtZXNzYWdlKSB7ID8+Cgk8ZGl2IGNsYXNzPSI8P3BocCBlY2hvICRtZXNzYWdlWyd0eXBlJ107ID8+Ij48P3BocCBlY2hvICRtZXNzYWdlWydtZXNzYWdlJ107ID8+PC9kaXY+Cgk8P3BocCB9OyA/Pgo8L2Rpdj4KPGRpdiBjbGFzcz0iaW5wdXRGb3JtIj4KCTxmb3JtIG5hbWU9IiIgbWV0aG9kPSJwb3N0IiBlbmN0eXBlPSJtdWx0aXBhcnQvZm9ybS1kYXRhIj4KCQk8P3BocCBmb3JlYWNoICgkZmllbGRzIGFzICRuYW1lID0+ICRmaWVsZCkgewoJCQlpZiAoJGZpZWxkWyd0eXBlJ10gPT0gInJhZGlvIikgeyA/PgoJCQkJPD9waHAgZm9yZWFjaCAoJGZpZWxkWyd2YWx1ZXMnXSBhcyAkdmFsdWUgPT4gJHZhbHVlRGF0YSkgeyA/PgoJCQkJPGRpdiBjbGFzcz0iZm9ybUZpZWxkIiBzdHlsZT0idGV4dC1hbGlnbjogbGVmdDsiPgoJCQkJCTxpbnB1dCB0eXBlPSJyYWRpbyIgbmFtZT0iPD9waHAgZWNobyAkbmFtZTsgPz4iIHZhbHVlPSI8P3BocCBlY2hvICR2YWx1ZTsgPz4iIDw/cGhwIGlmICgkZmllbGRbJ2RlZmF1bHQnXSA9PSAkdmFsdWUpIGVjaG8gJ2NoZWNrZWQ9ImNoZWNrZWQiJzsgPz4gLz4KCQkJCQk8P3BocCBlY2hvICR2YWx1ZURhdGFbJ2NhcHRpb24nXTsgPz4KCQkJCTwvZGl2PgoJCQkJPD9waHAgfSA/PgoJCQk8P3BocCB9IGVsc2UgeyA/PgoJCQkJPGRpdiBjbGFzcz0iZm9ybUZpZWxkIj4KCQkJCQk8bGFiZWwgZm9yPSI8P3BocCBlY2hvICRuYW1lOyA/PiI+PD9waHAgZWNobyAkZmllbGRbJ2NhcHRpb24nXTsgPz46PC9sYWJlbD4KCQkJCQk8aW5wdXQgdHlwZT0iPD9waHAgZWNobyAkZmllbGRbJ3R5cGUnXTsgPz4iIG5hbWU9Ijw/cGhwIGVjaG8gJG5hbWU7ID8+IiBpZD0iPD9waHAgZWNobyAkbmFtZTsgPz4iIHZhbHVlPSI8P3BocCBlY2hvICRmaWVsZFsndmFsdWUnXTsgPz4iLz4KCQkJCTwvZGl2PgoJCQk8P3BocCB9ID8+CgkJPD9waHAgfTsgPz4KCQk8ZGl2IGNsYXNzPSJmb3JtQ29udHJvbHMiPgoJCQk8aW5wdXQgdHlwZT0iaGlkZGVuIiBuYW1lPSJyZXN0b3JlIiB2YWx1ZT0iIiAvPgoJCQk8aW5wdXQgdHlwZT0ic3VibWl0IiB2YWx1ZT0iTmV4dCIgbmFtZT0iYWN0aW9uIiAvPgoJCQk8P3BocCBpZiAoJHNraXBhYmxlKSB7ID8+PGlucHV0IHR5cGU9InN1Ym1pdCIgdmFsdWU9IlNraXAiIG5hbWU9ImFjdGlvbiIgLz48P3BocCB9OyA/PgoJCTwvZGl2PgoJPC9mb3JtPgo8L2Rpdj4=',
  'files/get_license_form.tpl' => 'PGgxPjw/cGhwIGVjaG8gJGNhcHRpb247ID8+PC9oMT4NCg0KPD9waHAgZm9yZWFjaCAoJG1lc3NhZ2VzIGFzICRtZXNzYWdlKSA6ICA/Pg0KCTw/cGhwICRhbGVydENzcyA9ICRtZXNzYWdlWyd0eXBlJ10gPT0gJ2Vycm9yJyA/ICdkYW5nZXInIDogJG1lc3NhZ2VbJ3R5cGUnXTsgID8+DQoJPGRpdiBjbGFzcz0iYWxlcnQgYWxlcnQtPD9waHAgZWNobyAkYWxlcnRDc3M7ID8+IiByb2xlPSJhbGVydCI+PD9waHAgZWNobyAkbWVzc2FnZVsnbWVzc2FnZSddOyA/PjwvZGl2Pg0KPD9waHAgZW5kZm9yZWFjaDsgPz4NCg0KPGRpdiBjbGFzcz0id2VsbCI+DQoJPGZvcm0gbWV0aG9kPSJwb3N0IiBjbGFzcz0iZm9ybS1ob3Jpem9udGFsIj4NCgkJPGlucHV0IHR5cGU9ImhpZGRlbiIgbmFtZT0icmVzdG9yZSIgdmFsdWU9IiIgLz4NCg0KCQk8P3BocCBmb3JlYWNoICgkZmllbGRzIGFzICRuYW1lID0+ICRmaWVsZCkgew0KCQkJaWYgKCRmaWVsZFsndHlwZSddID09ICJyYWRpbyIpIHsgPz4NCgkJCQk8P3BocCBmb3JlYWNoICgkZmllbGRbJ3ZhbHVlcyddIGFzICR2YWx1ZSA9PiAkdmFsdWVEYXRhKSB7ID8+DQoJCQkJCTxkaXYgY2xhc3M9ImZvcm1GaWVsZCIgc3R5bGU9InRleHQtYWxpZ246IGxlZnQ7Ij4NCgkJCQkJCTxpbnB1dCB0eXBlPSJyYWRpbyIgbmFtZT0iPD9waHAgZWNobyAkbmFtZTsgPz4iIHZhbHVlPSI8P3BocCBlY2hvICR2YWx1ZTsgPz4iIDw/cGhwIGlmICgkZmllbGRbJ2RlZmF1bHQnXSA9PSAkdmFsdWUpIGVjaG8gJ2NoZWNrZWQ9ImNoZWNrZWQiJzsgPz4gLz4NCgkJCQkJCTw/cGhwIGVjaG8gJHZhbHVlRGF0YVsnY2FwdGlvbiddOyA/Pg0KCQkJCQk8L2Rpdj4NCgkJCQk8P3BocCB9ID8+DQoJCQk8P3BocCB9IGVsc2VpZigkZmllbGRbJ3R5cGUnXSAhPSAiY2hlY2tib3giKSB7ID8+DQoJCQkJPGRpdiBjbGFzcz0iZm9ybS1ncm91cCI+DQoJCQkJCTxsYWJlbCBmb3I9Ijw/cGhwIGVjaG8gJG5hbWU7ID8+IiBjbGFzcz0iY29sLXNtLTIgY29udHJvbC1sYWJlbCI+DQoJCQkJCQk8P3BocCBlY2hvICRmaWVsZFsnY2FwdGlvbiddOyA/Pg0KCQkJCQk8L2xhYmVsPg0KCQkJCQk8ZGl2IGNsYXNzPSJjb2wtc20tMTAiPg0KCQkJCQkJPGlucHV0IHR5cGU9Ijw/cGhwIGVjaG8gJGZpZWxkWyd0eXBlJ107ID8+IiBuYW1lPSI8P3BocCBlY2hvICRuYW1lOyA/PiIgaWQ9Ijw/cGhwIGVjaG8gJG5hbWU7ID8+IiB2YWx1ZT0iPD9waHAgZWNobyAkZmllbGRbJ3ZhbHVlJ107ID8+IiBjbGFzcz0iZm9ybS1jb250cm9sIi8+DQoJCQkJCTwvZGl2Pg0KCQkJCTwvZGl2Pg0KCQkJPD9waHAgfSA/Pg0KCQk8P3BocCB9OyA/Pg0KDQoNCgkJPD9waHAgZm9yZWFjaCAoJGZpZWxkcyBhcyAkbmFtZSA9PiAkZmllbGQpIHsNCgkJCWlmICgkZmllbGRbJ3R5cGUnXSA9PSAiY2hlY2tib3giKSB7ID8+DQoJCQkJPGRpdiBjbGFzcz0iZm9ybS1ncm91cCI+DQoJCQkJCTxkaXYgY2xhc3M9ImNvbC1zbS1vZmZzZXQtMiBjb2wtc20tMTAiPg0KCQkJCQkJPGRpdiBjbGFzcz0iY2hlY2tib3giPg0KCQkJCQkJCTxsYWJlbD4NCgkJCQkJCQkJPGlucHV0IHR5cGU9ImhpZGRlbiIgbmFtZT0iPD9waHAgZWNobyAkbmFtZTsgPz4iIHZhbHVlPSIwIj4NCgkJCQkJCQkJPGlucHV0IHR5cGU9ImNoZWNrYm94IiBuYW1lPSI8P3BocCBlY2hvICRuYW1lOyA/PiIgdmFsdWU9IjEiPiA8P3BocCBlY2hvICRmaWVsZFsnY2FwdGlvbiddOyA/Pg0KCQkJCQkJCQk8P3BocCBpZiAoJG5hbWUgPT0gJ3Rlcm1zX2FncmVlJykgeyA/Pg0KCQkJCQkJCQkJPGEgaHJlZj0iaHR0cDovL3d3dy53b3Jrc2ZvcndlYi5jb20vc3VwcG9ydC9ldWxhLyIgdGFyZ2V0PSJfYmxhbmsiPkxpY2Vuc2UgQWdyZWVtZW50PC9hPi4NCgkJCQkJCQkJPD9waHAgfSA/Pg0KCQkJCQkJCTwvbGFiZWw+DQoJCQkJCQk8L2Rpdj4NCgkJCQkJPC9kaXY+DQoJCQkJPC9kaXY+DQoJCQk8P3BocCB9ID8+DQoJCTw/cGhwIH07ID8+DQoNCgkJPGRpdiBjbGFzcz0iZm9ybS1ncm91cCI+DQoJCQk8ZGl2IGNsYXNzPSJjb2wtc20tb2Zmc2V0LTIgY29sLXNtLTEwIj4NCgkJCQk8aW5wdXQgdHlwZT0ic3VibWl0IiB2YWx1ZT0iTmV4dCIgY2xhc3M9ImJ0biBidG4tcHJpbWFyeSIgbmFtZT0iYWN0aW9uIiAvPg0KCQkJCTw/cGhwIGlmICgkc2tpcGFibGUpIHsgPz48aW5wdXQgdHlwZT0ic3VibWl0IiB2YWx1ZT0iU2tpcCIgbmFtZT0iYWN0aW9uIiBjbGFzcz0iYnRuIGJ0bi1wcmltYXJ5IiAvPjw/cGhwIH07ID8+DQoJCQk8L2Rpdj4NCgkJPC9kaXY+DQoJPC9mb3JtPg0KPC9kaXY+DQo=',
  'files/jquery.js' => 'LyohCiAqIGpRdWVyeSBKYXZhU2NyaXB0IExpYnJhcnkgdjEuNS4yCiAqIGh0dHA6Ly9qcXVlcnkuY29tLwogKgogKiBDb3B5cmlnaHQgMjAxMSwgSm9obiBSZXNpZwogKiBEdWFsIGxpY2Vuc2VkIHVuZGVyIHRoZSBNSVQgb3IgR1BMIFZlcnNpb24gMiBsaWNlbnNlcy4KICogaHR0cDovL2pxdWVyeS5vcmcvbGljZW5zZQogKgogKiBJbmNsdWRlcyBTaXp6bGUuanMKICogaHR0cDovL3NpenpsZWpzLmNvbS8KICogQ29weXJpZ2h0IDIwMTEsIFRoZSBEb2pvIEZvdW5kYXRpb24KICogUmVsZWFzZWQgdW5kZXIgdGhlIE1JVCwgQlNELCBhbmQgR1BMIExpY2Vuc2VzLgogKgogKiBEYXRlOiBUaHUgTWFyIDMxIDE1OjI4OjIzIDIwMTEgLTA0MDAKICovCihmdW5jdGlvbihhLGIpe2Z1bmN0aW9uIGNpKGEpe3JldHVybiBkLmlzV2luZG93KGEpP2E6YS5ub2RlVHlwZT09PTk/YS5kZWZhdWx0Vmlld3x8YS5wYXJlbnRXaW5kb3c6ITF9ZnVuY3Rpb24gY2YoYSl7aWYoIWJfW2FdKXt2YXIgYj1kKCI8IithKyI+IikuYXBwZW5kVG8oImJvZHkiKSxjPWIuY3NzKCJkaXNwbGF5Iik7Yi5yZW1vdmUoKTtpZihjPT09Im5vbmUifHxjPT09IiIpYz0iYmxvY2siO2JfW2FdPWN9cmV0dXJuIGJfW2FdfWZ1bmN0aW9uIGNlKGEsYil7dmFyIGM9e307ZC5lYWNoKGNkLmNvbmNhdC5hcHBseShbXSxjZC5zbGljZSgwLGIpKSxmdW5jdGlvbigpe2NbdGhpc109YX0pO3JldHVybiBjfWZ1bmN0aW9uIGIkKCl7dHJ5e3JldHVybiBuZXcgYS5BY3RpdmVYT2JqZWN0KCJNaWNyb3NvZnQuWE1MSFRUUCIpfWNhdGNoKGIpe319ZnVuY3Rpb24gYlooKXt0cnl7cmV0dXJuIG5ldyBhLlhNTEh0dHBSZXF1ZXN0fWNhdGNoKGIpe319ZnVuY3Rpb24gYlkoKXtkKGEpLnVubG9hZChmdW5jdGlvbigpe2Zvcih2YXIgYSBpbiBiVyliV1thXSgwLDEpfSl9ZnVuY3Rpb24gYlMoYSxjKXthLmRhdGFGaWx0ZXImJihjPWEuZGF0YUZpbHRlcihjLGEuZGF0YVR5cGUpKTt2YXIgZT1hLmRhdGFUeXBlcyxmPXt9LGcsaCxpPWUubGVuZ3RoLGosaz1lWzBdLGwsbSxuLG8scDtmb3IoZz0xO2c8aTtnKyspe2lmKGc9PT0xKWZvcihoIGluIGEuY29udmVydGVycyl0eXBlb2YgaD09PSJzdHJpbmciJiYoZltoLnRvTG93ZXJDYXNlKCldPWEuY29udmVydGVyc1toXSk7bD1rLGs9ZVtnXTtpZihrPT09IioiKWs9bDtlbHNlIGlmKGwhPT0iKiImJmwhPT1rKXttPWwrIiAiK2ssbj1mW21dfHxmWyIqICIra107aWYoIW4pe3A9Yjtmb3IobyBpbiBmKXtqPW8uc3BsaXQoIiAiKTtpZihqWzBdPT09bHx8alswXT09PSIqIil7cD1mW2pbMV0rIiAiK2tdO2lmKHApe289ZltvXSxvPT09ITA/bj1wOnA9PT0hMCYmKG49byk7YnJlYWt9fX19IW4mJiFwJiZkLmVycm9yKCJObyBjb252ZXJzaW9uIGZyb20gIittLnJlcGxhY2UoIiAiLCIgdG8gIikpLG4hPT0hMCYmKGM9bj9uKGMpOnAobyhjKSkpfX1yZXR1cm4gY31mdW5jdGlvbiBiUihhLGMsZCl7dmFyIGU9YS5jb250ZW50cyxmPWEuZGF0YVR5cGVzLGc9YS5yZXNwb25zZUZpZWxkcyxoLGksaixrO2ZvcihpIGluIGcpaSBpbiBkJiYoY1tnW2ldXT1kW2ldKTt3aGlsZShmWzBdPT09IioiKWYuc2hpZnQoKSxoPT09YiYmKGg9YS5taW1lVHlwZXx8Yy5nZXRSZXNwb25zZUhlYWRlcigiY29udGVudC10eXBlIikpO2lmKGgpZm9yKGkgaW4gZSlpZihlW2ldJiZlW2ldLnRlc3QoaCkpe2YudW5zaGlmdChpKTticmVha31pZihmWzBdaW4gZClqPWZbMF07ZWxzZXtmb3IoaSBpbiBkKXtpZighZlswXXx8YS5jb252ZXJ0ZXJzW2krIiAiK2ZbMF1dKXtqPWk7YnJlYWt9a3x8KGs9aSl9aj1qfHxrfWlmKGope2ohPT1mWzBdJiZmLnVuc2hpZnQoaik7cmV0dXJuIGRbal19fWZ1bmN0aW9uIGJRKGEsYixjLGUpe2lmKGQuaXNBcnJheShiKSYmYi5sZW5ndGgpZC5lYWNoKGIsZnVuY3Rpb24oYixmKXtjfHxicy50ZXN0KGEpP2UoYSxmKTpiUShhKyJbIisodHlwZW9mIGY9PT0ib2JqZWN0Inx8ZC5pc0FycmF5KGYpP2I6IiIpKyJdIixmLGMsZSl9KTtlbHNlIGlmKGN8fGI9PW51bGx8fHR5cGVvZiBiIT09Im9iamVjdCIpZShhLGIpO2Vsc2UgaWYoZC5pc0FycmF5KGIpfHxkLmlzRW1wdHlPYmplY3QoYikpZShhLCIiKTtlbHNlIGZvcih2YXIgZiBpbiBiKWJRKGErIlsiK2YrIl0iLGJbZl0sYyxlKX1mdW5jdGlvbiBiUChhLGMsZCxlLGYsZyl7Zj1mfHxjLmRhdGFUeXBlc1swXSxnPWd8fHt9LGdbZl09ITA7dmFyIGg9YVtmXSxpPTAsaj1oP2gubGVuZ3RoOjAsaz1hPT09YkosbDtmb3IoO2k8aiYmKGt8fCFsKTtpKyspbD1oW2ldKGMsZCxlKSx0eXBlb2YgbD09PSJzdHJpbmciJiYoIWt8fGdbbF0/bD1iOihjLmRhdGFUeXBlcy51bnNoaWZ0KGwpLGw9YlAoYSxjLGQsZSxsLGcpKSk7KGt8fCFsKSYmIWdbIioiXSYmKGw9YlAoYSxjLGQsZSwiKiIsZykpO3JldHVybiBsfWZ1bmN0aW9uIGJPKGEpe3JldHVybiBmdW5jdGlvbihiLGMpe3R5cGVvZiBiIT09InN0cmluZyImJihjPWIsYj0iKiIpO2lmKGQuaXNGdW5jdGlvbihjKSl7dmFyIGU9Yi50b0xvd2VyQ2FzZSgpLnNwbGl0KGJEKSxmPTAsZz1lLmxlbmd0aCxoLGksajtmb3IoO2Y8ZztmKyspaD1lW2ZdLGo9L15cKy8udGVzdChoKSxqJiYoaD1oLnN1YnN0cigxKXx8IioiKSxpPWFbaF09YVtoXXx8W10saVtqPyJ1bnNoaWZ0IjoicHVzaCJdKGMpfX19ZnVuY3Rpb24gYnEoYSxiLGMpe3ZhciBlPWI9PT0id2lkdGgiP2JrOmJsLGY9Yj09PSJ3aWR0aCI/YS5vZmZzZXRXaWR0aDphLm9mZnNldEhlaWdodDtpZihjPT09ImJvcmRlciIpcmV0dXJuIGY7ZC5lYWNoKGUsZnVuY3Rpb24oKXtjfHwoZi09cGFyc2VGbG9hdChkLmNzcyhhLCJwYWRkaW5nIit0aGlzKSl8fDApLGM9PT0ibWFyZ2luIj9mKz1wYXJzZUZsb2F0KGQuY3NzKGEsIm1hcmdpbiIrdGhpcykpfHwwOmYtPXBhcnNlRmxvYXQoZC5jc3MoYSwiYm9yZGVyIit0aGlzKyJXaWR0aCIpKXx8MH0pO3JldHVybiBmfWZ1bmN0aW9uIGJjKGEsYil7Yi5zcmM/ZC5hamF4KHt1cmw6Yi5zcmMsYXN5bmM6ITEsZGF0YVR5cGU6InNjcmlwdCJ9KTpkLmdsb2JhbEV2YWwoYi50ZXh0fHxiLnRleHRDb250ZW50fHxiLmlubmVySFRNTHx8IiIpLGIucGFyZW50Tm9kZSYmYi5wYXJlbnROb2RlLnJlbW92ZUNoaWxkKGIpfWZ1bmN0aW9uIGJiKGEpe3JldHVybiJnZXRFbGVtZW50c0J5VGFnTmFtZSJpbiBhP2EuZ2V0RWxlbWVudHNCeVRhZ05hbWUoIioiKToicXVlcnlTZWxlY3RvckFsbCJpbiBhP2EucXVlcnlTZWxlY3RvckFsbCgiKiIpOltdfWZ1bmN0aW9uIGJhKGEsYil7aWYoYi5ub2RlVHlwZT09PTEpe3ZhciBjPWIubm9kZU5hbWUudG9Mb3dlckNhc2UoKTtiLmNsZWFyQXR0cmlidXRlcygpLGIubWVyZ2VBdHRyaWJ1dGVzKGEpO2lmKGM9PT0ib2JqZWN0IiliLm91dGVySFRNTD1hLm91dGVySFRNTDtlbHNlIGlmKGMhPT0iaW5wdXQifHxhLnR5cGUhPT0iY2hlY2tib3giJiZhLnR5cGUhPT0icmFkaW8iKXtpZihjPT09Im9wdGlvbiIpYi5zZWxlY3RlZD1hLmRlZmF1bHRTZWxlY3RlZDtlbHNlIGlmKGM9PT0iaW5wdXQifHxjPT09InRleHRhcmVhIiliLmRlZmF1bHRWYWx1ZT1hLmRlZmF1bHRWYWx1ZX1lbHNlIGEuY2hlY2tlZCYmKGIuZGVmYXVsdENoZWNrZWQ9Yi5jaGVja2VkPWEuY2hlY2tlZCksYi52YWx1ZSE9PWEudmFsdWUmJihiLnZhbHVlPWEudmFsdWUpO2IucmVtb3ZlQXR0cmlidXRlKGQuZXhwYW5kbyl9fWZ1bmN0aW9uIF8oYSxiKXtpZihiLm5vZGVUeXBlPT09MSYmZC5oYXNEYXRhKGEpKXt2YXIgYz1kLmV4cGFuZG8sZT1kLmRhdGEoYSksZj1kLmRhdGEoYixlKTtpZihlPWVbY10pe3ZhciBnPWUuZXZlbnRzO2Y9ZltjXT1kLmV4dGVuZCh7fSxlKTtpZihnKXtkZWxldGUgZi5oYW5kbGUsZi5ldmVudHM9e307Zm9yKHZhciBoIGluIGcpZm9yKHZhciBpPTAsaj1nW2hdLmxlbmd0aDtpPGo7aSsrKWQuZXZlbnQuYWRkKGIsaCsoZ1toXVtpXS5uYW1lc3BhY2U/Ii4iOiIiKStnW2hdW2ldLm5hbWVzcGFjZSxnW2hdW2ldLGdbaF1baV0uZGF0YSl9fX19ZnVuY3Rpb24gJChhLGIpe3JldHVybiBkLm5vZGVOYW1lKGEsInRhYmxlIik/YS5nZXRFbGVtZW50c0J5VGFnTmFtZSgidGJvZHkiKVswXXx8YS5hcHBlbmRDaGlsZChhLm93bmVyRG9jdW1lbnQuY3JlYXRlRWxlbWVudCgidGJvZHkiKSk6YX1mdW5jdGlvbiBRKGEsYixjKXtpZihkLmlzRnVuY3Rpb24oYikpcmV0dXJuIGQuZ3JlcChhLGZ1bmN0aW9uKGEsZCl7dmFyIGU9ISFiLmNhbGwoYSxkLGEpO3JldHVybiBlPT09Y30pO2lmKGIubm9kZVR5cGUpcmV0dXJuIGQuZ3JlcChhLGZ1bmN0aW9uKGEsZCl7cmV0dXJuIGE9PT1iPT09Y30pO2lmKHR5cGVvZiBiPT09InN0cmluZyIpe3ZhciBlPWQuZ3JlcChhLGZ1bmN0aW9uKGEpe3JldHVybiBhLm5vZGVUeXBlPT09MX0pO2lmKEwudGVzdChiKSlyZXR1cm4gZC5maWx0ZXIoYixlLCFjKTtiPWQuZmlsdGVyKGIsZSl9cmV0dXJuIGQuZ3JlcChhLGZ1bmN0aW9uKGEsZSl7cmV0dXJuIGQuaW5BcnJheShhLGIpPj0wPT09Y30pfWZ1bmN0aW9uIFAoYSl7cmV0dXJuIWF8fCFhLnBhcmVudE5vZGV8fGEucGFyZW50Tm9kZS5ub2RlVHlwZT09PTExfWZ1bmN0aW9uIEgoYSxiKXtyZXR1cm4oYSYmYSE9PSIqIj9hKyIuIjoiIikrYi5yZXBsYWNlKHQsImAiKS5yZXBsYWNlKHUsIiYiKX1mdW5jdGlvbiBHKGEpe3ZhciBiLGMsZSxmLGcsaCxpLGosayxsLG0sbixvLHA9W10scT1bXSxzPWQuX2RhdGEodGhpcywiZXZlbnRzIik7aWYoYS5saXZlRmlyZWQhPT10aGlzJiZzJiZzLmxpdmUmJiFhLnRhcmdldC5kaXNhYmxlZCYmKCFhLmJ1dHRvbnx8YS50eXBlIT09ImNsaWNrIikpe2EubmFtZXNwYWNlJiYobj1uZXcgUmVnRXhwKCIoXnxcXC4pIithLm5hbWVzcGFjZS5zcGxpdCgiLiIpLmpvaW4oIlxcLig/Oi4qXFwuKT8iKSsiKFxcLnwkKSIpKSxhLmxpdmVGaXJlZD10aGlzO3ZhciB0PXMubGl2ZS5zbGljZSgwKTtmb3IoaT0wO2k8dC5sZW5ndGg7aSsrKWc9dFtpXSxnLm9yaWdUeXBlLnJlcGxhY2UociwiIik9PT1hLnR5cGU/cS5wdXNoKGcuc2VsZWN0b3IpOnQuc3BsaWNlKGktLSwxKTtmPWQoYS50YXJnZXQpLmNsb3Nlc3QocSxhLmN1cnJlbnRUYXJnZXQpO2ZvcihqPTAsaz1mLmxlbmd0aDtqPGs7aisrKXttPWZbal07Zm9yKGk9MDtpPHQubGVuZ3RoO2krKyl7Zz10W2ldO2lmKG0uc2VsZWN0b3I9PT1nLnNlbGVjdG9yJiYoIW58fG4udGVzdChnLm5hbWVzcGFjZSkpJiYhbS5lbGVtLmRpc2FibGVkKXtoPW0uZWxlbSxlPW51bGw7aWYoZy5wcmVUeXBlPT09Im1vdXNlZW50ZXIifHxnLnByZVR5cGU9PT0ibW91c2VsZWF2ZSIpYS50eXBlPWcucHJlVHlwZSxlPWQoYS5yZWxhdGVkVGFyZ2V0KS5jbG9zZXN0KGcuc2VsZWN0b3IpWzBdOyghZXx8ZSE9PWgpJiZwLnB1c2goe2VsZW06aCxoYW5kbGVPYmo6ZyxsZXZlbDptLmxldmVsfSl9fX1mb3Ioaj0wLGs9cC5sZW5ndGg7ajxrO2orKyl7Zj1wW2pdO2lmKGMmJmYubGV2ZWw+YylicmVhazthLmN1cnJlbnRUYXJnZXQ9Zi5lbGVtLGEuZGF0YT1mLmhhbmRsZU9iai5kYXRhLGEuaGFuZGxlT2JqPWYuaGFuZGxlT2JqLG89Zi5oYW5kbGVPYmoub3JpZ0hhbmRsZXIuYXBwbHkoZi5lbGVtLGFyZ3VtZW50cyk7aWYobz09PSExfHxhLmlzUHJvcGFnYXRpb25TdG9wcGVkKCkpe2M9Zi5sZXZlbCxvPT09ITEmJihiPSExKTtpZihhLmlzSW1tZWRpYXRlUHJvcGFnYXRpb25TdG9wcGVkKCkpYnJlYWt9fXJldHVybiBifX1mdW5jdGlvbiBFKGEsYyxlKXt2YXIgZj1kLmV4dGVuZCh7fSxlWzBdKTtmLnR5cGU9YSxmLm9yaWdpbmFsRXZlbnQ9e30sZi5saXZlRmlyZWQ9YixkLmV2ZW50LmhhbmRsZS5jYWxsKGMsZiksZi5pc0RlZmF1bHRQcmV2ZW50ZWQoKSYmZVswXS5wcmV2ZW50RGVmYXVsdCgpfWZ1bmN0aW9uIHkoKXtyZXR1cm4hMH1mdW5jdGlvbiB4KCl7cmV0dXJuITF9ZnVuY3Rpb24gaShhKXtmb3IodmFyIGIgaW4gYSlpZihiIT09InRvSlNPTiIpcmV0dXJuITE7cmV0dXJuITB9ZnVuY3Rpb24gaChhLGMsZSl7aWYoZT09PWImJmEubm9kZVR5cGU9PT0xKXtlPWEuZ2V0QXR0cmlidXRlKCJkYXRhLSIrYyk7aWYodHlwZW9mIGU9PT0ic3RyaW5nIil7dHJ5e2U9ZT09PSJ0cnVlIj8hMDplPT09ImZhbHNlIj8hMTplPT09Im51bGwiP251bGw6ZC5pc05hTihlKT9nLnRlc3QoZSk/ZC5wYXJzZUpTT04oZSk6ZTpwYXJzZUZsb2F0KGUpfWNhdGNoKGYpe31kLmRhdGEoYSxjLGUpfWVsc2UgZT1ifXJldHVybiBlfXZhciBjPWEuZG9jdW1lbnQsZD1mdW5jdGlvbigpe2Z1bmN0aW9uIEcoKXtpZighZC5pc1JlYWR5KXt0cnl7Yy5kb2N1bWVudEVsZW1lbnQuZG9TY3JvbGwoImxlZnQiKX1jYXRjaChhKXtzZXRUaW1lb3V0KEcsMSk7cmV0dXJufWQucmVhZHkoKX19dmFyIGQ9ZnVuY3Rpb24oYSxiKXtyZXR1cm4gbmV3IGQuZm4uaW5pdChhLGIsZyl9LGU9YS5qUXVlcnksZj1hLiQsZyxoPS9eKD86W148XSooPFtcd1xXXSs+KVtePl0qJHwjKFtcd1wtXSspJCkvLGk9L1xTLyxqPS9eXHMrLyxrPS9ccyskLyxsPS9cZC8sbT0vXjwoXHcrKVxzKlwvPz4oPzo8XC9cMT4pPyQvLG49L15bXF0sOnt9XHNdKiQvLG89L1xcKD86WyJcXFwvYmZucnRdfHVbMC05YS1mQS1GXXs0fSkvZyxwPS8iW14iXFxcblxyXSoifHRydWV8ZmFsc2V8bnVsbHwtP1xkKyg/OlwuXGQqKT8oPzpbZUVdWytcLV0/XGQrKT8vZyxxPS8oPzpefDp8LCkoPzpccypcWykrL2cscj0vKHdlYmtpdClbIFwvXShbXHcuXSspLyxzPS8ob3BlcmEpKD86Lip2ZXJzaW9uKT9bIFwvXShbXHcuXSspLyx0PS8obXNpZSkgKFtcdy5dKykvLHU9Lyhtb3ppbGxhKSg/Oi4qPyBydjooW1x3Ll0rKSk/Lyx2PW5hdmlnYXRvci51c2VyQWdlbnQsdyx4LHksej1PYmplY3QucHJvdG90eXBlLnRvU3RyaW5nLEE9T2JqZWN0LnByb3RvdHlwZS5oYXNPd25Qcm9wZXJ0eSxCPUFycmF5LnByb3RvdHlwZS5wdXNoLEM9QXJyYXkucHJvdG90eXBlLnNsaWNlLEQ9U3RyaW5nLnByb3RvdHlwZS50cmltLEU9QXJyYXkucHJvdG90eXBlLmluZGV4T2YsRj17fTtkLmZuPWQucHJvdG90eXBlPXtjb25zdHJ1Y3RvcjpkLGluaXQ6ZnVuY3Rpb24oYSxlLGYpe3ZhciBnLGksaixrO2lmKCFhKXJldHVybiB0aGlzO2lmKGEubm9kZVR5cGUpe3RoaXMuY29udGV4dD10aGlzWzBdPWEsdGhpcy5sZW5ndGg9MTtyZXR1cm4gdGhpc31pZihhPT09ImJvZHkiJiYhZSYmYy5ib2R5KXt0aGlzLmNvbnRleHQ9Yyx0aGlzWzBdPWMuYm9keSx0aGlzLnNlbGVjdG9yPSJib2R5Iix0aGlzLmxlbmd0aD0xO3JldHVybiB0aGlzfWlmKHR5cGVvZiBhPT09InN0cmluZyIpe2c9aC5leGVjKGEpO2lmKCFnfHwhZ1sxXSYmZSlyZXR1cm4hZXx8ZS5qcXVlcnk/KGV8fGYpLmZpbmQoYSk6dGhpcy5jb25zdHJ1Y3RvcihlKS5maW5kKGEpO2lmKGdbMV0pe2U9ZSBpbnN0YW5jZW9mIGQ/ZVswXTplLGs9ZT9lLm93bmVyRG9jdW1lbnR8fGU6YyxqPW0uZXhlYyhhKSxqP2QuaXNQbGFpbk9iamVjdChlKT8oYT1bYy5jcmVhdGVFbGVtZW50KGpbMV0pXSxkLmZuLmF0dHIuY2FsbChhLGUsITApKTphPVtrLmNyZWF0ZUVsZW1lbnQoalsxXSldOihqPWQuYnVpbGRGcmFnbWVudChbZ1sxXV0sW2tdKSxhPShqLmNhY2hlYWJsZT9kLmNsb25lKGouZnJhZ21lbnQpOmouZnJhZ21lbnQpLmNoaWxkTm9kZXMpO3JldHVybiBkLm1lcmdlKHRoaXMsYSl9aT1jLmdldEVsZW1lbnRCeUlkKGdbMl0pO2lmKGkmJmkucGFyZW50Tm9kZSl7aWYoaS5pZCE9PWdbMl0pcmV0dXJuIGYuZmluZChhKTt0aGlzLmxlbmd0aD0xLHRoaXNbMF09aX10aGlzLmNvbnRleHQ9Yyx0aGlzLnNlbGVjdG9yPWE7cmV0dXJuIHRoaXN9aWYoZC5pc0Z1bmN0aW9uKGEpKXJldHVybiBmLnJlYWR5KGEpO2Euc2VsZWN0b3IhPT1iJiYodGhpcy5zZWxlY3Rvcj1hLnNlbGVjdG9yLHRoaXMuY29udGV4dD1hLmNvbnRleHQpO3JldHVybiBkLm1ha2VBcnJheShhLHRoaXMpfSxzZWxlY3RvcjoiIixqcXVlcnk6IjEuNS4yIixsZW5ndGg6MCxzaXplOmZ1bmN0aW9uKCl7cmV0dXJuIHRoaXMubGVuZ3RofSx0b0FycmF5OmZ1bmN0aW9uKCl7cmV0dXJuIEMuY2FsbCh0aGlzLDApfSxnZXQ6ZnVuY3Rpb24oYSl7cmV0dXJuIGE9PW51bGw/dGhpcy50b0FycmF5KCk6YTwwP3RoaXNbdGhpcy5sZW5ndGgrYV06dGhpc1thXX0scHVzaFN0YWNrOmZ1bmN0aW9uKGEsYixjKXt2YXIgZT10aGlzLmNvbnN0cnVjdG9yKCk7ZC5pc0FycmF5KGEpP0IuYXBwbHkoZSxhKTpkLm1lcmdlKGUsYSksZS5wcmV2T2JqZWN0PXRoaXMsZS5jb250ZXh0PXRoaXMuY29udGV4dCxiPT09ImZpbmQiP2Uuc2VsZWN0b3I9dGhpcy5zZWxlY3RvcisodGhpcy5zZWxlY3Rvcj8iICI6IiIpK2M6YiYmKGUuc2VsZWN0b3I9dGhpcy5zZWxlY3RvcisiLiIrYisiKCIrYysiKSIpO3JldHVybiBlfSxlYWNoOmZ1bmN0aW9uKGEsYil7cmV0dXJuIGQuZWFjaCh0aGlzLGEsYil9LHJlYWR5OmZ1bmN0aW9uKGEpe2QuYmluZFJlYWR5KCkseC5kb25lKGEpO3JldHVybiB0aGlzfSxlcTpmdW5jdGlvbihhKXtyZXR1cm4gYT09PS0xP3RoaXMuc2xpY2UoYSk6dGhpcy5zbGljZShhLCthKzEpfSxmaXJzdDpmdW5jdGlvbigpe3JldHVybiB0aGlzLmVxKDApfSxsYXN0OmZ1bmN0aW9uKCl7cmV0dXJuIHRoaXMuZXEoLTEpfSxzbGljZTpmdW5jdGlvbigpe3JldHVybiB0aGlzLnB1c2hTdGFjayhDLmFwcGx5KHRoaXMsYXJndW1lbnRzKSwic2xpY2UiLEMuY2FsbChhcmd1bWVudHMpLmpvaW4oIiwiKSl9LG1hcDpmdW5jdGlvbihhKXtyZXR1cm4gdGhpcy5wdXNoU3RhY2soZC5tYXAodGhpcyxmdW5jdGlvbihiLGMpe3JldHVybiBhLmNhbGwoYixjLGIpfSkpfSxlbmQ6ZnVuY3Rpb24oKXtyZXR1cm4gdGhpcy5wcmV2T2JqZWN0fHx0aGlzLmNvbnN0cnVjdG9yKG51bGwpfSxwdXNoOkIsc29ydDpbXS5zb3J0LHNwbGljZTpbXS5zcGxpY2V9LGQuZm4uaW5pdC5wcm90b3R5cGU9ZC5mbixkLmV4dGVuZD1kLmZuLmV4dGVuZD1mdW5jdGlvbigpe3ZhciBhLGMsZSxmLGcsaCxpPWFyZ3VtZW50c1swXXx8e30saj0xLGs9YXJndW1lbnRzLmxlbmd0aCxsPSExO3R5cGVvZiBpPT09ImJvb2xlYW4iJiYobD1pLGk9YXJndW1lbnRzWzFdfHx7fSxqPTIpLHR5cGVvZiBpIT09Im9iamVjdCImJiFkLmlzRnVuY3Rpb24oaSkmJihpPXt9KSxrPT09aiYmKGk9dGhpcywtLWopO2Zvcig7ajxrO2orKylpZigoYT1hcmd1bWVudHNbal0pIT1udWxsKWZvcihjIGluIGEpe2U9aVtjXSxmPWFbY107aWYoaT09PWYpY29udGludWU7bCYmZiYmKGQuaXNQbGFpbk9iamVjdChmKXx8KGc9ZC5pc0FycmF5KGYpKSk/KGc/KGc9ITEsaD1lJiZkLmlzQXJyYXkoZSk/ZTpbXSk6aD1lJiZkLmlzUGxhaW5PYmplY3QoZSk/ZTp7fSxpW2NdPWQuZXh0ZW5kKGwsaCxmKSk6ZiE9PWImJihpW2NdPWYpfXJldHVybiBpfSxkLmV4dGVuZCh7bm9Db25mbGljdDpmdW5jdGlvbihiKXthLiQ9ZixiJiYoYS5qUXVlcnk9ZSk7cmV0dXJuIGR9LGlzUmVhZHk6ITEscmVhZHlXYWl0OjEscmVhZHk6ZnVuY3Rpb24oYSl7YT09PSEwJiZkLnJlYWR5V2FpdC0tO2lmKCFkLnJlYWR5V2FpdHx8YSE9PSEwJiYhZC5pc1JlYWR5KXtpZighYy5ib2R5KXJldHVybiBzZXRUaW1lb3V0KGQucmVhZHksMSk7ZC5pc1JlYWR5PSEwO2lmKGEhPT0hMCYmLS1kLnJlYWR5V2FpdD4wKXJldHVybjt4LnJlc29sdmVXaXRoKGMsW2RdKSxkLmZuLnRyaWdnZXImJmQoYykudHJpZ2dlcigicmVhZHkiKS51bmJpbmQoInJlYWR5Iil9fSxiaW5kUmVhZHk6ZnVuY3Rpb24oKXtpZigheCl7eD1kLl9EZWZlcnJlZCgpO2lmKGMucmVhZHlTdGF0ZT09PSJjb21wbGV0ZSIpcmV0dXJuIHNldFRpbWVvdXQoZC5yZWFkeSwxKTtpZihjLmFkZEV2ZW50TGlzdGVuZXIpYy5hZGRFdmVudExpc3RlbmVyKCJET01Db250ZW50TG9hZGVkIix5LCExKSxhLmFkZEV2ZW50TGlzdGVuZXIoImxvYWQiLGQucmVhZHksITEpO2Vsc2UgaWYoYy5hdHRhY2hFdmVudCl7Yy5hdHRhY2hFdmVudCgib25yZWFkeXN0YXRlY2hhbmdlIix5KSxhLmF0dGFjaEV2ZW50KCJvbmxvYWQiLGQucmVhZHkpO3ZhciBiPSExO3RyeXtiPWEuZnJhbWVFbGVtZW50PT1udWxsfWNhdGNoKGUpe31jLmRvY3VtZW50RWxlbWVudC5kb1Njcm9sbCYmYiYmRygpfX19LGlzRnVuY3Rpb246ZnVuY3Rpb24oYSl7cmV0dXJuIGQudHlwZShhKT09PSJmdW5jdGlvbiJ9LGlzQXJyYXk6QXJyYXkuaXNBcnJheXx8ZnVuY3Rpb24oYSl7cmV0dXJuIGQudHlwZShhKT09PSJhcnJheSJ9LGlzV2luZG93OmZ1bmN0aW9uKGEpe3JldHVybiBhJiZ0eXBlb2YgYT09PSJvYmplY3QiJiYic2V0SW50ZXJ2YWwiaW4gYX0saXNOYU46ZnVuY3Rpb24oYSl7cmV0dXJuIGE9PW51bGx8fCFsLnRlc3QoYSl8fGlzTmFOKGEpfSx0eXBlOmZ1bmN0aW9uKGEpe3JldHVybiBhPT1udWxsP1N0cmluZyhhKTpGW3ouY2FsbChhKV18fCJvYmplY3QifSxpc1BsYWluT2JqZWN0OmZ1bmN0aW9uKGEpe2lmKCFhfHxkLnR5cGUoYSkhPT0ib2JqZWN0Inx8YS5ub2RlVHlwZXx8ZC5pc1dpbmRvdyhhKSlyZXR1cm4hMTtpZihhLmNvbnN0cnVjdG9yJiYhQS5jYWxsKGEsImNvbnN0cnVjdG9yIikmJiFBLmNhbGwoYS5jb25zdHJ1Y3Rvci5wcm90b3R5cGUsImlzUHJvdG90eXBlT2YiKSlyZXR1cm4hMTt2YXIgYztmb3IoYyBpbiBhKXt9cmV0dXJuIGM9PT1ifHxBLmNhbGwoYSxjKX0saXNFbXB0eU9iamVjdDpmdW5jdGlvbihhKXtmb3IodmFyIGIgaW4gYSlyZXR1cm4hMTtyZXR1cm4hMH0sZXJyb3I6ZnVuY3Rpb24oYSl7dGhyb3cgYX0scGFyc2VKU09OOmZ1bmN0aW9uKGIpe2lmKHR5cGVvZiBiIT09InN0cmluZyJ8fCFiKXJldHVybiBudWxsO2I9ZC50cmltKGIpO2lmKG4udGVzdChiLnJlcGxhY2UobywiQCIpLnJlcGxhY2UocCwiXSIpLnJlcGxhY2UocSwiIikpKXJldHVybiBhLkpTT04mJmEuSlNPTi5wYXJzZT9hLkpTT04ucGFyc2UoYik6KG5ldyBGdW5jdGlvbigicmV0dXJuICIrYikpKCk7ZC5lcnJvcigiSW52YWxpZCBKU09OOiAiK2IpfSxwYXJzZVhNTDpmdW5jdGlvbihiLGMsZSl7YS5ET01QYXJzZXI/KGU9bmV3IERPTVBhcnNlcixjPWUucGFyc2VGcm9tU3RyaW5nKGIsInRleHQveG1sIikpOihjPW5ldyBBY3RpdmVYT2JqZWN0KCJNaWNyb3NvZnQuWE1MRE9NIiksYy5hc3luYz0iZmFsc2UiLGMubG9hZFhNTChiKSksZT1jLmRvY3VtZW50RWxlbWVudCwoIWV8fCFlLm5vZGVOYW1lfHxlLm5vZGVOYW1lPT09InBhcnNlcmVycm9yIikmJmQuZXJyb3IoIkludmFsaWQgWE1MOiAiK2IpO3JldHVybiBjfSxub29wOmZ1bmN0aW9uKCl7fSxnbG9iYWxFdmFsOmZ1bmN0aW9uKGEpe2lmKGEmJmkudGVzdChhKSl7dmFyIGI9Yy5oZWFkfHxjLmdldEVsZW1lbnRzQnlUYWdOYW1lKCJoZWFkIilbMF18fGMuZG9jdW1lbnRFbGVtZW50LGU9Yy5jcmVhdGVFbGVtZW50KCJzY3JpcHQiKTtkLnN1cHBvcnQuc2NyaXB0RXZhbCgpP2UuYXBwZW5kQ2hpbGQoYy5jcmVhdGVUZXh0Tm9kZShhKSk6ZS50ZXh0PWEsYi5pbnNlcnRCZWZvcmUoZSxiLmZpcnN0Q2hpbGQpLGIucmVtb3ZlQ2hpbGQoZSl9fSxub2RlTmFtZTpmdW5jdGlvbihhLGIpe3JldHVybiBhLm5vZGVOYW1lJiZhLm5vZGVOYW1lLnRvVXBwZXJDYXNlKCk9PT1iLnRvVXBwZXJDYXNlKCl9LGVhY2g6ZnVuY3Rpb24oYSxjLGUpe3ZhciBmLGc9MCxoPWEubGVuZ3RoLGk9aD09PWJ8fGQuaXNGdW5jdGlvbihhKTtpZihlKXtpZihpKXtmb3IoZiBpbiBhKWlmKGMuYXBwbHkoYVtmXSxlKT09PSExKWJyZWFrfWVsc2UgZm9yKDtnPGg7KWlmKGMuYXBwbHkoYVtnKytdLGUpPT09ITEpYnJlYWt9ZWxzZSBpZihpKXtmb3IoZiBpbiBhKWlmKGMuY2FsbChhW2ZdLGYsYVtmXSk9PT0hMSlicmVha31lbHNlIGZvcih2YXIgaj1hWzBdO2c8aCYmYy5jYWxsKGosZyxqKSE9PSExO2o9YVsrK2ddKXt9cmV0dXJuIGF9LHRyaW06RD9mdW5jdGlvbihhKXtyZXR1cm4gYT09bnVsbD8iIjpELmNhbGwoYSl9OmZ1bmN0aW9uKGEpe3JldHVybiBhPT1udWxsPyIiOihhKyIiKS5yZXBsYWNlKGosIiIpLnJlcGxhY2UoaywiIil9LG1ha2VBcnJheTpmdW5jdGlvbihhLGIpe3ZhciBjPWJ8fFtdO2lmKGEhPW51bGwpe3ZhciBlPWQudHlwZShhKTthLmxlbmd0aD09bnVsbHx8ZT09PSJzdHJpbmcifHxlPT09ImZ1bmN0aW9uInx8ZT09PSJyZWdleHAifHxkLmlzV2luZG93KGEpP0IuY2FsbChjLGEpOmQubWVyZ2UoYyxhKX1yZXR1cm4gY30saW5BcnJheTpmdW5jdGlvbihhLGIpe2lmKGIuaW5kZXhPZilyZXR1cm4gYi5pbmRleE9mKGEpO2Zvcih2YXIgYz0wLGQ9Yi5sZW5ndGg7YzxkO2MrKylpZihiW2NdPT09YSlyZXR1cm4gYztyZXR1cm4tMX0sbWVyZ2U6ZnVuY3Rpb24oYSxjKXt2YXIgZD1hLmxlbmd0aCxlPTA7aWYodHlwZW9mIGMubGVuZ3RoPT09Im51bWJlciIpZm9yKHZhciBmPWMubGVuZ3RoO2U8ZjtlKyspYVtkKytdPWNbZV07ZWxzZSB3aGlsZShjW2VdIT09YilhW2QrK109Y1tlKytdO2EubGVuZ3RoPWQ7cmV0dXJuIGF9LGdyZXA6ZnVuY3Rpb24oYSxiLGMpe3ZhciBkPVtdLGU7Yz0hIWM7Zm9yKHZhciBmPTAsZz1hLmxlbmd0aDtmPGc7ZisrKWU9ISFiKGFbZl0sZiksYyE9PWUmJmQucHVzaChhW2ZdKTtyZXR1cm4gZH0sbWFwOmZ1bmN0aW9uKGEsYixjKXt2YXIgZD1bXSxlO2Zvcih2YXIgZj0wLGc9YS5sZW5ndGg7ZjxnO2YrKyllPWIoYVtmXSxmLGMpLGUhPW51bGwmJihkW2QubGVuZ3RoXT1lKTtyZXR1cm4gZC5jb25jYXQuYXBwbHkoW10sZCl9LGd1aWQ6MSxwcm94eTpmdW5jdGlvbihhLGMsZSl7YXJndW1lbnRzLmxlbmd0aD09PTImJih0eXBlb2YgYz09PSJzdHJpbmciPyhlPWEsYT1lW2NdLGM9Yik6YyYmIWQuaXNGdW5jdGlvbihjKSYmKGU9YyxjPWIpKSwhYyYmYSYmKGM9ZnVuY3Rpb24oKXtyZXR1cm4gYS5hcHBseShlfHx0aGlzLGFyZ3VtZW50cyl9KSxhJiYoYy5ndWlkPWEuZ3VpZD1hLmd1aWR8fGMuZ3VpZHx8ZC5ndWlkKyspO3JldHVybiBjfSxhY2Nlc3M6ZnVuY3Rpb24oYSxjLGUsZixnLGgpe3ZhciBpPWEubGVuZ3RoO2lmKHR5cGVvZiBjPT09Im9iamVjdCIpe2Zvcih2YXIgaiBpbiBjKWQuYWNjZXNzKGEsaixjW2pdLGYsZyxlKTtyZXR1cm4gYX1pZihlIT09Yil7Zj0haCYmZiYmZC5pc0Z1bmN0aW9uKGUpO2Zvcih2YXIgaz0wO2s8aTtrKyspZyhhW2tdLGMsZj9lLmNhbGwoYVtrXSxrLGcoYVtrXSxjKSk6ZSxoKTtyZXR1cm4gYX1yZXR1cm4gaT9nKGFbMF0sYyk6Yn0sbm93OmZ1bmN0aW9uKCl7cmV0dXJuKG5ldyBEYXRlKS5nZXRUaW1lKCl9LHVhTWF0Y2g6ZnVuY3Rpb24oYSl7YT1hLnRvTG93ZXJDYXNlKCk7dmFyIGI9ci5leGVjKGEpfHxzLmV4ZWMoYSl8fHQuZXhlYyhhKXx8YS5pbmRleE9mKCJjb21wYXRpYmxlIik8MCYmdS5leGVjKGEpfHxbXTtyZXR1cm57YnJvd3NlcjpiWzFdfHwiIix2ZXJzaW9uOmJbMl18fCIwIn19LHN1YjpmdW5jdGlvbigpe2Z1bmN0aW9uIGEoYixjKXtyZXR1cm4gbmV3IGEuZm4uaW5pdChiLGMpfWQuZXh0ZW5kKCEwLGEsdGhpcyksYS5zdXBlcmNsYXNzPXRoaXMsYS5mbj1hLnByb3RvdHlwZT10aGlzKCksYS5mbi5jb25zdHJ1Y3Rvcj1hLGEuc3ViY2xhc3M9dGhpcy5zdWJjbGFzcyxhLmZuLmluaXQ9ZnVuY3Rpb24gYihiLGMpe2MmJmMgaW5zdGFuY2VvZiBkJiYhKGMgaW5zdGFuY2VvZiBhKSYmKGM9YShjKSk7cmV0dXJuIGQuZm4uaW5pdC5jYWxsKHRoaXMsYixjLGUpfSxhLmZuLmluaXQucHJvdG90eXBlPWEuZm47dmFyIGU9YShjKTtyZXR1cm4gYX0sYnJvd3Nlcjp7fX0pLGQuZWFjaCgiQm9vbGVhbiBOdW1iZXIgU3RyaW5nIEZ1bmN0aW9uIEFycmF5IERhdGUgUmVnRXhwIE9iamVjdCIuc3BsaXQoIiAiKSxmdW5jdGlvbihhLGIpe0ZbIltvYmplY3QgIitiKyJdIl09Yi50b0xvd2VyQ2FzZSgpfSksdz1kLnVhTWF0Y2godiksdy5icm93c2VyJiYoZC5icm93c2VyW3cuYnJvd3Nlcl09ITAsZC5icm93c2VyLnZlcnNpb249dy52ZXJzaW9uKSxkLmJyb3dzZXIud2Via2l0JiYoZC5icm93c2VyLnNhZmFyaT0hMCksRSYmKGQuaW5BcnJheT1mdW5jdGlvbihhLGIpe3JldHVybiBFLmNhbGwoYixhKX0pLGkudGVzdCgiwqAiKSYmKGo9L15bXHNceEEwXSsvLGs9L1tcc1x4QTBdKyQvKSxnPWQoYyksYy5hZGRFdmVudExpc3RlbmVyP3k9ZnVuY3Rpb24oKXtjLnJlbW92ZUV2ZW50TGlzdGVuZXIoIkRPTUNvbnRlbnRMb2FkZWQiLHksITEpLGQucmVhZHkoKX06Yy5hdHRhY2hFdmVudCYmKHk9ZnVuY3Rpb24oKXtjLnJlYWR5U3RhdGU9PT0iY29tcGxldGUiJiYoYy5kZXRhY2hFdmVudCgib25yZWFkeXN0YXRlY2hhbmdlIix5KSxkLnJlYWR5KCkpfSk7cmV0dXJuIGR9KCksZT0idGhlbiBkb25lIGZhaWwgaXNSZXNvbHZlZCBpc1JlamVjdGVkIHByb21pc2UiLnNwbGl0KCIgIiksZj1bXS5zbGljZTtkLmV4dGVuZCh7X0RlZmVycmVkOmZ1bmN0aW9uKCl7dmFyIGE9W10sYixjLGUsZj17ZG9uZTpmdW5jdGlvbigpe2lmKCFlKXt2YXIgYz1hcmd1bWVudHMsZyxoLGksaixrO2ImJihrPWIsYj0wKTtmb3IoZz0wLGg9Yy5sZW5ndGg7ZzxoO2crKylpPWNbZ10saj1kLnR5cGUoaSksaj09PSJhcnJheSI/Zi5kb25lLmFwcGx5KGYsaSk6aj09PSJmdW5jdGlvbiImJmEucHVzaChpKTtrJiZmLnJlc29sdmVXaXRoKGtbMF0sa1sxXSl9cmV0dXJuIHRoaXN9LHJlc29sdmVXaXRoOmZ1bmN0aW9uKGQsZil7aWYoIWUmJiFiJiYhYyl7Zj1mfHxbXSxjPTE7dHJ5e3doaWxlKGFbMF0pYS5zaGlmdCgpLmFwcGx5KGQsZil9ZmluYWxseXtiPVtkLGZdLGM9MH19cmV0dXJuIHRoaXN9LHJlc29sdmU6ZnVuY3Rpb24oKXtmLnJlc29sdmVXaXRoKHRoaXMsYXJndW1lbnRzKTtyZXR1cm4gdGhpc30saXNSZXNvbHZlZDpmdW5jdGlvbigpe3JldHVybiBjfHxifSxjYW5jZWw6ZnVuY3Rpb24oKXtlPTEsYT1bXTtyZXR1cm4gdGhpc319O3JldHVybiBmfSxEZWZlcnJlZDpmdW5jdGlvbihhKXt2YXIgYj1kLl9EZWZlcnJlZCgpLGM9ZC5fRGVmZXJyZWQoKSxmO2QuZXh0ZW5kKGIse3RoZW46ZnVuY3Rpb24oYSxjKXtiLmRvbmUoYSkuZmFpbChjKTtyZXR1cm4gdGhpc30sZmFpbDpjLmRvbmUscmVqZWN0V2l0aDpjLnJlc29sdmVXaXRoLHJlamVjdDpjLnJlc29sdmUsaXNSZWplY3RlZDpjLmlzUmVzb2x2ZWQscHJvbWlzZTpmdW5jdGlvbihhKXtpZihhPT1udWxsKXtpZihmKXJldHVybiBmO2Y9YT17fX12YXIgYz1lLmxlbmd0aDt3aGlsZShjLS0pYVtlW2NdXT1iW2VbY11dO3JldHVybiBhfX0pLGIuZG9uZShjLmNhbmNlbCkuZmFpbChiLmNhbmNlbCksZGVsZXRlIGIuY2FuY2VsLGEmJmEuY2FsbChiLGIpO3JldHVybiBifSx3aGVuOmZ1bmN0aW9uKGEpe2Z1bmN0aW9uIGkoYSl7cmV0dXJuIGZ1bmN0aW9uKGMpe2JbYV09YXJndW1lbnRzLmxlbmd0aD4xP2YuY2FsbChhcmd1bWVudHMsMCk6YywtLWd8fGgucmVzb2x2ZVdpdGgoaCxmLmNhbGwoYiwwKSl9fXZhciBiPWFyZ3VtZW50cyxjPTAsZT1iLmxlbmd0aCxnPWUsaD1lPD0xJiZhJiZkLmlzRnVuY3Rpb24oYS5wcm9taXNlKT9hOmQuRGVmZXJyZWQoKTtpZihlPjEpe2Zvcig7YzxlO2MrKyliW2NdJiZkLmlzRnVuY3Rpb24oYltjXS5wcm9taXNlKT9iW2NdLnByb21pc2UoKS50aGVuKGkoYyksaC5yZWplY3QpOi0tZztnfHxoLnJlc29sdmVXaXRoKGgsYil9ZWxzZSBoIT09YSYmaC5yZXNvbHZlV2l0aChoLGU/W2FdOltdKTtyZXR1cm4gaC5wcm9taXNlKCl9fSksZnVuY3Rpb24oKXtkLnN1cHBvcnQ9e307dmFyIGI9Yy5jcmVhdGVFbGVtZW50KCJkaXYiKTtiLnN0eWxlLmRpc3BsYXk9Im5vbmUiLGIuaW5uZXJIVE1MPSIgICA8bGluay8+PHRhYmxlPjwvdGFibGU+PGEgaHJlZj0nL2EnIHN0eWxlPSdjb2xvcjpyZWQ7ZmxvYXQ6bGVmdDtvcGFjaXR5Oi41NTsnPmE8L2E+PGlucHV0IHR5cGU9J2NoZWNrYm94Jy8+Ijt2YXIgZT1iLmdldEVsZW1lbnRzQnlUYWdOYW1lKCIqIiksZj1iLmdldEVsZW1lbnRzQnlUYWdOYW1lKCJhIilbMF0sZz1jLmNyZWF0ZUVsZW1lbnQoInNlbGVjdCIpLGg9Zy5hcHBlbmRDaGlsZChjLmNyZWF0ZUVsZW1lbnQoIm9wdGlvbiIpKSxpPWIuZ2V0RWxlbWVudHNCeVRhZ05hbWUoImlucHV0IilbMF07aWYoZSYmZS5sZW5ndGgmJmYpe2Quc3VwcG9ydD17bGVhZGluZ1doaXRlc3BhY2U6Yi5maXJzdENoaWxkLm5vZGVUeXBlPT09Myx0Ym9keTohYi5nZXRFbGVtZW50c0J5VGFnTmFtZSgidGJvZHkiKS5sZW5ndGgsaHRtbFNlcmlhbGl6ZTohIWIuZ2V0RWxlbWVudHNCeVRhZ05hbWUoImxpbmsiKS5sZW5ndGgsc3R5bGU6L3JlZC8udGVzdChmLmdldEF0dHJpYnV0ZSgic3R5bGUiKSksaHJlZk5vcm1hbGl6ZWQ6Zi5nZXRBdHRyaWJ1dGUoImhyZWYiKT09PSIvYSIsb3BhY2l0eTovXjAuNTUkLy50ZXN0KGYuc3R5bGUub3BhY2l0eSksY3NzRmxvYXQ6ISFmLnN0eWxlLmNzc0Zsb2F0LGNoZWNrT246aS52YWx1ZT09PSJvbiIsb3B0U2VsZWN0ZWQ6aC5zZWxlY3RlZCxkZWxldGVFeHBhbmRvOiEwLG9wdERpc2FibGVkOiExLGNoZWNrQ2xvbmU6ITEsbm9DbG9uZUV2ZW50OiEwLG5vQ2xvbmVDaGVja2VkOiEwLGJveE1vZGVsOm51bGwsaW5saW5lQmxvY2tOZWVkc0xheW91dDohMSxzaHJpbmtXcmFwQmxvY2tzOiExLHJlbGlhYmxlSGlkZGVuT2Zmc2V0czohMCxyZWxpYWJsZU1hcmdpblJpZ2h0OiEwfSxpLmNoZWNrZWQ9ITAsZC5zdXBwb3J0Lm5vQ2xvbmVDaGVja2VkPWkuY2xvbmVOb2RlKCEwKS5jaGVja2VkLGcuZGlzYWJsZWQ9ITAsZC5zdXBwb3J0Lm9wdERpc2FibGVkPSFoLmRpc2FibGVkO3ZhciBqPW51bGw7ZC5zdXBwb3J0LnNjcmlwdEV2YWw9ZnVuY3Rpb24oKXtpZihqPT09bnVsbCl7dmFyIGI9Yy5kb2N1bWVudEVsZW1lbnQsZT1jLmNyZWF0ZUVsZW1lbnQoInNjcmlwdCIpLGY9InNjcmlwdCIrZC5ub3coKTt0cnl7ZS5hcHBlbmRDaGlsZChjLmNyZWF0ZVRleHROb2RlKCJ3aW5kb3cuIitmKyI9MTsiKSl9Y2F0Y2goZyl7fWIuaW5zZXJ0QmVmb3JlKGUsYi5maXJzdENoaWxkKSxhW2ZdPyhqPSEwLGRlbGV0ZSBhW2ZdKTpqPSExLGIucmVtb3ZlQ2hpbGQoZSl9cmV0dXJuIGp9O3RyeXtkZWxldGUgYi50ZXN0fWNhdGNoKGspe2Quc3VwcG9ydC5kZWxldGVFeHBhbmRvPSExfSFiLmFkZEV2ZW50TGlzdGVuZXImJmIuYXR0YWNoRXZlbnQmJmIuZmlyZUV2ZW50JiYoYi5hdHRhY2hFdmVudCgib25jbGljayIsZnVuY3Rpb24gbCgpe2Quc3VwcG9ydC5ub0Nsb25lRXZlbnQ9ITEsYi5kZXRhY2hFdmVudCgib25jbGljayIsbCl9KSxiLmNsb25lTm9kZSghMCkuZmlyZUV2ZW50KCJvbmNsaWNrIikpLGI9Yy5jcmVhdGVFbGVtZW50KCJkaXYiKSxiLmlubmVySFRNTD0iPGlucHV0IHR5cGU9J3JhZGlvJyBuYW1lPSdyYWRpb3Rlc3QnIGNoZWNrZWQ9J2NoZWNrZWQnLz4iO3ZhciBtPWMuY3JlYXRlRG9jdW1lbnRGcmFnbWVudCgpO20uYXBwZW5kQ2hpbGQoYi5maXJzdENoaWxkKSxkLnN1cHBvcnQuY2hlY2tDbG9uZT1tLmNsb25lTm9kZSghMCkuY2xvbmVOb2RlKCEwKS5sYXN0Q2hpbGQuY2hlY2tlZCxkKGZ1bmN0aW9uKCl7dmFyIGE9Yy5jcmVhdGVFbGVtZW50KCJkaXYiKSxiPWMuZ2V0RWxlbWVudHNCeVRhZ05hbWUoImJvZHkiKVswXTtpZihiKXthLnN0eWxlLndpZHRoPWEuc3R5bGUucGFkZGluZ0xlZnQ9IjFweCIsYi5hcHBlbmRDaGlsZChhKSxkLmJveE1vZGVsPWQuc3VwcG9ydC5ib3hNb2RlbD1hLm9mZnNldFdpZHRoPT09Miwiem9vbSJpbiBhLnN0eWxlJiYoYS5zdHlsZS5kaXNwbGF5PSJpbmxpbmUiLGEuc3R5bGUuem9vbT0xLGQuc3VwcG9ydC5pbmxpbmVCbG9ja05lZWRzTGF5b3V0PWEub2Zmc2V0V2lkdGg9PT0yLGEuc3R5bGUuZGlzcGxheT0iIixhLmlubmVySFRNTD0iPGRpdiBzdHlsZT0nd2lkdGg6NHB4Oyc+PC9kaXY+IixkLnN1cHBvcnQuc2hyaW5rV3JhcEJsb2Nrcz1hLm9mZnNldFdpZHRoIT09MiksYS5pbm5lckhUTUw9Ijx0YWJsZT48dHI+PHRkIHN0eWxlPSdwYWRkaW5nOjA7Ym9yZGVyOjA7ZGlzcGxheTpub25lJz48L3RkPjx0ZD50PC90ZD48L3RyPjwvdGFibGU+Ijt2YXIgZT1hLmdldEVsZW1lbnRzQnlUYWdOYW1lKCJ0ZCIpO2Quc3VwcG9ydC5yZWxpYWJsZUhpZGRlbk9mZnNldHM9ZVswXS5vZmZzZXRIZWlnaHQ9PT0wLGVbMF0uc3R5bGUuZGlzcGxheT0iIixlWzFdLnN0eWxlLmRpc3BsYXk9Im5vbmUiLGQuc3VwcG9ydC5yZWxpYWJsZUhpZGRlbk9mZnNldHM9ZC5zdXBwb3J0LnJlbGlhYmxlSGlkZGVuT2Zmc2V0cyYmZVswXS5vZmZzZXRIZWlnaHQ9PT0wLGEuaW5uZXJIVE1MPSIiLGMuZGVmYXVsdFZpZXcmJmMuZGVmYXVsdFZpZXcuZ2V0Q29tcHV0ZWRTdHlsZSYmKGEuc3R5bGUud2lkdGg9IjFweCIsYS5zdHlsZS5tYXJnaW5SaWdodD0iMCIsZC5zdXBwb3J0LnJlbGlhYmxlTWFyZ2luUmlnaHQ9KHBhcnNlSW50KGMuZGVmYXVsdFZpZXcuZ2V0Q29tcHV0ZWRTdHlsZShhLG51bGwpLm1hcmdpblJpZ2h0LDEwKXx8MCk9PT0wKSxiLnJlbW92ZUNoaWxkKGEpLnN0eWxlLmRpc3BsYXk9Im5vbmUiLGE9ZT1udWxsfX0pO3ZhciBuPWZ1bmN0aW9uKGEpe3ZhciBiPWMuY3JlYXRlRWxlbWVudCgiZGl2Iik7YT0ib24iK2E7aWYoIWIuYXR0YWNoRXZlbnQpcmV0dXJuITA7dmFyIGQ9YSBpbiBiO2R8fChiLnNldEF0dHJpYnV0ZShhLCJyZXR1cm47IiksZD10eXBlb2YgYlthXT09PSJmdW5jdGlvbiIpO3JldHVybiBkfTtkLnN1cHBvcnQuc3VibWl0QnViYmxlcz1uKCJzdWJtaXQiKSxkLnN1cHBvcnQuY2hhbmdlQnViYmxlcz1uKCJjaGFuZ2UiKSxiPWU9Zj1udWxsfX0oKTt2YXIgZz0vXig/Olx7LipcfXxcWy4qXF0pJC87ZC5leHRlbmQoe2NhY2hlOnt9LHV1aWQ6MCxleHBhbmRvOiJqUXVlcnkiKyhkLmZuLmpxdWVyeStNYXRoLnJhbmRvbSgpKS5yZXBsYWNlKC9cRC9nLCIiKSxub0RhdGE6e2VtYmVkOiEwLG9iamVjdDoiY2xzaWQ6RDI3Q0RCNkUtQUU2RC0xMWNmLTk2QjgtNDQ0NTUzNTQwMDAwIixhcHBsZXQ6ITB9LGhhc0RhdGE6ZnVuY3Rpb24oYSl7YT1hLm5vZGVUeXBlP2QuY2FjaGVbYVtkLmV4cGFuZG9dXTphW2QuZXhwYW5kb107cmV0dXJuISFhJiYhaShhKX0sZGF0YTpmdW5jdGlvbihhLGMsZSxmKXtpZihkLmFjY2VwdERhdGEoYSkpe3ZhciBnPWQuZXhwYW5kbyxoPXR5cGVvZiBjPT09InN0cmluZyIsaSxqPWEubm9kZVR5cGUsaz1qP2QuY2FjaGU6YSxsPWo/YVtkLmV4cGFuZG9dOmFbZC5leHBhbmRvXSYmZC5leHBhbmRvO2lmKCghbHx8ZiYmbCYmIWtbbF1bZ10pJiZoJiZlPT09YilyZXR1cm47bHx8KGo/YVtkLmV4cGFuZG9dPWw9KytkLnV1aWQ6bD1kLmV4cGFuZG8pLGtbbF18fChrW2xdPXt9LGp8fChrW2xdLnRvSlNPTj1kLm5vb3ApKTtpZih0eXBlb2YgYz09PSJvYmplY3QifHx0eXBlb2YgYz09PSJmdW5jdGlvbiIpZj9rW2xdW2ddPWQuZXh0ZW5kKGtbbF1bZ10sYyk6a1tsXT1kLmV4dGVuZChrW2xdLGMpO2k9a1tsXSxmJiYoaVtnXXx8KGlbZ109e30pLGk9aVtnXSksZSE9PWImJihpW2NdPWUpO2lmKGM9PT0iZXZlbnRzIiYmIWlbY10pcmV0dXJuIGlbZ10mJmlbZ10uZXZlbnRzO3JldHVybiBoP2lbY106aX19LHJlbW92ZURhdGE6ZnVuY3Rpb24oYixjLGUpe2lmKGQuYWNjZXB0RGF0YShiKSl7dmFyIGY9ZC5leHBhbmRvLGc9Yi5ub2RlVHlwZSxoPWc/ZC5jYWNoZTpiLGo9Zz9iW2QuZXhwYW5kb106ZC5leHBhbmRvO2lmKCFoW2pdKXJldHVybjtpZihjKXt2YXIgaz1lP2hbal1bZl06aFtqXTtpZihrKXtkZWxldGUga1tjXTtpZighaShrKSlyZXR1cm59fWlmKGUpe2RlbGV0ZSBoW2pdW2ZdO2lmKCFpKGhbal0pKXJldHVybn12YXIgbD1oW2pdW2ZdO2Quc3VwcG9ydC5kZWxldGVFeHBhbmRvfHxoIT1hP2RlbGV0ZSBoW2pdOmhbal09bnVsbCxsPyhoW2pdPXt9LGd8fChoW2pdLnRvSlNPTj1kLm5vb3ApLGhbal1bZl09bCk6ZyYmKGQuc3VwcG9ydC5kZWxldGVFeHBhbmRvP2RlbGV0ZSBiW2QuZXhwYW5kb106Yi5yZW1vdmVBdHRyaWJ1dGU/Yi5yZW1vdmVBdHRyaWJ1dGUoZC5leHBhbmRvKTpiW2QuZXhwYW5kb109bnVsbCl9fSxfZGF0YTpmdW5jdGlvbihhLGIsYyl7cmV0dXJuIGQuZGF0YShhLGIsYywhMCl9LGFjY2VwdERhdGE6ZnVuY3Rpb24oYSl7aWYoYS5ub2RlTmFtZSl7dmFyIGI9ZC5ub0RhdGFbYS5ub2RlTmFtZS50b0xvd2VyQ2FzZSgpXTtpZihiKXJldHVybiBiIT09ITAmJmEuZ2V0QXR0cmlidXRlKCJjbGFzc2lkIik9PT1ifXJldHVybiEwfX0pLGQuZm4uZXh0ZW5kKHtkYXRhOmZ1bmN0aW9uKGEsYyl7dmFyIGU9bnVsbDtpZih0eXBlb2YgYT09PSJ1bmRlZmluZWQiKXtpZih0aGlzLmxlbmd0aCl7ZT1kLmRhdGEodGhpc1swXSk7aWYodGhpc1swXS5ub2RlVHlwZT09PTEpe3ZhciBmPXRoaXNbMF0uYXR0cmlidXRlcyxnO2Zvcih2YXIgaT0wLGo9Zi5sZW5ndGg7aTxqO2krKylnPWZbaV0ubmFtZSxnLmluZGV4T2YoImRhdGEtIik9PT0wJiYoZz1nLnN1YnN0cig1KSxoKHRoaXNbMF0sZyxlW2ddKSl9fXJldHVybiBlfWlmKHR5cGVvZiBhPT09Im9iamVjdCIpcmV0dXJuIHRoaXMuZWFjaChmdW5jdGlvbigpe2QuZGF0YSh0aGlzLGEpfSk7dmFyIGs9YS5zcGxpdCgiLiIpO2tbMV09a1sxXT8iLiIra1sxXToiIjtpZihjPT09Yil7ZT10aGlzLnRyaWdnZXJIYW5kbGVyKCJnZXREYXRhIitrWzFdKyIhIixba1swXV0pLGU9PT1iJiZ0aGlzLmxlbmd0aCYmKGU9ZC5kYXRhKHRoaXNbMF0sYSksZT1oKHRoaXNbMF0sYSxlKSk7cmV0dXJuIGU9PT1iJiZrWzFdP3RoaXMuZGF0YShrWzBdKTplfXJldHVybiB0aGlzLmVhY2goZnVuY3Rpb24oKXt2YXIgYj1kKHRoaXMpLGU9W2tbMF0sY107Yi50cmlnZ2VySGFuZGxlcigic2V0RGF0YSIra1sxXSsiISIsZSksZC5kYXRhKHRoaXMsYSxjKSxiLnRyaWdnZXJIYW5kbGVyKCJjaGFuZ2VEYXRhIitrWzFdKyIhIixlKX0pfSxyZW1vdmVEYXRhOmZ1bmN0aW9uKGEpe3JldHVybiB0aGlzLmVhY2goZnVuY3Rpb24oKXtkLnJlbW92ZURhdGEodGhpcyxhKX0pfX0pLGQuZXh0ZW5kKHtxdWV1ZTpmdW5jdGlvbihhLGIsYyl7aWYoYSl7Yj0oYnx8ImZ4IikrInF1ZXVlIjt2YXIgZT1kLl9kYXRhKGEsYik7aWYoIWMpcmV0dXJuIGV8fFtdOyFlfHxkLmlzQXJyYXkoYyk/ZT1kLl9kYXRhKGEsYixkLm1ha2VBcnJheShjKSk6ZS5wdXNoKGMpO3JldHVybiBlfX0sZGVxdWV1ZTpmdW5jdGlvbihhLGIpe2I9Ynx8ImZ4Ijt2YXIgYz1kLnF1ZXVlKGEsYiksZT1jLnNoaWZ0KCk7ZT09PSJpbnByb2dyZXNzIiYmKGU9Yy5zaGlmdCgpKSxlJiYoYj09PSJmeCImJmMudW5zaGlmdCgiaW5wcm9ncmVzcyIpLGUuY2FsbChhLGZ1bmN0aW9uKCl7ZC5kZXF1ZXVlKGEsYil9KSksYy5sZW5ndGh8fGQucmVtb3ZlRGF0YShhLGIrInF1ZXVlIiwhMCl9fSksZC5mbi5leHRlbmQoe3F1ZXVlOmZ1bmN0aW9uKGEsYyl7dHlwZW9mIGEhPT0ic3RyaW5nIiYmKGM9YSxhPSJmeCIpO2lmKGM9PT1iKXJldHVybiBkLnF1ZXVlKHRoaXNbMF0sYSk7cmV0dXJuIHRoaXMuZWFjaChmdW5jdGlvbihiKXt2YXIgZT1kLnF1ZXVlKHRoaXMsYSxjKTthPT09ImZ4IiYmZVswXSE9PSJpbnByb2dyZXNzIiYmZC5kZXF1ZXVlKHRoaXMsYSl9KX0sZGVxdWV1ZTpmdW5jdGlvbihhKXtyZXR1cm4gdGhpcy5lYWNoKGZ1bmN0aW9uKCl7ZC5kZXF1ZXVlKHRoaXMsYSl9KX0sZGVsYXk6ZnVuY3Rpb24oYSxiKXthPWQuZng/ZC5meC5zcGVlZHNbYV18fGE6YSxiPWJ8fCJmeCI7cmV0dXJuIHRoaXMucXVldWUoYixmdW5jdGlvbigpe3ZhciBjPXRoaXM7c2V0VGltZW91dChmdW5jdGlvbigpe2QuZGVxdWV1ZShjLGIpfSxhKX0pfSxjbGVhclF1ZXVlOmZ1bmN0aW9uKGEpe3JldHVybiB0aGlzLnF1ZXVlKGF8fCJmeCIsW10pfX0pO3ZhciBqPS9bXG5cdFxyXS9nLGs9L1xzKy8sbD0vXHIvZyxtPS9eKD86aHJlZnxzcmN8c3R5bGUpJC8sbj0vXig/OmJ1dHRvbnxpbnB1dCkkL2ksbz0vXig/OmJ1dHRvbnxpbnB1dHxvYmplY3R8c2VsZWN0fHRleHRhcmVhKSQvaSxwPS9eYSg/OnJlYSk/JC9pLHE9L14oPzpyYWRpb3xjaGVja2JveCkkL2k7ZC5wcm9wcz17ImZvciI6Imh0bWxGb3IiLCJjbGFzcyI6ImNsYXNzTmFtZSIscmVhZG9ubHk6InJlYWRPbmx5IixtYXhsZW5ndGg6Im1heExlbmd0aCIsY2VsbHNwYWNpbmc6ImNlbGxTcGFjaW5nIixyb3dzcGFuOiJyb3dTcGFuIixjb2xzcGFuOiJjb2xTcGFuIix0YWJpbmRleDoidGFiSW5kZXgiLHVzZW1hcDoidXNlTWFwIixmcmFtZWJvcmRlcjoiZnJhbWVCb3JkZXIifSxkLmZuLmV4dGVuZCh7YXR0cjpmdW5jdGlvbihhLGIpe3JldHVybiBkLmFjY2Vzcyh0aGlzLGEsYiwhMCxkLmF0dHIpfSxyZW1vdmVBdHRyOmZ1bmN0aW9uKGEsYil7cmV0dXJuIHRoaXMuZWFjaChmdW5jdGlvbigpe2QuYXR0cih0aGlzLGEsIiIpLHRoaXMubm9kZVR5cGU9PT0xJiZ0aGlzLnJlbW92ZUF0dHJpYnV0ZShhKX0pfSxhZGRDbGFzczpmdW5jdGlvbihhKXtpZihkLmlzRnVuY3Rpb24oYSkpcmV0dXJuIHRoaXMuZWFjaChmdW5jdGlvbihiKXt2YXIgYz1kKHRoaXMpO2MuYWRkQ2xhc3MoYS5jYWxsKHRoaXMsYixjLmF0dHIoImNsYXNzIikpKX0pO2lmKGEmJnR5cGVvZiBhPT09InN0cmluZyIpe3ZhciBiPShhfHwiIikuc3BsaXQoayk7Zm9yKHZhciBjPTAsZT10aGlzLmxlbmd0aDtjPGU7YysrKXt2YXIgZj10aGlzW2NdO2lmKGYubm9kZVR5cGU9PT0xKWlmKGYuY2xhc3NOYW1lKXt2YXIgZz0iICIrZi5jbGFzc05hbWUrIiAiLGg9Zi5jbGFzc05hbWU7Zm9yKHZhciBpPTAsaj1iLmxlbmd0aDtpPGo7aSsrKWcuaW5kZXhPZigiICIrYltpXSsiICIpPDAmJihoKz0iICIrYltpXSk7Zi5jbGFzc05hbWU9ZC50cmltKGgpfWVsc2UgZi5jbGFzc05hbWU9YX19cmV0dXJuIHRoaXN9LHJlbW92ZUNsYXNzOmZ1bmN0aW9uKGEpe2lmKGQuaXNGdW5jdGlvbihhKSlyZXR1cm4gdGhpcy5lYWNoKGZ1bmN0aW9uKGIpe3ZhciBjPWQodGhpcyk7Yy5yZW1vdmVDbGFzcyhhLmNhbGwodGhpcyxiLGMuYXR0cigiY2xhc3MiKSkpfSk7aWYoYSYmdHlwZW9mIGE9PT0ic3RyaW5nInx8YT09PWIpe3ZhciBjPShhfHwiIikuc3BsaXQoayk7Zm9yKHZhciBlPTAsZj10aGlzLmxlbmd0aDtlPGY7ZSsrKXt2YXIgZz10aGlzW2VdO2lmKGcubm9kZVR5cGU9PT0xJiZnLmNsYXNzTmFtZSlpZihhKXt2YXIgaD0oIiAiK2cuY2xhc3NOYW1lKyIgIikucmVwbGFjZShqLCIgIik7Zm9yKHZhciBpPTAsbD1jLmxlbmd0aDtpPGw7aSsrKWg9aC5yZXBsYWNlKCIgIitjW2ldKyIgIiwiICIpO2cuY2xhc3NOYW1lPWQudHJpbShoKX1lbHNlIGcuY2xhc3NOYW1lPSIifX1yZXR1cm4gdGhpc30sdG9nZ2xlQ2xhc3M6ZnVuY3Rpb24oYSxiKXt2YXIgYz10eXBlb2YgYSxlPXR5cGVvZiBiPT09ImJvb2xlYW4iO2lmKGQuaXNGdW5jdGlvbihhKSlyZXR1cm4gdGhpcy5lYWNoKGZ1bmN0aW9uKGMpe3ZhciBlPWQodGhpcyk7ZS50b2dnbGVDbGFzcyhhLmNhbGwodGhpcyxjLGUuYXR0cigiY2xhc3MiKSxiKSxiKX0pO3JldHVybiB0aGlzLmVhY2goZnVuY3Rpb24oKXtpZihjPT09InN0cmluZyIpe3ZhciBmLGc9MCxoPWQodGhpcyksaT1iLGo9YS5zcGxpdChrKTt3aGlsZShmPWpbZysrXSlpPWU/aTohaC5oYXNDbGFzcyhmKSxoW2k/ImFkZENsYXNzIjoicmVtb3ZlQ2xhc3MiXShmKX1lbHNlIGlmKGM9PT0idW5kZWZpbmVkInx8Yz09PSJib29sZWFuIil0aGlzLmNsYXNzTmFtZSYmZC5fZGF0YSh0aGlzLCJfX2NsYXNzTmFtZV9fIix0aGlzLmNsYXNzTmFtZSksdGhpcy5jbGFzc05hbWU9dGhpcy5jbGFzc05hbWV8fGE9PT0hMT8iIjpkLl9kYXRhKHRoaXMsIl9fY2xhc3NOYW1lX18iKXx8IiJ9KX0saGFzQ2xhc3M6ZnVuY3Rpb24oYSl7dmFyIGI9IiAiK2ErIiAiO2Zvcih2YXIgYz0wLGQ9dGhpcy5sZW5ndGg7YzxkO2MrKylpZigoIiAiK3RoaXNbY10uY2xhc3NOYW1lKyIgIikucmVwbGFjZShqLCIgIikuaW5kZXhPZihiKT4tMSlyZXR1cm4hMDtyZXR1cm4hMX0sdmFsOmZ1bmN0aW9uKGEpe2lmKCFhcmd1bWVudHMubGVuZ3RoKXt2YXIgYz10aGlzWzBdO2lmKGMpe2lmKGQubm9kZU5hbWUoYywib3B0aW9uIikpe3ZhciBlPWMuYXR0cmlidXRlcy52YWx1ZTtyZXR1cm4hZXx8ZS5zcGVjaWZpZWQ/Yy52YWx1ZTpjLnRleHR9aWYoZC5ub2RlTmFtZShjLCJzZWxlY3QiKSl7dmFyIGY9Yy5zZWxlY3RlZEluZGV4LGc9W10saD1jLm9wdGlvbnMsaT1jLnR5cGU9PT0ic2VsZWN0LW9uZSI7aWYoZjwwKXJldHVybiBudWxsO2Zvcih2YXIgaj1pP2Y6MCxrPWk/ZisxOmgubGVuZ3RoO2o8aztqKyspe3ZhciBtPWhbal07aWYobS5zZWxlY3RlZCYmKGQuc3VwcG9ydC5vcHREaXNhYmxlZD8hbS5kaXNhYmxlZDptLmdldEF0dHJpYnV0ZSgiZGlzYWJsZWQiKT09PW51bGwpJiYoIW0ucGFyZW50Tm9kZS5kaXNhYmxlZHx8IWQubm9kZU5hbWUobS5wYXJlbnROb2RlLCJvcHRncm91cCIpKSl7YT1kKG0pLnZhbCgpO2lmKGkpcmV0dXJuIGE7Zy5wdXNoKGEpfX1pZihpJiYhZy5sZW5ndGgmJmgubGVuZ3RoKXJldHVybiBkKGhbZl0pLnZhbCgpO3JldHVybiBnfWlmKHEudGVzdChjLnR5cGUpJiYhZC5zdXBwb3J0LmNoZWNrT24pcmV0dXJuIGMuZ2V0QXR0cmlidXRlKCJ2YWx1ZSIpPT09bnVsbD8ib24iOmMudmFsdWU7cmV0dXJuKGMudmFsdWV8fCIiKS5yZXBsYWNlKGwsIiIpfXJldHVybiBifXZhciBuPWQuaXNGdW5jdGlvbihhKTtyZXR1cm4gdGhpcy5lYWNoKGZ1bmN0aW9uKGIpe3ZhciBjPWQodGhpcyksZT1hO2lmKHRoaXMubm9kZVR5cGU9PT0xKXtuJiYoZT1hLmNhbGwodGhpcyxiLGMudmFsKCkpKSxlPT1udWxsP2U9IiI6dHlwZW9mIGU9PT0ibnVtYmVyIj9lKz0iIjpkLmlzQXJyYXkoZSkmJihlPWQubWFwKGUsZnVuY3Rpb24oYSl7cmV0dXJuIGE9PW51bGw/IiI6YSsiIn0pKTtpZihkLmlzQXJyYXkoZSkmJnEudGVzdCh0aGlzLnR5cGUpKXRoaXMuY2hlY2tlZD1kLmluQXJyYXkoYy52YWwoKSxlKT49MDtlbHNlIGlmKGQubm9kZU5hbWUodGhpcywic2VsZWN0Iikpe3ZhciBmPWQubWFrZUFycmF5KGUpO2QoIm9wdGlvbiIsdGhpcykuZWFjaChmdW5jdGlvbigpe3RoaXMuc2VsZWN0ZWQ9ZC5pbkFycmF5KGQodGhpcykudmFsKCksZik+PTB9KSxmLmxlbmd0aHx8KHRoaXMuc2VsZWN0ZWRJbmRleD0tMSl9ZWxzZSB0aGlzLnZhbHVlPWV9fSl9fSksZC5leHRlbmQoe2F0dHJGbjp7dmFsOiEwLGNzczohMCxodG1sOiEwLHRleHQ6ITAsZGF0YTohMCx3aWR0aDohMCxoZWlnaHQ6ITAsb2Zmc2V0OiEwfSxhdHRyOmZ1bmN0aW9uKGEsYyxlLGYpe2lmKCFhfHxhLm5vZGVUeXBlPT09M3x8YS5ub2RlVHlwZT09PTh8fGEubm9kZVR5cGU9PT0yKXJldHVybiBiO2lmKGYmJmMgaW4gZC5hdHRyRm4pcmV0dXJuIGQoYSlbY10oZSk7dmFyIGc9YS5ub2RlVHlwZSE9PTF8fCFkLmlzWE1MRG9jKGEpLGg9ZSE9PWI7Yz1nJiZkLnByb3BzW2NdfHxjO2lmKGEubm9kZVR5cGU9PT0xKXt2YXIgaT1tLnRlc3QoYyk7aWYoYz09PSJzZWxlY3RlZCImJiFkLnN1cHBvcnQub3B0U2VsZWN0ZWQpe3ZhciBqPWEucGFyZW50Tm9kZTtqJiYoai5zZWxlY3RlZEluZGV4LGoucGFyZW50Tm9kZSYmai5wYXJlbnROb2RlLnNlbGVjdGVkSW5kZXgpfWlmKChjIGluIGF8fGFbY10hPT1iKSYmZyYmIWkpe2gmJihjPT09InR5cGUiJiZuLnRlc3QoYS5ub2RlTmFtZSkmJmEucGFyZW50Tm9kZSYmZC5lcnJvcigidHlwZSBwcm9wZXJ0eSBjYW4ndCBiZSBjaGFuZ2VkIiksZT09PW51bGw/YS5ub2RlVHlwZT09PTEmJmEucmVtb3ZlQXR0cmlidXRlKGMpOmFbY109ZSk7aWYoZC5ub2RlTmFtZShhLCJmb3JtIikmJmEuZ2V0QXR0cmlidXRlTm9kZShjKSlyZXR1cm4gYS5nZXRBdHRyaWJ1dGVOb2RlKGMpLm5vZGVWYWx1ZTtpZihjPT09InRhYkluZGV4Iil7dmFyIGs9YS5nZXRBdHRyaWJ1dGVOb2RlKCJ0YWJJbmRleCIpO3JldHVybiBrJiZrLnNwZWNpZmllZD9rLnZhbHVlOm8udGVzdChhLm5vZGVOYW1lKXx8cC50ZXN0KGEubm9kZU5hbWUpJiZhLmhyZWY/MDpifXJldHVybiBhW2NdfWlmKCFkLnN1cHBvcnQuc3R5bGUmJmcmJmM9PT0ic3R5bGUiKXtoJiYoYS5zdHlsZS5jc3NUZXh0PSIiK2UpO3JldHVybiBhLnN0eWxlLmNzc1RleHR9aCYmYS5zZXRBdHRyaWJ1dGUoYywiIitlKTtpZighYS5hdHRyaWJ1dGVzW2NdJiYoYS5oYXNBdHRyaWJ1dGUmJiFhLmhhc0F0dHJpYnV0ZShjKSkpcmV0dXJuIGI7dmFyIGw9IWQuc3VwcG9ydC5ocmVmTm9ybWFsaXplZCYmZyYmaT9hLmdldEF0dHJpYnV0ZShjLDIpOmEuZ2V0QXR0cmlidXRlKGMpO3JldHVybiBsPT09bnVsbD9iOmx9aCYmKGFbY109ZSk7cmV0dXJuIGFbY119fSk7dmFyIHI9L1wuKC4qKSQvLHM9L14oPzp0ZXh0YXJlYXxpbnB1dHxzZWxlY3QpJC9pLHQ9L1wuL2csdT0vIC9nLHY9L1teXHdccy58YF0vZyx3PWZ1bmN0aW9uKGEpe3JldHVybiBhLnJlcGxhY2UodiwiXFwkJiIpfTtkLmV2ZW50PXthZGQ6ZnVuY3Rpb24oYyxlLGYsZyl7aWYoYy5ub2RlVHlwZSE9PTMmJmMubm9kZVR5cGUhPT04KXt0cnl7ZC5pc1dpbmRvdyhjKSYmKGMhPT1hJiYhYy5mcmFtZUVsZW1lbnQpJiYoYz1hKX1jYXRjaChoKXt9aWYoZj09PSExKWY9eDtlbHNlIGlmKCFmKXJldHVybjt2YXIgaSxqO2YuaGFuZGxlciYmKGk9ZixmPWkuaGFuZGxlciksZi5ndWlkfHwoZi5ndWlkPWQuZ3VpZCsrKTt2YXIgaz1kLl9kYXRhKGMpO2lmKCFrKXJldHVybjt2YXIgbD1rLmV2ZW50cyxtPWsuaGFuZGxlO2x8fChrLmV2ZW50cz1sPXt9KSxtfHwoay5oYW5kbGU9bT1mdW5jdGlvbihhKXtyZXR1cm4gdHlwZW9mIGQhPT0idW5kZWZpbmVkIiYmZC5ldmVudC50cmlnZ2VyZWQhPT1hLnR5cGU/ZC5ldmVudC5oYW5kbGUuYXBwbHkobS5lbGVtLGFyZ3VtZW50cyk6Yn0pLG0uZWxlbT1jLGU9ZS5zcGxpdCgiICIpO3ZhciBuLG89MCxwO3doaWxlKG49ZVtvKytdKXtqPWk/ZC5leHRlbmQoe30saSk6e2hhbmRsZXI6ZixkYXRhOmd9LG4uaW5kZXhPZigiLiIpPi0xPyhwPW4uc3BsaXQoIi4iKSxuPXAuc2hpZnQoKSxqLm5hbWVzcGFjZT1wLnNsaWNlKDApLnNvcnQoKS5qb2luKCIuIikpOihwPVtdLGoubmFtZXNwYWNlPSIiKSxqLnR5cGU9bixqLmd1aWR8fChqLmd1aWQ9Zi5ndWlkKTt2YXIgcT1sW25dLHI9ZC5ldmVudC5zcGVjaWFsW25dfHx7fTtpZighcSl7cT1sW25dPVtdO2lmKCFyLnNldHVwfHxyLnNldHVwLmNhbGwoYyxnLHAsbSk9PT0hMSljLmFkZEV2ZW50TGlzdGVuZXI/Yy5hZGRFdmVudExpc3RlbmVyKG4sbSwhMSk6Yy5hdHRhY2hFdmVudCYmYy5hdHRhY2hFdmVudCgib24iK24sbSl9ci5hZGQmJihyLmFkZC5jYWxsKGMsaiksai5oYW5kbGVyLmd1aWR8fChqLmhhbmRsZXIuZ3VpZD1mLmd1aWQpKSxxLnB1c2goaiksZC5ldmVudC5nbG9iYWxbbl09ITB9Yz1udWxsfX0sZ2xvYmFsOnt9LHJlbW92ZTpmdW5jdGlvbihhLGMsZSxmKXtpZihhLm5vZGVUeXBlIT09MyYmYS5ub2RlVHlwZSE9PTgpe2U9PT0hMSYmKGU9eCk7dmFyIGcsaCxpLGosaz0wLGwsbSxuLG8scCxxLHIscz1kLmhhc0RhdGEoYSkmJmQuX2RhdGEoYSksdD1zJiZzLmV2ZW50cztpZighc3x8IXQpcmV0dXJuO2MmJmMudHlwZSYmKGU9Yy5oYW5kbGVyLGM9Yy50eXBlKTtpZighY3x8dHlwZW9mIGM9PT0ic3RyaW5nIiYmYy5jaGFyQXQoMCk9PT0iLiIpe2M9Y3x8IiI7Zm9yKGggaW4gdClkLmV2ZW50LnJlbW92ZShhLGgrYyk7cmV0dXJufWM9Yy5zcGxpdCgiICIpO3doaWxlKGg9Y1trKytdKXtyPWgscT1udWxsLGw9aC5pbmRleE9mKCIuIik8MCxtPVtdLGx8fChtPWguc3BsaXQoIi4iKSxoPW0uc2hpZnQoKSxuPW5ldyBSZWdFeHAoIihefFxcLikiK2QubWFwKG0uc2xpY2UoMCkuc29ydCgpLHcpLmpvaW4oIlxcLig/Oi4qXFwuKT8iKSsiKFxcLnwkKSIpKSxwPXRbaF07aWYoIXApY29udGludWU7aWYoIWUpe2ZvcihqPTA7ajxwLmxlbmd0aDtqKyspe3E9cFtqXTtpZihsfHxuLnRlc3QocS5uYW1lc3BhY2UpKWQuZXZlbnQucmVtb3ZlKGEscixxLmhhbmRsZXIsaikscC5zcGxpY2Uoai0tLDEpfWNvbnRpbnVlfW89ZC5ldmVudC5zcGVjaWFsW2hdfHx7fTtmb3Ioaj1mfHwwO2o8cC5sZW5ndGg7aisrKXtxPXBbal07aWYoZS5ndWlkPT09cS5ndWlkKXtpZihsfHxuLnRlc3QocS5uYW1lc3BhY2UpKWY9PW51bGwmJnAuc3BsaWNlKGotLSwxKSxvLnJlbW92ZSYmby5yZW1vdmUuY2FsbChhLHEpO2lmKGYhPW51bGwpYnJlYWt9fWlmKHAubGVuZ3RoPT09MHx8ZiE9bnVsbCYmcC5sZW5ndGg9PT0xKSghby50ZWFyZG93bnx8by50ZWFyZG93bi5jYWxsKGEsbSk9PT0hMSkmJmQucmVtb3ZlRXZlbnQoYSxoLHMuaGFuZGxlKSxnPW51bGwsZGVsZXRlIHRbaF19aWYoZC5pc0VtcHR5T2JqZWN0KHQpKXt2YXIgdT1zLmhhbmRsZTt1JiYodS5lbGVtPW51bGwpLGRlbGV0ZSBzLmV2ZW50cyxkZWxldGUgcy5oYW5kbGUsZC5pc0VtcHR5T2JqZWN0KHMpJiZkLnJlbW92ZURhdGEoYSxiLCEwKX19fSx0cmlnZ2VyOmZ1bmN0aW9uKGEsYyxlKXt2YXIgZj1hLnR5cGV8fGEsZz1hcmd1bWVudHNbM107aWYoIWcpe2E9dHlwZW9mIGE9PT0ib2JqZWN0Ij9hW2QuZXhwYW5kb10/YTpkLmV4dGVuZChkLkV2ZW50KGYpLGEpOmQuRXZlbnQoZiksZi5pbmRleE9mKCIhIik+PTAmJihhLnR5cGU9Zj1mLnNsaWNlKDAsLTEpLGEuZXhjbHVzaXZlPSEwKSxlfHwoYS5zdG9wUHJvcGFnYXRpb24oKSxkLmV2ZW50Lmdsb2JhbFtmXSYmZC5lYWNoKGQuY2FjaGUsZnVuY3Rpb24oKXt2YXIgYj1kLmV4cGFuZG8sZT10aGlzW2JdO2UmJmUuZXZlbnRzJiZlLmV2ZW50c1tmXSYmZC5ldmVudC50cmlnZ2VyKGEsYyxlLmhhbmRsZS5lbGVtKX0pKTtpZighZXx8ZS5ub2RlVHlwZT09PTN8fGUubm9kZVR5cGU9PT04KXJldHVybiBiO2EucmVzdWx0PWIsYS50YXJnZXQ9ZSxjPWQubWFrZUFycmF5KGMpLGMudW5zaGlmdChhKX1hLmN1cnJlbnRUYXJnZXQ9ZTt2YXIgaD1kLl9kYXRhKGUsImhhbmRsZSIpO2gmJmguYXBwbHkoZSxjKTt2YXIgaT1lLnBhcmVudE5vZGV8fGUub3duZXJEb2N1bWVudDt0cnl7ZSYmZS5ub2RlTmFtZSYmZC5ub0RhdGFbZS5ub2RlTmFtZS50b0xvd2VyQ2FzZSgpXXx8ZVsib24iK2ZdJiZlWyJvbiIrZl0uYXBwbHkoZSxjKT09PSExJiYoYS5yZXN1bHQ9ITEsYS5wcmV2ZW50RGVmYXVsdCgpKX1jYXRjaChqKXt9aWYoIWEuaXNQcm9wYWdhdGlvblN0b3BwZWQoKSYmaSlkLmV2ZW50LnRyaWdnZXIoYSxjLGksITApO2Vsc2UgaWYoIWEuaXNEZWZhdWx0UHJldmVudGVkKCkpe3ZhciBrLGw9YS50YXJnZXQsbT1mLnJlcGxhY2UociwiIiksbj1kLm5vZGVOYW1lKGwsImEiKSYmbT09PSJjbGljayIsbz1kLmV2ZW50LnNwZWNpYWxbbV18fHt9O2lmKCghby5fZGVmYXVsdHx8by5fZGVmYXVsdC5jYWxsKGUsYSk9PT0hMSkmJiFuJiYhKGwmJmwubm9kZU5hbWUmJmQubm9EYXRhW2wubm9kZU5hbWUudG9Mb3dlckNhc2UoKV0pKXt0cnl7bFttXSYmKGs9bFsib24iK21dLGsmJihsWyJvbiIrbV09bnVsbCksZC5ldmVudC50cmlnZ2VyZWQ9YS50eXBlLGxbbV0oKSl9Y2F0Y2gocCl7fWsmJihsWyJvbiIrbV09ayksZC5ldmVudC50cmlnZ2VyZWQ9Yn19fSxoYW5kbGU6ZnVuY3Rpb24oYyl7dmFyIGUsZixnLGgsaSxqPVtdLGs9ZC5tYWtlQXJyYXkoYXJndW1lbnRzKTtjPWtbMF09ZC5ldmVudC5maXgoY3x8YS5ldmVudCksYy5jdXJyZW50VGFyZ2V0PXRoaXMsZT1jLnR5cGUuaW5kZXhPZigiLiIpPDAmJiFjLmV4Y2x1c2l2ZSxlfHwoZz1jLnR5cGUuc3BsaXQoIi4iKSxjLnR5cGU9Zy5zaGlmdCgpLGo9Zy5zbGljZSgwKS5zb3J0KCksaD1uZXcgUmVnRXhwKCIoXnxcXC4pIitqLmpvaW4oIlxcLig/Oi4qXFwuKT8iKSsiKFxcLnwkKSIpKSxjLm5hbWVzcGFjZT1jLm5hbWVzcGFjZXx8ai5qb2luKCIuIiksaT1kLl9kYXRhKHRoaXMsImV2ZW50cyIpLGY9KGl8fHt9KVtjLnR5cGVdO2lmKGkmJmYpe2Y9Zi5zbGljZSgwKTtmb3IodmFyIGw9MCxtPWYubGVuZ3RoO2w8bTtsKyspe3ZhciBuPWZbbF07aWYoZXx8aC50ZXN0KG4ubmFtZXNwYWNlKSl7Yy5oYW5kbGVyPW4uaGFuZGxlcixjLmRhdGE9bi5kYXRhLGMuaGFuZGxlT2JqPW47dmFyIG89bi5oYW5kbGVyLmFwcGx5KHRoaXMsayk7byE9PWImJihjLnJlc3VsdD1vLG89PT0hMSYmKGMucHJldmVudERlZmF1bHQoKSxjLnN0b3BQcm9wYWdhdGlvbigpKSk7aWYoYy5pc0ltbWVkaWF0ZVByb3BhZ2F0aW9uU3RvcHBlZCgpKWJyZWFrfX19cmV0dXJuIGMucmVzdWx0fSxwcm9wczoiYWx0S2V5IGF0dHJDaGFuZ2UgYXR0ck5hbWUgYnViYmxlcyBidXR0b24gY2FuY2VsYWJsZSBjaGFyQ29kZSBjbGllbnRYIGNsaWVudFkgY3RybEtleSBjdXJyZW50VGFyZ2V0IGRhdGEgZGV0YWlsIGV2ZW50UGhhc2UgZnJvbUVsZW1lbnQgaGFuZGxlciBrZXlDb2RlIGxheWVyWCBsYXllclkgbWV0YUtleSBuZXdWYWx1ZSBvZmZzZXRYIG9mZnNldFkgcGFnZVggcGFnZVkgcHJldlZhbHVlIHJlbGF0ZWROb2RlIHJlbGF0ZWRUYXJnZXQgc2NyZWVuWCBzY3JlZW5ZIHNoaWZ0S2V5IHNyY0VsZW1lbnQgdGFyZ2V0IHRvRWxlbWVudCB2aWV3IHdoZWVsRGVsdGEgd2hpY2giLnNwbGl0KCIgIiksZml4OmZ1bmN0aW9uKGEpe2lmKGFbZC5leHBhbmRvXSlyZXR1cm4gYTt2YXIgZT1hO2E9ZC5FdmVudChlKTtmb3IodmFyIGY9dGhpcy5wcm9wcy5sZW5ndGgsZztmOylnPXRoaXMucHJvcHNbLS1mXSxhW2ddPWVbZ107YS50YXJnZXR8fChhLnRhcmdldD1hLnNyY0VsZW1lbnR8fGMpLGEudGFyZ2V0Lm5vZGVUeXBlPT09MyYmKGEudGFyZ2V0PWEudGFyZ2V0LnBhcmVudE5vZGUpLCFhLnJlbGF0ZWRUYXJnZXQmJmEuZnJvbUVsZW1lbnQmJihhLnJlbGF0ZWRUYXJnZXQ9YS5mcm9tRWxlbWVudD09PWEudGFyZ2V0P2EudG9FbGVtZW50OmEuZnJvbUVsZW1lbnQpO2lmKGEucGFnZVg9PW51bGwmJmEuY2xpZW50WCE9bnVsbCl7dmFyIGg9Yy5kb2N1bWVudEVsZW1lbnQsaT1jLmJvZHk7YS5wYWdlWD1hLmNsaWVudFgrKGgmJmguc2Nyb2xsTGVmdHx8aSYmaS5zY3JvbGxMZWZ0fHwwKS0oaCYmaC5jbGllbnRMZWZ0fHxpJiZpLmNsaWVudExlZnR8fDApLGEucGFnZVk9YS5jbGllbnRZKyhoJiZoLnNjcm9sbFRvcHx8aSYmaS5zY3JvbGxUb3B8fDApLShoJiZoLmNsaWVudFRvcHx8aSYmaS5jbGllbnRUb3B8fDApfWEud2hpY2g9PW51bGwmJihhLmNoYXJDb2RlIT1udWxsfHxhLmtleUNvZGUhPW51bGwpJiYoYS53aGljaD1hLmNoYXJDb2RlIT1udWxsP2EuY2hhckNvZGU6YS5rZXlDb2RlKSwhYS5tZXRhS2V5JiZhLmN0cmxLZXkmJihhLm1ldGFLZXk9YS5jdHJsS2V5KSwhYS53aGljaCYmYS5idXR0b24hPT1iJiYoYS53aGljaD1hLmJ1dHRvbiYxPzE6YS5idXR0b24mMj8zOmEuYnV0dG9uJjQ/MjowKTtyZXR1cm4gYX0sZ3VpZDoxZTgscHJveHk6ZC5wcm94eSxzcGVjaWFsOntyZWFkeTp7c2V0dXA6ZC5iaW5kUmVhZHksdGVhcmRvd246ZC5ub29wfSxsaXZlOnthZGQ6ZnVuY3Rpb24oYSl7ZC5ldmVudC5hZGQodGhpcyxIKGEub3JpZ1R5cGUsYS5zZWxlY3RvciksZC5leHRlbmQoe30sYSx7aGFuZGxlcjpHLGd1aWQ6YS5oYW5kbGVyLmd1aWR9KSl9LHJlbW92ZTpmdW5jdGlvbihhKXtkLmV2ZW50LnJlbW92ZSh0aGlzLEgoYS5vcmlnVHlwZSxhLnNlbGVjdG9yKSxhKX19LGJlZm9yZXVubG9hZDp7c2V0dXA6ZnVuY3Rpb24oYSxiLGMpe2QuaXNXaW5kb3codGhpcykmJih0aGlzLm9uYmVmb3JldW5sb2FkPWMpfSx0ZWFyZG93bjpmdW5jdGlvbihhLGIpe3RoaXMub25iZWZvcmV1bmxvYWQ9PT1iJiYodGhpcy5vbmJlZm9yZXVubG9hZD1udWxsKX19fX0sZC5yZW1vdmVFdmVudD1jLnJlbW92ZUV2ZW50TGlzdGVuZXI/ZnVuY3Rpb24oYSxiLGMpe2EucmVtb3ZlRXZlbnRMaXN0ZW5lciYmYS5yZW1vdmVFdmVudExpc3RlbmVyKGIsYywhMSl9OmZ1bmN0aW9uKGEsYixjKXthLmRldGFjaEV2ZW50JiZhLmRldGFjaEV2ZW50KCJvbiIrYixjKX0sZC5FdmVudD1mdW5jdGlvbihhKXtpZighdGhpcy5wcmV2ZW50RGVmYXVsdClyZXR1cm4gbmV3IGQuRXZlbnQoYSk7YSYmYS50eXBlPyh0aGlzLm9yaWdpbmFsRXZlbnQ9YSx0aGlzLnR5cGU9YS50eXBlLHRoaXMuaXNEZWZhdWx0UHJldmVudGVkPWEuZGVmYXVsdFByZXZlbnRlZHx8YS5yZXR1cm5WYWx1ZT09PSExfHxhLmdldFByZXZlbnREZWZhdWx0JiZhLmdldFByZXZlbnREZWZhdWx0KCk/eTp4KTp0aGlzLnR5cGU9YSx0aGlzLnRpbWVTdGFtcD1kLm5vdygpLHRoaXNbZC5leHBhbmRvXT0hMH0sZC5FdmVudC5wcm90b3R5cGU9e3ByZXZlbnREZWZhdWx0OmZ1bmN0aW9uKCl7dGhpcy5pc0RlZmF1bHRQcmV2ZW50ZWQ9eTt2YXIgYT10aGlzLm9yaWdpbmFsRXZlbnQ7YSYmKGEucHJldmVudERlZmF1bHQ/YS5wcmV2ZW50RGVmYXVsdCgpOmEucmV0dXJuVmFsdWU9ITEpfSxzdG9wUHJvcGFnYXRpb246ZnVuY3Rpb24oKXt0aGlzLmlzUHJvcGFnYXRpb25TdG9wcGVkPXk7dmFyIGE9dGhpcy5vcmlnaW5hbEV2ZW50O2EmJihhLnN0b3BQcm9wYWdhdGlvbiYmYS5zdG9wUHJvcGFnYXRpb24oKSxhLmNhbmNlbEJ1YmJsZT0hMCl9LHN0b3BJbW1lZGlhdGVQcm9wYWdhdGlvbjpmdW5jdGlvbigpe3RoaXMuaXNJbW1lZGlhdGVQcm9wYWdhdGlvblN0b3BwZWQ9eSx0aGlzLnN0b3BQcm9wYWdhdGlvbigpfSxpc0RlZmF1bHRQcmV2ZW50ZWQ6eCxpc1Byb3BhZ2F0aW9uU3RvcHBlZDp4LGlzSW1tZWRpYXRlUHJvcGFnYXRpb25TdG9wcGVkOnh9O3ZhciB6PWZ1bmN0aW9uKGEpe3ZhciBiPWEucmVsYXRlZFRhcmdldDt0cnl7aWYoYiYmYiE9PWMmJiFiLnBhcmVudE5vZGUpcmV0dXJuO3doaWxlKGImJmIhPT10aGlzKWI9Yi5wYXJlbnROb2RlO2IhPT10aGlzJiYoYS50eXBlPWEuZGF0YSxkLmV2ZW50LmhhbmRsZS5hcHBseSh0aGlzLGFyZ3VtZW50cykpfWNhdGNoKGUpe319LEE9ZnVuY3Rpb24oYSl7YS50eXBlPWEuZGF0YSxkLmV2ZW50LmhhbmRsZS5hcHBseSh0aGlzLGFyZ3VtZW50cyl9O2QuZWFjaCh7bW91c2VlbnRlcjoibW91c2VvdmVyIixtb3VzZWxlYXZlOiJtb3VzZW91dCJ9LGZ1bmN0aW9uKGEsYil7ZC5ldmVudC5zcGVjaWFsW2FdPXtzZXR1cDpmdW5jdGlvbihjKXtkLmV2ZW50LmFkZCh0aGlzLGIsYyYmYy5zZWxlY3Rvcj9BOnosYSl9LHRlYXJkb3duOmZ1bmN0aW9uKGEpe2QuZXZlbnQucmVtb3ZlKHRoaXMsYixhJiZhLnNlbGVjdG9yP0E6eil9fX0pLGQuc3VwcG9ydC5zdWJtaXRCdWJibGVzfHwoZC5ldmVudC5zcGVjaWFsLnN1Ym1pdD17c2V0dXA6ZnVuY3Rpb24oYSxiKXtpZih0aGlzLm5vZGVOYW1lJiZ0aGlzLm5vZGVOYW1lLnRvTG93ZXJDYXNlKCkhPT0iZm9ybSIpZC5ldmVudC5hZGQodGhpcywiY2xpY2suc3BlY2lhbFN1Ym1pdCIsZnVuY3Rpb24oYSl7dmFyIGI9YS50YXJnZXQsYz1iLnR5cGU7KGM9PT0ic3VibWl0Inx8Yz09PSJpbWFnZSIpJiZkKGIpLmNsb3Nlc3QoImZvcm0iKS5sZW5ndGgmJkUoInN1Ym1pdCIsdGhpcyxhcmd1bWVudHMpfSksZC5ldmVudC5hZGQodGhpcywia2V5cHJlc3Muc3BlY2lhbFN1Ym1pdCIsZnVuY3Rpb24oYSl7dmFyIGI9YS50YXJnZXQsYz1iLnR5cGU7KGM9PT0idGV4dCJ8fGM9PT0icGFzc3dvcmQiKSYmZChiKS5jbG9zZXN0KCJmb3JtIikubGVuZ3RoJiZhLmtleUNvZGU9PT0xMyYmRSgic3VibWl0Iix0aGlzLGFyZ3VtZW50cyl9KTtlbHNlIHJldHVybiExfSx0ZWFyZG93bjpmdW5jdGlvbihhKXtkLmV2ZW50LnJlbW92ZSh0aGlzLCIuc3BlY2lhbFN1Ym1pdCIpfX0pO2lmKCFkLnN1cHBvcnQuY2hhbmdlQnViYmxlcyl7dmFyIEIsQz1mdW5jdGlvbihhKXt2YXIgYj1hLnR5cGUsYz1hLnZhbHVlO2I9PT0icmFkaW8ifHxiPT09ImNoZWNrYm94Ij9jPWEuY2hlY2tlZDpiPT09InNlbGVjdC1tdWx0aXBsZSI/Yz1hLnNlbGVjdGVkSW5kZXg+LTE/ZC5tYXAoYS5vcHRpb25zLGZ1bmN0aW9uKGEpe3JldHVybiBhLnNlbGVjdGVkfSkuam9pbigiLSIpOiIiOmEubm9kZU5hbWUudG9Mb3dlckNhc2UoKT09PSJzZWxlY3QiJiYoYz1hLnNlbGVjdGVkSW5kZXgpO3JldHVybiBjfSxEPWZ1bmN0aW9uIEQoYSl7dmFyIGM9YS50YXJnZXQsZSxmO2lmKHMudGVzdChjLm5vZGVOYW1lKSYmIWMucmVhZE9ubHkpe2U9ZC5fZGF0YShjLCJfY2hhbmdlX2RhdGEiKSxmPUMoYyksKGEudHlwZSE9PSJmb2N1c291dCJ8fGMudHlwZSE9PSJyYWRpbyIpJiZkLl9kYXRhKGMsIl9jaGFuZ2VfZGF0YSIsZik7aWYoZT09PWJ8fGY9PT1lKXJldHVybjtpZihlIT1udWxsfHxmKWEudHlwZT0iY2hhbmdlIixhLmxpdmVGaXJlZD1iLGQuZXZlbnQudHJpZ2dlcihhLGFyZ3VtZW50c1sxXSxjKX19O2QuZXZlbnQuc3BlY2lhbC5jaGFuZ2U9e2ZpbHRlcnM6e2ZvY3Vzb3V0OkQsYmVmb3JlZGVhY3RpdmF0ZTpELGNsaWNrOmZ1bmN0aW9uKGEpe3ZhciBiPWEudGFyZ2V0LGM9Yi50eXBlOyhjPT09InJhZGlvInx8Yz09PSJjaGVja2JveCJ8fGIubm9kZU5hbWUudG9Mb3dlckNhc2UoKT09PSJzZWxlY3QiKSYmRC5jYWxsKHRoaXMsYSl9LGtleWRvd246ZnVuY3Rpb24oYSl7dmFyIGI9YS50YXJnZXQsYz1iLnR5cGU7KGEua2V5Q29kZT09PTEzJiZiLm5vZGVOYW1lLnRvTG93ZXJDYXNlKCkhPT0idGV4dGFyZWEifHxhLmtleUNvZGU9PT0zMiYmKGM9PT0iY2hlY2tib3gifHxjPT09InJhZGlvIil8fGM9PT0ic2VsZWN0LW11bHRpcGxlIikmJkQuY2FsbCh0aGlzLGEpfSxiZWZvcmVhY3RpdmF0ZTpmdW5jdGlvbihhKXt2YXIgYj1hLnRhcmdldDtkLl9kYXRhKGIsIl9jaGFuZ2VfZGF0YSIsQyhiKSl9fSxzZXR1cDpmdW5jdGlvbihhLGIpe2lmKHRoaXMudHlwZT09PSJmaWxlIilyZXR1cm4hMTtmb3IodmFyIGMgaW4gQilkLmV2ZW50LmFkZCh0aGlzLGMrIi5zcGVjaWFsQ2hhbmdlIixCW2NdKTtyZXR1cm4gcy50ZXN0KHRoaXMubm9kZU5hbWUpfSx0ZWFyZG93bjpmdW5jdGlvbihhKXtkLmV2ZW50LnJlbW92ZSh0aGlzLCIuc3BlY2lhbENoYW5nZSIpO3JldHVybiBzLnRlc3QodGhpcy5ub2RlTmFtZSl9fSxCPWQuZXZlbnQuc3BlY2lhbC5jaGFuZ2UuZmlsdGVycyxCLmZvY3VzPUIuYmVmb3JlYWN0aXZhdGV9Yy5hZGRFdmVudExpc3RlbmVyJiZkLmVhY2goe2ZvY3VzOiJmb2N1c2luIixibHVyOiJmb2N1c291dCJ9LGZ1bmN0aW9uKGEsYil7ZnVuY3Rpb24gZihhKXt2YXIgYz1kLmV2ZW50LmZpeChhKTtjLnR5cGU9YixjLm9yaWdpbmFsRXZlbnQ9e30sZC5ldmVudC50cmlnZ2VyKGMsbnVsbCxjLnRhcmdldCksYy5pc0RlZmF1bHRQcmV2ZW50ZWQoKSYmYS5wcmV2ZW50RGVmYXVsdCgpfXZhciBlPTA7ZC5ldmVudC5zcGVjaWFsW2JdPXtzZXR1cDpmdW5jdGlvbigpe2UrKz09PTAmJmMuYWRkRXZlbnRMaXN0ZW5lcihhLGYsITApfSx0ZWFyZG93bjpmdW5jdGlvbigpey0tZT09PTAmJmMucmVtb3ZlRXZlbnRMaXN0ZW5lcihhLGYsITApfX19KSxkLmVhY2goWyJiaW5kIiwib25lIl0sZnVuY3Rpb24oYSxjKXtkLmZuW2NdPWZ1bmN0aW9uKGEsZSxmKXtpZih0eXBlb2YgYT09PSJvYmplY3QiKXtmb3IodmFyIGcgaW4gYSl0aGlzW2NdKGcsZSxhW2ddLGYpO3JldHVybiB0aGlzfWlmKGQuaXNGdW5jdGlvbihlKXx8ZT09PSExKWY9ZSxlPWI7dmFyIGg9Yz09PSJvbmUiP2QucHJveHkoZixmdW5jdGlvbihhKXtkKHRoaXMpLnVuYmluZChhLGgpO3JldHVybiBmLmFwcGx5KHRoaXMsYXJndW1lbnRzKX0pOmY7aWYoYT09PSJ1bmxvYWQiJiZjIT09Im9uZSIpdGhpcy5vbmUoYSxlLGYpO2Vsc2UgZm9yKHZhciBpPTAsaj10aGlzLmxlbmd0aDtpPGo7aSsrKWQuZXZlbnQuYWRkKHRoaXNbaV0sYSxoLGUpO3JldHVybiB0aGlzfX0pLGQuZm4uZXh0ZW5kKHt1bmJpbmQ6ZnVuY3Rpb24oYSxiKXtpZih0eXBlb2YgYSE9PSJvYmplY3QifHxhLnByZXZlbnREZWZhdWx0KWZvcih2YXIgZT0wLGY9dGhpcy5sZW5ndGg7ZTxmO2UrKylkLmV2ZW50LnJlbW92ZSh0aGlzW2VdLGEsYik7ZWxzZSBmb3IodmFyIGMgaW4gYSl0aGlzLnVuYmluZChjLGFbY10pO3JldHVybiB0aGlzfSxkZWxlZ2F0ZTpmdW5jdGlvbihhLGIsYyxkKXtyZXR1cm4gdGhpcy5saXZlKGIsYyxkLGEpfSx1bmRlbGVnYXRlOmZ1bmN0aW9uKGEsYixjKXtyZXR1cm4gYXJndW1lbnRzLmxlbmd0aD09PTA/dGhpcy51bmJpbmQoImxpdmUiKTp0aGlzLmRpZShiLG51bGwsYyxhKX0sdHJpZ2dlcjpmdW5jdGlvbihhLGIpe3JldHVybiB0aGlzLmVhY2goZnVuY3Rpb24oKXtkLmV2ZW50LnRyaWdnZXIoYSxiLHRoaXMpfSl9LHRyaWdnZXJIYW5kbGVyOmZ1bmN0aW9uKGEsYil7aWYodGhpc1swXSl7dmFyIGM9ZC5FdmVudChhKTtjLnByZXZlbnREZWZhdWx0KCksYy5zdG9wUHJvcGFnYXRpb24oKSxkLmV2ZW50LnRyaWdnZXIoYyxiLHRoaXNbMF0pO3JldHVybiBjLnJlc3VsdH19LHRvZ2dsZTpmdW5jdGlvbihhKXt2YXIgYj1hcmd1bWVudHMsYz0xO3doaWxlKGM8Yi5sZW5ndGgpZC5wcm94eShhLGJbYysrXSk7cmV0dXJuIHRoaXMuY2xpY2soZC5wcm94eShhLGZ1bmN0aW9uKGUpe3ZhciBmPShkLl9kYXRhKHRoaXMsImxhc3RUb2dnbGUiK2EuZ3VpZCl8fDApJWM7ZC5fZGF0YSh0aGlzLCJsYXN0VG9nZ2xlIithLmd1aWQsZisxKSxlLnByZXZlbnREZWZhdWx0KCk7cmV0dXJuIGJbZl0uYXBwbHkodGhpcyxhcmd1bWVudHMpfHwhMX0pKX0saG92ZXI6ZnVuY3Rpb24oYSxiKXtyZXR1cm4gdGhpcy5tb3VzZWVudGVyKGEpLm1vdXNlbGVhdmUoYnx8YSl9fSk7dmFyIEY9e2ZvY3VzOiJmb2N1c2luIixibHVyOiJmb2N1c291dCIsbW91c2VlbnRlcjoibW91c2VvdmVyIixtb3VzZWxlYXZlOiJtb3VzZW91dCJ9O2QuZWFjaChbImxpdmUiLCJkaWUiXSxmdW5jdGlvbihhLGMpe2QuZm5bY109ZnVuY3Rpb24oYSxlLGYsZyl7dmFyIGgsaT0wLGosayxsLG09Z3x8dGhpcy5zZWxlY3RvcixuPWc/dGhpczpkKHRoaXMuY29udGV4dCk7aWYodHlwZW9mIGE9PT0ib2JqZWN0IiYmIWEucHJldmVudERlZmF1bHQpe2Zvcih2YXIgbyBpbiBhKW5bY10obyxlLGFbb10sbSk7cmV0dXJuIHRoaXN9ZC5pc0Z1bmN0aW9uKGUpJiYoZj1lLGU9YiksYT0oYXx8IiIpLnNwbGl0KCIgIik7d2hpbGUoKGg9YVtpKytdKSE9bnVsbCl7aj1yLmV4ZWMoaCksaz0iIixqJiYoaz1qWzBdLGg9aC5yZXBsYWNlKHIsIiIpKTtpZihoPT09ImhvdmVyIil7YS5wdXNoKCJtb3VzZWVudGVyIitrLCJtb3VzZWxlYXZlIitrKTtjb250aW51ZX1sPWgsaD09PSJmb2N1cyJ8fGg9PT0iYmx1ciI/KGEucHVzaChGW2hdK2spLGg9aCtrKTpoPShGW2hdfHxoKStrO2lmKGM9PT0ibGl2ZSIpZm9yKHZhciBwPTAscT1uLmxlbmd0aDtwPHE7cCsrKWQuZXZlbnQuYWRkKG5bcF0sImxpdmUuIitIKGgsbSkse2RhdGE6ZSxzZWxlY3RvcjptLGhhbmRsZXI6ZixvcmlnVHlwZTpoLG9yaWdIYW5kbGVyOmYscHJlVHlwZTpsfSk7ZWxzZSBuLnVuYmluZCgibGl2ZS4iK0goaCxtKSxmKX1yZXR1cm4gdGhpc319KSxkLmVhY2goImJsdXIgZm9jdXMgZm9jdXNpbiBmb2N1c291dCBsb2FkIHJlc2l6ZSBzY3JvbGwgdW5sb2FkIGNsaWNrIGRibGNsaWNrIG1vdXNlZG93biBtb3VzZXVwIG1vdXNlbW92ZSBtb3VzZW92ZXIgbW91c2VvdXQgbW91c2VlbnRlciBtb3VzZWxlYXZlIGNoYW5nZSBzZWxlY3Qgc3VibWl0IGtleWRvd24ga2V5cHJlc3Mga2V5dXAgZXJyb3IiLnNwbGl0KCIgIiksZnVuY3Rpb24oYSxiKXtkLmZuW2JdPWZ1bmN0aW9uKGEsYyl7Yz09bnVsbCYmKGM9YSxhPW51bGwpO3JldHVybiBhcmd1bWVudHMubGVuZ3RoPjA/dGhpcy5iaW5kKGIsYSxjKTp0aGlzLnRyaWdnZXIoYil9LGQuYXR0ckZuJiYoZC5hdHRyRm5bYl09ITApfSksZnVuY3Rpb24oKXtmdW5jdGlvbiB1KGEsYixjLGQsZSxmKXtmb3IodmFyIGc9MCxoPWQubGVuZ3RoO2c8aDtnKyspe3ZhciBpPWRbZ107aWYoaSl7dmFyIGo9ITE7aT1pW2FdO3doaWxlKGkpe2lmKGkuc2l6Y2FjaGU9PT1jKXtqPWRbaS5zaXpzZXRdO2JyZWFrfWlmKGkubm9kZVR5cGU9PT0xKXtmfHwoaS5zaXpjYWNoZT1jLGkuc2l6c2V0PWcpO2lmKHR5cGVvZiBiIT09InN0cmluZyIpe2lmKGk9PT1iKXtqPSEwO2JyZWFrfX1lbHNlIGlmKGsuZmlsdGVyKGIsW2ldKS5sZW5ndGg+MCl7aj1pO2JyZWFrfX1pPWlbYV19ZFtnXT1qfX19ZnVuY3Rpb24gdChhLGIsYyxkLGUsZil7Zm9yKHZhciBnPTAsaD1kLmxlbmd0aDtnPGg7ZysrKXt2YXIgaT1kW2ddO2lmKGkpe3ZhciBqPSExO2k9aVthXTt3aGlsZShpKXtpZihpLnNpemNhY2hlPT09Yyl7aj1kW2kuc2l6c2V0XTticmVha31pLm5vZGVUeXBlPT09MSYmIWYmJihpLnNpemNhY2hlPWMsaS5zaXpzZXQ9Zyk7aWYoaS5ub2RlTmFtZS50b0xvd2VyQ2FzZSgpPT09Yil7aj1pO2JyZWFrfWk9aVthXX1kW2ddPWp9fX12YXIgYT0vKCg/OlwoKD86XChbXigpXStcKXxbXigpXSspK1wpfFxbKD86XFtbXlxbXF1dKlxdfFsnIl1bXiciXSpbJyJdfFteXFtcXSciXSspK1xdfFxcLnxbXiA+K34sKFxbXFxdKykrfFs+K35dKShccyosXHMqKT8oKD86LnxccnxcbikqKS9nLGU9MCxmPU9iamVjdC5wcm90b3R5cGUudG9TdHJpbmcsZz0hMSxoPSEwLGk9L1xcL2csaj0vXFcvO1swLDBdLnNvcnQoZnVuY3Rpb24oKXtoPSExO3JldHVybiAwfSk7dmFyIGs9ZnVuY3Rpb24oYixkLGUsZyl7ZT1lfHxbXSxkPWR8fGM7dmFyIGg9ZDtpZihkLm5vZGVUeXBlIT09MSYmZC5ub2RlVHlwZSE9PTkpcmV0dXJuW107aWYoIWJ8fHR5cGVvZiBiIT09InN0cmluZyIpcmV0dXJuIGU7dmFyIGksaixuLG8scSxyLHMsdCx1PSEwLHc9ay5pc1hNTChkKSx4PVtdLHk9Yjtkb3thLmV4ZWMoIiIpLGk9YS5leGVjKHkpO2lmKGkpe3k9aVszXSx4LnB1c2goaVsxXSk7aWYoaVsyXSl7bz1pWzNdO2JyZWFrfX19d2hpbGUoaSk7aWYoeC5sZW5ndGg+MSYmbS5leGVjKGIpKWlmKHgubGVuZ3RoPT09MiYmbC5yZWxhdGl2ZVt4WzBdXSlqPXYoeFswXSt4WzFdLGQpO2Vsc2V7aj1sLnJlbGF0aXZlW3hbMF1dP1tkXTprKHguc2hpZnQoKSxkKTt3aGlsZSh4Lmxlbmd0aCliPXguc2hpZnQoKSxsLnJlbGF0aXZlW2JdJiYoYis9eC5zaGlmdCgpKSxqPXYoYixqKX1lbHNleyFnJiZ4Lmxlbmd0aD4xJiZkLm5vZGVUeXBlPT09OSYmIXcmJmwubWF0Y2guSUQudGVzdCh4WzBdKSYmIWwubWF0Y2guSUQudGVzdCh4W3gubGVuZ3RoLTFdKSYmKHE9ay5maW5kKHguc2hpZnQoKSxkLHcpLGQ9cS5leHByP2suZmlsdGVyKHEuZXhwcixxLnNldClbMF06cS5zZXRbMF0pO2lmKGQpe3E9Zz97ZXhwcjp4LnBvcCgpLHNldDpwKGcpfTprLmZpbmQoeC5wb3AoKSx4Lmxlbmd0aD09PTEmJih4WzBdPT09In4ifHx4WzBdPT09IisiKSYmZC5wYXJlbnROb2RlP2QucGFyZW50Tm9kZTpkLHcpLGo9cS5leHByP2suZmlsdGVyKHEuZXhwcixxLnNldCk6cS5zZXQseC5sZW5ndGg+MD9uPXAoaik6dT0hMTt3aGlsZSh4Lmxlbmd0aClyPXgucG9wKCkscz1yLGwucmVsYXRpdmVbcl0/cz14LnBvcCgpOnI9IiIscz09bnVsbCYmKHM9ZCksbC5yZWxhdGl2ZVtyXShuLHMsdyl9ZWxzZSBuPXg9W119bnx8KG49aiksbnx8ay5lcnJvcihyfHxiKTtpZihmLmNhbGwobik9PT0iW29iamVjdCBBcnJheV0iKWlmKHUpaWYoZCYmZC5ub2RlVHlwZT09PTEpZm9yKHQ9MDtuW3RdIT1udWxsO3QrKyluW3RdJiYoblt0XT09PSEwfHxuW3RdLm5vZGVUeXBlPT09MSYmay5jb250YWlucyhkLG5bdF0pKSYmZS5wdXNoKGpbdF0pO2Vsc2UgZm9yKHQ9MDtuW3RdIT1udWxsO3QrKyluW3RdJiZuW3RdLm5vZGVUeXBlPT09MSYmZS5wdXNoKGpbdF0pO2Vsc2UgZS5wdXNoLmFwcGx5KGUsbik7ZWxzZSBwKG4sZSk7byYmKGsobyxoLGUsZyksay51bmlxdWVTb3J0KGUpKTtyZXR1cm4gZX07ay51bmlxdWVTb3J0PWZ1bmN0aW9uKGEpe2lmKHIpe2c9aCxhLnNvcnQocik7aWYoZylmb3IodmFyIGI9MTtiPGEubGVuZ3RoO2IrKylhW2JdPT09YVtiLTFdJiZhLnNwbGljZShiLS0sMSl9cmV0dXJuIGF9LGsubWF0Y2hlcz1mdW5jdGlvbihhLGIpe3JldHVybiBrKGEsbnVsbCxudWxsLGIpfSxrLm1hdGNoZXNTZWxlY3Rvcj1mdW5jdGlvbihhLGIpe3JldHVybiBrKGIsbnVsbCxudWxsLFthXSkubGVuZ3RoPjB9LGsuZmluZD1mdW5jdGlvbihhLGIsYyl7dmFyIGQ7aWYoIWEpcmV0dXJuW107Zm9yKHZhciBlPTAsZj1sLm9yZGVyLmxlbmd0aDtlPGY7ZSsrKXt2YXIgZyxoPWwub3JkZXJbZV07aWYoZz1sLmxlZnRNYXRjaFtoXS5leGVjKGEpKXt2YXIgaj1nWzFdO2cuc3BsaWNlKDEsMSk7aWYoai5zdWJzdHIoai5sZW5ndGgtMSkhPT0iXFwiKXtnWzFdPShnWzFdfHwiIikucmVwbGFjZShpLCIiKSxkPWwuZmluZFtoXShnLGIsYyk7aWYoZCE9bnVsbCl7YT1hLnJlcGxhY2UobC5tYXRjaFtoXSwiIik7YnJlYWt9fX19ZHx8KGQ9dHlwZW9mIGIuZ2V0RWxlbWVudHNCeVRhZ05hbWUhPT0idW5kZWZpbmVkIj9iLmdldEVsZW1lbnRzQnlUYWdOYW1lKCIqIik6W10pO3JldHVybntzZXQ6ZCxleHByOmF9fSxrLmZpbHRlcj1mdW5jdGlvbihhLGMsZCxlKXt2YXIgZixnLGg9YSxpPVtdLGo9YyxtPWMmJmNbMF0mJmsuaXNYTUwoY1swXSk7d2hpbGUoYSYmYy5sZW5ndGgpe2Zvcih2YXIgbiBpbiBsLmZpbHRlcilpZigoZj1sLmxlZnRNYXRjaFtuXS5leGVjKGEpKSE9bnVsbCYmZlsyXSl7dmFyIG8scCxxPWwuZmlsdGVyW25dLHI9ZlsxXTtnPSExLGYuc3BsaWNlKDEsMSk7aWYoci5zdWJzdHIoci5sZW5ndGgtMSk9PT0iXFwiKWNvbnRpbnVlO2o9PT1pJiYoaT1bXSk7aWYobC5wcmVGaWx0ZXJbbl0pe2Y9bC5wcmVGaWx0ZXJbbl0oZixqLGQsaSxlLG0pO2lmKGYpe2lmKGY9PT0hMCljb250aW51ZX1lbHNlIGc9bz0hMH1pZihmKWZvcih2YXIgcz0wOyhwPWpbc10pIT1udWxsO3MrKylpZihwKXtvPXEocCxmLHMsaik7dmFyIHQ9ZV4hIW87ZCYmbyE9bnVsbD90P2c9ITA6altzXT0hMTp0JiYoaS5wdXNoKHApLGc9ITApfWlmKG8hPT1iKXtkfHwoaj1pKSxhPWEucmVwbGFjZShsLm1hdGNoW25dLCIiKTtpZighZylyZXR1cm5bXTticmVha319aWYoYT09PWgpaWYoZz09bnVsbClrLmVycm9yKGEpO2Vsc2UgYnJlYWs7aD1hfXJldHVybiBqfSxrLmVycm9yPWZ1bmN0aW9uKGEpe3Rocm93IlN5bnRheCBlcnJvciwgdW5yZWNvZ25pemVkIGV4cHJlc3Npb246ICIrYX07dmFyIGw9ay5zZWxlY3RvcnM9e29yZGVyOlsiSUQiLCJOQU1FIiwiVEFHIl0sbWF0Y2g6e0lEOi8jKCg/Oltcd1x1MDBjMC1cdUZGRkZcLV18XFwuKSspLyxDTEFTUzovXC4oKD86W1x3XHUwMGMwLVx1RkZGRlwtXXxcXC4pKykvLE5BTUU6L1xbbmFtZT1bJyJdKigoPzpbXHdcdTAwYzAtXHVGRkZGXC1dfFxcLikrKVsnIl0qXF0vLEFUVFI6L1xbXHMqKCg/Oltcd1x1MDBjMC1cdUZGRkZcLV18XFwuKSspXHMqKD86KFxTPz0pXHMqKD86KFsnIl0pKC4qPylcM3woIz8oPzpbXHdcdTAwYzAtXHVGRkZGXC1dfFxcLikqKXwpfClccypcXS8sVEFHOi9eKCg/Oltcd1x1MDBjMC1cdUZGRkZcKlwtXXxcXC4pKykvLENISUxEOi86KG9ubHl8bnRofGxhc3R8Zmlyc3QpLWNoaWxkKD86XChccyooZXZlbnxvZGR8KD86WytcLV0/XGQrfCg/OlsrXC1dP1xkKik/blxzKig/OlsrXC1dXHMqXGQrKT8pKVxzKlwpKT8vLFBPUzovOihudGh8ZXF8Z3R8bHR8Zmlyc3R8bGFzdHxldmVufG9kZCkoPzpcKChcZCopXCkpPyg/PVteXC1dfCQpLyxQU0VVRE86LzooKD86W1x3XHUwMGMwLVx1RkZGRlwtXXxcXC4pKykoPzpcKChbJyJdPykoKD86XChbXlwpXStcKXxbXlwoXCldKikrKVwyXCkpPy99LGxlZnRNYXRjaDp7fSxhdHRyTWFwOnsiY2xhc3MiOiJjbGFzc05hbWUiLCJmb3IiOiJodG1sRm9yIn0sYXR0ckhhbmRsZTp7aHJlZjpmdW5jdGlvbihhKXtyZXR1cm4gYS5nZXRBdHRyaWJ1dGUoImhyZWYiKX0sdHlwZTpmdW5jdGlvbihhKXtyZXR1cm4gYS5nZXRBdHRyaWJ1dGUoInR5cGUiKX19LHJlbGF0aXZlOnsiKyI6ZnVuY3Rpb24oYSxiKXt2YXIgYz10eXBlb2YgYj09PSJzdHJpbmciLGQ9YyYmIWoudGVzdChiKSxlPWMmJiFkO2QmJihiPWIudG9Mb3dlckNhc2UoKSk7Zm9yKHZhciBmPTAsZz1hLmxlbmd0aCxoO2Y8ZztmKyspaWYoaD1hW2ZdKXt3aGlsZSgoaD1oLnByZXZpb3VzU2libGluZykmJmgubm9kZVR5cGUhPT0xKXt9YVtmXT1lfHxoJiZoLm5vZGVOYW1lLnRvTG93ZXJDYXNlKCk9PT1iP2h8fCExOmg9PT1ifWUmJmsuZmlsdGVyKGIsYSwhMCl9LCI+IjpmdW5jdGlvbihhLGIpe3ZhciBjLGQ9dHlwZW9mIGI9PT0ic3RyaW5nIixlPTAsZj1hLmxlbmd0aDtpZihkJiYhai50ZXN0KGIpKXtiPWIudG9Mb3dlckNhc2UoKTtmb3IoO2U8ZjtlKyspe2M9YVtlXTtpZihjKXt2YXIgZz1jLnBhcmVudE5vZGU7YVtlXT1nLm5vZGVOYW1lLnRvTG93ZXJDYXNlKCk9PT1iP2c6ITF9fX1lbHNle2Zvcig7ZTxmO2UrKyljPWFbZV0sYyYmKGFbZV09ZD9jLnBhcmVudE5vZGU6Yy5wYXJlbnROb2RlPT09Yik7ZCYmay5maWx0ZXIoYixhLCEwKX19LCIiOmZ1bmN0aW9uKGEsYixjKXt2YXIgZCxmPWUrKyxnPXU7dHlwZW9mIGI9PT0ic3RyaW5nIiYmIWoudGVzdChiKSYmKGI9Yi50b0xvd2VyQ2FzZSgpLGQ9YixnPXQpLGcoInBhcmVudE5vZGUiLGIsZixhLGQsYyl9LCJ+IjpmdW5jdGlvbihhLGIsYyl7dmFyIGQsZj1lKyssZz11O3R5cGVvZiBiPT09InN0cmluZyImJiFqLnRlc3QoYikmJihiPWIudG9Mb3dlckNhc2UoKSxkPWIsZz10KSxnKCJwcmV2aW91c1NpYmxpbmciLGIsZixhLGQsYyl9fSxmaW5kOntJRDpmdW5jdGlvbihhLGIsYyl7aWYodHlwZW9mIGIuZ2V0RWxlbWVudEJ5SWQhPT0idW5kZWZpbmVkIiYmIWMpe3ZhciBkPWIuZ2V0RWxlbWVudEJ5SWQoYVsxXSk7cmV0dXJuIGQmJmQucGFyZW50Tm9kZT9bZF06W119fSxOQU1FOmZ1bmN0aW9uKGEsYil7aWYodHlwZW9mIGIuZ2V0RWxlbWVudHNCeU5hbWUhPT0idW5kZWZpbmVkIil7dmFyIGM9W10sZD1iLmdldEVsZW1lbnRzQnlOYW1lKGFbMV0pO2Zvcih2YXIgZT0wLGY9ZC5sZW5ndGg7ZTxmO2UrKylkW2VdLmdldEF0dHJpYnV0ZSgibmFtZSIpPT09YVsxXSYmYy5wdXNoKGRbZV0pO3JldHVybiBjLmxlbmd0aD09PTA/bnVsbDpjfX0sVEFHOmZ1bmN0aW9uKGEsYil7aWYodHlwZW9mIGIuZ2V0RWxlbWVudHNCeVRhZ05hbWUhPT0idW5kZWZpbmVkIilyZXR1cm4gYi5nZXRFbGVtZW50c0J5VGFnTmFtZShhWzFdKX19LHByZUZpbHRlcjp7Q0xBU1M6ZnVuY3Rpb24oYSxiLGMsZCxlLGYpe2E9IiAiK2FbMV0ucmVwbGFjZShpLCIiKSsiICI7aWYoZilyZXR1cm4gYTtmb3IodmFyIGc9MCxoOyhoPWJbZ10pIT1udWxsO2crKyloJiYoZV4oaC5jbGFzc05hbWUmJigiICIraC5jbGFzc05hbWUrIiAiKS5yZXBsYWNlKC9bXHRcblxyXS9nLCIgIikuaW5kZXhPZihhKT49MCk/Y3x8ZC5wdXNoKGgpOmMmJihiW2ddPSExKSk7cmV0dXJuITF9LElEOmZ1bmN0aW9uKGEpe3JldHVybiBhWzFdLnJlcGxhY2UoaSwiIil9LFRBRzpmdW5jdGlvbihhLGIpe3JldHVybiBhWzFdLnJlcGxhY2UoaSwiIikudG9Mb3dlckNhc2UoKX0sQ0hJTEQ6ZnVuY3Rpb24oYSl7aWYoYVsxXT09PSJudGgiKXthWzJdfHxrLmVycm9yKGFbMF0pLGFbMl09YVsyXS5yZXBsYWNlKC9eXCt8XHMqL2csIiIpO3ZhciBiPS8oLT8pKFxkKikoPzpuKFsrXC1dP1xkKikpPy8uZXhlYyhhWzJdPT09ImV2ZW4iJiYiMm4ifHxhWzJdPT09Im9kZCImJiIybisxInx8IS9cRC8udGVzdChhWzJdKSYmIjBuKyIrYVsyXXx8YVsyXSk7YVsyXT1iWzFdKyhiWzJdfHwxKS0wLGFbM109YlszXS0wfWVsc2UgYVsyXSYmay5lcnJvcihhWzBdKTthWzBdPWUrKztyZXR1cm4gYX0sQVRUUjpmdW5jdGlvbihhLGIsYyxkLGUsZil7dmFyIGc9YVsxXT1hWzFdLnJlcGxhY2UoaSwiIik7IWYmJmwuYXR0ck1hcFtnXSYmKGFbMV09bC5hdHRyTWFwW2ddKSxhWzRdPShhWzRdfHxhWzVdfHwiIikucmVwbGFjZShpLCIiKSxhWzJdPT09In49IiYmKGFbNF09IiAiK2FbNF0rIiAiKTtyZXR1cm4gYX0sUFNFVURPOmZ1bmN0aW9uKGIsYyxkLGUsZil7aWYoYlsxXT09PSJub3QiKWlmKChhLmV4ZWMoYlszXSl8fCIiKS5sZW5ndGg+MXx8L15cdy8udGVzdChiWzNdKSliWzNdPWsoYlszXSxudWxsLG51bGwsYyk7ZWxzZXt2YXIgZz1rLmZpbHRlcihiWzNdLGMsZCwhMF5mKTtkfHxlLnB1c2guYXBwbHkoZSxnKTtyZXR1cm4hMX1lbHNlIGlmKGwubWF0Y2guUE9TLnRlc3QoYlswXSl8fGwubWF0Y2guQ0hJTEQudGVzdChiWzBdKSlyZXR1cm4hMDtyZXR1cm4gYn0sUE9TOmZ1bmN0aW9uKGEpe2EudW5zaGlmdCghMCk7cmV0dXJuIGF9fSxmaWx0ZXJzOntlbmFibGVkOmZ1bmN0aW9uKGEpe3JldHVybiBhLmRpc2FibGVkPT09ITEmJmEudHlwZSE9PSJoaWRkZW4ifSxkaXNhYmxlZDpmdW5jdGlvbihhKXtyZXR1cm4gYS5kaXNhYmxlZD09PSEwfSxjaGVja2VkOmZ1bmN0aW9uKGEpe3JldHVybiBhLmNoZWNrZWQ9PT0hMH0sc2VsZWN0ZWQ6ZnVuY3Rpb24oYSl7YS5wYXJlbnROb2RlJiZhLnBhcmVudE5vZGUuc2VsZWN0ZWRJbmRleDtyZXR1cm4gYS5zZWxlY3RlZD09PSEwfSxwYXJlbnQ6ZnVuY3Rpb24oYSl7cmV0dXJuISFhLmZpcnN0Q2hpbGR9LGVtcHR5OmZ1bmN0aW9uKGEpe3JldHVybiFhLmZpcnN0Q2hpbGR9LGhhczpmdW5jdGlvbihhLGIsYyl7cmV0dXJuISFrKGNbM10sYSkubGVuZ3RofSxoZWFkZXI6ZnVuY3Rpb24oYSl7cmV0dXJuL2hcZC9pLnRlc3QoYS5ub2RlTmFtZSl9LHRleHQ6ZnVuY3Rpb24oYSl7dmFyIGI9YS5nZXRBdHRyaWJ1dGUoInR5cGUiKSxjPWEudHlwZTtyZXR1cm4idGV4dCI9PT1jJiYoYj09PWN8fGI9PT1udWxsKX0scmFkaW86ZnVuY3Rpb24oYSl7cmV0dXJuInJhZGlvIj09PWEudHlwZX0sY2hlY2tib3g6ZnVuY3Rpb24oYSl7cmV0dXJuImNoZWNrYm94Ij09PWEudHlwZX0sZmlsZTpmdW5jdGlvbihhKXtyZXR1cm4iZmlsZSI9PT1hLnR5cGV9LHBhc3N3b3JkOmZ1bmN0aW9uKGEpe3JldHVybiJwYXNzd29yZCI9PT1hLnR5cGV9LHN1Ym1pdDpmdW5jdGlvbihhKXtyZXR1cm4ic3VibWl0Ij09PWEudHlwZX0saW1hZ2U6ZnVuY3Rpb24oYSl7cmV0dXJuImltYWdlIj09PWEudHlwZX0scmVzZXQ6ZnVuY3Rpb24oYSl7cmV0dXJuInJlc2V0Ij09PWEudHlwZX0sYnV0dG9uOmZ1bmN0aW9uKGEpe3JldHVybiJidXR0b24iPT09YS50eXBlfHxhLm5vZGVOYW1lLnRvTG93ZXJDYXNlKCk9PT0iYnV0dG9uIn0saW5wdXQ6ZnVuY3Rpb24oYSl7cmV0dXJuL2lucHV0fHNlbGVjdHx0ZXh0YXJlYXxidXR0b24vaS50ZXN0KGEubm9kZU5hbWUpfX0sc2V0RmlsdGVyczp7Zmlyc3Q6ZnVuY3Rpb24oYSxiKXtyZXR1cm4gYj09PTB9LGxhc3Q6ZnVuY3Rpb24oYSxiLGMsZCl7cmV0dXJuIGI9PT1kLmxlbmd0aC0xfSxldmVuOmZ1bmN0aW9uKGEsYil7cmV0dXJuIGIlMj09PTB9LG9kZDpmdW5jdGlvbihhLGIpe3JldHVybiBiJTI9PT0xfSxsdDpmdW5jdGlvbihhLGIsYyl7cmV0dXJuIGI8Y1szXS0wfSxndDpmdW5jdGlvbihhLGIsYyl7cmV0dXJuIGI+Y1szXS0wfSxudGg6ZnVuY3Rpb24oYSxiLGMpe3JldHVybiBjWzNdLTA9PT1ifSxlcTpmdW5jdGlvbihhLGIsYyl7cmV0dXJuIGNbM10tMD09PWJ9fSxmaWx0ZXI6e1BTRVVETzpmdW5jdGlvbihhLGIsYyxkKXt2YXIgZT1iWzFdLGY9bC5maWx0ZXJzW2VdO2lmKGYpcmV0dXJuIGYoYSxjLGIsZCk7aWYoZT09PSJjb250YWlucyIpcmV0dXJuKGEudGV4dENvbnRlbnR8fGEuaW5uZXJUZXh0fHxrLmdldFRleHQoW2FdKXx8IiIpLmluZGV4T2YoYlszXSk+PTA7aWYoZT09PSJub3QiKXt2YXIgZz1iWzNdO2Zvcih2YXIgaD0wLGk9Zy5sZW5ndGg7aDxpO2grKylpZihnW2hdPT09YSlyZXR1cm4hMTtyZXR1cm4hMH1rLmVycm9yKGUpfSxDSElMRDpmdW5jdGlvbihhLGIpe3ZhciBjPWJbMV0sZD1hO3N3aXRjaChjKXtjYXNlIm9ubHkiOmNhc2UiZmlyc3QiOndoaWxlKGQ9ZC5wcmV2aW91c1NpYmxpbmcpaWYoZC5ub2RlVHlwZT09PTEpcmV0dXJuITE7aWYoYz09PSJmaXJzdCIpcmV0dXJuITA7ZD1hO2Nhc2UibGFzdCI6d2hpbGUoZD1kLm5leHRTaWJsaW5nKWlmKGQubm9kZVR5cGU9PT0xKXJldHVybiExO3JldHVybiEwO2Nhc2UibnRoIjp2YXIgZT1iWzJdLGY9YlszXTtpZihlPT09MSYmZj09PTApcmV0dXJuITA7dmFyIGc9YlswXSxoPWEucGFyZW50Tm9kZTtpZihoJiYoaC5zaXpjYWNoZSE9PWd8fCFhLm5vZGVJbmRleCkpe3ZhciBpPTA7Zm9yKGQ9aC5maXJzdENoaWxkO2Q7ZD1kLm5leHRTaWJsaW5nKWQubm9kZVR5cGU9PT0xJiYoZC5ub2RlSW5kZXg9KytpKTtoLnNpemNhY2hlPWd9dmFyIGo9YS5ub2RlSW5kZXgtZjtyZXR1cm4gZT09PTA/aj09PTA6aiVlPT09MCYmai9lPj0wfX0sSUQ6ZnVuY3Rpb24oYSxiKXtyZXR1cm4gYS5ub2RlVHlwZT09PTEmJmEuZ2V0QXR0cmlidXRlKCJpZCIpPT09Yn0sVEFHOmZ1bmN0aW9uKGEsYil7cmV0dXJuIGI9PT0iKiImJmEubm9kZVR5cGU9PT0xfHxhLm5vZGVOYW1lLnRvTG93ZXJDYXNlKCk9PT1ifSxDTEFTUzpmdW5jdGlvbihhLGIpe3JldHVybigiICIrKGEuY2xhc3NOYW1lfHxhLmdldEF0dHJpYnV0ZSgiY2xhc3MiKSkrIiAiKS5pbmRleE9mKGIpPi0xfSxBVFRSOmZ1bmN0aW9uKGEsYil7dmFyIGM9YlsxXSxkPWwuYXR0ckhhbmRsZVtjXT9sLmF0dHJIYW5kbGVbY10oYSk6YVtjXSE9bnVsbD9hW2NdOmEuZ2V0QXR0cmlidXRlKGMpLGU9ZCsiIixmPWJbMl0sZz1iWzRdO3JldHVybiBkPT1udWxsP2Y9PT0iIT0iOmY9PT0iPSI/ZT09PWc6Zj09PSIqPSI/ZS5pbmRleE9mKGcpPj0wOmY9PT0ifj0iPygiICIrZSsiICIpLmluZGV4T2YoZyk+PTA6Zz9mPT09IiE9Ij9lIT09ZzpmPT09Il49Ij9lLmluZGV4T2YoZyk9PT0wOmY9PT0iJD0iP2Uuc3Vic3RyKGUubGVuZ3RoLWcubGVuZ3RoKT09PWc6Zj09PSJ8PSI/ZT09PWd8fGUuc3Vic3RyKDAsZy5sZW5ndGgrMSk9PT1nKyItIjohMTplJiZkIT09ITF9LFBPUzpmdW5jdGlvbihhLGIsYyxkKXt2YXIgZT1iWzJdLGY9bC5zZXRGaWx0ZXJzW2VdO2lmKGYpcmV0dXJuIGYoYSxjLGIsZCl9fX0sbT1sLm1hdGNoLlBPUyxuPWZ1bmN0aW9uKGEsYil7cmV0dXJuIlxcIisoYi0wKzEpfTtmb3IodmFyIG8gaW4gbC5tYXRjaClsLm1hdGNoW29dPW5ldyBSZWdFeHAobC5tYXRjaFtvXS5zb3VyY2UrLyg/IVteXFtdKlxdKSg/IVteXChdKlwpKS8uc291cmNlKSxsLmxlZnRNYXRjaFtvXT1uZXcgUmVnRXhwKC8oXig/Oi58XHJ8XG4pKj8pLy5zb3VyY2UrbC5tYXRjaFtvXS5zb3VyY2UucmVwbGFjZSgvXFwoXGQrKS9nLG4pKTt2YXIgcD1mdW5jdGlvbihhLGIpe2E9QXJyYXkucHJvdG90eXBlLnNsaWNlLmNhbGwoYSwwKTtpZihiKXtiLnB1c2guYXBwbHkoYixhKTtyZXR1cm4gYn1yZXR1cm4gYX07dHJ5e0FycmF5LnByb3RvdHlwZS5zbGljZS5jYWxsKGMuZG9jdW1lbnRFbGVtZW50LmNoaWxkTm9kZXMsMClbMF0ubm9kZVR5cGV9Y2F0Y2gocSl7cD1mdW5jdGlvbihhLGIpe3ZhciBjPTAsZD1ifHxbXTtpZihmLmNhbGwoYSk9PT0iW29iamVjdCBBcnJheV0iKUFycmF5LnByb3RvdHlwZS5wdXNoLmFwcGx5KGQsYSk7ZWxzZSBpZih0eXBlb2YgYS5sZW5ndGg9PT0ibnVtYmVyIilmb3IodmFyIGU9YS5sZW5ndGg7YzxlO2MrKylkLnB1c2goYVtjXSk7ZWxzZSBmb3IoO2FbY107YysrKWQucHVzaChhW2NdKTtyZXR1cm4gZH19dmFyIHIscztjLmRvY3VtZW50RWxlbWVudC5jb21wYXJlRG9jdW1lbnRQb3NpdGlvbj9yPWZ1bmN0aW9uKGEsYil7aWYoYT09PWIpe2c9ITA7cmV0dXJuIDB9aWYoIWEuY29tcGFyZURvY3VtZW50UG9zaXRpb258fCFiLmNvbXBhcmVEb2N1bWVudFBvc2l0aW9uKXJldHVybiBhLmNvbXBhcmVEb2N1bWVudFBvc2l0aW9uPy0xOjE7cmV0dXJuIGEuY29tcGFyZURvY3VtZW50UG9zaXRpb24oYikmND8tMToxfToocj1mdW5jdGlvbihhLGIpe3ZhciBjLGQsZT1bXSxmPVtdLGg9YS5wYXJlbnROb2RlLGk9Yi5wYXJlbnROb2RlLGo9aDtpZihhPT09Yil7Zz0hMDtyZXR1cm4gMH1pZihoPT09aSlyZXR1cm4gcyhhLGIpO2lmKCFoKXJldHVybi0xO2lmKCFpKXJldHVybiAxO3doaWxlKGopZS51bnNoaWZ0KGopLGo9ai5wYXJlbnROb2RlO2o9aTt3aGlsZShqKWYudW5zaGlmdChqKSxqPWoucGFyZW50Tm9kZTtjPWUubGVuZ3RoLGQ9Zi5sZW5ndGg7Zm9yKHZhciBrPTA7azxjJiZrPGQ7aysrKWlmKGVba10hPT1mW2tdKXJldHVybiBzKGVba10sZltrXSk7cmV0dXJuIGs9PT1jP3MoYSxmW2tdLC0xKTpzKGVba10sYiwxKX0scz1mdW5jdGlvbihhLGIsYyl7aWYoYT09PWIpcmV0dXJuIGM7dmFyIGQ9YS5uZXh0U2libGluZzt3aGlsZShkKXtpZihkPT09YilyZXR1cm4tMTtkPWQubmV4dFNpYmxpbmd9cmV0dXJuIDF9KSxrLmdldFRleHQ9ZnVuY3Rpb24oYSl7dmFyIGI9IiIsYztmb3IodmFyIGQ9MDthW2RdO2QrKyljPWFbZF0sYy5ub2RlVHlwZT09PTN8fGMubm9kZVR5cGU9PT00P2IrPWMubm9kZVZhbHVlOmMubm9kZVR5cGUhPT04JiYoYis9ay5nZXRUZXh0KGMuY2hpbGROb2RlcykpO3JldHVybiBifSxmdW5jdGlvbigpe3ZhciBhPWMuY3JlYXRlRWxlbWVudCgiZGl2IiksZD0ic2NyaXB0IisobmV3IERhdGUpLmdldFRpbWUoKSxlPWMuZG9jdW1lbnRFbGVtZW50O2EuaW5uZXJIVE1MPSI8YSBuYW1lPSciK2QrIicvPiIsZS5pbnNlcnRCZWZvcmUoYSxlLmZpcnN0Q2hpbGQpLGMuZ2V0RWxlbWVudEJ5SWQoZCkmJihsLmZpbmQuSUQ9ZnVuY3Rpb24oYSxjLGQpe2lmKHR5cGVvZiBjLmdldEVsZW1lbnRCeUlkIT09InVuZGVmaW5lZCImJiFkKXt2YXIgZT1jLmdldEVsZW1lbnRCeUlkKGFbMV0pO3JldHVybiBlP2UuaWQ9PT1hWzFdfHx0eXBlb2YgZS5nZXRBdHRyaWJ1dGVOb2RlIT09InVuZGVmaW5lZCImJmUuZ2V0QXR0cmlidXRlTm9kZSgiaWQiKS5ub2RlVmFsdWU9PT1hWzFdP1tlXTpiOltdfX0sbC5maWx0ZXIuSUQ9ZnVuY3Rpb24oYSxiKXt2YXIgYz10eXBlb2YgYS5nZXRBdHRyaWJ1dGVOb2RlIT09InVuZGVmaW5lZCImJmEuZ2V0QXR0cmlidXRlTm9kZSgiaWQiKTtyZXR1cm4gYS5ub2RlVHlwZT09PTEmJmMmJmMubm9kZVZhbHVlPT09Yn0pLGUucmVtb3ZlQ2hpbGQoYSksZT1hPW51bGx9KCksZnVuY3Rpb24oKXt2YXIgYT1jLmNyZWF0ZUVsZW1lbnQoImRpdiIpO2EuYXBwZW5kQ2hpbGQoYy5jcmVhdGVDb21tZW50KCIiKSksYS5nZXRFbGVtZW50c0J5VGFnTmFtZSgiKiIpLmxlbmd0aD4wJiYobC5maW5kLlRBRz1mdW5jdGlvbihhLGIpe3ZhciBjPWIuZ2V0RWxlbWVudHNCeVRhZ05hbWUoYVsxXSk7aWYoYVsxXT09PSIqIil7dmFyIGQ9W107Zm9yKHZhciBlPTA7Y1tlXTtlKyspY1tlXS5ub2RlVHlwZT09PTEmJmQucHVzaChjW2VdKTtjPWR9cmV0dXJuIGN9KSxhLmlubmVySFRNTD0iPGEgaHJlZj0nIyc+PC9hPiIsYS5maXJzdENoaWxkJiZ0eXBlb2YgYS5maXJzdENoaWxkLmdldEF0dHJpYnV0ZSE9PSJ1bmRlZmluZWQiJiZhLmZpcnN0Q2hpbGQuZ2V0QXR0cmlidXRlKCJocmVmIikhPT0iIyImJihsLmF0dHJIYW5kbGUuaHJlZj1mdW5jdGlvbihhKXtyZXR1cm4gYS5nZXRBdHRyaWJ1dGUoImhyZWYiLDIpfSksYT1udWxsfSgpLGMucXVlcnlTZWxlY3RvckFsbCYmZnVuY3Rpb24oKXt2YXIgYT1rLGI9Yy5jcmVhdGVFbGVtZW50KCJkaXYiKSxkPSJfX3NpenpsZV9fIjtiLmlubmVySFRNTD0iPHAgY2xhc3M9J1RFU1QnPjwvcD4iO2lmKCFiLnF1ZXJ5U2VsZWN0b3JBbGx8fGIucXVlcnlTZWxlY3RvckFsbCgiLlRFU1QiKS5sZW5ndGghPT0wKXtrPWZ1bmN0aW9uKGIsZSxmLGcpe2U9ZXx8YztpZighZyYmIWsuaXNYTUwoZSkpe3ZhciBoPS9eKFx3KyQpfF5cLihbXHdcLV0rJCl8XiMoW1x3XC1dKyQpLy5leGVjKGIpO2lmKGgmJihlLm5vZGVUeXBlPT09MXx8ZS5ub2RlVHlwZT09PTkpKXtpZihoWzFdKXJldHVybiBwKGUuZ2V0RWxlbWVudHNCeVRhZ05hbWUoYiksZik7aWYoaFsyXSYmbC5maW5kLkNMQVNTJiZlLmdldEVsZW1lbnRzQnlDbGFzc05hbWUpcmV0dXJuIHAoZS5nZXRFbGVtZW50c0J5Q2xhc3NOYW1lKGhbMl0pLGYpfWlmKGUubm9kZVR5cGU9PT05KXtpZihiPT09ImJvZHkiJiZlLmJvZHkpcmV0dXJuIHAoW2UuYm9keV0sZik7aWYoaCYmaFszXSl7dmFyIGk9ZS5nZXRFbGVtZW50QnlJZChoWzNdKTtpZighaXx8IWkucGFyZW50Tm9kZSlyZXR1cm4gcChbXSxmKTtpZihpLmlkPT09aFszXSlyZXR1cm4gcChbaV0sZil9dHJ5e3JldHVybiBwKGUucXVlcnlTZWxlY3RvckFsbChiKSxmKX1jYXRjaChqKXt9fWVsc2UgaWYoZS5ub2RlVHlwZT09PTEmJmUubm9kZU5hbWUudG9Mb3dlckNhc2UoKSE9PSJvYmplY3QiKXt2YXIgbT1lLG49ZS5nZXRBdHRyaWJ1dGUoImlkIiksbz1ufHxkLHE9ZS5wYXJlbnROb2RlLHI9L15ccypbK35dLy50ZXN0KGIpO24/bz1vLnJlcGxhY2UoLycvZywiXFwkJiIpOmUuc2V0QXR0cmlidXRlKCJpZCIsbyksciYmcSYmKGU9ZS5wYXJlbnROb2RlKTt0cnl7aWYoIXJ8fHEpcmV0dXJuIHAoZS5xdWVyeVNlbGVjdG9yQWxsKCJbaWQ9JyIrbysiJ10gIitiKSxmKX1jYXRjaChzKXt9ZmluYWxseXtufHxtLnJlbW92ZUF0dHJpYnV0ZSgiaWQiKX19fXJldHVybiBhKGIsZSxmLGcpfTtmb3IodmFyIGUgaW4gYSlrW2VdPWFbZV07Yj1udWxsfX0oKSxmdW5jdGlvbigpe3ZhciBhPWMuZG9jdW1lbnRFbGVtZW50LGI9YS5tYXRjaGVzU2VsZWN0b3J8fGEubW96TWF0Y2hlc1NlbGVjdG9yfHxhLndlYmtpdE1hdGNoZXNTZWxlY3Rvcnx8YS5tc01hdGNoZXNTZWxlY3RvcjtpZihiKXt2YXIgZD0hYi5jYWxsKGMuY3JlYXRlRWxlbWVudCgiZGl2IiksImRpdiIpLGU9ITE7dHJ5e2IuY2FsbChjLmRvY3VtZW50RWxlbWVudCwiW3Rlc3QhPScnXTpzaXp6bGUiKX1jYXRjaChmKXtlPSEwfWsubWF0Y2hlc1NlbGVjdG9yPWZ1bmN0aW9uKGEsYyl7Yz1jLnJlcGxhY2UoL1w9XHMqKFteJyJcXV0qKVxzKlxdL2csIj0nJDEnXSIpO2lmKCFrLmlzWE1MKGEpKXRyeXtpZihlfHwhbC5tYXRjaC5QU0VVRE8udGVzdChjKSYmIS8hPS8udGVzdChjKSl7dmFyIGY9Yi5jYWxsKGEsYyk7aWYoZnx8IWR8fGEuZG9jdW1lbnQmJmEuZG9jdW1lbnQubm9kZVR5cGUhPT0xMSlyZXR1cm4gZn19Y2F0Y2goZyl7fXJldHVybiBrKGMsbnVsbCxudWxsLFthXSkubGVuZ3RoPjB9fX0oKSxmdW5jdGlvbigpe3ZhciBhPWMuY3JlYXRlRWxlbWVudCgiZGl2Iik7YS5pbm5lckhUTUw9IjxkaXYgY2xhc3M9J3Rlc3QgZSc+PC9kaXY+PGRpdiBjbGFzcz0ndGVzdCc+PC9kaXY+IjtpZihhLmdldEVsZW1lbnRzQnlDbGFzc05hbWUmJmEuZ2V0RWxlbWVudHNCeUNsYXNzTmFtZSgiZSIpLmxlbmd0aCE9PTApe2EubGFzdENoaWxkLmNsYXNzTmFtZT0iZSI7aWYoYS5nZXRFbGVtZW50c0J5Q2xhc3NOYW1lKCJlIikubGVuZ3RoPT09MSlyZXR1cm47bC5vcmRlci5zcGxpY2UoMSwwLCJDTEFTUyIpLGwuZmluZC5DTEFTUz1mdW5jdGlvbihhLGIsYyl7aWYodHlwZW9mIGIuZ2V0RWxlbWVudHNCeUNsYXNzTmFtZSE9PSJ1bmRlZmluZWQiJiYhYylyZXR1cm4gYi5nZXRFbGVtZW50c0J5Q2xhc3NOYW1lKGFbMV0pfSxhPW51bGx9fSgpLGMuZG9jdW1lbnRFbGVtZW50LmNvbnRhaW5zP2suY29udGFpbnM9ZnVuY3Rpb24oYSxiKXtyZXR1cm4gYSE9PWImJihhLmNvbnRhaW5zP2EuY29udGFpbnMoYik6ITApfTpjLmRvY3VtZW50RWxlbWVudC5jb21wYXJlRG9jdW1lbnRQb3NpdGlvbj9rLmNvbnRhaW5zPWZ1bmN0aW9uKGEsYil7cmV0dXJuISEoYS5jb21wYXJlRG9jdW1lbnRQb3NpdGlvbihiKSYxNil9OmsuY29udGFpbnM9ZnVuY3Rpb24oKXtyZXR1cm4hMX0say5pc1hNTD1mdW5jdGlvbihhKXt2YXIgYj0oYT9hLm93bmVyRG9jdW1lbnR8fGE6MCkuZG9jdW1lbnRFbGVtZW50O3JldHVybiBiP2Iubm9kZU5hbWUhPT0iSFRNTCI6ITF9O3ZhciB2PWZ1bmN0aW9uKGEsYil7dmFyIGMsZD1bXSxlPSIiLGY9Yi5ub2RlVHlwZT9bYl06Yjt3aGlsZShjPWwubWF0Y2guUFNFVURPLmV4ZWMoYSkpZSs9Y1swXSxhPWEucmVwbGFjZShsLm1hdGNoLlBTRVVETywiIik7YT1sLnJlbGF0aXZlW2FdP2ErIioiOmE7Zm9yKHZhciBnPTAsaD1mLmxlbmd0aDtnPGg7ZysrKWsoYSxmW2ddLGQpO3JldHVybiBrLmZpbHRlcihlLGQpfTtkLmZpbmQ9ayxkLmV4cHI9ay5zZWxlY3RvcnMsZC5leHByWyI6Il09ZC5leHByLmZpbHRlcnMsZC51bmlxdWU9ay51bmlxdWVTb3J0LGQudGV4dD1rLmdldFRleHQsZC5pc1hNTERvYz1rLmlzWE1MLGQuY29udGFpbnM9ay5jb250YWluc30oKTt2YXIgST0vVW50aWwkLyxKPS9eKD86cGFyZW50c3xwcmV2VW50aWx8cHJldkFsbCkvLEs9LywvLEw9L14uW146I1xbXC4sXSokLyxNPUFycmF5LnByb3RvdHlwZS5zbGljZSxOPWQuZXhwci5tYXRjaC5QT1MsTz17Y2hpbGRyZW46ITAsY29udGVudHM6ITAsbmV4dDohMCxwcmV2OiEwfTtkLmZuLmV4dGVuZCh7ZmluZDpmdW5jdGlvbihhKXt2YXIgYj10aGlzLnB1c2hTdGFjaygiIiwiZmluZCIsYSksYz0wO2Zvcih2YXIgZT0wLGY9dGhpcy5sZW5ndGg7ZTxmO2UrKyl7Yz1iLmxlbmd0aCxkLmZpbmQoYSx0aGlzW2VdLGIpO2lmKGU+MClmb3IodmFyIGc9YztnPGIubGVuZ3RoO2crKylmb3IodmFyIGg9MDtoPGM7aCsrKWlmKGJbaF09PT1iW2ddKXtiLnNwbGljZShnLS0sMSk7YnJlYWt9fXJldHVybiBifSxoYXM6ZnVuY3Rpb24oYSl7dmFyIGI9ZChhKTtyZXR1cm4gdGhpcy5maWx0ZXIoZnVuY3Rpb24oKXtmb3IodmFyIGE9MCxjPWIubGVuZ3RoO2E8YzthKyspaWYoZC5jb250YWlucyh0aGlzLGJbYV0pKXJldHVybiEwfSl9LG5vdDpmdW5jdGlvbihhKXtyZXR1cm4gdGhpcy5wdXNoU3RhY2soUSh0aGlzLGEsITEpLCJub3QiLGEpfSxmaWx0ZXI6ZnVuY3Rpb24oYSl7cmV0dXJuIHRoaXMucHVzaFN0YWNrKFEodGhpcyxhLCEwKSwiZmlsdGVyIixhKX0saXM6ZnVuY3Rpb24oYSl7cmV0dXJuISFhJiZkLmZpbHRlcihhLHRoaXMpLmxlbmd0aD4wfSxjbG9zZXN0OmZ1bmN0aW9uKGEsYil7dmFyIGM9W10sZSxmLGc9dGhpc1swXTtpZihkLmlzQXJyYXkoYSkpe3ZhciBoLGksaj17fSxrPTE7aWYoZyYmYS5sZW5ndGgpe2ZvcihlPTAsZj1hLmxlbmd0aDtlPGY7ZSsrKWk9YVtlXSxqW2ldfHwoaltpXT1kLmV4cHIubWF0Y2guUE9TLnRlc3QoaSk/ZChpLGJ8fHRoaXMuY29udGV4dCk6aSk7d2hpbGUoZyYmZy5vd25lckRvY3VtZW50JiZnIT09Yil7Zm9yKGkgaW4gailoPWpbaV0sKGguanF1ZXJ5P2guaW5kZXgoZyk+LTE6ZChnKS5pcyhoKSkmJmMucHVzaCh7c2VsZWN0b3I6aSxlbGVtOmcsbGV2ZWw6a30pO2c9Zy5wYXJlbnROb2RlLGsrK319cmV0dXJuIGN9dmFyIGw9Ti50ZXN0KGEpP2QoYSxifHx0aGlzLmNvbnRleHQpOm51bGw7Zm9yKGU9MCxmPXRoaXMubGVuZ3RoO2U8ZjtlKyspe2c9dGhpc1tlXTt3aGlsZShnKXtpZihsP2wuaW5kZXgoZyk+LTE6ZC5maW5kLm1hdGNoZXNTZWxlY3RvcihnLGEpKXtjLnB1c2goZyk7YnJlYWt9Zz1nLnBhcmVudE5vZGU7aWYoIWd8fCFnLm93bmVyRG9jdW1lbnR8fGc9PT1iKWJyZWFrfX1jPWMubGVuZ3RoPjE/ZC51bmlxdWUoYyk6YztyZXR1cm4gdGhpcy5wdXNoU3RhY2soYywiY2xvc2VzdCIsYSl9LGluZGV4OmZ1bmN0aW9uKGEpe2lmKCFhfHx0eXBlb2YgYT09PSJzdHJpbmciKXJldHVybiBkLmluQXJyYXkodGhpc1swXSxhP2QoYSk6dGhpcy5wYXJlbnQoKS5jaGlsZHJlbigpKTtyZXR1cm4gZC5pbkFycmF5KGEuanF1ZXJ5P2FbMF06YSx0aGlzKX0sYWRkOmZ1bmN0aW9uKGEsYil7dmFyIGM9dHlwZW9mIGE9PT0ic3RyaW5nIj9kKGEsYik6ZC5tYWtlQXJyYXkoYSksZT1kLm1lcmdlKHRoaXMuZ2V0KCksYyk7cmV0dXJuIHRoaXMucHVzaFN0YWNrKFAoY1swXSl8fFAoZVswXSk/ZTpkLnVuaXF1ZShlKSl9LGFuZFNlbGY6ZnVuY3Rpb24oKXtyZXR1cm4gdGhpcy5hZGQodGhpcy5wcmV2T2JqZWN0KX19KSxkLmVhY2goe3BhcmVudDpmdW5jdGlvbihhKXt2YXIgYj1hLnBhcmVudE5vZGU7cmV0dXJuIGImJmIubm9kZVR5cGUhPT0xMT9iOm51bGx9LHBhcmVudHM6ZnVuY3Rpb24oYSl7cmV0dXJuIGQuZGlyKGEsInBhcmVudE5vZGUiKX0scGFyZW50c1VudGlsOmZ1bmN0aW9uKGEsYixjKXtyZXR1cm4gZC5kaXIoYSwicGFyZW50Tm9kZSIsYyl9LG5leHQ6ZnVuY3Rpb24oYSl7cmV0dXJuIGQubnRoKGEsMiwibmV4dFNpYmxpbmciKX0scHJldjpmdW5jdGlvbihhKXtyZXR1cm4gZC5udGgoYSwyLCJwcmV2aW91c1NpYmxpbmciKX0sbmV4dEFsbDpmdW5jdGlvbihhKXtyZXR1cm4gZC5kaXIoYSwibmV4dFNpYmxpbmciKX0scHJldkFsbDpmdW5jdGlvbihhKXtyZXR1cm4gZC5kaXIoYSwicHJldmlvdXNTaWJsaW5nIil9LG5leHRVbnRpbDpmdW5jdGlvbihhLGIsYyl7cmV0dXJuIGQuZGlyKGEsIm5leHRTaWJsaW5nIixjKX0scHJldlVudGlsOmZ1bmN0aW9uKGEsYixjKXtyZXR1cm4gZC5kaXIoYSwicHJldmlvdXNTaWJsaW5nIixjKX0sc2libGluZ3M6ZnVuY3Rpb24oYSl7cmV0dXJuIGQuc2libGluZyhhLnBhcmVudE5vZGUuZmlyc3RDaGlsZCxhKX0sY2hpbGRyZW46ZnVuY3Rpb24oYSl7cmV0dXJuIGQuc2libGluZyhhLmZpcnN0Q2hpbGQpfSxjb250ZW50czpmdW5jdGlvbihhKXtyZXR1cm4gZC5ub2RlTmFtZShhLCJpZnJhbWUiKT9hLmNvbnRlbnREb2N1bWVudHx8YS5jb250ZW50V2luZG93LmRvY3VtZW50OmQubWFrZUFycmF5KGEuY2hpbGROb2Rlcyl9fSxmdW5jdGlvbihhLGIpe2QuZm5bYV09ZnVuY3Rpb24oYyxlKXt2YXIgZj1kLm1hcCh0aGlzLGIsYyksZz1NLmNhbGwoYXJndW1lbnRzKTtJLnRlc3QoYSl8fChlPWMpLGUmJnR5cGVvZiBlPT09InN0cmluZyImJihmPWQuZmlsdGVyKGUsZikpLGY9dGhpcy5sZW5ndGg+MSYmIU9bYV0/ZC51bmlxdWUoZik6ZiwodGhpcy5sZW5ndGg+MXx8Sy50ZXN0KGUpKSYmSi50ZXN0KGEpJiYoZj1mLnJldmVyc2UoKSk7cmV0dXJuIHRoaXMucHVzaFN0YWNrKGYsYSxnLmpvaW4oIiwiKSl9fSksZC5leHRlbmQoe2ZpbHRlcjpmdW5jdGlvbihhLGIsYyl7YyYmKGE9Ijpub3QoIithKyIpIik7cmV0dXJuIGIubGVuZ3RoPT09MT9kLmZpbmQubWF0Y2hlc1NlbGVjdG9yKGJbMF0sYSk/W2JbMF1dOltdOmQuZmluZC5tYXRjaGVzKGEsYil9LGRpcjpmdW5jdGlvbihhLGMsZSl7dmFyIGY9W10sZz1hW2NdO3doaWxlKGcmJmcubm9kZVR5cGUhPT05JiYoZT09PWJ8fGcubm9kZVR5cGUhPT0xfHwhZChnKS5pcyhlKSkpZy5ub2RlVHlwZT09PTEmJmYucHVzaChnKSxnPWdbY107cmV0dXJuIGZ9LG50aDpmdW5jdGlvbihhLGIsYyxkKXtiPWJ8fDE7dmFyIGU9MDtmb3IoO2E7YT1hW2NdKWlmKGEubm9kZVR5cGU9PT0xJiYrK2U9PT1iKWJyZWFrO3JldHVybiBhfSxzaWJsaW5nOmZ1bmN0aW9uKGEsYil7dmFyIGM9W107Zm9yKDthO2E9YS5uZXh0U2libGluZylhLm5vZGVUeXBlPT09MSYmYSE9PWImJmMucHVzaChhKTtyZXR1cm4gY319KTt2YXIgUj0vIGpRdWVyeVxkKz0iKD86XGQrfG51bGwpIi9nLFM9L15ccysvLFQ9LzwoPyFhcmVhfGJyfGNvbHxlbWJlZHxocnxpbWd8aW5wdXR8bGlua3xtZXRhfHBhcmFtKSgoW1x3Ol0rKVtePl0qKVwvPi9pZyxVPS88KFtcdzpdKykvLFY9Lzx0Ym9keS9pLFc9Lzx8JiM/XHcrOy8sWD0vPCg/OnNjcmlwdHxvYmplY3R8ZW1iZWR8b3B0aW9ufHN0eWxlKS9pLFk9L2NoZWNrZWRccyooPzpbXj1dfD1ccyouY2hlY2tlZC4pL2ksWj17b3B0aW9uOlsxLCI8c2VsZWN0IG11bHRpcGxlPSdtdWx0aXBsZSc+IiwiPC9zZWxlY3Q+Il0sbGVnZW5kOlsxLCI8ZmllbGRzZXQ+IiwiPC9maWVsZHNldD4iXSx0aGVhZDpbMSwiPHRhYmxlPiIsIjwvdGFibGU+Il0sdHI6WzIsIjx0YWJsZT48dGJvZHk+IiwiPC90Ym9keT48L3RhYmxlPiJdLHRkOlszLCI8dGFibGU+PHRib2R5Pjx0cj4iLCI8L3RyPjwvdGJvZHk+PC90YWJsZT4iXSxjb2w6WzIsIjx0YWJsZT48dGJvZHk+PC90Ym9keT48Y29sZ3JvdXA+IiwiPC9jb2xncm91cD48L3RhYmxlPiJdLGFyZWE6WzEsIjxtYXA+IiwiPC9tYXA+Il0sX2RlZmF1bHQ6WzAsIiIsIiJdfTtaLm9wdGdyb3VwPVoub3B0aW9uLFoudGJvZHk9Wi50Zm9vdD1aLmNvbGdyb3VwPVouY2FwdGlvbj1aLnRoZWFkLFoudGg9Wi50ZCxkLnN1cHBvcnQuaHRtbFNlcmlhbGl6ZXx8KFouX2RlZmF1bHQ9WzEsImRpdjxkaXY+IiwiPC9kaXY+Il0pLGQuZm4uZXh0ZW5kKHt0ZXh0OmZ1bmN0aW9uKGEpe2lmKGQuaXNGdW5jdGlvbihhKSlyZXR1cm4gdGhpcy5lYWNoKGZ1bmN0aW9uKGIpe3ZhciBjPWQodGhpcyk7Yy50ZXh0KGEuY2FsbCh0aGlzLGIsYy50ZXh0KCkpKX0pO2lmKHR5cGVvZiBhIT09Im9iamVjdCImJmEhPT1iKXJldHVybiB0aGlzLmVtcHR5KCkuYXBwZW5kKCh0aGlzWzBdJiZ0aGlzWzBdLm93bmVyRG9jdW1lbnR8fGMpLmNyZWF0ZVRleHROb2RlKGEpKTtyZXR1cm4gZC50ZXh0KHRoaXMpfSx3cmFwQWxsOmZ1bmN0aW9uKGEpe2lmKGQuaXNGdW5jdGlvbihhKSlyZXR1cm4gdGhpcy5lYWNoKGZ1bmN0aW9uKGIpe2QodGhpcykud3JhcEFsbChhLmNhbGwodGhpcyxiKSl9KTtpZih0aGlzWzBdKXt2YXIgYj1kKGEsdGhpc1swXS5vd25lckRvY3VtZW50KS5lcSgwKS5jbG9uZSghMCk7dGhpc1swXS5wYXJlbnROb2RlJiZiLmluc2VydEJlZm9yZSh0aGlzWzBdKSxiLm1hcChmdW5jdGlvbigpe3ZhciBhPXRoaXM7d2hpbGUoYS5maXJzdENoaWxkJiZhLmZpcnN0Q2hpbGQubm9kZVR5cGU9PT0xKWE9YS5maXJzdENoaWxkO3JldHVybiBhfSkuYXBwZW5kKHRoaXMpfXJldHVybiB0aGlzfSx3cmFwSW5uZXI6ZnVuY3Rpb24oYSl7aWYoZC5pc0Z1bmN0aW9uKGEpKXJldHVybiB0aGlzLmVhY2goZnVuY3Rpb24oYil7ZCh0aGlzKS53cmFwSW5uZXIoYS5jYWxsKHRoaXMsYikpfSk7cmV0dXJuIHRoaXMuZWFjaChmdW5jdGlvbigpe3ZhciBiPWQodGhpcyksYz1iLmNvbnRlbnRzKCk7Yy5sZW5ndGg/Yy53cmFwQWxsKGEpOmIuYXBwZW5kKGEpfSl9LHdyYXA6ZnVuY3Rpb24oYSl7cmV0dXJuIHRoaXMuZWFjaChmdW5jdGlvbigpe2QodGhpcykud3JhcEFsbChhKX0pfSx1bndyYXA6ZnVuY3Rpb24oKXtyZXR1cm4gdGhpcy5wYXJlbnQoKS5lYWNoKGZ1bmN0aW9uKCl7ZC5ub2RlTmFtZSh0aGlzLCJib2R5Iil8fGQodGhpcykucmVwbGFjZVdpdGgodGhpcy5jaGlsZE5vZGVzKX0pLmVuZCgpfSxhcHBlbmQ6ZnVuY3Rpb24oKXtyZXR1cm4gdGhpcy5kb21NYW5pcChhcmd1bWVudHMsITAsZnVuY3Rpb24oYSl7dGhpcy5ub2RlVHlwZT09PTEmJnRoaXMuYXBwZW5kQ2hpbGQoYSl9KX0scHJlcGVuZDpmdW5jdGlvbigpe3JldHVybiB0aGlzLmRvbU1hbmlwKGFyZ3VtZW50cywhMCxmdW5jdGlvbihhKXt0aGlzLm5vZGVUeXBlPT09MSYmdGhpcy5pbnNlcnRCZWZvcmUoYSx0aGlzLmZpcnN0Q2hpbGQpfSl9LGJlZm9yZTpmdW5jdGlvbigpe2lmKHRoaXNbMF0mJnRoaXNbMF0ucGFyZW50Tm9kZSlyZXR1cm4gdGhpcy5kb21NYW5pcChhcmd1bWVudHMsITEsZnVuY3Rpb24oYSl7dGhpcy5wYXJlbnROb2RlLmluc2VydEJlZm9yZShhLHRoaXMpfSk7aWYoYXJndW1lbnRzLmxlbmd0aCl7dmFyIGE9ZChhcmd1bWVudHNbMF0pO2EucHVzaC5hcHBseShhLHRoaXMudG9BcnJheSgpKTtyZXR1cm4gdGhpcy5wdXNoU3RhY2soYSwiYmVmb3JlIixhcmd1bWVudHMpfX0sYWZ0ZXI6ZnVuY3Rpb24oKXtpZih0aGlzWzBdJiZ0aGlzWzBdLnBhcmVudE5vZGUpcmV0dXJuIHRoaXMuZG9tTWFuaXAoYXJndW1lbnRzLCExLGZ1bmN0aW9uKGEpe3RoaXMucGFyZW50Tm9kZS5pbnNlcnRCZWZvcmUoYSx0aGlzLm5leHRTaWJsaW5nKX0pO2lmKGFyZ3VtZW50cy5sZW5ndGgpe3ZhciBhPXRoaXMucHVzaFN0YWNrKHRoaXMsImFmdGVyIixhcmd1bWVudHMpO2EucHVzaC5hcHBseShhLGQoYXJndW1lbnRzWzBdKS50b0FycmF5KCkpO3JldHVybiBhfX0scmVtb3ZlOmZ1bmN0aW9uKGEsYil7Zm9yKHZhciBjPTAsZTsoZT10aGlzW2NdKSE9bnVsbDtjKyspaWYoIWF8fGQuZmlsdGVyKGEsW2VdKS5sZW5ndGgpIWImJmUubm9kZVR5cGU9PT0xJiYoZC5jbGVhbkRhdGEoZS5nZXRFbGVtZW50c0J5VGFnTmFtZSgiKiIpKSxkLmNsZWFuRGF0YShbZV0pKSxlLnBhcmVudE5vZGUmJmUucGFyZW50Tm9kZS5yZW1vdmVDaGlsZChlKTtyZXR1cm4gdGhpc30sZW1wdHk6ZnVuY3Rpb24oKXtmb3IodmFyIGE9MCxiOyhiPXRoaXNbYV0pIT1udWxsO2ErKyl7Yi5ub2RlVHlwZT09PTEmJmQuY2xlYW5EYXRhKGIuZ2V0RWxlbWVudHNCeVRhZ05hbWUoIioiKSk7d2hpbGUoYi5maXJzdENoaWxkKWIucmVtb3ZlQ2hpbGQoYi5maXJzdENoaWxkKX1yZXR1cm4gdGhpc30sY2xvbmU6ZnVuY3Rpb24oYSxiKXthPWE9PW51bGw/ITE6YSxiPWI9PW51bGw/YTpiO3JldHVybiB0aGlzLm1hcChmdW5jdGlvbigpe3JldHVybiBkLmNsb25lKHRoaXMsYSxiKX0pfSxodG1sOmZ1bmN0aW9uKGEpe2lmKGE9PT1iKXJldHVybiB0aGlzWzBdJiZ0aGlzWzBdLm5vZGVUeXBlPT09MT90aGlzWzBdLmlubmVySFRNTC5yZXBsYWNlKFIsIiIpOm51bGw7aWYodHlwZW9mIGEhPT0ic3RyaW5nInx8WC50ZXN0KGEpfHwhZC5zdXBwb3J0LmxlYWRpbmdXaGl0ZXNwYWNlJiZTLnRlc3QoYSl8fFpbKFUuZXhlYyhhKXx8WyIiLCIiXSlbMV0udG9Mb3dlckNhc2UoKV0pZC5pc0Z1bmN0aW9uKGEpP3RoaXMuZWFjaChmdW5jdGlvbihiKXt2YXIgYz1kKHRoaXMpO2MuaHRtbChhLmNhbGwodGhpcyxiLGMuaHRtbCgpKSl9KTp0aGlzLmVtcHR5KCkuYXBwZW5kKGEpO2Vsc2V7YT1hLnJlcGxhY2UoVCwiPCQxPjwvJDI+Iik7dHJ5e2Zvcih2YXIgYz0wLGU9dGhpcy5sZW5ndGg7YzxlO2MrKyl0aGlzW2NdLm5vZGVUeXBlPT09MSYmKGQuY2xlYW5EYXRhKHRoaXNbY10uZ2V0RWxlbWVudHNCeVRhZ05hbWUoIioiKSksdGhpc1tjXS5pbm5lckhUTUw9YSl9Y2F0Y2goZil7dGhpcy5lbXB0eSgpLmFwcGVuZChhKX19cmV0dXJuIHRoaXN9LHJlcGxhY2VXaXRoOmZ1bmN0aW9uKGEpe2lmKHRoaXNbMF0mJnRoaXNbMF0ucGFyZW50Tm9kZSl7aWYoZC5pc0Z1bmN0aW9uKGEpKXJldHVybiB0aGlzLmVhY2goZnVuY3Rpb24oYil7dmFyIGM9ZCh0aGlzKSxlPWMuaHRtbCgpO2MucmVwbGFjZVdpdGgoYS5jYWxsKHRoaXMsYixlKSl9KTt0eXBlb2YgYSE9PSJzdHJpbmciJiYoYT1kKGEpLmRldGFjaCgpKTtyZXR1cm4gdGhpcy5lYWNoKGZ1bmN0aW9uKCl7dmFyIGI9dGhpcy5uZXh0U2libGluZyxjPXRoaXMucGFyZW50Tm9kZTtkKHRoaXMpLnJlbW92ZSgpLGI/ZChiKS5iZWZvcmUoYSk6ZChjKS5hcHBlbmQoYSl9KX1yZXR1cm4gdGhpcy5sZW5ndGg/dGhpcy5wdXNoU3RhY2soZChkLmlzRnVuY3Rpb24oYSk/YSgpOmEpLCJyZXBsYWNlV2l0aCIsYSk6dGhpc30sZGV0YWNoOmZ1bmN0aW9uKGEpe3JldHVybiB0aGlzLnJlbW92ZShhLCEwKX0sZG9tTWFuaXA6ZnVuY3Rpb24oYSxjLGUpe3ZhciBmLGcsaCxpLGo9YVswXSxrPVtdO2lmKCFkLnN1cHBvcnQuY2hlY2tDbG9uZSYmYXJndW1lbnRzLmxlbmd0aD09PTMmJnR5cGVvZiBqPT09InN0cmluZyImJlkudGVzdChqKSlyZXR1cm4gdGhpcy5lYWNoKGZ1bmN0aW9uKCl7ZCh0aGlzKS5kb21NYW5pcChhLGMsZSwhMCl9KTtpZihkLmlzRnVuY3Rpb24oaikpcmV0dXJuIHRoaXMuZWFjaChmdW5jdGlvbihmKXt2YXIgZz1kKHRoaXMpO2FbMF09ai5jYWxsKHRoaXMsZixjP2cuaHRtbCgpOmIpLGcuZG9tTWFuaXAoYSxjLGUpfSk7aWYodGhpc1swXSl7aT1qJiZqLnBhcmVudE5vZGUsZC5zdXBwb3J0LnBhcmVudE5vZGUmJmkmJmkubm9kZVR5cGU9PT0xMSYmaS5jaGlsZE5vZGVzLmxlbmd0aD09PXRoaXMubGVuZ3RoP2Y9e2ZyYWdtZW50Oml9OmY9ZC5idWlsZEZyYWdtZW50KGEsdGhpcyxrKSxoPWYuZnJhZ21lbnQsaC5jaGlsZE5vZGVzLmxlbmd0aD09PTE/Zz1oPWguZmlyc3RDaGlsZDpnPWguZmlyc3RDaGlsZDtpZihnKXtjPWMmJmQubm9kZU5hbWUoZywidHIiKTtmb3IodmFyIGw9MCxtPXRoaXMubGVuZ3RoLG49bS0xO2w8bTtsKyspZS5jYWxsKGM/JCh0aGlzW2xdLGcpOnRoaXNbbF0sZi5jYWNoZWFibGV8fG0+MSYmbDxuP2QuY2xvbmUoaCwhMCwhMCk6aCl9ay5sZW5ndGgmJmQuZWFjaChrLGJjKX1yZXR1cm4gdGhpc319KSxkLmJ1aWxkRnJhZ21lbnQ9ZnVuY3Rpb24oYSxiLGUpe3ZhciBmLGcsaCxpPWImJmJbMF0/YlswXS5vd25lckRvY3VtZW50fHxiWzBdOmM7YS5sZW5ndGg9PT0xJiZ0eXBlb2YgYVswXT09PSJzdHJpbmciJiZhWzBdLmxlbmd0aDw1MTImJmk9PT1jJiZhWzBdLmNoYXJBdCgwKT09PSI8IiYmIVgudGVzdChhWzBdKSYmKGQuc3VwcG9ydC5jaGVja0Nsb25lfHwhWS50ZXN0KGFbMF0pKSYmKGc9ITAsaD1kLmZyYWdtZW50c1thWzBdXSxoJiYoaCE9PTEmJihmPWgpKSksZnx8KGY9aS5jcmVhdGVEb2N1bWVudEZyYWdtZW50KCksZC5jbGVhbihhLGksZixlKSksZyYmKGQuZnJhZ21lbnRzW2FbMF1dPWg/ZjoxKTtyZXR1cm57ZnJhZ21lbnQ6ZixjYWNoZWFibGU6Z319LGQuZnJhZ21lbnRzPXt9LGQuZWFjaCh7YXBwZW5kVG86ImFwcGVuZCIscHJlcGVuZFRvOiJwcmVwZW5kIixpbnNlcnRCZWZvcmU6ImJlZm9yZSIsaW5zZXJ0QWZ0ZXI6ImFmdGVyIixyZXBsYWNlQWxsOiJyZXBsYWNlV2l0aCJ9LGZ1bmN0aW9uKGEsYil7ZC5mblthXT1mdW5jdGlvbihjKXt2YXIgZT1bXSxmPWQoYyksZz10aGlzLmxlbmd0aD09PTEmJnRoaXNbMF0ucGFyZW50Tm9kZTtpZihnJiZnLm5vZGVUeXBlPT09MTEmJmcuY2hpbGROb2Rlcy5sZW5ndGg9PT0xJiZmLmxlbmd0aD09PTEpe2ZbYl0odGhpc1swXSk7cmV0dXJuIHRoaXN9Zm9yKHZhciBoPTAsaT1mLmxlbmd0aDtoPGk7aCsrKXt2YXIgaj0oaD4wP3RoaXMuY2xvbmUoITApOnRoaXMpLmdldCgpO2QoZltoXSlbYl0oaiksZT1lLmNvbmNhdChqKX1yZXR1cm4gdGhpcy5wdXNoU3RhY2soZSxhLGYuc2VsZWN0b3IpfX0pLGQuZXh0ZW5kKHtjbG9uZTpmdW5jdGlvbihhLGIsYyl7dmFyIGU9YS5jbG9uZU5vZGUoITApLGYsZyxoO2lmKCghZC5zdXBwb3J0Lm5vQ2xvbmVFdmVudHx8IWQuc3VwcG9ydC5ub0Nsb25lQ2hlY2tlZCkmJihhLm5vZGVUeXBlPT09MXx8YS5ub2RlVHlwZT09PTExKSYmIWQuaXNYTUxEb2MoYSkpe2JhKGEsZSksZj1iYihhKSxnPWJiKGUpO2ZvcihoPTA7ZltoXTsrK2gpYmEoZltoXSxnW2hdKX1pZihiKXtfKGEsZSk7aWYoYyl7Zj1iYihhKSxnPWJiKGUpO2ZvcihoPTA7ZltoXTsrK2gpXyhmW2hdLGdbaF0pfX1yZXR1cm4gZX0sY2xlYW46ZnVuY3Rpb24oYSxiLGUsZil7Yj1ifHxjLHR5cGVvZiBiLmNyZWF0ZUVsZW1lbnQ9PT0idW5kZWZpbmVkIiYmKGI9Yi5vd25lckRvY3VtZW50fHxiWzBdJiZiWzBdLm93bmVyRG9jdW1lbnR8fGMpO3ZhciBnPVtdO2Zvcih2YXIgaD0wLGk7KGk9YVtoXSkhPW51bGw7aCsrKXt0eXBlb2YgaT09PSJudW1iZXIiJiYoaSs9IiIpO2lmKCFpKWNvbnRpbnVlO2lmKHR5cGVvZiBpIT09InN0cmluZyJ8fFcudGVzdChpKSl7aWYodHlwZW9mIGk9PT0ic3RyaW5nIil7aT1pLnJlcGxhY2UoVCwiPCQxPjwvJDI+Iik7dmFyIGo9KFUuZXhlYyhpKXx8WyIiLCIiXSlbMV0udG9Mb3dlckNhc2UoKSxrPVpbal18fFouX2RlZmF1bHQsbD1rWzBdLG09Yi5jcmVhdGVFbGVtZW50KCJkaXYiKTttLmlubmVySFRNTD1rWzFdK2kra1syXTt3aGlsZShsLS0pbT1tLmxhc3RDaGlsZDtpZighZC5zdXBwb3J0LnRib2R5KXt2YXIgbj1WLnRlc3QoaSksbz1qPT09InRhYmxlIiYmIW4/bS5maXJzdENoaWxkJiZtLmZpcnN0Q2hpbGQuY2hpbGROb2RlczprWzFdPT09Ijx0YWJsZT4iJiYhbj9tLmNoaWxkTm9kZXM6W107Zm9yKHZhciBwPW8ubGVuZ3RoLTE7cD49MDstLXApZC5ub2RlTmFtZShvW3BdLCJ0Ym9keSIpJiYhb1twXS5jaGlsZE5vZGVzLmxlbmd0aCYmb1twXS5wYXJlbnROb2RlLnJlbW92ZUNoaWxkKG9bcF0pfSFkLnN1cHBvcnQubGVhZGluZ1doaXRlc3BhY2UmJlMudGVzdChpKSYmbS5pbnNlcnRCZWZvcmUoYi5jcmVhdGVUZXh0Tm9kZShTLmV4ZWMoaSlbMF0pLG0uZmlyc3RDaGlsZCksaT1tLmNoaWxkTm9kZXN9fWVsc2UgaT1iLmNyZWF0ZVRleHROb2RlKGkpO2kubm9kZVR5cGU/Zy5wdXNoKGkpOmc9ZC5tZXJnZShnLGkpfWlmKGUpZm9yKGg9MDtnW2hdO2grKykhZnx8IWQubm9kZU5hbWUoZ1toXSwic2NyaXB0Iil8fGdbaF0udHlwZSYmZ1toXS50eXBlLnRvTG93ZXJDYXNlKCkhPT0idGV4dC9qYXZhc2NyaXB0Ij8oZ1toXS5ub2RlVHlwZT09PTEmJmcuc3BsaWNlLmFwcGx5KGcsW2grMSwwXS5jb25jYXQoZC5tYWtlQXJyYXkoZ1toXS5nZXRFbGVtZW50c0J5VGFnTmFtZSgic2NyaXB0IikpKSksZS5hcHBlbmRDaGlsZChnW2hdKSk6Zi5wdXNoKGdbaF0ucGFyZW50Tm9kZT9nW2hdLnBhcmVudE5vZGUucmVtb3ZlQ2hpbGQoZ1toXSk6Z1toXSk7cmV0dXJuIGd9LGNsZWFuRGF0YTpmdW5jdGlvbihhKXt2YXIgYixjLGU9ZC5jYWNoZSxmPWQuZXhwYW5kbyxnPWQuZXZlbnQuc3BlY2lhbCxoPWQuc3VwcG9ydC5kZWxldGVFeHBhbmRvO2Zvcih2YXIgaT0wLGo7KGo9YVtpXSkhPW51bGw7aSsrKXtpZihqLm5vZGVOYW1lJiZkLm5vRGF0YVtqLm5vZGVOYW1lLnRvTG93ZXJDYXNlKCldKWNvbnRpbnVlO2M9altkLmV4cGFuZG9dO2lmKGMpe2I9ZVtjXSYmZVtjXVtmXTtpZihiJiZiLmV2ZW50cyl7Zm9yKHZhciBrIGluIGIuZXZlbnRzKWdba10/ZC5ldmVudC5yZW1vdmUoaixrKTpkLnJlbW92ZUV2ZW50KGosayxiLmhhbmRsZSk7Yi5oYW5kbGUmJihiLmhhbmRsZS5lbGVtPW51bGwpfWg/ZGVsZXRlIGpbZC5leHBhbmRvXTpqLnJlbW92ZUF0dHJpYnV0ZSYmai5yZW1vdmVBdHRyaWJ1dGUoZC5leHBhbmRvKSxkZWxldGUgZVtjXX19fX0pO3ZhciBiZD0vYWxwaGFcKFteKV0qXCkvaSxiZT0vb3BhY2l0eT0oW14pXSopLyxiZj0vLShbYS16XSkvaWcsYmc9LyhbQS1aXXxebXMpL2csYmg9L14tP1xkKyg/OnB4KT8kL2ksYmk9L14tP1xkLyxiaj17cG9zaXRpb246ImFic29sdXRlIix2aXNpYmlsaXR5OiJoaWRkZW4iLGRpc3BsYXk6ImJsb2NrIn0sYms9WyJMZWZ0IiwiUmlnaHQiXSxibD1bIlRvcCIsIkJvdHRvbSJdLGJtLGJuLGJvLGJwPWZ1bmN0aW9uKGEsYil7cmV0dXJuIGIudG9VcHBlckNhc2UoKX07ZC5mbi5jc3M9ZnVuY3Rpb24oYSxjKXtpZihhcmd1bWVudHMubGVuZ3RoPT09MiYmYz09PWIpcmV0dXJuIHRoaXM7cmV0dXJuIGQuYWNjZXNzKHRoaXMsYSxjLCEwLGZ1bmN0aW9uKGEsYyxlKXtyZXR1cm4gZSE9PWI/ZC5zdHlsZShhLGMsZSk6ZC5jc3MoYSxjKX0pfSxkLmV4dGVuZCh7Y3NzSG9va3M6e29wYWNpdHk6e2dldDpmdW5jdGlvbihhLGIpe2lmKGIpe3ZhciBjPWJtKGEsIm9wYWNpdHkiLCJvcGFjaXR5Iik7cmV0dXJuIGM9PT0iIj8iMSI6Y31yZXR1cm4gYS5zdHlsZS5vcGFjaXR5fX19LGNzc051bWJlcjp7ekluZGV4OiEwLGZvbnRXZWlnaHQ6ITAsb3BhY2l0eTohMCx6b29tOiEwLGxpbmVIZWlnaHQ6ITB9LGNzc1Byb3BzOnsiZmxvYXQiOmQuc3VwcG9ydC5jc3NGbG9hdD8iY3NzRmxvYXQiOiJzdHlsZUZsb2F0In0sc3R5bGU6ZnVuY3Rpb24oYSxjLGUsZil7aWYoYSYmYS5ub2RlVHlwZSE9PTMmJmEubm9kZVR5cGUhPT04JiZhLnN0eWxlKXt2YXIgZyxoPWQuY2FtZWxDYXNlKGMpLGk9YS5zdHlsZSxqPWQuY3NzSG9va3NbaF07Yz1kLmNzc1Byb3BzW2hdfHxoO2lmKGU9PT1iKXtpZihqJiYiZ2V0ImluIGomJihnPWouZ2V0KGEsITEsZikpIT09YilyZXR1cm4gZztyZXR1cm4gaVtjXX1pZih0eXBlb2YgZT09PSJudW1iZXIiJiZpc05hTihlKXx8ZT09bnVsbClyZXR1cm47dHlwZW9mIGU9PT0ibnVtYmVyIiYmIWQuY3NzTnVtYmVyW2hdJiYoZSs9InB4Iik7aWYoIWp8fCEoInNldCJpbiBqKXx8KGU9ai5zZXQoYSxlKSkhPT1iKXRyeXtpW2NdPWV9Y2F0Y2goayl7fX19LGNzczpmdW5jdGlvbihhLGMsZSl7dmFyIGYsZz1kLmNhbWVsQ2FzZShjKSxoPWQuY3NzSG9va3NbZ107Yz1kLmNzc1Byb3BzW2ddfHxnO2lmKGgmJiJnZXQiaW4gaCYmKGY9aC5nZXQoYSwhMCxlKSkhPT1iKXJldHVybiBmO2lmKGJtKXJldHVybiBibShhLGMsZyl9LHN3YXA6ZnVuY3Rpb24oYSxiLGMpe3ZhciBkPXt9O2Zvcih2YXIgZSBpbiBiKWRbZV09YS5zdHlsZVtlXSxhLnN0eWxlW2VdPWJbZV07Yy5jYWxsKGEpO2ZvcihlIGluIGIpYS5zdHlsZVtlXT1kW2VdfSxjYW1lbENhc2U6ZnVuY3Rpb24oYSl7cmV0dXJuIGEucmVwbGFjZShiZixicCl9fSksZC5jdXJDU1M9ZC5jc3MsZC5lYWNoKFsiaGVpZ2h0Iiwid2lkdGgiXSxmdW5jdGlvbihhLGIpe2QuY3NzSG9va3NbYl09e2dldDpmdW5jdGlvbihhLGMsZSl7dmFyIGY7aWYoYyl7YS5vZmZzZXRXaWR0aCE9PTA/Zj1icShhLGIsZSk6ZC5zd2FwKGEsYmosZnVuY3Rpb24oKXtmPWJxKGEsYixlKX0pO2lmKGY8PTApe2Y9Ym0oYSxiLGIpLGY9PT0iMHB4IiYmYm8mJihmPWJvKGEsYixiKSk7aWYoZiE9bnVsbClyZXR1cm4gZj09PSIifHxmPT09ImF1dG8iPyIwcHgiOmZ9aWYoZjwwfHxmPT1udWxsKXtmPWEuc3R5bGVbYl07cmV0dXJuIGY9PT0iInx8Zj09PSJhdXRvIj8iMHB4IjpmfXJldHVybiB0eXBlb2YgZj09PSJzdHJpbmciP2Y6ZisicHgifX0sc2V0OmZ1bmN0aW9uKGEsYil7aWYoIWJoLnRlc3QoYikpcmV0dXJuIGI7Yj1wYXJzZUZsb2F0KGIpO2lmKGI+PTApcmV0dXJuIGIrInB4In19fSksZC5zdXBwb3J0Lm9wYWNpdHl8fChkLmNzc0hvb2tzLm9wYWNpdHk9e2dldDpmdW5jdGlvbihhLGIpe3JldHVybiBiZS50ZXN0KChiJiZhLmN1cnJlbnRTdHlsZT9hLmN1cnJlbnRTdHlsZS5maWx0ZXI6YS5zdHlsZS5maWx0ZXIpfHwiIik/cGFyc2VGbG9hdChSZWdFeHAuJDEpLzEwMCsiIjpiPyIxIjoiIn0sc2V0OmZ1bmN0aW9uKGEsYil7dmFyIGM9YS5zdHlsZTtjLnpvb209MTt2YXIgZT1kLmlzTmFOKGIpPyIiOiJhbHBoYShvcGFjaXR5PSIrYioxMDArIikiLGY9Yy5maWx0ZXJ8fCIiO2MuZmlsdGVyPWJkLnRlc3QoZik/Zi5yZXBsYWNlKGJkLGUpOmMuZmlsdGVyKyIgIitlfX0pLGQoZnVuY3Rpb24oKXtkLnN1cHBvcnQucmVsaWFibGVNYXJnaW5SaWdodHx8KGQuY3NzSG9va3MubWFyZ2luUmlnaHQ9e2dldDpmdW5jdGlvbihhLGIpe3ZhciBjO2Quc3dhcChhLHtkaXNwbGF5OiJpbmxpbmUtYmxvY2sifSxmdW5jdGlvbigpe2I/Yz1ibShhLCJtYXJnaW4tcmlnaHQiLCJtYXJnaW5SaWdodCIpOmM9YS5zdHlsZS5tYXJnaW5SaWdodH0pO3JldHVybiBjfX0pfSksYy5kZWZhdWx0VmlldyYmYy5kZWZhdWx0Vmlldy5nZXRDb21wdXRlZFN0eWxlJiYoYm49ZnVuY3Rpb24oYSxjLGUpe3ZhciBmLGcsaDtlPWUucmVwbGFjZShiZywiLSQxIikudG9Mb3dlckNhc2UoKTtpZighKGc9YS5vd25lckRvY3VtZW50LmRlZmF1bHRWaWV3KSlyZXR1cm4gYjtpZihoPWcuZ2V0Q29tcHV0ZWRTdHlsZShhLG51bGwpKWY9aC5nZXRQcm9wZXJ0eVZhbHVlKGUpLGY9PT0iIiYmIWQuY29udGFpbnMoYS5vd25lckRvY3VtZW50LmRvY3VtZW50RWxlbWVudCxhKSYmKGY9ZC5zdHlsZShhLGUpKTtyZXR1cm4gZn0pLGMuZG9jdW1lbnRFbGVtZW50LmN1cnJlbnRTdHlsZSYmKGJvPWZ1bmN0aW9uKGEsYil7dmFyIGMsZD1hLmN1cnJlbnRTdHlsZSYmYS5jdXJyZW50U3R5bGVbYl0sZT1hLnJ1bnRpbWVTdHlsZSYmYS5ydW50aW1lU3R5bGVbYl0sZj1hLnN0eWxlOyFiaC50ZXN0KGQpJiZiaS50ZXN0KGQpJiYoYz1mLmxlZnQsZSYmKGEucnVudGltZVN0eWxlLmxlZnQ9YS5jdXJyZW50U3R5bGUubGVmdCksZi5sZWZ0PWI9PT0iZm9udFNpemUiPyIxZW0iOmR8fDAsZD1mLnBpeGVsTGVmdCsicHgiLGYubGVmdD1jLGUmJihhLnJ1bnRpbWVTdHlsZS5sZWZ0PWUpKTtyZXR1cm4gZD09PSIiPyJhdXRvIjpkfSksYm09Ym58fGJvLGQuZXhwciYmZC5leHByLmZpbHRlcnMmJihkLmV4cHIuZmlsdGVycy5oaWRkZW49ZnVuY3Rpb24oYSl7dmFyIGI9YS5vZmZzZXRXaWR0aCxjPWEub2Zmc2V0SGVpZ2h0O3JldHVybiBiPT09MCYmYz09PTB8fCFkLnN1cHBvcnQucmVsaWFibGVIaWRkZW5PZmZzZXRzJiYoYS5zdHlsZS5kaXNwbGF5fHxkLmNzcyhhLCJkaXNwbGF5IikpPT09Im5vbmUifSxkLmV4cHIuZmlsdGVycy52aXNpYmxlPWZ1bmN0aW9uKGEpe3JldHVybiFkLmV4cHIuZmlsdGVycy5oaWRkZW4oYSl9KTt2YXIgYnI9LyUyMC9nLGJzPS9cW1xdJC8sYnQ9L1xyP1xuL2csYnU9LyMuKiQvLGJ2PS9eKC4qPyk6WyBcdF0qKFteXHJcbl0qKVxyPyQvbWcsYnc9L14oPzpjb2xvcnxkYXRlfGRhdGV0aW1lfGVtYWlsfGhpZGRlbnxtb250aHxudW1iZXJ8cGFzc3dvcmR8cmFuZ2V8c2VhcmNofHRlbHx0ZXh0fHRpbWV8dXJsfHdlZWspJC9pLGJ4PS9eKD86YWJvdXR8YXBwfGFwcFwtc3RvcmFnZXwuK1wtZXh0ZW5zaW9ufGZpbGV8d2lkZ2V0KTokLyxieT0vXig/OkdFVHxIRUFEKSQvLGJ6PS9eXC9cLy8sYkE9L1w/LyxiQj0vPHNjcmlwdFxiW148XSooPzooPyE8XC9zY3JpcHQ+KTxbXjxdKikqPFwvc2NyaXB0Pi9naSxiQz0vXig/OnNlbGVjdHx0ZXh0YXJlYSkvaSxiRD0vXHMrLyxiRT0vKFs/Jl0pXz1bXiZdKi8sYkY9LyhefFwtKShbYS16XSkvZyxiRz1mdW5jdGlvbihhLGIsYyl7cmV0dXJuIGIrYy50b1VwcGVyQ2FzZSgpfSxiSD0vXihbXHdcK1wuXC1dKzopKD86XC9cLyhbXlwvPyM6XSopKD86OihcZCspKT8pPy8sYkk9ZC5mbi5sb2FkLGJKPXt9LGJLPXt9LGJMLGJNO3RyeXtiTD1jLmxvY2F0aW9uLmhyZWZ9Y2F0Y2goYk4pe2JMPWMuY3JlYXRlRWxlbWVudCgiYSIpLGJMLmhyZWY9IiIsYkw9YkwuaHJlZn1iTT1iSC5leGVjKGJMLnRvTG93ZXJDYXNlKCkpfHxbXSxkLmZuLmV4dGVuZCh7bG9hZDpmdW5jdGlvbihhLGMsZSl7aWYodHlwZW9mIGEhPT0ic3RyaW5nIiYmYkkpcmV0dXJuIGJJLmFwcGx5KHRoaXMsYXJndW1lbnRzKTtpZighdGhpcy5sZW5ndGgpcmV0dXJuIHRoaXM7dmFyIGY9YS5pbmRleE9mKCIgIik7aWYoZj49MCl7dmFyIGc9YS5zbGljZShmLGEubGVuZ3RoKTthPWEuc2xpY2UoMCxmKX12YXIgaD0iR0VUIjtjJiYoZC5pc0Z1bmN0aW9uKGMpPyhlPWMsYz1iKTp0eXBlb2YgYz09PSJvYmplY3QiJiYoYz1kLnBhcmFtKGMsZC5hamF4U2V0dGluZ3MudHJhZGl0aW9uYWwpLGg9IlBPU1QiKSk7dmFyIGk9dGhpcztkLmFqYXgoe3VybDphLHR5cGU6aCxkYXRhVHlwZToiaHRtbCIsZGF0YTpjLGNvbXBsZXRlOmZ1bmN0aW9uKGEsYixjKXtjPWEucmVzcG9uc2VUZXh0LGEuaXNSZXNvbHZlZCgpJiYoYS5kb25lKGZ1bmN0aW9uKGEpe2M9YX0pLGkuaHRtbChnP2QoIjxkaXY+IikuYXBwZW5kKGMucmVwbGFjZShiQiwiIikpLmZpbmQoZyk6YykpLGUmJmkuZWFjaChlLFtjLGIsYV0pfX0pO3JldHVybiB0aGlzfSxzZXJpYWxpemU6ZnVuY3Rpb24oKXtyZXR1cm4gZC5wYXJhbSh0aGlzLnNlcmlhbGl6ZUFycmF5KCkpfSxzZXJpYWxpemVBcnJheTpmdW5jdGlvbigpe3JldHVybiB0aGlzLm1hcChmdW5jdGlvbigpe3JldHVybiB0aGlzLmVsZW1lbnRzP2QubWFrZUFycmF5KHRoaXMuZWxlbWVudHMpOnRoaXN9KS5maWx0ZXIoZnVuY3Rpb24oKXtyZXR1cm4gdGhpcy5uYW1lJiYhdGhpcy5kaXNhYmxlZCYmKHRoaXMuY2hlY2tlZHx8YkMudGVzdCh0aGlzLm5vZGVOYW1lKXx8YncudGVzdCh0aGlzLnR5cGUpKX0pLm1hcChmdW5jdGlvbihhLGIpe3ZhciBjPWQodGhpcykudmFsKCk7cmV0dXJuIGM9PW51bGw/bnVsbDpkLmlzQXJyYXkoYyk/ZC5tYXAoYyxmdW5jdGlvbihhLGMpe3JldHVybntuYW1lOmIubmFtZSx2YWx1ZTphLnJlcGxhY2UoYnQsIlxyXG4iKX19KTp7bmFtZTpiLm5hbWUsdmFsdWU6Yy5yZXBsYWNlKGJ0LCJcclxuIil9fSkuZ2V0KCl9fSksZC5lYWNoKCJhamF4U3RhcnQgYWpheFN0b3AgYWpheENvbXBsZXRlIGFqYXhFcnJvciBhamF4U3VjY2VzcyBhamF4U2VuZCIuc3BsaXQoIiAiKSxmdW5jdGlvbihhLGIpe2QuZm5bYl09ZnVuY3Rpb24oYSl7cmV0dXJuIHRoaXMuYmluZChiLGEpfX0pLGQuZWFjaChbImdldCIsInBvc3QiXSxmdW5jdGlvbihhLGMpe2RbY109ZnVuY3Rpb24oYSxlLGYsZyl7ZC5pc0Z1bmN0aW9uKGUpJiYoZz1nfHxmLGY9ZSxlPWIpO3JldHVybiBkLmFqYXgoe3R5cGU6Yyx1cmw6YSxkYXRhOmUsc3VjY2VzczpmLGRhdGFUeXBlOmd9KX19KSxkLmV4dGVuZCh7Z2V0U2NyaXB0OmZ1bmN0aW9uKGEsYyl7cmV0dXJuIGQuZ2V0KGEsYixjLCJzY3JpcHQiKX0sZ2V0SlNPTjpmdW5jdGlvbihhLGIsYyl7cmV0dXJuIGQuZ2V0KGEsYixjLCJqc29uIil9LGFqYXhTZXR1cDpmdW5jdGlvbihhLGIpe2I/ZC5leHRlbmQoITAsYSxkLmFqYXhTZXR0aW5ncyxiKTooYj1hLGE9ZC5leHRlbmQoITAsZC5hamF4U2V0dGluZ3MsYikpO2Zvcih2YXIgYyBpbiB7Y29udGV4dDoxLHVybDoxfSljIGluIGI/YVtjXT1iW2NdOmMgaW4gZC5hamF4U2V0dGluZ3MmJihhW2NdPWQuYWpheFNldHRpbmdzW2NdKTtyZXR1cm4gYX0sYWpheFNldHRpbmdzOnt1cmw6YkwsaXNMb2NhbDpieC50ZXN0KGJNWzFdKSxnbG9iYWw6ITAsdHlwZToiR0VUIixjb250ZW50VHlwZToiYXBwbGljYXRpb24veC13d3ctZm9ybS11cmxlbmNvZGVkIixwcm9jZXNzRGF0YTohMCxhc3luYzohMCxhY2NlcHRzOnt4bWw6ImFwcGxpY2F0aW9uL3htbCwgdGV4dC94bWwiLGh0bWw6InRleHQvaHRtbCIsdGV4dDoidGV4dC9wbGFpbiIsanNvbjoiYXBwbGljYXRpb24vanNvbiwgdGV4dC9qYXZhc2NyaXB0IiwiKiI6IiovKiJ9LGNvbnRlbnRzOnt4bWw6L3htbC8saHRtbDovaHRtbC8sanNvbjovanNvbi99LHJlc3BvbnNlRmllbGRzOnt4bWw6InJlc3BvbnNlWE1MIix0ZXh0OiJyZXNwb25zZVRleHQifSxjb252ZXJ0ZXJzOnsiKiB0ZXh0IjphLlN0cmluZywidGV4dCBodG1sIjohMCwidGV4dCBqc29uIjpkLnBhcnNlSlNPTiwidGV4dCB4bWwiOmQucGFyc2VYTUx9fSxhamF4UHJlZmlsdGVyOmJPKGJKKSxhamF4VHJhbnNwb3J0OmJPKGJLKSxhamF4OmZ1bmN0aW9uKGEsYyl7ZnVuY3Rpb24gdihhLGMsbCxuKXtpZihyIT09Mil7cj0yLHAmJmNsZWFyVGltZW91dChwKSxvPWIsbT1ufHwiIix1LnJlYWR5U3RhdGU9YT80OjA7dmFyIHEsdCx2LHc9bD9iUihlLHUsbCk6Yix4LHk7aWYoYT49MjAwJiZhPDMwMHx8YT09PTMwNCl7aWYoZS5pZk1vZGlmaWVkKXtpZih4PXUuZ2V0UmVzcG9uc2VIZWFkZXIoIkxhc3QtTW9kaWZpZWQiKSlkLmxhc3RNb2RpZmllZFtrXT14O2lmKHk9dS5nZXRSZXNwb25zZUhlYWRlcigiRXRhZyIpKWQuZXRhZ1trXT15fWlmKGE9PT0zMDQpYz0ibm90bW9kaWZpZWQiLHE9ITA7ZWxzZSB0cnl7dD1iUyhlLHcpLGM9InN1Y2Nlc3MiLHE9ITB9Y2F0Y2goeil7Yz0icGFyc2VyZXJyb3IiLHY9en19ZWxzZXt2PWM7aWYoIWN8fGEpYz0iZXJyb3IiLGE8MCYmKGE9MCl9dS5zdGF0dXM9YSx1LnN0YXR1c1RleHQ9YyxxP2gucmVzb2x2ZVdpdGgoZixbdCxjLHVdKTpoLnJlamVjdFdpdGgoZixbdSxjLHZdKSx1LnN0YXR1c0NvZGUoaiksaj1iLHMmJmcudHJpZ2dlcigiYWpheCIrKHE/IlN1Y2Nlc3MiOiJFcnJvciIpLFt1LGUscT90OnZdKSxpLnJlc29sdmVXaXRoKGYsW3UsY10pLHMmJihnLnRyaWdnZXIoImFqYXhDb21wbGV0ZSIsW3UsZV0pLC0tZC5hY3RpdmV8fGQuZXZlbnQudHJpZ2dlcigiYWpheFN0b3AiKSl9fXR5cGVvZiBhPT09Im9iamVjdCImJihjPWEsYT1iKSxjPWN8fHt9O3ZhciBlPWQuYWpheFNldHVwKHt9LGMpLGY9ZS5jb250ZXh0fHxlLGc9ZiE9PWUmJihmLm5vZGVUeXBlfHxmIGluc3RhbmNlb2YgZCk/ZChmKTpkLmV2ZW50LGg9ZC5EZWZlcnJlZCgpLGk9ZC5fRGVmZXJyZWQoKSxqPWUuc3RhdHVzQ29kZXx8e30sayxsPXt9LG0sbixvLHAscSxyPTAscyx0LHU9e3JlYWR5U3RhdGU6MCxzZXRSZXF1ZXN0SGVhZGVyOmZ1bmN0aW9uKGEsYil7cnx8KGxbYS50b0xvd2VyQ2FzZSgpLnJlcGxhY2UoYkYsYkcpXT1iKTtyZXR1cm4gdGhpc30sZ2V0QWxsUmVzcG9uc2VIZWFkZXJzOmZ1bmN0aW9uKCl7cmV0dXJuIHI9PT0yP206bnVsbH0sZ2V0UmVzcG9uc2VIZWFkZXI6ZnVuY3Rpb24oYSl7dmFyIGM7aWYocj09PTIpe2lmKCFuKXtuPXt9O3doaWxlKGM9YnYuZXhlYyhtKSluW2NbMV0udG9Mb3dlckNhc2UoKV09Y1syXX1jPW5bYS50b0xvd2VyQ2FzZSgpXX1yZXR1cm4gYz09PWI/bnVsbDpjfSxvdmVycmlkZU1pbWVUeXBlOmZ1bmN0aW9uKGEpe3J8fChlLm1pbWVUeXBlPWEpO3JldHVybiB0aGlzfSxhYm9ydDpmdW5jdGlvbihhKXthPWF8fCJhYm9ydCIsbyYmby5hYm9ydChhKSx2KDAsYSk7cmV0dXJuIHRoaXN9fTtoLnByb21pc2UodSksdS5zdWNjZXNzPXUuZG9uZSx1LmVycm9yPXUuZmFpbCx1LmNvbXBsZXRlPWkuZG9uZSx1LnN0YXR1c0NvZGU9ZnVuY3Rpb24oYSl7aWYoYSl7dmFyIGI7aWYocjwyKWZvcihiIGluIGEpaltiXT1baltiXSxhW2JdXTtlbHNlIGI9YVt1LnN0YXR1c10sdS50aGVuKGIsYil9cmV0dXJuIHRoaXN9LGUudXJsPSgoYXx8ZS51cmwpKyIiKS5yZXBsYWNlKGJ1LCIiKS5yZXBsYWNlKGJ6LGJNWzFdKyIvLyIpLGUuZGF0YVR5cGVzPWQudHJpbShlLmRhdGFUeXBlfHwiKiIpLnRvTG93ZXJDYXNlKCkuc3BsaXQoYkQpLGUuY3Jvc3NEb21haW49PW51bGwmJihxPWJILmV4ZWMoZS51cmwudG9Mb3dlckNhc2UoKSksZS5jcm9zc0RvbWFpbj1xJiYocVsxXSE9Yk1bMV18fHFbMl0hPWJNWzJdfHwocVszXXx8KHFbMV09PT0iaHR0cDoiPzgwOjQ0MykpIT0oYk1bM118fChiTVsxXT09PSJodHRwOiI/ODA6NDQzKSkpKSxlLmRhdGEmJmUucHJvY2Vzc0RhdGEmJnR5cGVvZiBlLmRhdGEhPT0ic3RyaW5nIiYmKGUuZGF0YT1kLnBhcmFtKGUuZGF0YSxlLnRyYWRpdGlvbmFsKSksYlAoYkosZSxjLHUpO2lmKHI9PT0yKXJldHVybiExO3M9ZS5nbG9iYWwsZS50eXBlPWUudHlwZS50b1VwcGVyQ2FzZSgpLGUuaGFzQ29udGVudD0hYnkudGVzdChlLnR5cGUpLHMmJmQuYWN0aXZlKys9PT0wJiZkLmV2ZW50LnRyaWdnZXIoImFqYXhTdGFydCIpO2lmKCFlLmhhc0NvbnRlbnQpe2UuZGF0YSYmKGUudXJsKz0oYkEudGVzdChlLnVybCk/IiYiOiI/IikrZS5kYXRhKSxrPWUudXJsO2lmKGUuY2FjaGU9PT0hMSl7dmFyIHc9ZC5ub3coKSx4PWUudXJsLnJlcGxhY2UoYkUsIiQxXz0iK3cpO2UudXJsPXgrKHg9PT1lLnVybD8oYkEudGVzdChlLnVybCk/IiYiOiI/IikrIl89Iit3OiIiKX19aWYoZS5kYXRhJiZlLmhhc0NvbnRlbnQmJmUuY29udGVudFR5cGUhPT0hMXx8Yy5jb250ZW50VHlwZSlsWyJDb250ZW50LVR5cGUiXT1lLmNvbnRlbnRUeXBlO2UuaWZNb2RpZmllZCYmKGs9a3x8ZS51cmwsZC5sYXN0TW9kaWZpZWRba10mJihsWyJJZi1Nb2RpZmllZC1TaW5jZSJdPWQubGFzdE1vZGlmaWVkW2tdKSxkLmV0YWdba10mJihsWyJJZi1Ob25lLU1hdGNoIl09ZC5ldGFnW2tdKSksbC5BY2NlcHQ9ZS5kYXRhVHlwZXNbMF0mJmUuYWNjZXB0c1tlLmRhdGFUeXBlc1swXV0/ZS5hY2NlcHRzW2UuZGF0YVR5cGVzWzBdXSsoZS5kYXRhVHlwZXNbMF0hPT0iKiI/IiwgKi8qOyBxPTAuMDEiOiIiKTplLmFjY2VwdHNbIioiXTtmb3IodCBpbiBlLmhlYWRlcnMpdS5zZXRSZXF1ZXN0SGVhZGVyKHQsZS5oZWFkZXJzW3RdKTtpZihlLmJlZm9yZVNlbmQmJihlLmJlZm9yZVNlbmQuY2FsbChmLHUsZSk9PT0hMXx8cj09PTIpKXt1LmFib3J0KCk7cmV0dXJuITF9Zm9yKHQgaW4ge3N1Y2Nlc3M6MSxlcnJvcjoxLGNvbXBsZXRlOjF9KXVbdF0oZVt0XSk7bz1iUChiSyxlLGMsdSk7aWYobyl7dS5yZWFkeVN0YXRlPTEscyYmZy50cmlnZ2VyKCJhamF4U2VuZCIsW3UsZV0pLGUuYXN5bmMmJmUudGltZW91dD4wJiYocD1zZXRUaW1lb3V0KGZ1bmN0aW9uKCl7dS5hYm9ydCgidGltZW91dCIpfSxlLnRpbWVvdXQpKTt0cnl7cj0xLG8uc2VuZChsLHYpfWNhdGNoKHkpe3N0YXR1czwyP3YoLTEseSk6ZC5lcnJvcih5KX19ZWxzZSB2KC0xLCJObyBUcmFuc3BvcnQiKTtyZXR1cm4gdX0scGFyYW06ZnVuY3Rpb24oYSxjKXt2YXIgZT1bXSxmPWZ1bmN0aW9uKGEsYil7Yj1kLmlzRnVuY3Rpb24oYik/YigpOmIsZVtlLmxlbmd0aF09ZW5jb2RlVVJJQ29tcG9uZW50KGEpKyI9IitlbmNvZGVVUklDb21wb25lbnQoYil9O2M9PT1iJiYoYz1kLmFqYXhTZXR0aW5ncy50cmFkaXRpb25hbCk7aWYoZC5pc0FycmF5KGEpfHxhLmpxdWVyeSYmIWQuaXNQbGFpbk9iamVjdChhKSlkLmVhY2goYSxmdW5jdGlvbigpe2YodGhpcy5uYW1lLHRoaXMudmFsdWUpfSk7ZWxzZSBmb3IodmFyIGcgaW4gYSliUShnLGFbZ10sYyxmKTtyZXR1cm4gZS5qb2luKCImIikucmVwbGFjZShiciwiKyIpfX0pLGQuZXh0ZW5kKHthY3RpdmU6MCxsYXN0TW9kaWZpZWQ6e30sZXRhZzp7fX0pO3ZhciBiVD1kLm5vdygpLGJVPS8oXD0pXD8oJnwkKXxcP1w/L2k7ZC5hamF4U2V0dXAoe2pzb25wOiJjYWxsYmFjayIsanNvbnBDYWxsYmFjazpmdW5jdGlvbigpe3JldHVybiBkLmV4cGFuZG8rIl8iK2JUKyt9fSksZC5hamF4UHJlZmlsdGVyKCJqc29uIGpzb25wIixmdW5jdGlvbihiLGMsZSl7dmFyIGY9dHlwZW9mIGIuZGF0YT09PSJzdHJpbmciO2lmKGIuZGF0YVR5cGVzWzBdPT09Impzb25wInx8Yy5qc29ucENhbGxiYWNrfHxjLmpzb25wIT1udWxsfHxiLmpzb25wIT09ITEmJihiVS50ZXN0KGIudXJsKXx8ZiYmYlUudGVzdChiLmRhdGEpKSl7dmFyIGcsaD1iLmpzb25wQ2FsbGJhY2s9ZC5pc0Z1bmN0aW9uKGIuanNvbnBDYWxsYmFjayk/Yi5qc29ucENhbGxiYWNrKCk6Yi5qc29ucENhbGxiYWNrLGk9YVtoXSxqPWIudXJsLGs9Yi5kYXRhLGw9IiQxIitoKyIkMiIsbT1mdW5jdGlvbigpe2FbaF09aSxnJiZkLmlzRnVuY3Rpb24oaSkmJmFbaF0oZ1swXSl9O2IuanNvbnAhPT0hMSYmKGo9ai5yZXBsYWNlKGJVLGwpLGIudXJsPT09aiYmKGYmJihrPWsucmVwbGFjZShiVSxsKSksYi5kYXRhPT09ayYmKGorPSgvXD8vLnRlc3Qoaik/IiYiOiI/IikrYi5qc29ucCsiPSIraCkpKSxiLnVybD1qLGIuZGF0YT1rLGFbaF09ZnVuY3Rpb24oYSl7Zz1bYV19LGUudGhlbihtLG0pLGIuY29udmVydGVyc1sic2NyaXB0IGpzb24iXT1mdW5jdGlvbigpe2d8fGQuZXJyb3IoaCsiIHdhcyBub3QgY2FsbGVkIik7cmV0dXJuIGdbMF19LGIuZGF0YVR5cGVzWzBdPSJqc29uIjtyZXR1cm4ic2NyaXB0In19KSxkLmFqYXhTZXR1cCh7YWNjZXB0czp7c2NyaXB0OiJ0ZXh0L2phdmFzY3JpcHQsIGFwcGxpY2F0aW9uL2phdmFzY3JpcHQsIGFwcGxpY2F0aW9uL2VjbWFzY3JpcHQsIGFwcGxpY2F0aW9uL3gtZWNtYXNjcmlwdCJ9LGNvbnRlbnRzOntzY3JpcHQ6L2phdmFzY3JpcHR8ZWNtYXNjcmlwdC99LGNvbnZlcnRlcnM6eyJ0ZXh0IHNjcmlwdCI6ZnVuY3Rpb24oYSl7ZC5nbG9iYWxFdmFsKGEpO3JldHVybiBhfX19KSxkLmFqYXhQcmVmaWx0ZXIoInNjcmlwdCIsZnVuY3Rpb24oYSl7YS5jYWNoZT09PWImJihhLmNhY2hlPSExKSxhLmNyb3NzRG9tYWluJiYoYS50eXBlPSJHRVQiLGEuZ2xvYmFsPSExKX0pLGQuYWpheFRyYW5zcG9ydCgic2NyaXB0IixmdW5jdGlvbihhKXtpZihhLmNyb3NzRG9tYWluKXt2YXIgZCxlPWMuaGVhZHx8Yy5nZXRFbGVtZW50c0J5VGFnTmFtZSgiaGVhZCIpWzBdfHxjLmRvY3VtZW50RWxlbWVudDtyZXR1cm57c2VuZDpmdW5jdGlvbihmLGcpe2Q9Yy5jcmVhdGVFbGVtZW50KCJzY3JpcHQiKSxkLmFzeW5jPSJhc3luYyIsYS5zY3JpcHRDaGFyc2V0JiYoZC5jaGFyc2V0PWEuc2NyaXB0Q2hhcnNldCksZC5zcmM9YS51cmwsZC5vbmxvYWQ9ZC5vbnJlYWR5c3RhdGVjaGFuZ2U9ZnVuY3Rpb24oYSxjKXtpZighZC5yZWFkeVN0YXRlfHwvbG9hZGVkfGNvbXBsZXRlLy50ZXN0KGQucmVhZHlTdGF0ZSkpZC5vbmxvYWQ9ZC5vbnJlYWR5c3RhdGVjaGFuZ2U9bnVsbCxlJiZkLnBhcmVudE5vZGUmJmUucmVtb3ZlQ2hpbGQoZCksZD1iLGN8fGcoMjAwLCJzdWNjZXNzIil9LGUuaW5zZXJ0QmVmb3JlKGQsZS5maXJzdENoaWxkKX0sYWJvcnQ6ZnVuY3Rpb24oKXtkJiZkLm9ubG9hZCgwLDEpfX19fSk7dmFyIGJWPWQubm93KCksYlcsYlg7ZC5hamF4U2V0dGluZ3MueGhyPWEuQWN0aXZlWE9iamVjdD9mdW5jdGlvbigpe3JldHVybiF0aGlzLmlzTG9jYWwmJmJaKCl8fGIkKCl9OmJaLGJYPWQuYWpheFNldHRpbmdzLnhocigpLGQuc3VwcG9ydC5hamF4PSEhYlgsZC5zdXBwb3J0LmNvcnM9YlgmJiJ3aXRoQ3JlZGVudGlhbHMiaW4gYlgsYlg9YixkLnN1cHBvcnQuYWpheCYmZC5hamF4VHJhbnNwb3J0KGZ1bmN0aW9uKGEpe2lmKCFhLmNyb3NzRG9tYWlufHxkLnN1cHBvcnQuY29ycyl7dmFyIGM7cmV0dXJue3NlbmQ6ZnVuY3Rpb24oZSxmKXt2YXIgZz1hLnhocigpLGgsaTthLnVzZXJuYW1lP2cub3BlbihhLnR5cGUsYS51cmwsYS5hc3luYyxhLnVzZXJuYW1lLGEucGFzc3dvcmQpOmcub3BlbihhLnR5cGUsYS51cmwsYS5hc3luYyk7aWYoYS54aHJGaWVsZHMpZm9yKGkgaW4gYS54aHJGaWVsZHMpZ1tpXT1hLnhockZpZWxkc1tpXTthLm1pbWVUeXBlJiZnLm92ZXJyaWRlTWltZVR5cGUmJmcub3ZlcnJpZGVNaW1lVHlwZShhLm1pbWVUeXBlKSwhYS5jcm9zc0RvbWFpbiYmIWVbIlgtUmVxdWVzdGVkLVdpdGgiXSYmKGVbIlgtUmVxdWVzdGVkLVdpdGgiXT0iWE1MSHR0cFJlcXVlc3QiKTt0cnl7Zm9yKGkgaW4gZSlnLnNldFJlcXVlc3RIZWFkZXIoaSxlW2ldKX1jYXRjaChqKXt9Zy5zZW5kKGEuaGFzQ29udGVudCYmYS5kYXRhfHxudWxsKSxjPWZ1bmN0aW9uKGUsaSl7dmFyIGosayxsLG0sbjt0cnl7aWYoYyYmKGl8fGcucmVhZHlTdGF0ZT09PTQpKXtjPWIsaCYmKGcub25yZWFkeXN0YXRlY2hhbmdlPWQubm9vcCxkZWxldGUgYldbaF0pO2lmKGkpZy5yZWFkeVN0YXRlIT09NCYmZy5hYm9ydCgpO2Vsc2V7aj1nLnN0YXR1cyxsPWcuZ2V0QWxsUmVzcG9uc2VIZWFkZXJzKCksbT17fSxuPWcucmVzcG9uc2VYTUwsbiYmbi5kb2N1bWVudEVsZW1lbnQmJihtLnhtbD1uKSxtLnRleHQ9Zy5yZXNwb25zZVRleHQ7dHJ5e2s9Zy5zdGF0dXNUZXh0fWNhdGNoKG8pe2s9IiJ9anx8IWEuaXNMb2NhbHx8YS5jcm9zc0RvbWFpbj9qPT09MTIyMyYmKGo9MjA0KTpqPW0udGV4dD8yMDA6NDA0fX19Y2F0Y2gocCl7aXx8ZigtMSxwKX1tJiZmKGosayxtLGwpfSxhLmFzeW5jJiZnLnJlYWR5U3RhdGUhPT00PyhiV3x8KGJXPXt9LGJZKCkpLGg9YlYrKyxnLm9ucmVhZHlzdGF0ZWNoYW5nZT1iV1toXT1jKTpjKCl9LGFib3J0OmZ1bmN0aW9uKCl7YyYmYygwLDEpfX19fSk7dmFyIGJfPXt9LGNhPS9eKD86dG9nZ2xlfHNob3d8aGlkZSkkLyxjYj0vXihbK1wtXT0pPyhbXGQrLlwtXSspKFthLXolXSopJC9pLGNjLGNkPVtbImhlaWdodCIsIm1hcmdpblRvcCIsIm1hcmdpbkJvdHRvbSIsInBhZGRpbmdUb3AiLCJwYWRkaW5nQm90dG9tIl0sWyJ3aWR0aCIsIm1hcmdpbkxlZnQiLCJtYXJnaW5SaWdodCIsInBhZGRpbmdMZWZ0IiwicGFkZGluZ1JpZ2h0Il0sWyJvcGFjaXR5Il1dO2QuZm4uZXh0ZW5kKHtzaG93OmZ1bmN0aW9uKGEsYixjKXt2YXIgZSxmO2lmKGF8fGE9PT0wKXJldHVybiB0aGlzLmFuaW1hdGUoY2UoInNob3ciLDMpLGEsYixjKTtmb3IodmFyIGc9MCxoPXRoaXMubGVuZ3RoO2c8aDtnKyspZT10aGlzW2ddLGY9ZS5zdHlsZS5kaXNwbGF5LCFkLl9kYXRhKGUsIm9sZGRpc3BsYXkiKSYmZj09PSJub25lIiYmKGY9ZS5zdHlsZS5kaXNwbGF5PSIiKSxmPT09IiImJmQuY3NzKGUsImRpc3BsYXkiKT09PSJub25lIiYmZC5fZGF0YShlLCJvbGRkaXNwbGF5IixjZihlLm5vZGVOYW1lKSk7Zm9yKGc9MDtnPGg7ZysrKXtlPXRoaXNbZ10sZj1lLnN0eWxlLmRpc3BsYXk7aWYoZj09PSIifHxmPT09Im5vbmUiKWUuc3R5bGUuZGlzcGxheT1kLl9kYXRhKGUsIm9sZGRpc3BsYXkiKXx8IiJ9cmV0dXJuIHRoaXN9LGhpZGU6ZnVuY3Rpb24oYSxiLGMpe2lmKGF8fGE9PT0wKXJldHVybiB0aGlzLmFuaW1hdGUoY2UoImhpZGUiLDMpLGEsYixjKTtmb3IodmFyIGU9MCxmPXRoaXMubGVuZ3RoO2U8ZjtlKyspe3ZhciBnPWQuY3NzKHRoaXNbZV0sImRpc3BsYXkiKTtnIT09Im5vbmUiJiYhZC5fZGF0YSh0aGlzW2VdLCJvbGRkaXNwbGF5IikmJmQuX2RhdGEodGhpc1tlXSwib2xkZGlzcGxheSIsZyl9Zm9yKGU9MDtlPGY7ZSsrKXRoaXNbZV0uc3R5bGUuZGlzcGxheT0ibm9uZSI7cmV0dXJuIHRoaXN9LF90b2dnbGU6ZC5mbi50b2dnbGUsdG9nZ2xlOmZ1bmN0aW9uKGEsYixjKXt2YXIgZT10eXBlb2YgYT09PSJib29sZWFuIjtkLmlzRnVuY3Rpb24oYSkmJmQuaXNGdW5jdGlvbihiKT90aGlzLl90b2dnbGUuYXBwbHkodGhpcyxhcmd1bWVudHMpOmE9PW51bGx8fGU/dGhpcy5lYWNoKGZ1bmN0aW9uKCl7dmFyIGI9ZT9hOmQodGhpcykuaXMoIjpoaWRkZW4iKTtkKHRoaXMpW2I/InNob3ciOiJoaWRlIl0oKX0pOnRoaXMuYW5pbWF0ZShjZSgidG9nZ2xlIiwzKSxhLGIsYyk7cmV0dXJuIHRoaXN9LGZhZGVUbzpmdW5jdGlvbihhLGIsYyxkKXtyZXR1cm4gdGhpcy5maWx0ZXIoIjpoaWRkZW4iKS5jc3MoIm9wYWNpdHkiLDApLnNob3coKS5lbmQoKS5hbmltYXRlKHtvcGFjaXR5OmJ9LGEsYyxkKX0sYW5pbWF0ZTpmdW5jdGlvbihhLGIsYyxlKXt2YXIgZj1kLnNwZWVkKGIsYyxlKTtpZihkLmlzRW1wdHlPYmplY3QoYSkpcmV0dXJuIHRoaXMuZWFjaChmLmNvbXBsZXRlKTtyZXR1cm4gdGhpc1tmLnF1ZXVlPT09ITE/ImVhY2giOiJxdWV1ZSJdKGZ1bmN0aW9uKCl7dmFyIGI9ZC5leHRlbmQoe30sZiksYyxlPXRoaXMubm9kZVR5cGU9PT0xLGc9ZSYmZCh0aGlzKS5pcygiOmhpZGRlbiIpLGg9dGhpcztmb3IoYyBpbiBhKXt2YXIgaT1kLmNhbWVsQ2FzZShjKTtjIT09aSYmKGFbaV09YVtjXSxkZWxldGUgYVtjXSxjPWkpO2lmKGFbY109PT0iaGlkZSImJmd8fGFbY109PT0ic2hvdyImJiFnKXJldHVybiBiLmNvbXBsZXRlLmNhbGwodGhpcyk7aWYoZSYmKGM9PT0iaGVpZ2h0Inx8Yz09PSJ3aWR0aCIpKXtiLm92ZXJmbG93PVt0aGlzLnN0eWxlLm92ZXJmbG93LHRoaXMuc3R5bGUub3ZlcmZsb3dYLHRoaXMuc3R5bGUub3ZlcmZsb3dZXTtpZihkLmNzcyh0aGlzLCJkaXNwbGF5Iik9PT0iaW5saW5lIiYmZC5jc3ModGhpcywiZmxvYXQiKT09PSJub25lIilpZihkLnN1cHBvcnQuaW5saW5lQmxvY2tOZWVkc0xheW91dCl7dmFyIGo9Y2YodGhpcy5ub2RlTmFtZSk7aj09PSJpbmxpbmUiP3RoaXMuc3R5bGUuZGlzcGxheT0iaW5saW5lLWJsb2NrIjoodGhpcy5zdHlsZS5kaXNwbGF5PSJpbmxpbmUiLHRoaXMuc3R5bGUuem9vbT0xKX1lbHNlIHRoaXMuc3R5bGUuZGlzcGxheT0iaW5saW5lLWJsb2NrIn1kLmlzQXJyYXkoYVtjXSkmJigoYi5zcGVjaWFsRWFzaW5nPWIuc3BlY2lhbEVhc2luZ3x8e30pW2NdPWFbY11bMV0sYVtjXT1hW2NdWzBdKX1iLm92ZXJmbG93IT1udWxsJiYodGhpcy5zdHlsZS5vdmVyZmxvdz0iaGlkZGVuIiksYi5jdXJBbmltPWQuZXh0ZW5kKHt9LGEpLGQuZWFjaChhLGZ1bmN0aW9uKGMsZSl7dmFyIGY9bmV3IGQuZngoaCxiLGMpO2lmKGNhLnRlc3QoZSkpZltlPT09InRvZ2dsZSI/Zz8ic2hvdyI6ImhpZGUiOmVdKGEpO2Vsc2V7dmFyIGk9Y2IuZXhlYyhlKSxqPWYuY3VyKCk7aWYoaSl7dmFyIGs9cGFyc2VGbG9hdChpWzJdKSxsPWlbM118fChkLmNzc051bWJlcltjXT8iIjoicHgiKTtsIT09InB4IiYmKGQuc3R5bGUoaCxjLChrfHwxKStsKSxqPShrfHwxKS9mLmN1cigpKmosZC5zdHlsZShoLGMsaitsKSksaVsxXSYmKGs9KGlbMV09PT0iLT0iPy0xOjEpKmsraiksZi5jdXN0b20oaixrLGwpfWVsc2UgZi5jdXN0b20oaixlLCIiKX19KTtyZXR1cm4hMH0pfSxzdG9wOmZ1bmN0aW9uKGEsYil7dmFyIGM9ZC50aW1lcnM7YSYmdGhpcy5xdWV1ZShbXSksdGhpcy5lYWNoKGZ1bmN0aW9uKCl7Zm9yKHZhciBhPWMubGVuZ3RoLTE7YT49MDthLS0pY1thXS5lbGVtPT09dGhpcyYmKGImJmNbYV0oITApLGMuc3BsaWNlKGEsMSkpfSksYnx8dGhpcy5kZXF1ZXVlKCk7cmV0dXJuIHRoaXN9fSksZC5lYWNoKHtzbGlkZURvd246Y2UoInNob3ciLDEpLHNsaWRlVXA6Y2UoImhpZGUiLDEpLHNsaWRlVG9nZ2xlOmNlKCJ0b2dnbGUiLDEpLGZhZGVJbjp7b3BhY2l0eToic2hvdyJ9LGZhZGVPdXQ6e29wYWNpdHk6ImhpZGUifSxmYWRlVG9nZ2xlOntvcGFjaXR5OiJ0b2dnbGUifX0sZnVuY3Rpb24oYSxiKXtkLmZuW2FdPWZ1bmN0aW9uKGEsYyxkKXtyZXR1cm4gdGhpcy5hbmltYXRlKGIsYSxjLGQpfX0pLGQuZXh0ZW5kKHtzcGVlZDpmdW5jdGlvbihhLGIsYyl7dmFyIGU9YSYmdHlwZW9mIGE9PT0ib2JqZWN0Ij9kLmV4dGVuZCh7fSxhKTp7Y29tcGxldGU6Y3x8IWMmJmJ8fGQuaXNGdW5jdGlvbihhKSYmYSxkdXJhdGlvbjphLGVhc2luZzpjJiZifHxiJiYhZC5pc0Z1bmN0aW9uKGIpJiZifTtlLmR1cmF0aW9uPWQuZngub2ZmPzA6dHlwZW9mIGUuZHVyYXRpb249PT0ibnVtYmVyIj9lLmR1cmF0aW9uOmUuZHVyYXRpb24gaW4gZC5meC5zcGVlZHM/ZC5meC5zcGVlZHNbZS5kdXJhdGlvbl06ZC5meC5zcGVlZHMuX2RlZmF1bHQsZS5vbGQ9ZS5jb21wbGV0ZSxlLmNvbXBsZXRlPWZ1bmN0aW9uKCl7ZS5xdWV1ZSE9PSExJiZkKHRoaXMpLmRlcXVldWUoKSxkLmlzRnVuY3Rpb24oZS5vbGQpJiZlLm9sZC5jYWxsKHRoaXMpfTtyZXR1cm4gZX0sZWFzaW5nOntsaW5lYXI6ZnVuY3Rpb24oYSxiLGMsZCl7cmV0dXJuIGMrZCphfSxzd2luZzpmdW5jdGlvbihhLGIsYyxkKXtyZXR1cm4oLU1hdGguY29zKGEqTWF0aC5QSSkvMisuNSkqZCtjfX0sdGltZXJzOltdLGZ4OmZ1bmN0aW9uKGEsYixjKXt0aGlzLm9wdGlvbnM9Yix0aGlzLmVsZW09YSx0aGlzLnByb3A9YyxiLm9yaWd8fChiLm9yaWc9e30pfX0pLGQuZngucHJvdG90eXBlPXt1cGRhdGU6ZnVuY3Rpb24oKXt0aGlzLm9wdGlvbnMuc3RlcCYmdGhpcy5vcHRpb25zLnN0ZXAuY2FsbCh0aGlzLmVsZW0sdGhpcy5ub3csdGhpcyksKGQuZnguc3RlcFt0aGlzLnByb3BdfHxkLmZ4LnN0ZXAuX2RlZmF1bHQpKHRoaXMpfSxjdXI6ZnVuY3Rpb24oKXtpZih0aGlzLmVsZW1bdGhpcy5wcm9wXSE9bnVsbCYmKCF0aGlzLmVsZW0uc3R5bGV8fHRoaXMuZWxlbS5zdHlsZVt0aGlzLnByb3BdPT1udWxsKSlyZXR1cm4gdGhpcy5lbGVtW3RoaXMucHJvcF07dmFyIGEsYj1kLmNzcyh0aGlzLmVsZW0sdGhpcy5wcm9wKTtyZXR1cm4gaXNOYU4oYT1wYXJzZUZsb2F0KGIpKT8hYnx8Yj09PSJhdXRvIj8wOmI6YX0sY3VzdG9tOmZ1bmN0aW9uKGEsYixjKXtmdW5jdGlvbiBnKGEpe3JldHVybiBlLnN0ZXAoYSl9dmFyIGU9dGhpcyxmPWQuZng7dGhpcy5zdGFydFRpbWU9ZC5ub3coKSx0aGlzLnN0YXJ0PWEsdGhpcy5lbmQ9Yix0aGlzLnVuaXQ9Y3x8dGhpcy51bml0fHwoZC5jc3NOdW1iZXJbdGhpcy5wcm9wXT8iIjoicHgiKSx0aGlzLm5vdz10aGlzLnN0YXJ0LHRoaXMucG9zPXRoaXMuc3RhdGU9MCxnLmVsZW09dGhpcy5lbGVtLGcoKSYmZC50aW1lcnMucHVzaChnKSYmIWNjJiYoY2M9c2V0SW50ZXJ2YWwoZi50aWNrLGYuaW50ZXJ2YWwpKX0sc2hvdzpmdW5jdGlvbigpe3RoaXMub3B0aW9ucy5vcmlnW3RoaXMucHJvcF09ZC5zdHlsZSh0aGlzLmVsZW0sdGhpcy5wcm9wKSx0aGlzLm9wdGlvbnMuc2hvdz0hMCx0aGlzLmN1c3RvbSh0aGlzLnByb3A9PT0id2lkdGgifHx0aGlzLnByb3A9PT0iaGVpZ2h0Ij8xOjAsdGhpcy5jdXIoKSksZCh0aGlzLmVsZW0pLnNob3coKX0saGlkZTpmdW5jdGlvbigpe3RoaXMub3B0aW9ucy5vcmlnW3RoaXMucHJvcF09ZC5zdHlsZSh0aGlzLmVsZW0sdGhpcy5wcm9wKSx0aGlzLm9wdGlvbnMuaGlkZT0hMCx0aGlzLmN1c3RvbSh0aGlzLmN1cigpLDApfSxzdGVwOmZ1bmN0aW9uKGEpe3ZhciBiPWQubm93KCksYz0hMDtpZihhfHxiPj10aGlzLm9wdGlvbnMuZHVyYXRpb24rdGhpcy5zdGFydFRpbWUpe3RoaXMubm93PXRoaXMuZW5kLHRoaXMucG9zPXRoaXMuc3RhdGU9MSx0aGlzLnVwZGF0ZSgpLHRoaXMub3B0aW9ucy5jdXJBbmltW3RoaXMucHJvcF09ITA7Zm9yKHZhciBlIGluIHRoaXMub3B0aW9ucy5jdXJBbmltKXRoaXMub3B0aW9ucy5jdXJBbmltW2VdIT09ITAmJihjPSExKTtpZihjKXtpZih0aGlzLm9wdGlvbnMub3ZlcmZsb3chPW51bGwmJiFkLnN1cHBvcnQuc2hyaW5rV3JhcEJsb2Nrcyl7dmFyIGY9dGhpcy5lbGVtLGc9dGhpcy5vcHRpb25zO2QuZWFjaChbIiIsIlgiLCJZIl0sZnVuY3Rpb24oYSxiKXtmLnN0eWxlWyJvdmVyZmxvdyIrYl09Zy5vdmVyZmxvd1thXX0pfXRoaXMub3B0aW9ucy5oaWRlJiZkKHRoaXMuZWxlbSkuaGlkZSgpO2lmKHRoaXMub3B0aW9ucy5oaWRlfHx0aGlzLm9wdGlvbnMuc2hvdylmb3IodmFyIGggaW4gdGhpcy5vcHRpb25zLmN1ckFuaW0pZC5zdHlsZSh0aGlzLmVsZW0saCx0aGlzLm9wdGlvbnMub3JpZ1toXSk7dGhpcy5vcHRpb25zLmNvbXBsZXRlLmNhbGwodGhpcy5lbGVtKX1yZXR1cm4hMX12YXIgaT1iLXRoaXMuc3RhcnRUaW1lO3RoaXMuc3RhdGU9aS90aGlzLm9wdGlvbnMuZHVyYXRpb247dmFyIGo9dGhpcy5vcHRpb25zLnNwZWNpYWxFYXNpbmcmJnRoaXMub3B0aW9ucy5zcGVjaWFsRWFzaW5nW3RoaXMucHJvcF0saz10aGlzLm9wdGlvbnMuZWFzaW5nfHwoZC5lYXNpbmcuc3dpbmc/InN3aW5nIjoibGluZWFyIik7dGhpcy5wb3M9ZC5lYXNpbmdbanx8a10odGhpcy5zdGF0ZSxpLDAsMSx0aGlzLm9wdGlvbnMuZHVyYXRpb24pLHRoaXMubm93PXRoaXMuc3RhcnQrKHRoaXMuZW5kLXRoaXMuc3RhcnQpKnRoaXMucG9zLHRoaXMudXBkYXRlKCk7cmV0dXJuITB9fSxkLmV4dGVuZChkLmZ4LHt0aWNrOmZ1bmN0aW9uKCl7dmFyIGE9ZC50aW1lcnM7Zm9yKHZhciBiPTA7YjxhLmxlbmd0aDtiKyspYVtiXSgpfHxhLnNwbGljZShiLS0sMSk7YS5sZW5ndGh8fGQuZnguc3RvcCgpfSxpbnRlcnZhbDoxMyxzdG9wOmZ1bmN0aW9uKCl7Y2xlYXJJbnRlcnZhbChjYyksY2M9bnVsbH0sc3BlZWRzOntzbG93OjYwMCxmYXN0OjIwMCxfZGVmYXVsdDo0MDB9LHN0ZXA6e29wYWNpdHk6ZnVuY3Rpb24oYSl7ZC5zdHlsZShhLmVsZW0sIm9wYWNpdHkiLGEubm93KX0sX2RlZmF1bHQ6ZnVuY3Rpb24oYSl7YS5lbGVtLnN0eWxlJiZhLmVsZW0uc3R5bGVbYS5wcm9wXSE9bnVsbD9hLmVsZW0uc3R5bGVbYS5wcm9wXT0oYS5wcm9wPT09IndpZHRoInx8YS5wcm9wPT09ImhlaWdodCI/TWF0aC5tYXgoMCxhLm5vdyk6YS5ub3cpK2EudW5pdDphLmVsZW1bYS5wcm9wXT1hLm5vd319fSksZC5leHByJiZkLmV4cHIuZmlsdGVycyYmKGQuZXhwci5maWx0ZXJzLmFuaW1hdGVkPWZ1bmN0aW9uKGEpe3JldHVybiBkLmdyZXAoZC50aW1lcnMsZnVuY3Rpb24oYil7cmV0dXJuIGE9PT1iLmVsZW19KS5sZW5ndGh9KTt2YXIgY2c9L150KD86YWJsZXxkfGgpJC9pLGNoPS9eKD86Ym9keXxodG1sKSQvaTsiZ2V0Qm91bmRpbmdDbGllbnRSZWN0ImluIGMuZG9jdW1lbnRFbGVtZW50P2QuZm4ub2Zmc2V0PWZ1bmN0aW9uKGEpe3ZhciBiPXRoaXNbMF0sYztpZihhKXJldHVybiB0aGlzLmVhY2goZnVuY3Rpb24oYil7ZC5vZmZzZXQuc2V0T2Zmc2V0KHRoaXMsYSxiKX0pO2lmKCFifHwhYi5vd25lckRvY3VtZW50KXJldHVybiBudWxsO2lmKGI9PT1iLm93bmVyRG9jdW1lbnQuYm9keSlyZXR1cm4gZC5vZmZzZXQuYm9keU9mZnNldChiKTt0cnl7Yz1iLmdldEJvdW5kaW5nQ2xpZW50UmVjdCgpfWNhdGNoKGUpe312YXIgZj1iLm93bmVyRG9jdW1lbnQsZz1mLmRvY3VtZW50RWxlbWVudDtpZighY3x8IWQuY29udGFpbnMoZyxiKSlyZXR1cm4gYz97dG9wOmMudG9wLGxlZnQ6Yy5sZWZ0fTp7dG9wOjAsbGVmdDowfTt2YXIgaD1mLmJvZHksaT1jaShmKSxqPWcuY2xpZW50VG9wfHxoLmNsaWVudFRvcHx8MCxrPWcuY2xpZW50TGVmdHx8aC5jbGllbnRMZWZ0fHwwLGw9aS5wYWdlWU9mZnNldHx8ZC5zdXBwb3J0LmJveE1vZGVsJiZnLnNjcm9sbFRvcHx8aC5zY3JvbGxUb3AsbT1pLnBhZ2VYT2Zmc2V0fHxkLnN1cHBvcnQuYm94TW9kZWwmJmcuc2Nyb2xsTGVmdHx8aC5zY3JvbGxMZWZ0LG49Yy50b3ArbC1qLG89Yy5sZWZ0K20taztyZXR1cm57dG9wOm4sbGVmdDpvfX06ZC5mbi5vZmZzZXQ9ZnVuY3Rpb24oYSl7dmFyIGI9dGhpc1swXTtpZihhKXJldHVybiB0aGlzLmVhY2goZnVuY3Rpb24oYil7ZC5vZmZzZXQuc2V0T2Zmc2V0KHRoaXMsYSxiKX0pO2lmKCFifHwhYi5vd25lckRvY3VtZW50KXJldHVybiBudWxsO2lmKGI9PT1iLm93bmVyRG9jdW1lbnQuYm9keSlyZXR1cm4gZC5vZmZzZXQuYm9keU9mZnNldChiKTtkLm9mZnNldC5pbml0aWFsaXplKCk7dmFyIGMsZT1iLm9mZnNldFBhcmVudCxmPWIsZz1iLm93bmVyRG9jdW1lbnQsaD1nLmRvY3VtZW50RWxlbWVudCxpPWcuYm9keSxqPWcuZGVmYXVsdFZpZXcsaz1qP2ouZ2V0Q29tcHV0ZWRTdHlsZShiLG51bGwpOmIuY3VycmVudFN0eWxlLGw9Yi5vZmZzZXRUb3AsbT1iLm9mZnNldExlZnQ7d2hpbGUoKGI9Yi5wYXJlbnROb2RlKSYmYiE9PWkmJmIhPT1oKXtpZihkLm9mZnNldC5zdXBwb3J0c0ZpeGVkUG9zaXRpb24mJmsucG9zaXRpb249PT0iZml4ZWQiKWJyZWFrO2M9aj9qLmdldENvbXB1dGVkU3R5bGUoYixudWxsKTpiLmN1cnJlbnRTdHlsZSxsLT1iLnNjcm9sbFRvcCxtLT1iLnNjcm9sbExlZnQsYj09PWUmJihsKz1iLm9mZnNldFRvcCxtKz1iLm9mZnNldExlZnQsZC5vZmZzZXQuZG9lc05vdEFkZEJvcmRlciYmKCFkLm9mZnNldC5kb2VzQWRkQm9yZGVyRm9yVGFibGVBbmRDZWxsc3x8IWNnLnRlc3QoYi5ub2RlTmFtZSkpJiYobCs9cGFyc2VGbG9hdChjLmJvcmRlclRvcFdpZHRoKXx8MCxtKz1wYXJzZUZsb2F0KGMuYm9yZGVyTGVmdFdpZHRoKXx8MCksZj1lLGU9Yi5vZmZzZXRQYXJlbnQpLGQub2Zmc2V0LnN1YnRyYWN0c0JvcmRlckZvck92ZXJmbG93Tm90VmlzaWJsZSYmYy5vdmVyZmxvdyE9PSJ2aXNpYmxlIiYmKGwrPXBhcnNlRmxvYXQoYy5ib3JkZXJUb3BXaWR0aCl8fDAsbSs9cGFyc2VGbG9hdChjLmJvcmRlckxlZnRXaWR0aCl8fDApLGs9Y31pZihrLnBvc2l0aW9uPT09InJlbGF0aXZlInx8ay5wb3NpdGlvbj09PSJzdGF0aWMiKWwrPWkub2Zmc2V0VG9wLG0rPWkub2Zmc2V0TGVmdDtkLm9mZnNldC5zdXBwb3J0c0ZpeGVkUG9zaXRpb24mJmsucG9zaXRpb249PT0iZml4ZWQiJiYobCs9TWF0aC5tYXgoaC5zY3JvbGxUb3AsaS5zY3JvbGxUb3ApLG0rPU1hdGgubWF4KGguc2Nyb2xsTGVmdCxpLnNjcm9sbExlZnQpKTtyZXR1cm57dG9wOmwsbGVmdDptfX0sZC5vZmZzZXQ9e2luaXRpYWxpemU6ZnVuY3Rpb24oKXt2YXIgYT1jLmJvZHksYj1jLmNyZWF0ZUVsZW1lbnQoImRpdiIpLGUsZixnLGgsaT1wYXJzZUZsb2F0KGQuY3NzKGEsIm1hcmdpblRvcCIpKXx8MCxqPSI8ZGl2IHN0eWxlPSdwb3NpdGlvbjphYnNvbHV0ZTt0b3A6MDtsZWZ0OjA7bWFyZ2luOjA7Ym9yZGVyOjVweCBzb2xpZCAjMDAwO3BhZGRpbmc6MDt3aWR0aDoxcHg7aGVpZ2h0OjFweDsnPjxkaXY+PC9kaXY+PC9kaXY+PHRhYmxlIHN0eWxlPSdwb3NpdGlvbjphYnNvbHV0ZTt0b3A6MDtsZWZ0OjA7bWFyZ2luOjA7Ym9yZGVyOjVweCBzb2xpZCAjMDAwO3BhZGRpbmc6MDt3aWR0aDoxcHg7aGVpZ2h0OjFweDsnIGNlbGxwYWRkaW5nPScwJyBjZWxsc3BhY2luZz0nMCc+PHRyPjx0ZD48L3RkPjwvdHI+PC90YWJsZT4iO2QuZXh0ZW5kKGIuc3R5bGUse3Bvc2l0aW9uOiJhYnNvbHV0ZSIsdG9wOjAsbGVmdDowLG1hcmdpbjowLGJvcmRlcjowLHdpZHRoOiIxcHgiLGhlaWdodDoiMXB4Iix2aXNpYmlsaXR5OiJoaWRkZW4ifSksYi5pbm5lckhUTUw9aixhLmluc2VydEJlZm9yZShiLGEuZmlyc3RDaGlsZCksZT1iLmZpcnN0Q2hpbGQsZj1lLmZpcnN0Q2hpbGQsaD1lLm5leHRTaWJsaW5nLmZpcnN0Q2hpbGQuZmlyc3RDaGlsZCx0aGlzLmRvZXNOb3RBZGRCb3JkZXI9Zi5vZmZzZXRUb3AhPT01LHRoaXMuZG9lc0FkZEJvcmRlckZvclRhYmxlQW5kQ2VsbHM9aC5vZmZzZXRUb3A9PT01LGYuc3R5bGUucG9zaXRpb249ImZpeGVkIixmLnN0eWxlLnRvcD0iMjBweCIsdGhpcy5zdXBwb3J0c0ZpeGVkUG9zaXRpb249Zi5vZmZzZXRUb3A9PT0yMHx8Zi5vZmZzZXRUb3A9PT0xNSxmLnN0eWxlLnBvc2l0aW9uPWYuc3R5bGUudG9wPSIiLGUuc3R5bGUub3ZlcmZsb3c9ImhpZGRlbiIsZS5zdHlsZS5wb3NpdGlvbj0icmVsYXRpdmUiLHRoaXMuc3VidHJhY3RzQm9yZGVyRm9yT3ZlcmZsb3dOb3RWaXNpYmxlPWYub2Zmc2V0VG9wPT09LTUsdGhpcy5kb2VzTm90SW5jbHVkZU1hcmdpbkluQm9keU9mZnNldD1hLm9mZnNldFRvcCE9PWksYS5yZW1vdmVDaGlsZChiKSxkLm9mZnNldC5pbml0aWFsaXplPWQubm9vcH0sYm9keU9mZnNldDpmdW5jdGlvbihhKXt2YXIgYj1hLm9mZnNldFRvcCxjPWEub2Zmc2V0TGVmdDtkLm9mZnNldC5pbml0aWFsaXplKCksZC5vZmZzZXQuZG9lc05vdEluY2x1ZGVNYXJnaW5JbkJvZHlPZmZzZXQmJihiKz1wYXJzZUZsb2F0KGQuY3NzKGEsIm1hcmdpblRvcCIpKXx8MCxjKz1wYXJzZUZsb2F0KGQuY3NzKGEsIm1hcmdpbkxlZnQiKSl8fDApO3JldHVybnt0b3A6YixsZWZ0OmN9fSxzZXRPZmZzZXQ6ZnVuY3Rpb24oYSxiLGMpe3ZhciBlPWQuY3NzKGEsInBvc2l0aW9uIik7ZT09PSJzdGF0aWMiJiYoYS5zdHlsZS5wb3NpdGlvbj0icmVsYXRpdmUiKTt2YXIgZj1kKGEpLGc9Zi5vZmZzZXQoKSxoPWQuY3NzKGEsInRvcCIpLGk9ZC5jc3MoYSwibGVmdCIpLGo9KGU9PT0iYWJzb2x1dGUifHxlPT09ImZpeGVkIikmJmQuaW5BcnJheSgiYXV0byIsW2gsaV0pPi0xLGs9e30sbD17fSxtLG47aiYmKGw9Zi5wb3NpdGlvbigpKSxtPWo/bC50b3A6cGFyc2VJbnQoaCwxMCl8fDAsbj1qP2wubGVmdDpwYXJzZUludChpLDEwKXx8MCxkLmlzRnVuY3Rpb24oYikmJihiPWIuY2FsbChhLGMsZykpLGIudG9wIT1udWxsJiYoay50b3A9Yi50b3AtZy50b3ArbSksYi5sZWZ0IT1udWxsJiYoay5sZWZ0PWIubGVmdC1nLmxlZnQrbiksInVzaW5nImluIGI/Yi51c2luZy5jYWxsKGEsayk6Zi5jc3Moayl9fSxkLmZuLmV4dGVuZCh7cG9zaXRpb246ZnVuY3Rpb24oKXtpZighdGhpc1swXSlyZXR1cm4gbnVsbDt2YXIgYT10aGlzWzBdLGI9dGhpcy5vZmZzZXRQYXJlbnQoKSxjPXRoaXMub2Zmc2V0KCksZT1jaC50ZXN0KGJbMF0ubm9kZU5hbWUpP3t0b3A6MCxsZWZ0OjB9OmIub2Zmc2V0KCk7Yy50b3AtPXBhcnNlRmxvYXQoZC5jc3MoYSwibWFyZ2luVG9wIikpfHwwLGMubGVmdC09cGFyc2VGbG9hdChkLmNzcyhhLCJtYXJnaW5MZWZ0IikpfHwwLGUudG9wKz1wYXJzZUZsb2F0KGQuY3NzKGJbMF0sImJvcmRlclRvcFdpZHRoIikpfHwwLGUubGVmdCs9cGFyc2VGbG9hdChkLmNzcyhiWzBdLCJib3JkZXJMZWZ0V2lkdGgiKSl8fDA7cmV0dXJue3RvcDpjLnRvcC1lLnRvcCxsZWZ0OmMubGVmdC1lLmxlZnR9fSxvZmZzZXRQYXJlbnQ6ZnVuY3Rpb24oKXtyZXR1cm4gdGhpcy5tYXAoZnVuY3Rpb24oKXt2YXIgYT10aGlzLm9mZnNldFBhcmVudHx8Yy5ib2R5O3doaWxlKGEmJighY2gudGVzdChhLm5vZGVOYW1lKSYmZC5jc3MoYSwicG9zaXRpb24iKT09PSJzdGF0aWMiKSlhPWEub2Zmc2V0UGFyZW50O3JldHVybiBhfSl9fSksZC5lYWNoKFsiTGVmdCIsIlRvcCJdLGZ1bmN0aW9uKGEsYyl7dmFyIGU9InNjcm9sbCIrYztkLmZuW2VdPWZ1bmN0aW9uKGMpe3ZhciBmPXRoaXNbMF0sZztpZighZilyZXR1cm4gbnVsbDtpZihjIT09YilyZXR1cm4gdGhpcy5lYWNoKGZ1bmN0aW9uKCl7Zz1jaSh0aGlzKSxnP2cuc2Nyb2xsVG8oYT9kKGcpLnNjcm9sbExlZnQoKTpjLGE/YzpkKGcpLnNjcm9sbFRvcCgpKTp0aGlzW2VdPWN9KTtnPWNpKGYpO3JldHVybiBnPyJwYWdlWE9mZnNldCJpbiBnP2dbYT8icGFnZVlPZmZzZXQiOiJwYWdlWE9mZnNldCJdOmQuc3VwcG9ydC5ib3hNb2RlbCYmZy5kb2N1bWVudC5kb2N1bWVudEVsZW1lbnRbZV18fGcuZG9jdW1lbnQuYm9keVtlXTpmW2VdfX0pLGQuZWFjaChbIkhlaWdodCIsIldpZHRoIl0sZnVuY3Rpb24oYSxjKXt2YXIgZT1jLnRvTG93ZXJDYXNlKCk7ZC5mblsiaW5uZXIiK2NdPWZ1bmN0aW9uKCl7cmV0dXJuIHRoaXNbMF0/cGFyc2VGbG9hdChkLmNzcyh0aGlzWzBdLGUsInBhZGRpbmciKSk6bnVsbH0sZC5mblsib3V0ZXIiK2NdPWZ1bmN0aW9uKGEpe3JldHVybiB0aGlzWzBdP3BhcnNlRmxvYXQoZC5jc3ModGhpc1swXSxlLGE/Im1hcmdpbiI6ImJvcmRlciIpKTpudWxsfSxkLmZuW2VdPWZ1bmN0aW9uKGEpe3ZhciBmPXRoaXNbMF07aWYoIWYpcmV0dXJuIGE9PW51bGw/bnVsbDp0aGlzO2lmKGQuaXNGdW5jdGlvbihhKSlyZXR1cm4gdGhpcy5lYWNoKGZ1bmN0aW9uKGIpe3ZhciBjPWQodGhpcyk7Y1tlXShhLmNhbGwodGhpcyxiLGNbZV0oKSkpfSk7aWYoZC5pc1dpbmRvdyhmKSl7dmFyIGc9Zi5kb2N1bWVudC5kb2N1bWVudEVsZW1lbnRbImNsaWVudCIrY107cmV0dXJuIGYuZG9jdW1lbnQuY29tcGF0TW9kZT09PSJDU1MxQ29tcGF0IiYmZ3x8Zi5kb2N1bWVudC5ib2R5WyJjbGllbnQiK2NdfHxnfWlmKGYubm9kZVR5cGU9PT05KXJldHVybiBNYXRoLm1heChmLmRvY3VtZW50RWxlbWVudFsiY2xpZW50IitjXSxmLmJvZHlbInNjcm9sbCIrY10sZi5kb2N1bWVudEVsZW1lbnRbInNjcm9sbCIrY10sZi5ib2R5WyJvZmZzZXQiK2NdLGYuZG9jdW1lbnRFbGVtZW50WyJvZmZzZXQiK2NdKTtpZihhPT09Yil7dmFyIGg9ZC5jc3MoZixlKSxpPXBhcnNlRmxvYXQoaCk7cmV0dXJuIGQuaXNOYU4oaSk/aDppfXJldHVybiB0aGlzLmNzcyhlLHR5cGVvZiBhPT09InN0cmluZyI/YTphKyJweCIpfX0pLGEualF1ZXJ5PWEuJD1kfSkod2luZG93KTs=',
  'files/logo.png' => 'iVBORw0KGgoAAAANSUhEUgAAAIwAAAAaCAMAAABSIURSAAAAclBMVEUAAAD////////////////////////////////////////////bKCf3k0XbKCfbKCfbKCf////////bKCf3k0X3k0X3k0XbKCfbKCfbKCf3k0X3k0X3k0X3k0X3k0XbKCfbKCf3k0X3k0X////bKCf3k0Ur2CGkAAAAI3RSTlMA+nbttj/Wo+OO9Vzh+cWi+schHx/cxjxw76+chzzph1dwV6HptJ4AAAJwSURBVEjHzZVtk6IwEIQnQJRFRVHXd0/vJvz/v3jpZOIYrrbK3Q9yT62pXpwMTdIRou1n3/eLC3lUg8Pcufme3sl20QfOA003F7jTG/nshWuuyUWO9Eb6xCnXSyfQ+1ADuyf9SbT6b8zs6O7GMLPohUum506Y0yswV5RjDXNBL2F4IurUC5neuMThh2ZK5nL2XTPXx860+22KjMZ3RS9g1Ux+h2GZ8kXprz7wh1btQ2t8lzqjxlAS1Rg+DLOZEU3ZTJmDmYJ907VfEW6s1x4t8//7v2EPX1LG1iUmeXnpwYJubvXQB5fYkFCzwW4whs57CBQiYCZ4ocb39was725KLSuiyHpU6YrhyCPCZ0T2plpoScBjWDzKzPqPb1YjFga3MzY0xyUJD26PtdeyAjPzHmuUWgwwjwrs9A4GtojsXPTeJfbPGZ123HBVxd42+FtP45lhsKawMk03kyBoGW416IHdDfsVEyDjVSO7ibqFHr4LSuzslMuaJ9IIQYBKZhBD20A10YyW4TPoMXnsnOEqfFdKhH/HyC6jdhpfxT+Nn+gH/shWRsw0NTYCFF5V/65MMejR+SowzVaGzo/IrqC/ONc2ZL+JQcMypMzI7wwMoahA3y6Y0TKYyXuI064MmVnHzID+lCJ7gD7m8U0YlE/QjfSYqBnIKp2mGcxomZjJe5Ry8HBRMgd2iC9AhAfxVSZyIDo5XGwKejZDBrpGZIqQGS1LZvIeFdZphlI/CV8IS/1dyV5L32VNP8Ra1UeNbPZaGgWXaLP4joKaOVKrizQG+cq0Gp+RaPUAHTS+I7FZ6dbMk63R2LSI7I0A5PFO7+cvX/qJhTbo2vgAAAAASUVORK5CYII=',
  'files/requirements.tpl' => 'PGgxPlN5c3RlbSBSZXF1aXJlbWVudHM8L2gxPgoKPD9waHAKIGZvcmVhY2ggKCRyZXN1bHQgYXMgJGNhcHRpb24gPT4gJGluZm8pIHsKCWlmICgkaW5mb1snc3RhdHVzJ10gPT0gJ3N1Y2Nlc3MnKSB7CgkJcHJpbnRmKCc8ZGl2IGNsYXNzPSJhbGVydCBhbGVydC1zdWNjZXNzIj48ZGl2IGNsYXNzPSJwdWxsLXJpZ2h0Ij48c3BhbiBjbGFzcz0iZ2x5cGhpY29uIGdseXBoaWNvbi1vayIgYXJpYS1oaWRkZW49InRydWUiPjwvc3Bhbj48L2Rpdj48c3Ryb25nPiVzPC9zdHJvbmc+PC9kaXY+JywgJGNhcHRpb24pOwoJfSBlbHNlIHsKCQlpZiAoJGluZm9bJ3N0YXR1cyddID09ICdlcnJvcicpIHsKCQkJJGFsZXJ0Q2xhc3MgPSAnZGFuZ2VyJzsKCQkJJGljb24gPSAncmVtb3ZlJzsKCQl9IGVsc2UgewoJCQkkYWxlcnRDbGFzcyA9ICRpbmZvWydzdGF0dXMnXTsKCQkJJGljb24gPSAnd2FybmluZy1zaWduJzsKCQl9CgoJCXByaW50ZignPGRpdiBjbGFzcz0iYWxlcnQgYWxlcnQtJXMiPjxkaXYgY2xhc3M9InB1bGwtcmlnaHQiPjxzcGFuIGNsYXNzPSJnbHlwaGljb24gZ2x5cGhpY29uLSVzIiBhcmlhLWhpZGRlbj0idHJ1ZSI+PC9zcGFuPjwvZGl2PjxzdHJvbmc+JXM8L3N0cm9uZz48cD4lczwvcD48L2Rpdj4nLCAkYWxlcnRDbGFzcyAsICRpY29uLCAkY2FwdGlvbiwgJGluZm9bJ21lc3NhZ2UnXSk7Cgl9CiB9Cj8+Cg==',
  'files/retry_control.tpl' => 'PGZvcm0gbWV0aG9kPSJwb3N0Ij4KCTxpbnB1dCB0eXBlPSJoaWRkZW4iIHZhbHVlPSIiIG5hbWU9InJlc3RvcmUiPgoKCTxkaXYgY2xhc3M9InJvdyI+CgkJPGRpdiBjbGFzcz0iY29sLXhzLW9mZnNldC00IGNvbC14cy00IHRleHQtY2VudGVyIj4KCQkJPGlucHV0IHR5cGU9InN1Ym1pdCIgbmFtZT0iYWN0aW9uIiB2YWx1ZT0iUmV0cnkiIGNsYXNzPSJidG4gYnRuLXByaW1hcnkiPgoJCTwvZGl2PgoJPC9kaXY+CjwvZm9ybT4K',
  'files/retry_skip_confirmation.tpl' => 'PGgxPjw/cGhwIGVjaG8gJGNhcHRpb247ID8+PC9oMT4NCg0KPGRpdiBjbGFzcz0iYWxlcnQgYWxlcnQtaW5mbyIgcm9sZT0iYWxlcnQiPg0KCTw/cGhwIGVjaG8gJG1lc3NhZ2U7ID8+DQo8L2Rpdj4NCg0KPGZvcm0gbWV0aG9kPSJwb3N0Ij4NCgk8aW5wdXQgdHlwZT0iaGlkZGVuIiBuYW1lPSJyZXN0b3JlIiB2YWx1ZT0iIi8+DQoNCgk8ZGl2IGNsYXNzPSJyb3ciPg0KCQk8ZGl2IGNsYXNzPSJjb2wteHMtb2Zmc2V0LTQgY29sLXhzLTQgdGV4dC1jZW50ZXIiPg0KCQkJPGlucHV0IHR5cGU9InN1Ym1pdCIgdmFsdWU9IlJldHJ5IiBuYW1lPSJhY3Rpb25SZXRyeSIgY2xhc3M9ImJ0biBidG4tcHJpbWFyeSByZXRyeSIvPg0KCQk8L2Rpdj4NCg0KCQk8P3BocCBpZiAoJHNraXBhYmxlKSA6ID8+DQoJCQk8ZGl2IGNsYXNzPSJjb2wteHMtNCB0ZXh0LXJpZ2h0Ij4NCgkJCQk8aW5wdXQgdHlwZT0ic3VibWl0IiB2YWx1ZT0iU2tpcCIgbmFtZT0iYWN0aW9uU2tpcCIgY2xhc3M9ImJ0biBidG4tbGluayBza2lwIi8+DQoJCQk8L2Rpdj4NCgkJPD9waHAgZW5kaWY7ID8+DQoJPC9kaXY+DQo8L2Zvcm0+DQo=',
  'files/spinner.gif' => 'R0lGODlhHwAfAPUAADMzM////0RERFZWVmdnZ3Jycnx8fE5OTmpqaoKCgkdHR1FRUXZ2dn5+fnFxcVpaWjk5OXd3d1RUVEZGRtLS0t/f376+vmFhYaSkpImJibm5uTY2NqqqqsbGxl9fXzc3N8TExNbW1gAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQJCgAAACwAAAAAHwAfAAAG/0CAcEgUDAgFA4BiwSQexKh0eEAkrldAZbvlOD5TqYKALWu5XIwnPFwwymY0GsRgAxrwuJwbCi8aAHlYZ3sVdwtRCm8JgVgODwoQAAIXGRpojQwKRGSDCRESYRsGHYZlBFR5AJt2a3kHQlZlERN2QxMRcAiTeaG2QxJ5RnAOv1EOcEdwUMZDD3BIcKzNq3BJcJLUABBwStrNBtjf3GUGBdLfCtadWMzUz6cDxN/IZQMCvdTBcAIAsli0jOHSJeSAqmlhNr0awo7RJ19TJORqdAXVEEVZyjyKtE3Bg3oZE2iK8oeiKkFZGiCaggelSTiA2LhxiZLBSjZjBL2siNBOFQ84LxHA+mYEiRJzBO7ZCQIAIfkECQoAAAAsAAAAAB8AHwAABv9AgHBIFAwIBQPAUCAMBMSodHhAJK5XAPaKOEynCsIWqx0nCIrvcMEwZ90JxkINaMATZXfju9jf82YAIQxRCm14Ww4PChAAEAoPDlsAFRUgHkRiZAkREmoSEXiVlRgfQgeBaXRpo6MOQlZbERN0Qx4drRUcAAJmnrVDBrkVDwNjr8BDGxq5Z2MPyUQZuRgFY6rRABe5FgZjjdm8uRTh2d5b4NkQY0zX5QpjTc/lD2NOx+WSW0++2RJmUGJhmZVsQqgtCE6lqpXGjBchmt50+hQKEAEiht5gUcTIESR9GhlgE9IH0BiTkxrMmWIHDkose9SwcQlHDsOIk9ygiVbl5JgMLuV4HUmypMkTOkEAACH5BAkKAAAALAAAAAAfAB8AAAb/QIBwSBQMCAUDwFAgDATEqHR4QCSuVwD2ijhMpwrCFqsdJwiK73DBMGfdCcZCDWjAE2V347vY3/NmdXNECm14Ww4PChAAEAoPDltlDGlDYmQJERJqEhGHWARUgZVqaWZeAFZbERN0QxOeWwgAAmabrkMSZkZjDrhRkVtHYw+/RA9jSGOkxgpjSWOMxkIQY0rT0wbR2LQV3t4UBcvcF9/eFpdYxdgZ5hUYA73YGxruCbVjt78G7hXFqlhY/fLQwR0HIQdGuUrTz5eQdIc0cfIEwByGD0MKvcGSaFGjR8GyeAPhIUofQGNQSgrB4IsdOCqx7FHDBiYcOQshYjKDxliVDpRjunCjdSTJkiZP6AQBACH5BAkKAAAALAAAAAAfAB8AAAb/QIBwSBQMCAUDwFAgDATEqHR4QCSuVwD2ijhMpwrCFqsdJwiK73DBMGfdCcZCDWjAE2V347vY3/NmdXNECm14Ww4PChAAEAoPDltlDGlDYmQJERJqEhGHWARUgZVqaWZeAFZbERN0QxOeWwgAAmabrkMSZkZjDrhRkVtHYw+/RA9jSGOkxgpjSWOMxkIQY0rT0wbR2I3WBcvczltNxNzIW0693MFYT7bTumNQqlisv7BjswAHo64egFdQAbj0RtOXDQY6VAAUakihN1gSLaJ1IYOGChgXXqEUpQ9ASRlDYhT0xQ4cACJDhqDD5mRKjCAYuArjBmVKDP9+VRljMyMHDwcfuBlBooSCBQwJiqkJAgAh+QQJCgAAACwAAAAAHwAfAAAG/0CAcEgUDAgFA8BQIAwExKh0eEAkrlcA9oo4TKcKwharHScIiu9wwTBn3QnGQg1owBNld+O72N/zZnVzRApteFsODwoQABAKDw5bZQxpQ2JkCRESahIRh1gEVIGVamlmXgBWWxETdEMTnlsIAAJmm65DEmZGYw64UZFbR2MPv0QPY0hjpMYKY0ljjMZCEGNK09MG0diN1gXL3M5bTcTcyFtOvdzBWE+207pjUKpYrL+wY7MAB4EerqZjUAG4lKVCBwMbvnT6dCXUkEIFK0jUkOECFEeQJF2hFKUPAIkgQwIaI+hLiJAoR27Zo4YBCJQgVW4cpMYDBpgVZKL59cEBhw+U+QROQ4bBAoUlTZ7QCQIAIfkECQoAAAAsAAAAAB8AHwAABv9AgHBIFAwIBQPAUCAMBMSodHhAJK5XAPaKOEynCsIWqx0nCIrvcMEwZ90JxkINaMATZXfju9jf82Z1c0QKbXhbDg8KEAAQCg8OW2UMaUNiZAkREmoSEYdYBFSBlWppZl4AVlsRE3RDE55bCAACZpuuQxJmRmMOuFGRW0djD79ED2NIY6TGCmNJY4zGQhBjStPTFBXb21DY1VsGFtzbF9gAzlsFGOQVGefIW2LtGhvYwVgDD+0V17+6Y6BwaNfBwy9YY2YBcMAPnStTY1B9YMdNiyZOngCFGuIBxDZAiRY1eoTvE6UoDEIAGrNSUoNBUuzAaYlljxo2M+HIeXiJpRsRNMaq+JSFCpsRJEqYOPH2JQgAIfkECQoAAAAsAAAAAB8AHwAABv9AgHBIFAwIBQPAUCAMBMSodHhAJK5XAPaKOEynCsIWqx0nCIrvcMEwZ90JxkINaMATZXfjywjlzX9jdXNEHiAVFX8ODwoQABAKDw5bZQxpQh8YiIhaERJqEhF4WwRDDpubAJdqaWZeAByoFR0edEMTolsIAA+yFUq2QxJmAgmyGhvBRJNbA5qoGcpED2MEFrIX0kMKYwUUslDaj2PA4soGY47iEOQFY6vS3FtNYw/m1KQDYw7mzFhPZj5JGzYGipUtESYowzVmF4ADgOCBCZTgFQAxZBJ4AiXqT6ltbUZhWdToUSR/Ii1FWbDnDkUyDQhJsQPn5ZU9atjUhCPHVhgTNy/RSKsiqKFFbUaQKGHiJNyXIAAh+QQJCgAAACwAAAAAHwAfAAAG/0CAcEh8JDAWCsBQIAwExKhU+HFwKlgsIMHlIg7TqQeTLW+7XYIiPGSAymY0mrFgA0LwuLzbCC/6eVlnewkADXVECgxcAGUaGRdQEAoPDmhnDGtDBJcVHQYbYRIRhWgEQwd7AB52AGt7YAAIchETrUITpGgIAAJ7ErdDEnsCA3IOwUSWaAOcaA/JQ0amBXKa0QpyBQZyENFCEHIG39HcaN7f4WhM1uTZaE1y0N/TacZoyN/LXU+/0cNyoMxCUytYLjm8AKSS46rVKzmxADhjlCACMFGkBiU4NUQRxS4OHijwNqnSJS6ZovzRyJAQo0NhGrgs5bIPmwWLCLHsQsfhxBWTe9QkOzCwC8sv5Ho127akyRM7QQAAOwAAAAAAAAAAAA==',
  'files/how_to_get_license_page.tpl' => 'PGgxPk9idGFpbiB0aGUgUHJvZHVjdCBMaWNlbnNlPC9oMT4KPGRpdiBjbGFzcz0iaW5mbyI+Cgk8cD5QbGVhc2UgbWFrZSBzdXJlIHRoYXQgeW91IGhhdmUgYSBsaWNlbnNlIHRvIHVzZSB0aGUgPD9waHAgZWNobyAkcHJvZHVjdF9uYW1lOyA/PiBzY3JpcHQuPC9wPgoJPHA+WW91IGNhbiBlYXNpbHkgZ2VuZXJhdGUgYSAxNS1kYXkgdHJpYWwgbGljZW5zZSBmb3IgeW91ciB3ZWJzaXRlIGF0CgkJPGEgb25jbGljaz0iamF2YXNjcmlwdDp3aW5kb3cub3Blbih0aGlzLmhyZWYsICdfYmxhbmsnKTsgcmV0dXJuIGZhbHNlOyIgaHJlZj0iaHR0cDovL2xpY2Vuc2Uud29ya3Nmb3J3ZWIuY29tLyIgYWx0PSJXb3Jrc0ZvcldlYuKAmXMgTGljZW5zZSBHZW5lcmF0b3Igd2Vic2l0ZSI+V29ya3NGb3JXZWLigJlzIExpY2Vuc2UgR2VuZXJhdG9yIHBhZ2U8L2E+LgoJCVBsZWFzZSByZWFkIGFsbCB0aGUgaW5zdHJ1Y3Rpb25zIG9uIGhvdyB0byBnZW5lcmF0ZSBhIHdvcmtpbmcgbGljZW5zZSBhbmQgY2FyZWZ1bGx5IGZvbGxvdyB0aGVtLgoJPC9wPgoJPHA+VGhlIHBlcm1hbmVudCBsaWNlbnNlIHdpbGwgYmUgc2VudCB0byB5b3UgYnkgZW1haWwgYWZ0ZXIgcHVyY2hhc2luZyBhbiA8P3BocCBlY2hvICRwcm9kdWN0X25hbWU7ID8+IHBhY2thZ2UuPC9wPgo8L2Rpdj4K',
  'files/installation_success.tpl' => 'PGRpdiAgY2xhc3M9ImluZm8iPgoJPGRpdiBjbGFzcz0iYWxlcnQgYWxlcnQtc3VjY2VzcyIgcm9sZT0iYWxlcnQiPgoJCUNvbmdyYXR1bGF0aW9ucyEhISBUaGUgaW5zdGFsbGF0aW9uIHdhcyBzdWNjZXNzZnVsIQoJPC9kaXY+CgoJPHA+Tm93LCBwbGVhc2UgcmVtb3ZlIHRoZSA8c3Ryb25nPmluc3RhbGwucGhwPC9zdHJvbmc+IGZpbGUgZnJvbSB0aGUgcm9vdCBvZiB0aGUgaW5zdGFsbGF0aW9uIGZvbGRlci48L3A+Cgk8cD5GYWlsdXJlIHRvIGRvIHNvIG1heSBhbGxvdyB0aGlyZCBwYXJ0aWVzIHRvIHJlLWluc3RhbGwgeW91ciBzb2Z0d2FyZSBhbmQgZ2FpbiBjb250cm9sIG92ZXIgaXQuPC9wPgoJPGEgaHJlZj0iPD9waHAgZWNobyAkYmFzZVVybDsgPz5hZG1pbi8iIGNsYXNzPSJidG4gYnRuLXByaW1hcnkiPkdvIHRvIHRoZSBhZG1pbiBwYW5lbDwvYT4mbmJzcDsmbmJzcDsKCTxhIGhyZWY9Ijw/cGhwIGVjaG8gJGJhc2VVcmw7ID8+IiBjbGFzcz0iYnRuIGJ0bi1wcmltYXJ5Ij4gR28gdG8gdGhlIGZyb250IGVuZCBhcmVhPC9hPgo8L2Rpdj4=',
  'files/show_license.tpl' => 'PGRpdiBjbGFzcz0iaW5mbyI+PD9waHAgZWNobyAkbWVzc2FnZTsgPz48L2Rpdj4=',
  'files/welcome_page.tpl' => 'PGgxPldlbGNvbWUgdG8gPD9waHAgZWNobyAkcHJvZHVjdF9uYW1lOyA/PiBJbnN0YWxsYXRpb24gU2NyaXB0PC9oMT4KPGRpdiBjbGFzcz0iaW5mbyI+Cgk8bm9zY3JpcHQ+CgkJPGRpdiBjbGFzcz0iZXJyb3JNZXNzYWdlQmxvY2siPkphdmFTY3JpcHQgaXMgZGlzYWJsZWQgaW4geW91ciBpbnRlcm5ldCBicm93c2VyLiBQbGVhc2UgZW5hYmxlIEphdmFTY3JpcHQgaW4gb3JkZXIgdG8gdXNlIHRoZSBlc3NlbnRpYWwgZmVhdHVyZXMgb2YgdGhpcyB3ZWJzaXRlLCBvdGhlcndpc2UgaXQgd2lsbCBiZSB1bmFibGUgdG8gd29yayBwcm9wZXJseS48L2Rpdj4KCTwvbm9zY3JpcHQ+CgoJPGRpdiBpZD0iY29va2llRGlzYWJsZWRXYXJuaW5nIiBjbGFzcz0iZXJyb3JNZXNzYWdlQmxvY2siIHN0eWxlPSJkaXNwbGF5OiBub25lIj5Db29raWVzIGFyZSBkaXNhYmxlZCBpbiB5b3VyIGludGVybmV0IGJyb3dzZXIuIFBsZWFzZSBlbmFibGUgQ29va2llcyBpbiBvcmRlciB0byB1c2UgdGhlIGVzc2VudGlhbCBmZWF0dXJlcyBvZiB0aGlzIHdlYnNpdGUsIG90aGVyd2lzZSBpdCB3aWxsIGJlIHVuYWJsZSB0byB3b3JrIHByb3Blcmx5LjwvZGl2PgoJPHNjcmlwdCB0eXBlPSJ0ZXh0L2phdmFzY3JpcHQiPgoJCWlmICghd2luZG93Lm5hdmlnYXRvci5jb29raWVFbmFibGVkIHx8IGRvY3VtZW50LmNvb2tpZT09JycpCgkJewoJCQlkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgiY29va2llRGlzYWJsZWRXYXJuaW5nIikuc3R5bGUuZGlzcGxheT0iYmxvY2siOwoJCX0KCTwvc2NyaXB0PgkKCQoJPHA+V2VsY29tZSB0byB0aGUgPD9waHAgZWNobyAkcHJvZHVjdF9uYW1lOyA/PiBpbnN0YWxsZXIsIGFuZCB0aGFuayB5b3UgdmVyeSBtdWNoIGZvciBjaG9vc2luZyB0aGUgPD9waHAgZWNobyAkcHJvZHVjdF9uYW1lOyA/PiBDbGFzc2lmaWVkIFNjcmlwdC4gT3VyIGdvYWwgaXMgdG8gcHJvdmlkZSB0aGUgbW9zdCB2ZXJzYXRpbGUsIGZsZXhpYmxlIGFuZCBlYXN5LXRvLXVzZSBjbGFzc2lmaWVkIHNjcmlwdCBmb3IgeW91ciBvbmxpbmUgYnVzaW5lc3MuPC9wPgoJPHA+VGhpcyBzY3JpcHQgd2lsbCBoZWxwIHlvdSBpbnN0YWxsIDw/cGhwIGVjaG8gJHByb2R1Y3RfbmFtZTsgPz4gcXVpY2tseSBhbmQgZWZmb3J0bGVzc2x5LjwvcD4KCTxwPlNvbWUgb2YgdGhlIHN0ZXBzIHdpbGwgcmVxdWlyZSB5b3VyIGlucHV0cywgd2hpbGUgb3RoZXJzIHdpbGwgYmUgcGVyZm9ybWVkIGF1dG9tYXRpY2FsbHkuPC9wPgoJPHA+QWxsIHlvdSBuZWVkIHRvIGRvIGlzIGZvbGxvdyB0aGUgZ3VpZGVsaW5lcywgYW5kIGlmIGluIGRvdWJ0LCBjb25zdWx0IHRoZSA8YSBvbmNsaWNrPSJqYXZhc2NyaXB0OndpbmRvdy5vcGVuKHRoaXMuaHJlZiwgJ19ibGFuaycpOyByZXR1cm4gZmFsc2U7IiBocmVmPSI8P3BocCBlY2hvICRzaXRlX3VybDsgPz4vZG9jL1VzZXJNYW51YWwvbWFudWFsX2luc3RhbGxhdGlvbi5odG0iPkluc3RhbGxhdGlvbiBNYW51YWw8L2E+LiA8L3A+Cgk8cD5UaGUgY29udGVudHMgb2YgdGhlIGluc3RhbGwgbG9nIGF0IHRoZSBsb3dlciBwYXJ0IG9mIHRoZSBwYWdlIHNob3VsZCBhY2NvbXBhbnkgYW55IHN1cHBvcnQgcmVxdWVzdHMgYXNzb2NpYXRlZCB3aXRoIHRoZSBpbnN0YWxsYXRpb24uPC9wPgo8L2Rpdj4=',
);$config = array (
  'build' => 
  array (
    'Welcome' => 
    array (
      'DisplayTemplate' => 
      array (
        'templateFileName' => 'welcome_page.tpl',
        'waitForConfirmation' => '1',
      ),
      'caption' => 'Welcome to iLister installation script',
    ),
    'GenerateLicense' => 
    array (
      'GenerateLicense' => 
      array (
        'message' => '<p>Please enter all the information below to generate a license:</p>',
        'dataSet' => 'UserData',
      ),
      'caption' => 'Get a License',
    ),
    'PlaceLicense' => 
    array (
      'PlaceLicense' => 
      array (
        'message' => '<p>Please enter your correct FTP access details to the installation folder. License generator will automatically upload the new license to your installation folder.</p>',
        'dataSet' => 'FTPCredentials',
        'LicenseVerificationCodeMessage' => 'The email containing your trial license file and verification code has been successfully sent to your email address. Please enter the verification code from the e-mail.',
        'verificationData' => 'LicenseVerificationCode',
        'mode' => '644',
      ),
      'caption' => 'License Installation',
      'skipable' => '1',
    ),
    'checkLicense' => 
    array (
      'CheckLicense' => 
      array (
        'message' => '<p>Please place the license into the root of your installation without editing or modifying the file in any way.</p>',
        'waitForConfirmation' => '1',
      ),
      'caption' => 'Check License',
    ),
    'CheckSystemRequirements' => 
    array (
      'CheckRequirements' => 
      array (
        'requirements' => 
        array (
          0 => 
          array (
            'message' => 'The PHP version installed on your server is ${php_version}. However, iLister requires PHP version 5.4.x. Please ask your server administrator to install PHP of the required versions.',
            'min' => '5.4',
            'max' => '5.6',
            'mandatory' => '1',
            'type' => 'PhpVersion',
          ),
          1 => 
          array (
            'message' => 'The ionCube loader is not installed on your server. However, iLister requires ionCube loader to operate properly. Please ask your server administrator to install ionCube on your server.',
            'name' => 'IonCube Loader',
            'mandatory' => '1',
            'type' => 'PhpExtension',
          ),
          2 => 
          array (
            'message' => 'The PHP on your server does not have the BCMath extension enabled. However, iLister requires BCMath to operates properly. Please ask your server administrator to enable BCMath on your server.',
            'name' => 'bcmath',
            'mandatory' => '1',
            'type' => 'PhpExtension',
          ),
          3 => 
          array (
            'message' => 'The PHP on your server does not have the Imagick extension enabled. You will not be able to upload images to listings and user profiles without this extension. Please ask your server administrator to enable Imagick on your server.',
            'name' => 'Imagick',
            'mandatory' => '',
            'type' => 'PhpExtension',
          ),
          4 => 
          array (
            'message' => 'The "cURL" library is not found. PayPal requires to have this library enabled. Without it, the PayPal payment functionality will not work. Please ask your server administrator to enable cURL on your server to be able to use PayPal payment gateway.',
            'name' => 'curl',
            'mandatory' => '',
            'type' => 'PhpExtension',
          ),
          5 => 
          array (
            'message' => 'The PHP on your server has the <tt>magic_quotes</tt> setting set to "On". We recommend to set it to "Off". Please read more about "magic quotes" <a href="http://php.net/manual/en/security.magicquotes.php">here</a>. Please ask your server administrator to disable this option for your server.',
            'name' => 'magic_quotes',
            'value' => '',
            'mandatory' => '',
            'type' => 'PhpIniSetting',
          ),
          6 => 
          array (
            'message' => '
						The interface cache cannot be created.<br />
						Please copy the installation log below and <a href="http://www.worksforweb.com/company/contact-us/support-ticket/">contact our Tech Support Dept.</a> for assistance. <br />
						Please also include your FTP and DB access details in your request.
						',
            'mandatory' => '1',
            'type' => 'RebuildInterfaceCacheCheck',
          ),
          7 => 
          array (
            'message' => 'Please be informed that the timezone on your server is not specified. Please ask your hosting support to configure the timezone.',
            'mandatory' => '',
            'type' => 'PhpTimeZone',
          ),
        ),
      ),
      'caption' => 'Check system requirements',
    ),
    'ChmodDirs' => 
    array (
      'ApplyPermissionsViaFTP' => 
      array (
        'message' => '<p>The installer will need to set file and folder permissions that are secure, yet allow the proper operation of iLister.</p><p>Please provide FTP user details for user with sufficient rights to modify directory permissions to 777 or "drwxrwxrwx". After the installation, permissions will be reverted. Alternatively, you can skip this step and set the correct permissions manually.</p>',
        'dirset' => 
        array (
          0 => 
          array (
            'path' => 'cache',
            'type' => 'dir',
            'optional' => '',
          ),
          1 => 
          array (
            'path' => 'modules',
            'type' => 'dir',
            'optional' => '',
          ),
          2 => 
          array (
            'path' => 'languages',
            'type' => 'dir',
            'optional' => '',
          ),
          3 => 
          array (
            'path' => 'files',
            'type' => 'dir',
            'optional' => '',
          ),
          4 => 
          array (
            'path' => 'files/files',
            'optional' => '',
            'type' => 'dir',
          ),
          5 => 
          array (
            'path' => 'files/kcfinder',
            'optional' => '',
            'type' => 'dir',
          ),
          6 => 
          array (
            'path' => 'files/pictures',
            'optional' => '',
            'type' => 'dir',
          ),
          7 => 
          array (
            'path' => 'files/temp',
            'optional' => '',
            'type' => 'dir',
          ),
          8 => 
          array (
            'path' => 'files/video',
            'optional' => '',
            'type' => 'dir',
          ),
        ),
        'dataSet' => 'FTPCredentials',
        'mode' => '777',
      ),
      'caption' => 'Change directory permissions',
      'skipable' => '1',
    ),
    'ChmodFiles' => 
    array (
      'ApplyPermissionsViaFTP' => 
      array (
        'message' => '<p>The correct permissions protect your website from unauthorized access of the third-party scripts, which can harm your website.</p><p>Please specify an FTP user which has enough rights to change file permissions to 666 or "-rw-rw-rw-". After the installation, permissions for most files will be reverted.  Alternatively, you can skip this step and set the correct permissions manually.</p>',
        'fileset' => 
        array (
          0 => 
          array (
            'path' => 'taskscheduler.log',
            'type' => 'file',
            'optional' => '',
          ),
          1 => 
          array (
            'path' => '.htaccess',
            'type' => 'file',
            'optional' => '',
          ),
          2 => 
          array (
            'path' => 'admin/.htaccess',
            'type' => 'file',
            'optional' => '',
          ),
          3 => 
          array (
            'path' => 'apps/AdminPanel/config/local.ini',
            'type' => 'file',
            'optional' => '',
          ),
          4 => 
          array (
            'path' => 'apps/FrontEnd/config/local.ini',
            'type' => 'file',
            'optional' => '',
          ),
          5 => 
          array (
            'path' => 'apps/MobileFrontEnd/config/local.ini',
            'optional' => '1',
            'type' => 'file',
          ),
          6 => 
          array (
            'path' => 'files/pictures/watermark.gif',
            'optional' => '',
            'type' => 'file',
          ),
          7 => 
          array (
            'path' => 'languages/en.xml',
            'optional' => '',
            'type' => 'file',
          ),
          8 => 
          array (
            'path' => 'languages/ru.xml',
            'optional' => '',
            'type' => 'file',
          ),
        ),
        'dataSet' => 'FTPCredentials',
        'mode' => '666',
      ),
      'caption' => 'Change file permissions',
      'skipable' => '1',
    ),
    'SetCharacterSetToUTF8' => 
    array (
      'SetCharacterSet' => 
      array (
        'message' => '<p>Databases are used to store all the info on listings, users, plans, etc. To configure the database, please specify a database host, database name, database user and password combination in order to continue the installation.</p><p>If you have any questions on how to create or configure the database, please contact your hosting service provider\'s tech support staff. They will explain you how to create databases and assign users for them.</p>',
        'dataSet' => 'DBCredentials',
        'charset' => 'utf8',
      ),
      'caption' => 'Set database encoding to UTF8',
    ),
    'CheckLocalSettingsPermissions' => 
    array (
      'CheckFileWritable' => 
      array (
        'messages' => 
        array (
          'log' => 'Checking Local Settings Permissions',
          'failureLog' => 'Can not write to Local Settings',
          'failureMessage' => '
						
							<p>Certain configuration settings of the iLister-based website are stored in the local.ini files. These local settings include the URL of your website, the path to the Admin Panel, and the database access details. The installation script should have the rights to write these data elements to the local.ini files.</p>
							<p class="error">Please set the permissions manually so that PHP can write to these files and proceed with the installation by clicking "Retry".</p>
						
					',
          'failureLogType' => 'warning',
        ),
        'fileset' => 
        array (
          0 => 
          array (
            'path' => 'apps/AdminPanel/config/local.ini',
            'type' => 'file',
            'optional' => '',
          ),
          1 => 
          array (
            'path' => 'apps/FrontEnd/config/local.ini',
            'type' => 'file',
            'optional' => '',
          ),
          2 => 
          array (
            'path' => 'apps/MobileFrontEnd/config/local.ini',
            'optional' => '1',
            'type' => 'file',
          ),
        ),
      ),
      'caption' => 'Checking local settings permissions',
    ),
    'DefineLocalSettings' => 
    array (
      'DefineLocalSettings' => 
      array (
        'dataSet' => 'DBCredentials',
        'ftpDataSet' => 'FTPCredentials',
        'charset' => 'utf8',
      ),
      'caption' => 'Define local settings',
    ),
    'CheckCacheDirPermission' => 
    array (
      'CheckFileWritable' => 
      array (
        'messages' => 
        array (
          'log' => 'Checking Cache Directory Permissions',
          'failureLog' => 'Cannot write to Cache Directory',
          'failureMessage' => '
					<p class="error">The items listed below require writable permissions. Please set the permissions manually so that PHP can write to these files and proceed with the installation by clicking "Retry".</p>
					',
          'failureLogType' => 'warning',
        ),
        'dirset' => 
        array (
          0 => 
          array (
            'path' => 'cache',
            'type' => 'dir',
            'optional' => '',
          ),
        ),
      ),
      'caption' => 'Checking cache directory permissions',
    ),
    'InstallProductDBByRequestingFrontEnd' => 
    array (
      'RequestFrontEnd' => 
      array (
        'failureMessage' => '
					
					    This step was not performed correctly due to one of the following causes:
						<ul>
							<li>PHP memory limit is set too low (256MB or better would work fine).</li>
							<li>The db for iLister installation should be empty with no tables.</li>
							<li>The db user should be granted ALL permissions to manage the db.</li>
						</ul>
						In case all of the above is in place, but the installation does not proceed further,
						please copy the installation log below and <a href="http://www.worksforweb.com/company/contact-us/support-ticket/">contact our Tech Support Dept.</a> for assistance. <br />
						Please also include your FTP and DB access details in your request.
					
				',
      ),
      'caption' => 'Setup environment, cache & DB tables',
    ),
    'DefineAdminCredentials' => 
    array (
      'DefineAdminCredentials' => 
      array (
        'message' => '
				<p>The "system email" is the email address that the website uses as the “FROM” address to dispatch notification emails to users and the website administrator.</p>
				<p>As for admin username and password, we recommend that you choose a non-standard admin username and a strong password of no less than 8 symbols long, containing at least one capital letter, one digit, and one symbol.</p>
				',
        'dbDataSet' => 'DBCredentials',
        'dataSet' => 'AdminCredentials',
      ),
      'caption' => 'Set admin login and password',
    ),
    'CheckHtaccessPermissions' => 
    array (
      'CheckFileWritable' => 
      array (
        'messages' => 
        array (
          'log' => 'Checking .htaccess Permissions',
          'failureLog' => 'Can not write to .htaccess',
          'failureMessage' => '<p class="error">The items listed below require writable permissions. Please set the permissions manually so that PHP can write to these files and proceed with the installation by clicking "Retry".</p>',
          'failureLogType' => 'warning',
        ),
        'fileset' => 
        array (
          0 => 
          array (
            'path' => '.htaccess',
            'type' => 'file',
            'optional' => '',
          ),
          1 => 
          array (
            'path' => 'admin/.htaccess',
            'type' => 'file',
            'optional' => '',
          ),
          2 => 
          array (
            'path' => 'm/.htaccess',
            'optional' => '1',
            'type' => 'file',
          ),
        ),
      ),
      'caption' => 'Checking .htaccess permissions',
    ),
    'WriteHtaccessForFrontEnd' => 
    array (
      'WriteHtaccess' => 
      array (
        'pathToHtaccess' => '/',
      ),
      'caption' => 'Write .htaccess file for the Front End',
    ),
    'WriteHtaccessForAdminPanel' => 
    array (
      'WriteHtaccess' => 
      array (
        'pathToHtaccess' => '/admin/',
      ),
      'caption' => 'Write .htaccess file for the Admin Panel',
    ),
    'DenyWriteAccessToConfigFiles' => 
    array (
      'ApplyPermissionsViaFTP' => 
      array (
        'message' => 'The configuration files contain access details to the website database. It is necessary to change the permissions to 644 (not writable) for the configuration files in order to prevent an unauthorized access to the website data.',
        'fileset' => 
        array (
          0 => 
          array (
            'path' => '.htaccess',
            'type' => 'file',
            'optional' => '',
          ),
          1 => 
          array (
            'path' => 'admin/.htaccess',
            'type' => 'file',
            'optional' => '',
          ),
          2 => 
          array (
            'path' => 'apps/AdminPanel/config/local.ini',
            'type' => 'file',
            'optional' => '',
          ),
          3 => 
          array (
            'path' => 'apps/FrontEnd/config/local.ini',
            'type' => 'file',
            'optional' => '',
          ),
          4 => 
          array (
            'path' => 'apps/MobileFrontEnd/config/local.ini',
            'optional' => '1',
            'type' => 'file',
          ),
        ),
        'dataSet' => 'FTPCredentials',
        'mode' => '644',
      ),
      'caption' => 'Deny write access to config files',
      'skipable' => '1',
    ),
    'CheckPermissionsForSecurity' => 
    array (
      'CheckFilePermission' => 
      array (
        'messages' => 
        array (
          'log' => 'Checking Config File Permissions',
          'failureLog' => 'Current Config File Permissions are not recomended for security purposes.',
          'failureMessage' => '<p class="error">For security purposes, please set the permissions to ${expectedPermissionMode} for the items listed below and then proceed with the installation by clicking "Retry". Alternatively, you can skip this step by clicking "Next".</p>',
          'failureLogType' => 'warning',
        ),
        'fileset' => 
        array (
          0 => 
          array (
            'path' => '.htaccess',
            'type' => 'file',
            'optional' => '',
          ),
          1 => 
          array (
            'path' => 'admin/.htaccess',
            'type' => 'file',
            'optional' => '',
          ),
          2 => 
          array (
            'path' => 'apps/AdminPanel/config/local.ini',
            'type' => 'file',
            'optional' => '',
          ),
          3 => 
          array (
            'path' => 'apps/FrontEnd/config/local.ini',
            'type' => 'file',
            'optional' => '',
          ),
          4 => 
          array (
            'path' => 'apps/MobileFrontEnd/config/local.ini',
            'optional' => '1',
            'type' => 'file',
          ),
        ),
        'expectedMode' => '644',
        'skipable' => '1',
      ),
      'caption' => 'Checking config files permissions',
    ),
    'CheckDirectoryPermissions' => 
    array (
      'CheckFileWritable' => 
      array (
        'messages' => 
        array (
          'log' => 'Checking Cache Directory Permissions',
          'failureLog' => 'Cannot write to directories',
          'failureMessage' => '<p class="error">The items listed below require writable permissions. Please set the permissions manually so that PHP can write to these files and proceed with the installation by clicking "Retry".</p>',
          'failureLogType' => 'warning',
        ),
        'dirset' => 
        array (
          0 => 
          array (
            'path' => 'cache',
            'type' => 'dir',
            'optional' => '',
          ),
          1 => 
          array (
            'path' => 'modules',
            'type' => 'dir',
            'optional' => '',
          ),
          2 => 
          array (
            'path' => 'languages',
            'type' => 'dir',
            'optional' => '',
          ),
          3 => 
          array (
            'path' => 'files',
            'type' => 'dir',
            'optional' => '',
          ),
          4 => 
          array (
            'path' => 'files/files',
            'optional' => '',
            'type' => 'dir',
          ),
          5 => 
          array (
            'path' => 'files/kcfinder',
            'optional' => '',
            'type' => 'dir',
          ),
          6 => 
          array (
            'path' => 'files/pictures',
            'optional' => '',
            'type' => 'dir',
          ),
          7 => 
          array (
            'path' => 'files/temp',
            'optional' => '',
            'type' => 'dir',
          ),
          8 => 
          array (
            'path' => 'files/video',
            'optional' => '',
            'type' => 'dir',
          ),
        ),
      ),
      'caption' => 'Checking directories permissions',
    ),
    'InstallAllModules' => 
    array (
      'InstallAllModules' => 
      array (
        'adminDataSet' => 'AdminCredentials',
      ),
      'caption' => 'Install modules',
    ),
    'ShowInstallationSuccessMessage' => 
    array (
      'DisplayTemplate' => 
      array (
        'log' => 
        array (
          'message' => 'Congratulations! The installation was completed successfully!',
          'type' => 'success',
        ),
        'templateFileName' => 'installation_success.tpl',
      ),
      'caption' => 'Complete installation',
    ),
  ),
  'forms' => 
  array (
    'UserData' => 
    array (
      'fields' => 
      array (
        'first_name' => 
        array (
          'caption' => 'First Name',
          'type' => 'text',
          'default' => '',
        ),
        'last_name' => 
        array (
          'caption' => 'Last Name',
          'type' => 'text',
          'default' => '',
        ),
        'email' => 
        array (
          'caption' => 'Email',
          'type' => 'text',
          'default' => '',
        ),
        'subscription_accepted' => 
        array (
          'caption' => 'I agree to be informed of new products and events via my email',
          'type' => 'checkbox',
          'default' => '0',
        ),
        'terms_agree' => 
        array (
          'caption' => 'I have read and agreed to the terms of the relevant iAuto, iRealty or iLister',
          'type' => 'checkbox',
          'default' => '0',
        ),
      ),
      'caption' => 'Get a License',
      'validator' => 'UserData',
      'templateFileName' => 'get_license_form.tpl',
    ),
    'LicenseVerificationCode' => 
    array (
      'fields' => 
      array (
        'code' => 
        array (
          'caption' => 'Verification Code',
          'type' => 'text',
          'default' => '',
        ),
      ),
      'caption' => 'Verification Code',
      'validator' => 'LicenseVerificationCode',
    ),
    'DBCredentials' => 
    array (
      'fields' => 
      array (
        'dbHost' => 
        array (
          'caption' => 'DB Host',
          'type' => 'text',
          'default' => 'localhost',
        ),
        'dbUser' => 
        array (
          'caption' => 'DB User',
          'type' => 'text',
          'default' => '',
        ),
        'dbPassword' => 
        array (
          'caption' => 'DB Password',
          'type' => 'text',
          'default' => '',
        ),
        'dbName' => 
        array (
          'caption' => 'DB Name',
          'type' => 'text',
          'default' => '',
        ),
      ),
      'caption' => 'Database Details',
      'validator' => 'DBCredentials',
    ),
    'FTPCredentials' => 
    array (
      'fields' => 
      array (
        'ftpHost' => 
        array (
          'caption' => 'FTP Host',
          'type' => 'text',
          'default' => 'localhost',
        ),
        'ftpPort' => 
        array (
          'caption' => 'FTP Port',
          'type' => 'text',
          'default' => '21',
        ),
        'ftpUser' => 
        array (
          'caption' => 'FTP User',
          'type' => 'text',
          'default' => '',
        ),
        'ftpPassword' => 
        array (
          'caption' => 'FTP Password',
          'type' => 'text',
          'default' => '',
        ),
        'ftpDirectory' => 
        array (
          'caption' => 'FTP Directory',
          'type' => 'text',
          'default' => 'php_function:getcwd',
        ),
      ),
      'caption' => 'FTP Access Details',
      'validator' => 'FTPCredentials',
    ),
    'AdminCredentials' => 
    array (
      'fields' => 
      array (
        'adminUsername' => 
        array (
          'caption' => 'Admin Username',
          'type' => 'text',
          'default' => '',
        ),
        'adminPassword' => 
        array (
          'caption' => 'Admin Password',
          'type' => 'text',
          'default' => '',
        ),
        'systemEmail' => 
        array (
          'caption' => 'System Email',
          'type' => 'text',
          'default' => '',
        ),
      ),
      'caption' => 'Admin Login and Password',
      'validator' => 'AdminCredentials',
    ),
    'MFAUrl' => 
    array (
      'fields' => 
      array (
        'url' => 
        array (
          'caption' => 'Url',
          'type' => 'text',
          'default' => '',
        ),
      ),
      'caption' => 'Mobile FrontEnd Url',
      'validator' => 'MFAUrl',
    ),
  ),
  'product' => 'iLister',
  'version' => '7.5.0',
  'remoteLogHandlerUrl' => 'http://license.worksforweb.com/system/installations/log_install/',
  'licenseWorksforweb' => 'http://license.worksforweb.com/',
); $installer = Installer::getInstance(); $installer->init($config); $installer->run(); }namespace
{
	function d()
	{
		$args = func_get_args();
		$die = (count($args) > 1 && end($args) === 1) && array_pop($args);
		echo "<pre>";
		$backtrace = debug_backtrace();
		echo "<strong>{$backtrace[0]['file']}:{$backtrace[0]['line']}</strong>\n";
		foreach($args as $v)
		{
			$output = print_r($v, true);
			echo $output . "\n";
		}
		echo "</pre>";
		if ($die) die();
	}

	function dd()
	{
		$args = func_get_args();
		$die = (end($args) === 1) && array_pop($args);
		echo "<pre>";
		$backtrace = debug_backtrace();
		echo "<strong>{$backtrace[0]['file']}:{$backtrace[0]['line']}</strong>\n";
		foreach($args as $v)
		{
			var_dump($v);
			echo "\n";
		}
		echo "</pre>";
		if ($die) die();
	}
}