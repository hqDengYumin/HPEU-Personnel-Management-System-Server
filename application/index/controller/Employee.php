<?php
/**
 * Created by PhpStorm.
 * User: Tang
 * Date: 2018/10/31
 * Time: 11:00
 */

namespace app\index\controller;

use app\index\model as Model;

/**
 * Class Employee
 * 员工控制器
 * @package app\index\controller
 */
class Employee extends Auth
{
  public function add()
  {
    // 获取参数
    // =============================================================================================================

    $data = $this->request->only([
      "eName/s",
      "eBirthdate/s",
      "eEmail/s",
      "eSex/b",
      "eCondition/b",
      "eStartTime/s",
      "department_dId/d"
    ], 'post');

    // TODO: 字段检测与处理

    // 数据库插入
    // =============================================================================================================

    $employee = new Model\Employee();
    $result = $employee->save($data);

    // 返回结果
    // =============================================================================================================

    $response = $result === 1 ? [
      "success" => true,
      "code" => "0201",
      "codeInfo" => "添加成功",
      "data" => [
        "eId" => $employee->eId
      ]
    ] : [
      "success" => false,
      "code" => "0401",
      "codeInfo" => "添加失败",
      "data" => null
    ];

    return json($response);
  }

  public function delete()
  {
    // 获取参数
    // =============================================================================================================

    $eId = $this->request->post("eId/d");

    // 数据库删除
    // =============================================================================================================

    $result = Model\Employee::destroy($eId);

    // 返回结果
    // =============================================================================================================
    $response = $result ? [
      "success" => true,
      "code" => "0203",
      "codeInfo" => "删除成功"
    ] : [
      "success" => false,
      "code" => "0403",
      "codeInfo" => "删除失败"
    ];

    return json($response);
  }

  public function update()
  {
    // 可选参数
    $data = $this->request->only([
      "eName/s",
      "eBirthdate/s",
      "eEmail/s",
      "eSex/b",
      "eCondition/b",
      "eStartTime/s",
      "department_dId/d"
    ], 'post');

    // 过滤空白变量
    foreach ($data as $key => $value) {
      if ($data[$key] === null) {
        unset($data[$key]);
      }
    }

    // TODO: 字段检测与处理

    $employee = new Model\Employee();
    $result = $employee->force()->save($data, [
      "eId" => $this->request->post("eId/d")
    ]);

    $response = $result === 1 ? [
      "success" => true,
      "code" => "0202",
      "codeInfo" => "修改成功"
    ] : [
      "success" => false,
      "code" => "0402",
      "codeInfo" => "修改失败"
    ];

    return json($response);
  }

  public function getList()
  {
    // 获取参数
    // =============================================================================================================

    $department_dId = $this->request->post("department_dId/d");
    $department_dName = $this->request->post("department_dName/s");
    $eName = $this->request->post("eName/s");
    $eSex = $this->request->post("eSex/b");
    $eCondition = $this->request->post("eCondition/b");

    // TODO: 字段检测与处理

    // 数据库查询
    // =============================================================================================================

    // 全部查询规则
    $rules = [
      "department_dId" => $department_dId === null ? null : ["=", $department_dId],
      "department_dName" => $department_dName === null ? null : ["LIKE", "%$department_dName%"],
      "eName" => $eName === null ? null : ["LIKE", "%$eName%"],
      "eSex" => $eSex === null ? null : ["=", $eSex],
      "eCondition" => $eCondition === null ? null : ["=", $eCondition]
    ];

    // 实际查询规则
    foreach ($rules as $key => $value) {
      if ($rules[$key] === null) {
        unset($rules[$key]);
      }
    }

    // 查询语句
    $query = Model\Employee::where($rules);

    // 先排序再分页
    if ($this->request->has("order")) {
      $order = $this->request->post("order/a");

      // TODO: 字段检测与处理

      $query = $query->order($order['field'], $order['method']);
    }

    // 获取记录总数与总页数
    $counts = count($query->cache(true)->select());

    // 分页（limit）
    if ($this->request->has("page")) {
      $page = $this->request->post("page/a");

      // TODO: 字段检测与处理

      $curPage = $page['curPage'];
      $limit = $page['limit'];

      $query = $query->limit(($curPage - 1) * $limit, $limit);

      // 总页数 = 总记录数 / 每页条数 （进一法）
      $pages = ceil($counts / $limit);
    } else {
      // 未分页总页数默认为1
      $pages = 1;
    }

    $emoloyees = $query->cache(true)->select();

    // 返回结果
    // =============================================================================================================

    if ($emoloyees === null) {
      $response = [
        "success" => false,
        "code" => "0407",
        "codeInfo" => "获取失败",
        "data" => null
      ];
    } else {
      // 数据处理
      $datas = [];
      foreach ($emoloyees as $emoloyee) {
        $data = $emoloyee->toArray();
        $data['department_dName'] = $emoloyee->profile->dName;
        array_push($objects, $data);
      }

      $response = [
        "success" => true,
        "code" => "0207",
        "codeInfo" => "获取成功",
        "data" => [
          "page" => [
            "counts" => $counts,
            "pages" => $pages
          ],
          "objects" => $datas
        ]
      ];
    }

    return json($response);

  }

  public function getDetailed()
  {
    // 获取参数
    // =============================================================================================================

    $eId = $this->request->post("eId/d");

    // TODO: 字段检测与处理

    // 数据库查询
    // =============================================================================================================

    $employee = Model\Employee::get($eId);

    // 返回结果
    // =============================================================================================================

    // FIXME: 在查询时过滤字段而不是查询之后
    $response = $employee ? [
      "success" => true,
      "code" => "0200",
      "codeInfo" => "获取成功",
      "data" => [
        "eName" => $employee->eName,
        "eBirthdate" => $employee->eBirthdate,
        "eEmail" => $employee->eEmail,
        "eSex" => $employee->eSex,
        "eCondition" => $employee->eCondition,
        "eStartTime" => $employee->eStartTime,
        "department_dId" => $employee->department_dId,
        "department_dName" => $employee->profile->dName
      ]
    ] : [
      "success" => false,
      "code" => "0400",
      "codeInfo" => "获取失败",
      "data" => null
    ];

    return json($response);
  }
}