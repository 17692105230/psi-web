<?php

namespace app\web\controller;

use think\facade\Db;
use think\Request;
use app\web\model\Goods as GoodModel;
use app\web\validate\Goods as GoodsValidate;
use app\web\model\GoodsDetails;

use app\web\model\GoodsAssist;
use think\Db\Raw;
use app\web\logic\SystemLogic;
use app\common\utils\BuildCode;
use app\common\library\storage\Driver as FileDriver;


/**
 * 商品
 * Class Goods
 * @package app\web\controller
 */
class Goods extends Controller
{
    public function loadGoodsGrid(Request $request)
    {

        $pages = $request->get('pages', 1);
        $rows = $request->get('rows', 30);
        $where = $request->get();
        $good_model = new GoodModel();
        $data = $good_model->getGoodsList($this->getData($where), $rows, $pages);
        return Json($data);
    }

    public function saveGoods(Request $request)
    {
        $form_data = $request->post();
        $goods_model = new GoodModel();
        $good_validate = new GoodsValidate();
        if (!$good_validate->scene('add')->check($form_data)) {
            return $this->renderError($good_validate->getError());
        }
        Db::startTrans();
        $res = $goods_model->saveGoods($form_data, $this->com_id);
        if (!$res) {
            Db::rollback();
            return $this->renderError('新增商品失败');
        }
        $goods_id = $goods_model->goods_id;
        $goods_details = new GoodsDetails();
        $details = json_decode($form_data['details'], true);
        foreach ($details as $key => $rows) {
            $res2 = $goods_details->saveDetails($rows, $this->com_id, $form_data['goods_code'], $goods_id);
            if (!$res2) {
                Db::rollback();
                return $this->renderError('插入单品失败');
            }
        }
        $goods_assist = new GoodsAssist();
        $res3 = $goods_assist->where('goods_id', $form_data['temp_code'])->update(['goods_id' => $goods_id]);
        /*if (!$res3) {
            Db::rollback();
            return $this->renderError('请先上传图片');
        }*/
        Db::commit();
        return $this->renderSuccess('', '商品添加成功');
    }


    /**
     * @desc  添加商品
     * @param Request $request
     * @return array
     * Date: 2021/1/21
     * Time: 10:56
     * @author myy
     */
    public function createGoods(Request $request)
    {
        // 数据验证
        $data = $request->post();
        $goodValidate = new GoodsValidate();
        if (!$goodValidate->scene('add')->check($data)) {
            return $this->renderError($goodValidate->getError());
        }
        // 创建商品数据
        $goodsModel = new GoodModel();
        $res = $goodsModel->createGoods($this->getData($data));
        if ($res) {
            return $this->renderSuccess([], '商品添加成功');
        }
        return $this->renderError($goodsModel->getError('商品添加失败'));
    }


    public function codeExample()
    {
        $code = 'GN' . (100000 + $this->com_id) . date('Ymd');
        return $this->renderSuccess(['code' => $code]);
    }

    public function newGoods()
    {
        // 创建商品数据
        $goodsModel = new GoodModel();
        $historyData = $goodsModel->getOne(['com_id' => $this->com_id,'goods_status' => 100],'goods_id');
        if (empty($historyData)) {
          $goods_id = $goodsModel->insertGetId([
                'com_id' => $this->com_id,
                'goods_status' => 100,
                'create_time' => time(),
                'update_time' => time(),
          ]);
          $data = $goodsModel->getDetail(['com_id' => $this->com_id, 'goods_id' => $goods_id])->toArray();
          $data['goods_code'] = 'GN' . (100000 + $this->com_id) . date('Ymd');
          return $this->renderSuccess($data);
        }
        $data = $goodsModel->getDetail(['com_id' => $this->com_id, 'goods_id' => $historyData['goods_id']])->toArray();
        $data['goods_code'] = 'GN' . (100000 + $this->com_id) . date('ymdHis');
        return $this->renderSuccess($data);
    }

    public function formPurchase(Request $request)
    {
        $data = $request->post();
       // var_dump($data);
        return json($this->renderSuccess());
    }

    public function loadConfig()
    {
        $data = [
            'config' => [
                'prefix' => '54821946'
            ],
            'seed' => '6000'
        ];
        return json($data);
    }

    public function delGoodsItem()
    {
        $goods_code = $this->request->get('goods_code');
        if (empty($goods_code)) {
            return $this->renderError('商品货号不能为空');
        }
        // 已使用的不允许删除
        $logic = new SystemLogic();
        $exist_flag = $logic->exist(['goods_code' => $goods_code, 'com_id' => $this->com_id], 'goods_code');
        if ($exist_flag) {
            return $this->renderError('已使用的商品不允许删除');
        }
        $goodModel = new GoodModel();
        $result = $goodModel->delGoods($this->getData(['goods_code' => $goods_code, 'com_id' => $this->com_id]));
        if ($result) {
            return $this->renderSuccess();
        }
        return $this->renderError($goodModel->getError('删除失败'));
    }

