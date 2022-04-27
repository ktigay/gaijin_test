<?php
namespace App;

use App\Data\AccessPointInterface;
use App\Web\HttpStatuses;

class App
{
    private static App $_instance;
    private array $_config;
    private AccessPointInterface $_accessPoint;
    private string $_path;

    protected function __construct()
    {
        $this->_config = include ('config/main.php');
        $this->_path = __DIR__;
    }

    public static function getInstance(): self
    {
        if(!isset(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function getAccessPoint(): AccessPointInterface
    {
        if(!isset($this->_accessPoint)) {
            $className = '\\App\\Data\\'. $this->_config['db']['driver'] .'\\AccessPoint';


            if(!class_exists($className)) {
                throw new \Exception('class not found '. $className);
            }

            $this->_accessPoint = new $className($this->_config['db']);
        }
        return $this->_accessPoint;
    }

    public function getAppPath(): string
    {
        return $this->_path;
    }

    public function getTemplatesPath(): string
    {
        return $this->getAppPath() .'/templates/';
    }

    public function run(string $uri): void
    {
        try {
            $controller = 'Index';
            $action = 'index';

            $uri = trim($uri, '/');
            $uri = (str_contains($uri, '?') ? substr($uri, 0, strpos($uri, '?')) : $uri);
            if ($uri) {
                $uriArr = array_reverse(preg_split('/\//', $uri));
                $action = array_shift($uriArr);
                $controller = ucfirst(array_shift($uriArr) ?? 'Index');
            }
            $controller = "\\App\\Controllers\\{$controller}Controller";
            $controllerObj = new $controller();

            ['status' => $status, 'response' => $response] = $controllerObj->runAction($action);

        } catch(\Exception $ex) {
            $response = $ex->getMessage();
            $status = 500;
        }

        header('HTTP/1.1 ' . $status . ' ' . HttpStatuses::$codes[$status]);
        echo $response;
    }
}