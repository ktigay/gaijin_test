<?php
namespace App\Controllers;

use App\Models\Posts;

class IndexController extends AppController
{

    /**
     * @return array
     * @throws \Exception
     */
    public function actionIndex(): array
    {
        return $this->response($this->renderTpl('index/index'));
    }
}