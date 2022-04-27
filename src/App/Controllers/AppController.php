<?php

namespace App\Controllers;

use App\App;

abstract class AppController
{
    protected string $_layout = 'index';

    protected function getLayout(): string
    {
        return $this->_layout;
    }

    protected function response(string $response, int $status = 200, $partial = false): array
    {
        return ['response' => $response, 'status' => $status, 'partial' => $partial];
    }

    public function runAction($action): array
    {
        try {
            $method = 'action' . ucfirst($action);
            $partial = false;

            if (method_exists($this, $method)) {
                try {
                    ['status' => $status, 'response' => $response, 'partial' => $partial] = $this->$method();
                } catch (\Exception $ex) {
                    $status = 500;
                    $response = $ex->getMessage();
                }
            } else {
                $status = 404;
                $response = 'Page not found';
            }

            if(!$this->isAjax() && !$partial) {
                $response = $this->renderTpl('layouts/index', ['content' => $response]);
            }
        } catch(\Exception $ex) {
            $status = 500;
            $response = $ex->getMessage();
        }

        return ['status' => $status, 'response' => $response];
    }

    public function isAjax(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }

    protected function renderTpl(string $template, array $params = []): string
    {
        $app = App::getInstance();
        $layout = $app->getTemplatesPath() .'/'. $template .'.php';

        if(!file_exists($layout)) {
            throw new \Exception('template does not exists');
        }
        ob_start();

        extract($params);
        include($layout);

        $data = ob_get_contents();

        ob_end_clean();

        return $data;
    }
}