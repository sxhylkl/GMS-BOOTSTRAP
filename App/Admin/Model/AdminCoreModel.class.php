<?php
/**
 * Created by PhpStorm.
 * Auth   : li
 * QQ     : 184117183
 * Email  : 184117183@qq.com
 */

namespace Admin\Model;


use Think\Model;

class AdminCoreModel extends Model
{
    protected $tablePrefix = 'gms_';

    /**
     * 返回检索字段名称
     * @return string
     */
    protected function searchFields()
    {
        return '';
    }

    /**
     * 列表拉取函数搜索条件
     * @return array
     */
    protected function search()
    {
        return array();
    }

    /**
     * 列表拉取函数数据内容整理
     * @param $list
     */
    protected function formatData($list)
    {
        return $list;
    }

    /**
     * 列表拉取函数数据格式整理
     * @param $list
     */
    protected function formatStruct($total, $list)
    {
        return fromData($total, $list);
    }


    /* 列表拉取函数
     * Auth   : li
     * Time   : 2017年2月13日
     **/
    public function getIndexData($parameter)
    {

        if (!isset($parameter) || !$parameter) {
            return errorData('参数错误！');
        }

        $parameter ['first'] = $parameter ['limit'] * $parameter ['offset'];

        $fields = $this->searchFields();

        $map = $this->search();
        //system_user 判定用户是否为系统管理员
        $userInfo = getUserInfo();

        $sysUser = $userInfo['system_user'];

        if (1 != $sysUser) {
            $map ['system_user'] = 1;
        } else {
            $map ['system_user'] = array('in', "0,1");
        }
        $map['_logic'] = "and";
        $map['status'] = 0;

        $total = $this->where($map)->count();
        if ($total == 0) {
            $list = '';
        } else {
            $list = $this->where($map)
                ->limit($parameter ['first'] . ',' . $parameter ['limit'])
                ->field($fields)
                ->select();
        }
        $list = $this->formatData($list);
        $list = $this->formatStruct($total, $list);
        return $list;
    }


    /**
     * @return array 返回表格列名称
     */
    protected function getColumnName()
    {
        return array();
    }

    /**
     * @return array 返回表格列字段名称
     */
    protected function getTableFieldName()
    {
        return array();
    }

    /**
     * @return string 返回导出文件名称
     */
    protected function getXlsName()
    {

    }

    /**
     * 基础信息导出函数
     */
    public function ExportData()
    {
    }


}