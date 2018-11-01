<?php
/**
 * Created by PhpStorm.
 * User: Tang
 * Date: 2018/10/31
 * Time: 13:55
 */

namespace app\index\model;


use think\Model;

/**
 * Class Employee
 * 员工数据表模型
 * @package app\index\model
 */
class Employee extends Model
{
  protected $fk = "eId";

  public function profile()
  {
    return $this->hasOne('app\index\model\Department', "dId", 'department_dId');
  }
}