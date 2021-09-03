<?php


namespace app\web\controller;


use app\Request;
use \app\web\model\Color as ColorModel;
use app\web\validate\Color as ColorValidate;
use app\web\model\GoodsDetails as GoodsDetailsModel;

/** 颜色管理
 * Class Color
 * @package app\web\controller
 * Date: 2020/12/31
 * Time: 11:55
 * @author mll
 */
class Color extends Controller
{
    //定义color模型
    protected $colorModel;
    protected $colorValidate;

    //初始化
    public function initialize()
    {
        parent::initialize();
        $this->colorModel = new ColorModel;
        $this->colorValidate = new ColorValidate;
    }
    //加载
    public function loadCombobox()
    {
        $colorModel = new ColorModel();
        $data = $colorModel->getList([$this->getData(["color_group", "<>", 0])], "color_id,color_name", "sort asc");
        return json($data);
    }

    /**
     * 函数描述:查询颜色列表
     * @return \think\response\Json
     * Date: 2020/12/31
     * Time: 11:55
     * @author mll
     */
    public function loadColorList()
    {
        $model = new ColorModel();
        $data = $model->getColorList($this->getData());
        return Json($data);
    }

    public function loadTreeList()
    {
        $model = new ColorModel();
        $data = $model->loadTreeList($this->getData());
        return Json($data);
    }

    public function editColor(Request $request)
    {
        $data = $request->post();
        if (!$this->colorValidate->scene('edit')->check($data)){
            return $this->renderError($this->colorValidate->getError());
        }
        $res = $this->colorModel->edit($this->getData($data));
        if ($res){
            return $this->renderSuccess('','修改成功');
        }
        return $this->renderError($this->colorModel->getError() ?: '修改失败');
    }

    /**
     * 函数描述: 删除颜色记录
     * @param Request $request
     * Date: 2020/12/31
     * Time: 15:37
     * @author mll
     */
    public function delColor(Request $request)
    {
        $data = $request->post();
        // 确定是否被使用过
        $goodsInfo = (new GoodsDetailsModel())->getOne(['color_id' => $data['color_id']], 'details_id');
        if ($goodsInfo) {
            return $this->renderError('已在使用的颜色不允许删除');
        }
        if (!empty($this->colorModel->getOne(['color_group' => $data['color_id']]))) {
            return $this->renderError('请先删除子节点');
        }
        $res = $this->colorModel->where(['color_id' => $data['color_id'],'com_id' => $this->com_id])->delete();
        if (!$res) {
            return $this->renderError('删除失败');
        }
        return $this->renderSuccess('', '删除成功');
    }


    public function addColor(Request $request)
    {
        $data = $request->post();
        if (!$this->colorValidate->scene('add')->check($data)){
            return $this->renderError($this->colorValidate->getError());
        }
        $res = $this->colorModel->add($this->getData($data));
        if ($res){
            return $this->renderSuccess('','添加成功');
        }
        return $this->renderError($this->colorModel->getError() ?: '添加失败');

    }


//    public function editColor(Request $request){
//        $data = $request->post();
//        $color_parent = $data['color_parent'];
//        $color_name = $data['color_name'];
//        $color_id = $data['color_id'];
//        $color_sort = $data['sort'];
//        $lock_version = $data['lock_version'];
//        $com_id = $this->com_id;
//        //父节点存在则 为更新 不存在则为新增
//        if (empty($this->colorModel->getOne(['com_id' => $com_id, 'color_name' => $color_parent], '*'))) {
//            $color_son = new colorModel();
//            $parent_id = $this->colorModel->addParent($color_parent, $com_id);
//            $res = $color_son->addSon($parent_id, $color_name, $com_id, $color_sort);
//            return $res ? $this->renderSuccess('', '添加成功') : $this->renderError('添加失败');
//        } else {
//            //color_id为空则新增 不为空则为更新
//            if (!empty($color_id)) {
//                $res = $this->colorModel->where(['color_id' => $color_id, 'lock_version' => $lock_version])->inc('lock_version')->save(['color_name' => $color_name, 'sort' => $color_sort]);
//                return $res ? $this->renderSuccess('', '更新成功') : $this->renderError('更新失败');
//            } else {
//                $parent_id = $this->colorModel->getOne(['color_name' => $color_parent, 'com_id' => $com_id], 'color_id');
//                $res = $this->colorModel->addSon($parent_id['color_id'], $color_name, $com_id, $color_sort);
//                return $res ? $this->renderSuccess('', '添加颜色成功') : $this->renderError('添加颜色失败');
//            }
//        }
//    }


}