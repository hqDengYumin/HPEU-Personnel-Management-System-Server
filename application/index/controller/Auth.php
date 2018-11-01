<?php
/**
 * Created by PhpStorm.
 * User: Tang
 * Date: 2018/10/31
 * Time: 10:19
 */

namespace app\index\controller;

use think\Request;

use think\facade\Env;


/**
 * Class Auth
 * 权限拦截类
 * @package app\index\controller
 */
class Auth extends Base
{
  public function __construct(Request $request, Env $env)
  {
    parent::__construct($request, $env);
    // TODO: Auth
  }
}