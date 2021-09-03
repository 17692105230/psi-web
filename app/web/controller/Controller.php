<?php

namespace app\web\controller;

use Symfony\Component\VarDumper\Command\Descriptor\DumpDescriptorInterface;
use think\facade\View;
use app\common\service\Render;
use think\exception\ValidateException;

class Controller extends \app\BaseController
{

    use Render;
    // 企业id
    protected $com_id = 1;
    protected $user = [];
    // use Render;
    // 不允许加载模板页面
    private $notLayoutPage = [
        'index',
        'client_list_left',
        'client_list_center',
        'client_edit_left',
        'client_edit_center',
        'work_accept_left',
        'work_accept_center',
        'demo',
        'demo1',
        'demo2',
        'goods_list_left',
        'goods_list_center',
        'store_list_east',
        'store_list_center',
        'node_list_left',
        'node_list_center',
        'node_list_right',
        'role_list_left',
        'role_list_center',
        'module_list_left',
        'module_list_center',
        'cell_editing_left',
        'cell_editing_center',
        'customer_classify_list',
        'color_list',
        'material_list',
        'category_list',
        'unit_list',
        'brand_list',
        'size_list_center',
        'barcode_list_center',
        'season_list_center',
        'style_list_center',
        'purchase_order_east',
        'purchase_order_center',
        'supplier_list_center',
        'supplier_list_left',
        'booking_order_list_center',
        'booking_order_list_left',
        'sales_ticket_center',
        'sales_ticket_east',
        'purchase_reject_east',
        'purchase_reject_center',
        'color_size',
        'sale_reject_order_center',
        'sale_reject_order_left',
        'sale_reject_apply_center',
        'sale_reject_apply_east',
        'purchase_orders_center_complete',
        'purchase_reject_center_complete',
        'store_record_center',
        'store_transfer_orders_center',
        'store_transfer_orders_east',
        'store_transfer_orders_center_complete',
        'purchase_plan_left',
        'purchase_plan_center',
        'purchase_plan_center_complete',
        'purchase_reject_center_revoke',
        'store_inventory_orders_center',
        'store_inventory_orders_east',
        'store_inventory_orders_win',
        'store_inventory_orders_center_complete',
        'sale_order_east',
        'sale_order_center',
        'sale_order_center_complete',
        'sale_reject_order_center_complete',
        'sale_reject_apply_center_complete',
        'sale_plan_east',
        'sale_plan_center',
        'sale_plan_center_complete',
        'member_center',
        'member_right',
        'account_type',
        'finance_account_east',
        'finance_account_center',
        'finance_client_center',
        'finance_client_left',
        'finance_supplier_begin',
        'finance_supplier_center',
        'finance_supplier_east',
        'finance_supplier_out',
        'settlement_center',
        'settlement_right',
        'report_purchase_east',
        'report_purchase_center',
        'report_sale_east',
        'report_sale_center',
        'report_store_center',
        'report_store_east',
        'report_transfer_center',
        'report_transfer_east',
        'user_info',
        'reset_password',
        'finance_client_begin',
        'finance_client_in',
        'finance_account_begin',
        'finance_account_in',
        'finance_account_loop',
        'finance_account_out',
        'role_center',
        'role_left',
        'user_center',
        'user_east',
        'user_west',
        'org_window',
        'logistics_center',
        'logistics_right',
        'company_report_center',
        'company_report_right',
        'system_log_center',
        'system_parameter',
        'purchase_orders_center_revoke',
        'file_view',
        'data_import',
        'file_upload',
    ];

    /** @var string $module 当前模块名称 */
    protected $module = '';

    /** @var string $controller 当前控制器名称 */
    protected $controller = '';

    /** @var string $action 当前方法名称 */
    protected $action = '';

    /** @var string $routeUri 当前路由uri */
    protected $routeUri = '';

    /** @var string $group 当前路由：分组名称 */
    protected $group = '';

    /* @var array $notLayoutAction 无需全局layout */
    protected $notLayoutAction = [
        // 登录页面
        'passport/login',
    ];

    /**
     * 后台初始化
     */
    public function initialize()
    {
        // 当前路由信息
        //   $this->getRouteinfo();
        // 全局 layout
        //  echo 11111;
        //临时设置登录用户
        session("user",["user_id"=>1,"org_id"=>1,"user_name"=>"hello world"]);
        $this->user = session("user");
        $this->layout();
    }

    /**
     * 解析当前路由参数 （分组名称、控制器名称、方法名）
     */
    protected function getRouteinfo()
    {
        // 模块名称
        $this->module = toUnderScore($this->request->module());
        // 控制器名称
        $this->controller = toUnderScore($this->request->controller());
        // 方法名称
        $this->action = $this->request->action();
        // 控制器分组 (用于定义所属模块)
        $groupstr = strstr($this->controller, '.', true);
        $this->group = $groupstr !== false ? $groupstr : $this->controller;
        // 当前uri
        $this->routeUri = $this->controller . '/' . $this->action;
    }

    /**
     * 全局 layout 模板输出
     */
    private function layout()
    {

        // 验证当前请求是否在白名单
        if (!in_array($this->routeUri, $this->notLayoutAction)) {
            // 输出到view
            View::assign([
                //  'base_url' => base_url(),                      // 当前域名
                'assets' => 'web',                       // 后台模块url
                //  'group' => $this->group,                       // 当前控制器分组
                //    'request' => Request::instance(),              // Request对象
                //  'version' => get_version(),                    // 系统版本号
            ]);
        }
    }

    public function __call($name, $args)
    {

        if (in_array(strtolower($name), $this->notLayoutPage)) {
            config(['layout_on' => false], 'view');
        }
        return View::fetch($name);
    }

    protected function validate(array $data, $validate, array $message = [], bool $batch = false)
    {
        try {
            return parent::validate($data, $validate, $message, $batch);
        } catch (ValidateException $e) {
            return $e->getMessage();
        }

    }

    protected function getData($data = [])
    {
        return array_merge($data, ['com_id' => $this->com_id]);
    }
}
