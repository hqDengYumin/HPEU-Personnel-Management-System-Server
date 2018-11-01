<?php
/**
 * Created by PhpStorm.
 * User: 邓渝敏
 * Date: 2018/10/31
 * Time: 11:10
 */

namespace app\index\controller;
use app\index\model;

/**
 * Class Department
 * 部门控制器
 * @package app\index\controller
 */
class Department extends Auth
{

  /**
   *添加新部门 端口0252
   */
  public function  add()
  {
//    获取全部参数
    $data = $this->request->only([
      "dId",
      "dName",
      "dIntroduce",
      "dLeaderName",
      "dLeaderEmail"
    ]);
//    过滤空白变量
    foreach ($data as $key=>$value)
    {
      if($data[$key]=== null)
      {
        unset($data[$key]);
      }
    }
    $value = model\Department::where("")->insert($data);
    if ($value !="")
    {
      $response=[
        "success"=>true,
        "code"=>"0252",
        "codeInfo" => "成功"
      ];
    }else{
      $response=[
        "success"=>false,
        "code"=>"0452",
        "codeInfo" => "失败"
      ];
    }
    return json($response);
  }

  /**
   *删除部门信息 端口0253
   */
  public function delete()
  {
    $dName = $this->request->post("dName");
    $value= Department::where("dName",$dName)->delete();
    if ($value!=0)
    {
      $response=[
        "success"=>true,
        "code"=>"0253",
        "codeInfo" => "成功"
      ];
    }else{
      $response=[
        "success"=>false,
        "code"=>"0453",
        "codeInfo" => "失败"
      ];
    }
    return json($response);
  }

  /**
   *更新部门 端口0256
   */
  public function update()
  {
    $dId =$this->request->post("dId");
    //    获取全部参数
    $data = $this->request->only([
      "dId",
      "dName",
      "dIntroduce",
      "dLeaderName",
      "dLeaderEmail"
    ]);
//    过滤空白变量
    foreach ($data as $key=>$value)
    {
      if($data[$key]=== null)
      {
        unset($data[$key]);
      }
    }
    $value = model\Department::where("dId",$dId)->update($data);
    if ($value !="")
    {
      $response=[
        "success"=>true,
        "code"=>"0256",
        "codeInfo" => "成功"
      ];
    }else{
      $response=[
        "success"=>false,
        "code"=>"0456",
        "codeInfo" => "失败"
      ];
    }
    return json($response);
  }

  /**
   *查询某个信息 端口0255
   */
  public function select()
  {
    //判断是否设置了post过来了dId
    $hasDName = $this->request->has('dName','post');
    if ($hasDName){
      $dName = $this->request->post('dName');
      //ajax查询
      if($dName == '' or $dName == null){
        $department = model\Department::where('')->select();
      }else{
        $department = model\Department::where('dName','like','%'.$dName.'%')->select();
      }
    }else{
      //查询所有 仅仅是跳转到当前页面
      $department = model\Department::where('')->select();
    }
    if ($department!=""){
      $response =[
        "success"=>true,
        "code"=>"0255",
        "codeInfo" => "成功",
        "data"=>$department
      ];
    }else{
      $response =[
        "success"=>false,
        "code"=>"0455",
        "codeInfo" => "失败",
        "data"=>$department
      ];
    }

    return json($response);
  }
}