    public function loadGoodsInfo()
    {
        $goods_code = $this->request->get('goods_code');
        if (empty($goods_code)) {
            return $this->renderError('商品货号不能为空');
        }

        $goodModel = new GoodModel();
        $result = $goodModel->getDetail($this->getData(['goods_code' => $goods_code]));

        if ($result) {
            return $this->renderSuccess($result);
        }
        return $this->renderError($goodModel->getError('查询失败'));
    }

    public function updateGoods(Request $request)
    {
        $form_data = $request->post();
        $goods_model = new GoodModel();
        $good_validate = new GoodsValidate();
        if (!$good_validate->scene('add')->check($form_data)) {
            return $this->renderError($good_validate->getError());
        }
        $res = $goods_model->editNew($form_data, $this->com_id);
        if ($res) {
            return $this->renderSuccess('', '商品修改成功');
        }
        return $this->renderError($goods_model->getError('修改失败'));
    }

    /**
     * 采购使用
     */
    public function sPurchaseOrdersGoods() {
        $resMessage = $this->mSearchGoods('goods_id,goods_name,goods_code,goods_barcode,goods_pprice,brand_id,material_id,unit_id,goods_season,goods_status');
        return $resMessage;
    }

    /**
     * 仓库盘点使用
     */
    public function sGeneralOrdersGoods()
    {
        $resMessage = $this->mSearchGoods('goods_id,goods_name,goods_code,goods_barcode,goods_pprice,brand_id,material_id,unit_id,goods_season');
        return $resMessage;
    }

    /**
     * 查询商品
     */
    private function mSearchGoods($fields)
    {
        $keyword = $this->request->get('keyword');
        $page = $this->request->get('page', 1);
        $rows = $this->request->get('rows', 10);
        $req = ['keyword' => $keyword, 'page' => $page, 'rows' => $rows];
        $good_validate = new GoodsValidate();
        if (!$good_validate->scene("search_goods")->check($req)) {
            return ["total" => 0, "rows" => []];
        }
        $goods_model = new GoodModel();
        $res = $goods_model->getGoodsPaginate($this->com_id, [["goods_name", "like", "%{$keyword}%"], ["goods_code", "like", "%{$keyword}%"], ["goods_barcode", "like", "%{$keyword}%"]], ["page" => $page, "list_rows" => $rows], $fields);
        return ["total" => $res->total(), "rows" => $res->items()];
    }

    public function test()
    {
        dd(config('goods.image.default'));
    }

    /**
     * @desc   删除商品相册图片
     * @return array
     * Date: 2021/1/27
     * Time: 11:33
     * @author myy
     */
    public function delImage()
    {
        $assist_id = $this->request->post('assist_id');
        if (empty($assist_id)) {
            return $this->renderError('缺少要删除的图片信息');
        }
        $goodsAssistModel = new GoodsAssist();
        $assistInfo = $goodsAssistModel->getOne(['com_id' => $this->com_id, 'assist_id' => $assist_id]);
        if (!$assistInfo) {
            return $this->renderError('图片未找到');
        }
        $assistInfo->delete();
        if (file_exists('.'.$assistInfo['assist_url'])) {
            unlink('.'.$assistInfo['assist_url']);
        }

        return $this->renderSuccess([],'图片删除成功');
    }

    /**
     * @desc  设置为主图
     * @return array
     * Date: 2021/1/27
     * Time: 15:35
     * @author myy
     */
    public function mainImage()
    {
        $assist_id = $this->request->post('assist_id');
        if (empty($assist_id)) {
            return $this->renderError('缺少要设置的图片信息');
        }
        $goodsAssistModel = new GoodsAssist();
        $assistInfo = $goodsAssistModel->getOne(['com_id' => $this->com_id, 'assist_id' => $assist_id]);
        if (!$assistInfo) {
            return $this->renderError('图片未找到');
        }
        $assistInfo->save(['assist_sort' => 0]);

        return $this->renderSuccess([],'设置成功');
    }

    public function imageUpload()
    {
        $goods_id = $this->request->post('goods_id',0);
        $driver = new FileDriver(config('upload'), 'local');
        $driver->setUploadFile('file');
        $driver->uploadImage();
        $driver->setSavePath("/storage/{$this->com_id}/goods/{$goods_id}/");
        $res = $driver->upload();
        if (!$res) {
            return $this->renderError($driver->getError());
        }
        $file_name = $driver->getFileName();
        $fileInfo = $driver->getUploadFileInfo();
        $goods_assist = new GoodsAssist();
        $data = [
            'goods_id' => $goods_id,
            'assist_category' => 'image',
            'assist_name' => $fileInfo['origin_name'],
            'assist_extension' => $fileInfo['ext'],
            'assist_url' => $file_name,
            'assist_size' => $fileInfo['size'],
            'create_time' => time(),
            'assist_md5' => $fileInfo['md5'],
            'assist_sha1' => $fileInfo['sha1'],
            'status' => 1,
            'com_id' => $this->com_id,
            'type' => 'localWeb',
        ];
        $goods_assist->save($data);
        return $this->renderSuccess(['path' => $file_name, 'assist_id' =>$goods_assist['assist_id'] ],'上传成功');
    }


}