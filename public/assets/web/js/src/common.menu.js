/**
 * 通用菜单函数
 */
(function($){
	/**
	 * 声明主窗口 ID
	 */
	var data = {
		id_MainTabs: '#main_tabs',
		id_MainIframe: '#main_iframe',
		/*tabs表单角标*/
		index :0,
	}
	$.h.menu = {
		/**
         * 首页
		 * SysMain
         */
		onMenuSysMain : function(e) {
            getMenuSelect(e);
			var objMainTabs = $(data.id_MainTabs);
			if (objMainTabs.tabs('existsById', 'SysMain')) {
				objMainTabs.tabs('selectById', 'SysMain');
			} else {
                objMainTabs.tabs('update', {
                    tab: objMainTabs.tabs('getTab',0),
                    options: {
						id: 'SysMain',
                        title: '首页'
                    }
                });
                $(data.id_MainIframe).attr('src', $.toUrl('index', 'main'));
				objMainTabs.tabs('selectById', 'SysMain');
            }
		},
		/**
         * 客户管理
		 * ClientListMain
         */
		onMenuClientListMain : function(e) {
            getMenuSelect(e);
			var objMainTabs = $(data.id_MainTabs);
			if (objMainTabs.tabs('existsById', 'ClientListMain')) {
				objMainTabs.tabs('selectById', 'ClientListMain');
			} else {
                objMainTabs.tabs('update', {
                    tab: objMainTabs.tabs('getTab',0),
                    options: {
						id: 'ClientListMain',
                        title: '客户管理'
                    }
                });
                $(data.id_MainIframe).attr('src', $.toUrl('client', 'client_list_main'));
				objMainTabs.tabs('selectById', 'ClientListMain');
            }
		},
        /**
         * 商品列表
		 * GooldsListMain
         */
		onMenuGooldsListMain : function(e) {
            getMenuSelect(e);
			var objMainTabs = $(data.id_MainTabs);
			if (objMainTabs.tabs('existsById', 'GooldsListMain')) {
				objMainTabs.tabs('selectById', 'GooldsListMain');
			} else {
                objMainTabs.tabs('update', {
                    tab: objMainTabs.tabs('getTab',0),
                    options: {
						id: 'GooldsListMain',
                        title: '商品列表'
                    }
                });
                $(data.id_MainIframe).attr('src', $.toUrl('goods', 'goods_list_main'));
				objMainTabs.tabs('selectById', 'GooldsListMain');
            }
		},
		/**
         * 库存管理
		 * StoreListMain
         */
		onMenuStoreListMain : function(e) {
            getMenuSelect(e);
			var objMainTabs = $(data.id_MainTabs);
			if (objMainTabs.tabs('existsById', 'StoreListMain')) {
				objMainTabs.tabs('selectById', 'StoreListMain');
			} else {
                objMainTabs.tabs('update', {
                    tab: objMainTabs.tabs('getTab',0),
                    options: {
						id: 'StoreListMain',
                        title: '库存管理'
                    }
                });
                $(data.id_MainIframe).attr('src', $.toUrl('store', 'store_list_main'));
				objMainTabs.tabs('selectById', 'StoreListMain');
            }
		},
		/**
         * 权限管理
		 * NodeListMain
         */
		onMenuNodeListMain : function(e) {
            getMenuSelect(e);
			var objMainTabs = $(data.id_MainTabs);
			if (objMainTabs.tabs('existsById', 'NodeListMain')) {
				objMainTabs.tabs('selectById', 'NodeListMain');
			} else {
                objMainTabs.tabs('update', {
                    tab: objMainTabs.tabs('getTab',0),
                    options: {
						id: 'NodeListMain',
                        title: '权限管理'
                    }
                });
                $(data.id_MainIframe).attr('src', $.toUrl('role', 'node_list_main'));
				objMainTabs.tabs('selectById', 'NodeListMain');
            }
		},
        /**
         * 库存查询
         * @param e
         */
        onMenuDepotQuery:function(e){
            getMenuSelect(e);
            var objMainTabs = $(data.id_MainTabs);
            if (objMainTabs.tabs('existsById', 'StoreListMain')) {
                objMainTabs.tabs('selectById', 'StoreListMain');
            } else {
                objMainTabs.tabs('update', {
                    tab: objMainTabs.tabs('getTab',0),
                    options: {
                        id: 'StoreListMain',
                        title: '库存查询'
                    }
                });
                $(data.id_MainIframe).attr('src', $.toUrl('store', 'store_list_main'));
                objMainTabs.tabs('selectById', 'StoreListMain');
            }
        },
        /**
         *  库存流水
         * @param e
         */
        onMenuDepotAccount:function(e){
            getMenuSelect(e);
            var objMainTabs = $(data.id_MainTabs);
            if (objMainTabs.tabs('existsById', 'StoreRecordMain')) {
                objMainTabs.tabs('selectById', 'StoreRecordMain');
            } else {
                objMainTabs.tabs('update', {
                    tab: objMainTabs.tabs('getTab',0),
                    options: {
                        id: 'StoreRecordMain',
                        title: '库存流水'
                    }
                });
                $(data.id_MainIframe).attr('src', $.toUrl('store', 'store_record_main'));
                objMainTabs.tabs('selectById', 'StoreRecordMain');
            }
        },
        /**
         * 库存调拨单
         * @param e
         */
        onMenuDepotAllotted:function(e){
            getMenuSelect(e);
            var objMainTabs = $(data.id_MainTabs);
            if (objMainTabs.tabs('existsById', 'StoreTransferOrdersMain')) {
                objMainTabs.tabs('selectById', 'StoreTransferOrdersMain');
            } else {
                objMainTabs.tabs('update', {
                    tab: objMainTabs.tabs('getTab',0),
                    options: {
                        id: 'StoreTransferOrdersMain',
                        title: '库存调拨单'
                    }
                });
                $(data.id_MainIframe).attr('src', $.toUrl('store', 'store_transfer_orders_main'));
                objMainTabs.tabs('selectById', 'StoreTransferOrdersMain');
            }
        },
        /**
         * 库存盘点单
         * @param e
         */
        onMenuDepotInventory:function(e){
            getMenuSelect(e);
            var objMainTabs = $(data.id_MainTabs);
            if (objMainTabs.tabs('existsById', 'StoreInventoryOrdersMain')) {
                objMainTabs.tabs('selectById', 'StoreInventoryOrdersMain');
            } else {
                objMainTabs.tabs('update', {
                    tab: objMainTabs.tabs('getTab',0),
                    options: {
                        id: 'StoreInventoryOrdersMain',
                        title: '库存盘点单'
                    }
                });
                $(data.id_MainIframe).attr('src', $.toUrl('store', 'store_inventory_orders_main'));
                objMainTabs.tabs('selectById', 'StoreInventoryOrdersMain');
            }
        },
		/**
         * 角色管理
		 * RoleListMain
         */
		onMenuRoleListMain : function(e) {
            getMenuSelect(e);
			var objMainTabs = $(data.id_MainTabs);
			if (objMainTabs.tabs('existsById', 'RoleListMain')) {
				objMainTabs.tabs('selectById', 'RoleListMain');
			} else {
                objMainTabs.tabs('update', {
                    tab: objMainTabs.tabs('getTab',0),
                    options: {
						id: 'RoleListMain',
                        title: '角色管理'
                    }
                });
                $(data.id_MainIframe).attr('src', $.toUrl('role', 'role_list_main'));
				objMainTabs.tabs('selectById', 'RoleListMain');
            }
		},
		/**
         * 权限页面
		 * ModuleListMain
         */
		onMenuModuleListMain : function(e) {
            getMenuSelect(e);
			var objMainTabs = $(data.id_MainTabs);
			if (objMainTabs.tabs('existsById', 'ModuleListMain')) {
				objMainTabs.tabs('selectById', 'ModuleListMain');
			} else {
                objMainTabs.tabs('update', {
                    tab: objMainTabs.tabs('getTab',0),
                    options: {
						id: 'ModuleListMain',
                        title: '权限页面'
                    }
                });
                $(data.id_MainIframe).attr('src', $.toUrl('role', 'module_list_main'));
				objMainTabs.tabs('selectById', 'ModuleListMain');
            }
		},
		/**
         * 单元格编辑
		 * CellEditingMain
         */
		onMenuCellEditingMain : function(e) {
            getMenuSelect(e);
			var objMainTabs = $(data.id_MainTabs);
			if (objMainTabs.tabs('existsById', 'CellEditingMain')) {
				objMainTabs.tabs('selectById', 'CellEditingMain');
			} else {
                objMainTabs.tabs('update', {
                    tab: objMainTabs.tabs('getTab',0),
                    options: {
						id: 'CellEditingMain',
                        title: '单元格编辑'
                    }
                });
                $(data.id_MainIframe).attr('src', $.toUrl('cell_editing', 'cell_editing_main'));
				objMainTabs.tabs('selectById', 'CellEditingMain');
            }
		},
        onMenuColourListMain:function(e) {
            getMenuSelect(e);
            var objMainTabs = $(data.id_MainTabs);
            if (objMainTabs.tabs('existsById', 'ColourListMain')) {
                objMainTabs.tabs('selectById', 'ColourListMain');
            } else {
                objMainTabs.tabs('update', {
                    tab: objMainTabs.tabs('getTab',0),
                    options: {
                        id: 'ColourListMain',
                        title: '颜色管理'
                    }
                });
                $(data.id_MainIframe).attr('src', $.toUrl('color', 'color_list_main'));
                objMainTabs.tabs('selectById', 'ColorListMain');
            }
        },

		/**
		 * 采购单
		 * PurchaseOrdersMain
		 */
		onMenuPurchaseOrder : function (e) {
            getMenuSelect(e);
			var objMainTabs = $(data.id_MainTabs);
			if (objMainTabs.tabs('existsById', 'PurchaseOrdersMain')) {
				objMainTabs.tabs('selectById', 'PurchaseOrdersMain');
			} else {
				objMainTabs.tabs('update', {
					tab: objMainTabs.tabs('getTab',0),
					options: {
						id: 'PurchaseOrdersMain',
						title: '采购单'
					}
				});
				$(data.id_MainIframe).attr('src', $.toUrl('purchase', 'purchase_order_list_main'));
				objMainTabs.tabs('selectById', 'PurchaseOrdersMain');
			}
		},


        /**
         * 销售订单
         * StoreListMain
         */
        onMenuMarketSoMain : function(e) {
            getMenuSelect(e);
            var objMainTabs = $(data.id_MainTabs);
            if (objMainTabs.tabs('existsById', 'SalePlanMain')) {
                objMainTabs.tabs('selectById', 'SalePlanMain');

            } else {
                objMainTabs.tabs('update', {
                    tab: objMainTabs.tabs('getTab',0),
                    options: {
                        id: 'SalePlanMain',
                        title: '销售订单'
                    }
                });
                $(data.id_MainIframe).attr('src', $.toUrl('sale', 'sale_plan_main'));
                objMainTabs.tabs('selectById', 'SalePlanMain');
            }
        },
        /**
         * 销售单
         * @param e
         */
        onMenuSalesTicketMain:function(e){
            getMenuSelect(e);
            var objMainTabs = $(data.id_MainTabs);
            if (objMainTabs.tabs('existsById', 'SalesTicketMain')) {
                objMainTabs.tabs('selectById', 'SalesTicketMain');

            } else {
                objMainTabs.tabs('update', {
                    tab: objMainTabs.tabs('getTab',0),
                    options: {
                        id: 'SalesTicketMain',
                        title: '销售单'
                    }
                });
                $(data.id_MainIframe).attr('src', $.toUrl('sale', 'sale_order_main'));
                objMainTabs.tabs('selectById', 'SalesTicketMain');
            }
        },
        /**
         * 销售退货申请
         * @param e
         */
        onMenuSaleRejectApply:function(e){
            getMenuSelect(e);
            var objMainTabs = $(data.id_MainTabs);
            if (objMainTabs.tabs('existsById', 'SaleRejectApplyMain')) {
                objMainTabs.tabs('selectById', 'SaleRejectApplyMain');
            } else {
                objMainTabs.tabs('update', {
                    tab: objMainTabs.tabs('getTab',0),
                    options: {
                        id: 'SaleRejectApplyMain',
                        title: '销售退货申请'
                    }
                });
                $(data.id_MainIframe).attr('src', $.toUrl('sale', 'sale_reject_apply_main'));
                objMainTabs.tabs('selectById', 'SaleRejectApplyMain');
            }
        },
		/**
		 *  供应商管理
		 */
        onMenuSupplierListMain:function(e) {
            getMenuSelect(e);
            var objMainTabs = $(data.id_MainTabs);
            if (objMainTabs.tabs('existsById', 'SupplierListMain')) {
                objMainTabs.tabs('selectById', 'SupplierListMain');

            } else {
                objMainTabs.tabs('update', {
                    tab: objMainTabs.tabs('getTab',0),
                    options: {
                        id: 'SupplierListMain',
                        title: '供应商管理'
                    }
                });
                $(data.id_MainIframe).attr('src', $.toUrl('purchase', 'supplier_list_main'));
                objMainTabs.tabs('selectById', 'SupplierListMain');
            }
        },
        /**
         * 供应商管理
         * SupplierMain
         */
        onMenuSupplier : function(e) {
            getMenuSelect(e);
            var objMainTabs = $(id_MainTabs);
            if (objMainTabs.tabs("existsById", 'SupplierMain')) {
                objMainTabs.tabs('selectById', 'SupplierMain');
            } else {
                objMainTabs.tabs('addIframe', {
                    id: 'SupplierMain',
                    title: '供应商管理',
                    fit: true,
                    url:'/manage/supplier/index',
                    url:$.toUrl('purchase', 'supplier_list_main'),
                    style: {overflow:'hidden',padding:'4px'},
                    closable: true
                });
            }
        },
        /**
         * 采购订单
         * @param e
         */
        onMenuBookingOrderListMain:function(e) {
            getMenuSelect(e);
            var objMainTabs = $(data.id_MainTabs);
            if (objMainTabs.tabs('existsById', 'BookingOrderMain')) {
                objMainTabs.tabs('selectById', 'BookingOrderMain');
            } else {
                objMainTabs.tabs('update', {
                    tab: objMainTabs.tabs('getTab',0),
                    options: {
                        id: 'BookingOrderMain',
                        title: '采购订单'
                    }
                });
                $(data.id_MainIframe).attr('src', $.toUrl('purchase', 'booking_order_list_main'));
                objMainTabs.tabs('selectById', 'BookingOrderMain');
            }
        },
        /**
         * 采购退货单
         * @param e
         */
        onMenuReturnPurchaseOrder : function (e) {
            getMenuSelect(e);
            var objMainTabs = $(data.id_MainTabs);
            if (objMainTabs.tabs('existsById', 'PurchaseReturn')) {
                objMainTabs.tabs('selectById', 'PurchaseReturn');
            } else {
                objMainTabs.tabs('update', {
                    tab: objMainTabs.tabs('getTab',0),
                    options: {
                        id: 'PurchaseReturn',
                        title: '采购退货单'
                    }
                });
                $(data.id_MainIframe).attr('src', $.toUrl('purchase', 'purchase_reject_list_main'));
                objMainTabs.tabs('selectById', 'PurchaseReturn');
            }
        },
        /**
         * 采购退货单
         * PurchaseRejectMain
         */
        onMenuPurchaseReject : function(e) {
            getMenuSelect(e);
            var objMainTabs = $(data.id_MainTabs);
            if (objMainTabs.tabs("existsById", 'PurchaseRejectMain')) {
                objMainTabs.tabs('selectById', 'PurchaseRejectMain');
            } else {
                objMainTabs.tabs('update', {
                    tab: objMainTabs.tabs('getTab',0),
                    options: {
                        id: 'PurchaseRejectMain',
                        title: '采购退货单'
                    }
                });
                $(data.id_MainIframe).attr('src',$.toUrl('purchase', 'purchase_reject_list_main'));
                objMainTabs.tabs('selectById', 'PurchaseRejectMain');
            }
        },
        /**
         * 销售退货单
         * SaleRejectOrdersMain
         */
        onMenuSaleRejectOrders : function(e) {
            getMenuSelect(e);
            var objMainTabs = $(data.id_MainTabs);
            if (objMainTabs.tabs("existsById", 'SaleRejectOrdersMain')) {
                objMainTabs.tabs('selectById', 'SaleRejectOrdersMain');
            } else {
                objMainTabs.tabs('update', {
                    tab: objMainTabs.tabs('getTab',0),
                    options: {
                        id: 'SaleRejectOrdersMain',
                        title: '销售退货单'
                    }
                });
                $(data.id_MainIframe).attr('src',$.toUrl('sale', 'sale_reject_order_main'));
                objMainTabs.tabs('selectById', 'SaleRejectOrdersMain');
            }
        },
        /**
         * 账户流水
         * FinanceAccountMain
         */
        onMenuFinanceAccount : function(e) {
            getMenuSelect(e);
            var objMainTabs = $(data.id_MainTabs);
            if (objMainTabs.tabs("existsById", 'FinanceAccountMain')) {
                objMainTabs.tabs('selectById', 'FinanceAccountMain');
            } else {
                objMainTabs.tabs('update', {
                    tab: objMainTabs.tabs('getTab',0),
                    options: {
                        id: 'FinanceAccountMain',
                        title: '账户流水'
                    }
                });
                $(data.id_MainIframe).attr('src',$.toUrl('finance', 'finance_account_main'));
                objMainTabs.tabs('selectById', 'FinanceAccountMain');
            }
        },
        /**
         * 客户对账
         * FinanceClientMain
         */
        onMenuFinanceClient : function(e) {
            getMenuSelect(e);
            var objMainTabs = $(data.id_MainTabs);
            if (objMainTabs.tabs("existsById", 'FinanceClientMain')) {
                objMainTabs.tabs('selectById', 'FinanceClientMain');
            } else {
                objMainTabs.tabs('update', {
                    tab: objMainTabs.tabs('getTab',0),
                    options: {
                        id: 'FinanceClientMain',
                        title: '客户对账'
                    }
                });
                $(data.id_MainIframe).attr('src',$.toUrl('finance', 'finance_client_main'));
                objMainTabs.tabs('selectById', 'FinanceClientMain');
            }
        },
        /**
         * 供应商对账
         * FinanceSupplierMain
         */
        onMenuFinanceSupplier : function(e) {
            getMenuSelect(e);
            var objMainTabs = $(data.id_MainTabs);
            if (objMainTabs.tabs("existsById", 'FinanceSupplierMain')) {
                objMainTabs.tabs('selectById', 'FinanceSupplierMain');
            } else {
                objMainTabs.tabs('update', {
                    tab: objMainTabs.tabs('getTab',0),
                    options: {
                        id: 'FinanceSupplierMain',
                        title: '供应商对账'
                    }
                });
                $(data.id_MainIframe).attr('src',$.toUrl('finance', 'finance_supplier_main'));
                objMainTabs.tabs('selectById', 'FinanceSupplierMain');
            }
        },
        /**
         * 结算账户
         * SettlementMain
         */
        onMenuSettlement : function(e) {
            getMenuSelect(e);
            var objMainTabs = $(data.id_MainTabs);
            if (objMainTabs.tabs("existsById", 'SettlementMain')) {
                objMainTabs.tabs('selectById', 'SettlementMain');
            } else {
                objMainTabs.tabs('update', {
                    tab: objMainTabs.tabs('getTab',0),
                    options: {
                        id: 'SettlementMain',
                        title: '结算账户'
                    }
                });
                $(data.id_MainIframe).attr('src',$.toUrl('finance', 'settlement_main'));
                objMainTabs.tabs('selectById', 'SettlementMain');
            }
        },
        /**
         * 采购报表
         * ReportPurchaseMain
         */
        onMenuReportPurchase : function(e) {
            getMenuSelect(e);
            var objMainTabs = $(data.id_MainTabs);
            if (objMainTabs.tabs("existsById", 'ReportPurchaseMain')) {
                objMainTabs.tabs('selectById', 'ReportPurchaseMain');
            } else {
                objMainTabs.tabs('update', {
                    tab: objMainTabs.tabs('getTab',0),
                    options: {
                        id: 'ReportPurchaseMain',
                        title: '采购报表'
                    }
                });
                $(data.id_MainIframe).attr('src',$.toUrl('report', 'report_purchase_main'));
                objMainTabs.tabs('selectById', 'ReportPurchaseMain');
            }
        },
        /**
         * 销售报表
         * @param e
         */
        onMenuReportSale : function(e) {
            getMenuSelect(e);
            var objMainTabs = $(data.id_MainTabs);
            if (objMainTabs.tabs("existsById", 'ReportSaleMain')) {
                objMainTabs.tabs('selectById', 'ReportSaleMain');
            } else {
                objMainTabs.tabs('update', {
                    tab: objMainTabs.tabs('getTab',0),
                    options: {
                        id: 'ReportSaleMain',
                        title: '销售报表'
                    }
                });
                $(data.id_MainIframe).attr('src',$.toUrl('report', 'report_sale_main'));
                objMainTabs.tabs('selectById', 'ReportSaleMain');
            }
        },
        /**
         * 盘点报表
         * @param e
         */
        onMenuReportStore : function(e) {
            getMenuSelect(e);
            var objMainTabs = $(data.id_MainTabs);
            if (objMainTabs.tabs("existsById", 'ReportStoreMain')) {
                objMainTabs.tabs('selectById', 'ReportStoreMain');
            } else {
                objMainTabs.tabs('update', {
                    tab: objMainTabs.tabs('getTab',0),
                    options: {
                        id: 'ReportStoreMain',
                        title: '盘点报表'
                    }
                });
                $(data.id_MainIframe).attr('src',$.toUrl('report', 'report_store_main'));
                objMainTabs.tabs('selectById', 'ReportStoreMain');
            }
        },
        /**
         * 调拨报表
         * @param e
         */
        onMenuReportTransfer : function(e) {
            getMenuSelect(e);
            var objMainTabs = $(data.id_MainTabs);
            if (objMainTabs.tabs("existsById", 'ReportTransferMain')) {
                objMainTabs.tabs('selectById', 'ReportTransferMain');
            } else {
                objMainTabs.tabs('update', {
                    tab: objMainTabs.tabs('getTab',0),
                    options: {
                        id: 'ReportTransferMain',
                        title: '调拨报表'
                    }
                });
                $(data.id_MainIframe).attr('src',$.toUrl('report', 'report_transfer_main'));
                objMainTabs.tabs('selectById', 'ReportTransferMain');
            }
        },
        /**
         * 会员管理
         * MemberMain
         */
        onMenuMember : function (obj) {

            getMenuSelect(obj);
            var objMainTabs = $(data.id_MainTabs);
            if (objMainTabs.tabs("existsById", 'MemberMain')) {
                objMainTabs.tabs('selectById', 'MemberMain');
            } else {
                objMainTabs.tabs('update', {
                    tab: objMainTabs.tabs('getTab',0),
                    options: {
                        id: 'MemberMain',
                        title: '会员管理'
                    }
                });
                $(data.id_MainIframe).attr('src',$.toUrl('member', 'member_main'));
                objMainTabs.tabs('selectById', 'MemberMain');
            }
        },
        /**
         * 员工管理
         * @param e
         */
        onMenuUser : function (obj) {
            getMenuSelect(obj);
            var objMainTabs = $(data.id_MainTabs);
            if (objMainTabs.tabs("existsById", 'UserMain')) {
                objMainTabs.tabs('selectById', 'UserMain');
            } else {
                objMainTabs.tabs('update', {
                    tab: objMainTabs.tabs('getTab',0),
                    options: {
                        id: 'UserMain',
                        title: '员工管理'
                    }
                });
                $(data.id_MainIframe).attr('src',$.toUrl('user', 'user_main'));
                objMainTabs.tabs('selectById', 'UserMain');
            }
        },

        /**
         * 加载角色管理
         * RoleMain
         */
        onMenuRole : function (e) {
            getMenuSelect(e);
            var objMainTabs = $(data.id_MainTabs);
            if (objMainTabs.tabs("existsById", 'RoleMain')) {
                objMainTabs.tabs('selectById', 'RoleMain');
            } else {
                objMainTabs.tabs('update', {
                    tab: objMainTabs.tabs('getTab',0),
                    options: {
                        id: 'RoleMain',
                        title: '角色管理'
                    }
                });
                $(data.id_MainIframe).attr('src',$.toUrl('manage', 'role_main'));
                objMainTabs.tabs('selectById', 'RoleMain');
            }
        },
        /**
         * 员工管理
         * @param e
         */
        onMenuSystemLog : function (obj) {
            getMenuSelect(obj);
            var objMainTabs = $(data.id_MainTabs);
            if (objMainTabs.tabs("existsById", 'SystemLog')) {
                objMainTabs.tabs('selectById', 'SystemLog');
            } else {
                    objMainTabs.tabs('update', {
                        tab: objMainTabs.tabs('getTab',0),
                        options: {

                            id: 'SystemLog',
                            title: '系统日志'
                        }
                    });
                    $(data.id_MainIframe).attr('src',$.toUrl('manage', 'system_log_main'));
                    objMainTabs.tabs('selectById', 'SystemLog');
                }
            },

         /* 物流设置
         * LogisticsMain
         */
        onMenuLogistics : function (obj) {
            getMenuSelect(obj);
            var objMainTabs = $(data.id_MainTabs);
            if (objMainTabs.tabs("existsById", 'LogisticsMain')) {
                objMainTabs.tabs('selectById', 'LogisticsMain');

            } else {
                objMainTabs.tabs('update', {
                    tab: objMainTabs.tabs('getTab',0),
                    options: {
                        id: 'LogisticsMain',
                        title: '物流设置'
                    }
                });
                $(data.id_MainIframe).attr('src',$.toUrl('logistics', 'Logistics_Main'));
                objMainTabs.tabs('selectById', 'LogisticsMain');
            }
        },
        /* 公司报告
        * CompanyReportMain
        */
        onMenuCompanyReport : function (obj) {
            getMenuSelect(obj);
            var objMainTabs = $(data.id_MainTabs);
            if (objMainTabs.tabs("existsById", 'CompanyReportMain')) {
                objMainTabs.tabs('selectById', 'CompanyReportMain');

            } else {
                objMainTabs.tabs('update', {
                    tab: objMainTabs.tabs('getTab',0),
                    options: {
                        id: 'CompanyReportMain',
                        title: '公司报告'
                    }
                });
                $(data.id_MainIframe).attr('src',$.toUrl('company_report', 'company_report_main'));
                objMainTabs.tabs('selectById', 'CompanyReportMain');
            }
        },
        /* 新手入门*/
        onMenuTheNew : function (obj) {
            getMenuSelect(obj);
            var objMainTabs = $(data.id_MainTabs);
            if (objMainTabs.tabs("existsById", 'main')) {
                objMainTabs.tabs('selectById', 'main');

            } else {
                objMainTabs.tabs('update', {
                    tab: objMainTabs.tabs('getTab',0),
                    options: {
                        id: 'main',
                        title: '新手入门'
                    }
                });
                $(data.id_MainIframe).attr('src',$.toUrl('index', 'main'));
                objMainTabs.tabs('selectById', 'main');
            }
        },
	}
	function getMenuSelect(obj) {
        $('.menu-item').removeClass('menu-selected');
        $(obj).addClass('menu-selected');
    }
})(jQuery);