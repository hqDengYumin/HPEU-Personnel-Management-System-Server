<?php
/**
 * Created by PhpStorm.
 * User: Tang
 * Date: 2018/10/31
 * Time: 10:20
 */

namespace app\index\controller;

// 允许跨域ajax
header('Access-Control-Allow-Origin:*');

use think\Request;

use think\facade\Env;

use think\Controller;

/**
 * Class Base
 * 控制器基类
 * @package app\index\controller
 */
class Base extends Controller
{
  /**
   * Request实例
   * @var \think\Request
   */
  protected $request;
  /**
   * Env实例
   * @var think\facade\Env
   */
  protected $env;
  /**
   * 项目根目录
   * @var string
   */
  protected $root_path;
  /**
   * 应用根目录
   * @var string
   */
  protected $app_path;
  /**
   * 入口文件目录
   * @var string
   */
  protected $enrty_path;
  /**
   * 静态资源目录
   * @var string
   */
  protected $static_path;

  /**
   * Interceptor constructor.
   * @param Request $request
   * @param Env $env
   */
  public function __construct(Request $request, Env $env)
  {
    parent::__construct();
    // 工具类依赖注入
    $this->request = $request;
    $this->env = $env;
    // 常量初始化
    $this->root_path = $this->env::get('root_path');
    $this->app_path = $this->env::get('app_path');
    $this->enrty_path = $this->root_path . "public";
    $this->static_path = $this->enrty_path . "\static";
  }
}