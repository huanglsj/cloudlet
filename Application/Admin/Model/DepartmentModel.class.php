<?php
namespace Admin\Model;

use Think\Model;

class DepartmentModel extends Model
{

    /**
     * 自动验证
     */
    protected $_validate = array(
        array(
            'code',
            'require',
            '部门编号不能为空！'
        ),
        array(
            'name',
            'require',
            '部门名称不能为空！'
        ),
        array(
            'type',
            'require',
            '部门类型不能为空！'
        ),
        array(
            'invite_code',
            'require',
            '邀请码不能为空！'
        )
    );

    /**
     * 自动完成
     */
    protected $_auto = array(
        
    );

    public function addDepartment($name, $type, $parentId=null)
    {
        $department['name'] = $name;
        $department['type'] = $type;
        $department['isdelete'] = 'N';
        
        if(!$parentId && $type == 2){
            //创建会员单位
            $parent = $this->where("code='000'")->field('id')->find();
            $parentId = $parent['id'];
            if(!$parent){
                $parentId = $this->generateTopDepartment();
            }
        }
        if ($parentId != null) {
            // 获取新code
            $department['code'] = $this->getNewDepartmentCode($parentId, $department['type']);
            $department['invite_code'] = $this->generateInviteCode();
            $department['parent_id'] = $parentId;
            $parentinfo = $this->where('id=' . $parentId)->find();
            $department['parent_code'] = $parentinfo['code'];
            $department['parent_name'] = $parentinfo['name'];
            $department['create_time'] = time();
            $department['create_uid'] = $parentId;
            
            $result = $this->add($department);
            if($result){
                //设置关联组织信息
                if($type == 2){
                    //会员时设置会员信息
                    $department['huiyuan_id'] = $result;
                    $department['huiyuan_code'] = $department['code'];
                    $department['huiyuan_name'] = $name;
                }else if($type == 3){
                    //代理时设置会员信息
                    $department['huiyuan_id'] = $parentId;
                    $department['huiyuan_code'] = $parentinfo['code'];
                    $department['huiyuan_name'] = $parentinfo['name'];
                    //设置代理信息
                    $department['daili_id'] = $result;
                    $department['daili_code'] = $department['code'];
                    $department['daili_name'] = $name;
                }else if($type == 4){
                    //机构时设置会员信息
                    if($parentinfo['type'] == 2){
                        //机构直属会员
                        $department['huiyuan_id'] = $parentId;
                        $department['huiyuan_code'] = $parentinfo['code'];
                        $department['huiyuan_name'] = $parentinfo['name'];
                        
                        //设置机构信息
                        $department['jigou_id'] = $result;
                        $department['jigou_code'] = $department['code'];
                        $department['jigou_name'] = $name;
                    }else if($parentinfo['type'] == 3){
                        $grandinfo = $this->where('id=' . $parentinfo['parent_id'])->find();
                        $department['huiyuan_id'] = $grandinfo['id'];
                        $department['huiyuan_code'] = $grandinfo['code'];
                        $department['huiyuan_name'] = $grandinfo['name'];
                        //设置代理信息
                        $department['daili_id'] = $parentId;
                        $department['daili_code'] = $parentinfo['code'];
                        $department['daili_name'] = $parentinfo['name'];
                        //设置机构信息
                        $department['jigou_id'] = $result;
                        $department['jigou_code'] = $department['code'];
                        $department['jigou_name'] = $name;
                    }
                }
                $this->where('id='.$result)->save($department);
            }else{
                return $result;
            }
        } else {
            //无父节点，属于总公司
            $result = $this->generateTopDepartment();
        }
        return $result;
    }

    public function getNewDepartmentCode($parentid = null, $type = null)
    {
        if ($type != 2) {
            // 非会员单位
            $parentinfo = D('department')->where('id=' . $parentid)->find();
            $count = D('department')->where('parent_id=' . $parentid)->count();
            $temp_num = 1000 + $count + 1;
            if ($parentinfo['type'] == 2 && $type == 4) {
                // 会员下面直属机构
                return $parentinfo['code'] . '000' . substr($temp_num, 1);
            }
            return $parentinfo['code'] . substr($temp_num, 1);
        } else {
            // 会员单位
            $count = $this->where('type=2')->count();
            $temp_num = 1000 + $count + 1;
            return substr($temp_num, 1);
        }
    }

    public function generateInviteCode($length = 6)
    {
        $department = D('department');
        $inviteCode = strval(rand(pow(10, ($length - 1)), pow(10, $length) - 1));
        while ($department->where('invite_code=' . $inviteCode)->find()) {
            $inviteCode = strval(rand(pow(10, ($length - 1)), pow(10, $length) - 1));
        }
        return $inviteCode;
    }
    
    /**
     * 产生总公司的信息
     */
    public function generateTopDepartment(){
       $data['code'] = '000';
       $data['name'] = C('PLATFORM_USERNAME');
       $data['type'] = 1;
       $data['invite_code'] = $this->generateInviteCode();
       $data['isdelete'] = 'N';
       $data['create_time'] = time();
       $data['create_uid'] = -1;
       
       return $this->add($data);
    }
}