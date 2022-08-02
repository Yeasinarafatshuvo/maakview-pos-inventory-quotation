<div class="aiz-sidebar-wrap">
    <div class="aiz-sidebar left c-scrollbar">
        <div class="aiz-side-nav-logo-wrap">
            <a href="{{ route('admin.dashboard') }}" class="d-block text-left">
                @if (get_setting('system_logo_white') != null)
                    <img class="mw-100" src="{{ uploaded_asset(get_setting('system_logo_white')) }}"
                        class="brand-icon" alt="{{ get_setting('site_name') }}">
                @else
                    <img class="mw-100" src="{{ static_asset('assets/img/logo-white.png') }}"
                        class="brand-icon" alt="{{ get_setting('site_name') }}">
                @endif
            </a>
        </div>
        <div class="aiz-side-nav-wrap">
            <ul class="aiz-side-nav-list" data-toggle="aiz-side-menu">
                <li class="aiz-side-nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="aiz-side-nav-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                            <path id="Path_18917" data-name="Path 18917"
                                d="M3.889,11.889H9.222A.892.892,0,0,0,10.111,11V3.889A.892.892,0,0,0,9.222,3H3.889A.892.892,0,0,0,3,3.889V11A.892.892,0,0,0,3.889,11.889Zm0,7.111H9.222a.892.892,0,0,0,.889-.889V14.556a.892.892,0,0,0-.889-.889H3.889A.892.892,0,0,0,3,14.556v3.556A.892.892,0,0,0,3.889,19Zm8.889,0h5.333A.892.892,0,0,0,19,18.111V11a.892.892,0,0,0-.889-.889H12.778a.892.892,0,0,0-.889.889v7.111A.892.892,0,0,0,12.778,19ZM11.889,3.889V7.444a.892.892,0,0,0,.889.889h5.333A.892.892,0,0,0,19,7.444V3.889A.892.892,0,0,0,18.111,3H12.778A.892.892,0,0,0,11.889,3.889Z"
                                transform="translate(-3 -3)" fill="#707070" />
                        </svg>
                        <span class="aiz-side-nav-text">{{ translate('Dashboard') }}</span>
                    </a>
                </li>


                <!-- Inventory -->
                @can('inventory_manage')
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                            <g id="Group_23" data-name="Group 23" transform="translate(-126 -590)">
                                <path id="Subtraction_31" data-name="Subtraction 31"
                                    d="M15,16H1a1,1,0,0,1-1-1V1A1,1,0,0,1,1,0H4.8V4.4a2,2,0,0,0,2,2H9.2a2,2,0,0,0,2-2V0H15a1,1,0,0,1,1,1V15A1,1,0,0,1,15,16Z"
                                    transform="translate(126 590)" fill="#707070" />
                                <path id="Rectangle_93" data-name="Rectangle 93"
                                    d="M0,0H4A0,0,0,0,1,4,0V4A1,1,0,0,1,3,5H1A1,1,0,0,1,0,4V0A0,0,0,0,1,0,0Z"
                                    transform="translate(132 590)" fill="#707070" />
                            </g>
                        </svg>
                        <span class="aiz-side-nav-text">{{ translate('Inventory') }}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <!--Submenu-->

                    <ul class="aiz-side-nav-list level-2">
                        @can('inventory_manage')
                            <li class="aiz-side-nav-item">
                                <a href="{{route('pos.inventory.home')}}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['pos.inventory.home']) }}">
                                    <span class="aiz-side-nav-text">
                                        {{ translate('Inventory  Manage') }}
                                    </span>
                                </a>
                            </li>
                        @endcan
                        @can('purchase_price_invoice')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('orders.purchase_order.home') }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes([ 'orders.purchase_order.home']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Purchase Order list') }}</span>
                                    </a>
                                </li>
                        @endcan
                    </ul>
                </li>
                @endcan
                <!-- Inventory End-->

                <!-- Product -->

                <!-- Quotation -->
                @can('pos_sale')
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                            <g id="Group_23" data-name="Group 23" transform="translate(-126 -590)">
                                <path id="Subtraction_31" data-name="Subtraction 31"
                                    d="M15,16H1a1,1,0,0,1-1-1V1A1,1,0,0,1,1,0H4.8V4.4a2,2,0,0,0,2,2H9.2a2,2,0,0,0,2-2V0H15a1,1,0,0,1,1,1V15A1,1,0,0,1,15,16Z"
                                    transform="translate(126 590)" fill="#707070" />
                                <path id="Rectangle_93" data-name="Rectangle 93"
                                    d="M0,0H4A0,0,0,0,1,4,0V4A1,1,0,0,1,3,5H1A1,1,0,0,1,0,4V0A0,0,0,0,1,0,0Z"
                                    transform="translate(132 590)" fill="#707070" />
                            </g>
                        </svg>
                        <span class="aiz-side-nav-text">{{ translate('POS') }}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <!--Submenu-->

                    <ul class="aiz-side-nav-list level-2">
                        @can('pos_sale')
                            <li class="aiz-side-nav-item">
                                <a href="{{route('pos.dashboard')}}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['pos.dashboard']) }}">
                                    <span class="aiz-side-nav-text">
                                        {{ translate('POS Sale') }}
                                    </span>
                                </a>
                            </li>
                        @endcan
                        <!-- @can('inventory_manage')
                            <li class="aiz-side-nav-item">
                                <a href="{{route('pos.inventory.home')}}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['pos.inventory.home']) }}">
                                    <span class="aiz-side-nav-text">
                                        {{ translate('Inventory  Manage') }}
                                    </span>
                                </a>
                            </li>
                        @endcan -->
                    </ul>
                </li>
                @endcan

                <!-- Report -->
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                            <g id="Group_23" data-name="Group 23" transform="translate(-126 -590)">
                                <path id="Subtraction_31" data-name="Subtraction 31"
                                    d="M15,16H1a1,1,0,0,1-1-1V1A1,1,0,0,1,1,0H4.8V4.4a2,2,0,0,0,2,2H9.2a2,2,0,0,0,2-2V0H15a1,1,0,0,1,1,1V15A1,1,0,0,1,15,16Z"
                                    transform="translate(126 590)" fill="#707070" />
                                <path id="Rectangle_93" data-name="Rectangle 93"
                                    d="M0,0H4A0,0,0,0,1,4,0V4A1,1,0,0,1,3,5H1A1,1,0,0,1,0,4V0A0,0,0,0,1,0,0Z"
                                    transform="translate(132 590)" fill="#707070" />
                            </g>
                        </svg>
                        <span class="aiz-side-nav-text">{{ translate('Report') }}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <!--Submenu-->

                    <ul class="aiz-side-nav-list level-2">
                        @can('summary_report')
                            <li class="aiz-side-nav-item">
                                <a href="{{route('summary.report')}}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['summary.report']) }}">
                                    <span class="aiz-side-nav-text">
                                        {{ translate('Summary Report') }}
                                    </span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                    <ul class="aiz-side-nav-list level-2">
                        @can('product_sale_report')
                            <li class="aiz-side-nav-item">
                                <a href="{{route('product_sale.report')}}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['product_sale.report']) }}">
                                    <span class="aiz-side-nav-text">
                                        {{ translate('product Sale Report') }}
                                    </span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                    <ul class="aiz-side-nav-list level-2">
                        @can('stock_report')
                            <li class="aiz-side-nav-item">
                                <a href="{{route('stock_report')}}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['stock_report']) }}">
                                    <span class="aiz-side-nav-text">
                                        {{ translate('Stock Report') }}
                                    </span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                    <ul class="aiz-side-nav-list level-2">
                        @can('user_history')
                            <li class="aiz-side-nav-item">
                                <a href="{{route('user_history.list')}}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['user_history.list']) }}">
                                    <span class="aiz-side-nav-text">
                                        {{ translate('User History') }}
                                    </span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>

                @can('accounts')
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                            <g id="Group_23" data-name="Group 23" transform="translate(-126 -590)">
                                <path id="Subtraction_31" data-name="Subtraction 31"
                                    d="M15,16H1a1,1,0,0,1-1-1V1A1,1,0,0,1,1,0H4.8V4.4a2,2,0,0,0,2,2H9.2a2,2,0,0,0,2-2V0H15a1,1,0,0,1,1,1V15A1,1,0,0,1,15,16Z"
                                    transform="translate(126 590)" fill="#707070" />
                                <path id="Rectangle_93" data-name="Rectangle 93"
                                    d="M0,0H4A0,0,0,0,1,4,0V4A1,1,0,0,1,3,5H1A1,1,0,0,1,0,4V0A0,0,0,0,1,0,0Z"
                                    transform="translate(132 590)" fill="#707070" />
                            </g>
                        </svg>
                        <span class="aiz-side-nav-text">{{ translate('Accounts') }}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <!--Submenu-->

                    <ul class="aiz-side-nav-list level-2">
                        @can('cash_report')
                            <li class="aiz-side-nav-item">
                                <a href="{{route('cash_report')}}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['cash_report']) }}">
                                    <span class="aiz-side-nav-text">
                                        {{ translate('Cash Report') }}
                                    </span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
                @endcan

                <!-- Product -->
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                            <g id="Group_23" data-name="Group 23" transform="translate(-126 -590)">
                                <path id="Subtraction_31" data-name="Subtraction 31"
                                    d="M15,16H1a1,1,0,0,1-1-1V1A1,1,0,0,1,1,0H4.8V4.4a2,2,0,0,0,2,2H9.2a2,2,0,0,0,2-2V0H15a1,1,0,0,1,1,1V15A1,1,0,0,1,15,16Z"
                                    transform="translate(126 590)" fill="#707070" />
                                <path id="Rectangle_93" data-name="Rectangle 93"
                                    d="M0,0H4A0,0,0,0,1,4,0V4A1,1,0,0,1,3,5H1A1,1,0,0,1,0,4V0A0,0,0,0,1,0,0Z"
                                    transform="translate(132 590)" fill="#707070" />
                            </g>
                        </svg>
                        <span class="aiz-side-nav-text">{{ translate('Product') }}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <!--Submenu-->
                    <ul class="aiz-side-nav-list level-2">
                        @can('show_products')
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('product.index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['product.index', 'product.create', 'product.edit', 'product_bulk_upload.index']) }}">
                                    <span class="aiz-side-nav-text">
                                        {{ addon_is_activated('multi_vendor') ? translate('Inhouse Products') : translate('Products') }}
                                    </span>
                                </a>
                            </li>
                        @endcan
                        @if (addon_is_activated('multi_vendor'))
                            @can('show_seller_products')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('admin.seller_products.index') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{ translate('Seller Products') }}</span>
                                        @if (env('DEMO_MODE') == 'On')
                                            <span class="badge badge-inline badge-danger">Addon</span>
                                        @endif
                                    </a>
                                </li>
                            @endcan
                        @endif
                    </ul>
                </li>

                <!-- Quotation -->
                @can('quotation_create')
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                            <g id="Group_23" data-name="Group 23" transform="translate(-126 -590)">
                                <path id="Subtraction_31" data-name="Subtraction 31"
                                    d="M15,16H1a1,1,0,0,1-1-1V1A1,1,0,0,1,1,0H4.8V4.4a2,2,0,0,0,2,2H9.2a2,2,0,0,0,2-2V0H15a1,1,0,0,1,1,1V15A1,1,0,0,1,15,16Z"
                                    transform="translate(126 590)" fill="#707070" />
                                <path id="Rectangle_93" data-name="Rectangle 93"
                                    d="M0,0H4A0,0,0,0,1,4,0V4A1,1,0,0,1,3,5H1A1,1,0,0,1,0,4V0A0,0,0,0,1,0,0Z"
                                    transform="translate(132 590)" fill="#707070" />
                            </g>
                        </svg>
                        <span class="aiz-side-nav-text">{{ translate('Quotation') }}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <!--Submenu-->

                    <ul class="aiz-side-nav-list level-2">
                        @can('quotation_create')
                            <li class="aiz-side-nav-item">
                                <a href="{{route('quotation.home')}}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['quotation.home']) }}">
                                    <span class="aiz-side-nav-text">
                                        {{ translate('Quotation Create') }}
                                    </span>
                                </a>
                            </li>
                        @endcan
                        @can('quotation_list')
                            <li class="aiz-side-nav-item">
                                <a href="{{route('quotation.list')}}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['quotation.home']) }}">
                                    <span class="aiz-side-nav-text">
                                        {{ addon_is_activated('multi_vendor') ? translate('Quotation List') : translate('Quotation List') }}
                                    </span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
                @endcan

                <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                <path id="Subtraction_32" data-name="Subtraction 32"
                                    d="M15,16H1a1,1,0,0,1-1-1V1A1,1,0,0,1,1,0H15a1,1,0,0,1,1,1V15A1,1,0,0,1,15,16ZM7,11a1,1,0,1,0,0,2h6a1,1,0,0,0,0-2ZM3,11a1,1,0,1,0,1,1A1,1,0,0,0,3,11ZM7,7A1,1,0,1,0,7,9h6a1,1,0,0,0,0-2ZM3,7A1,1,0,1,0,4,8,1,1,0,0,0,3,7ZM7,3A1,1,0,1,0,7,5h6a1,1,0,0,0,0-2ZM3,3A1,1,0,1,0,4,4,1,1,0,0,0,3,3Z"
                                    fill="#707070" />
                            </svg>
                            <span class="aiz-side-nav-text">{{ translate('Orders') }}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            @can('show_orders')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('orders.index') }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['orders.index', 'orders.show']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Inhouse Orders') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('show_seller_orders')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('admin.seller_orders.index') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{ translate('Seller Orders') }}</span>
                                        @if (env('DEMO_MODE') == 'On')
                                            <span class="badge badge-inline badge-danger">Addon</span>
                                        @endif
                                    </a>
                                </li>
                            @endcan
                            @can('return_product')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('orders.return_products') }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['orders.return_products', 'orders.show']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Return Products') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('return_product_list')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('orders.return_product_list') }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['orders.return_product_list', 'orders.show']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Return Products  List') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('order_cancel_list')
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('orders.cancel_list') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['orders.cancel_list', 'orders.show']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Order Cancel  List') }}</span>
                                </a>
                            </li>
                        @endcan
                        </ul>
                    </li>
                <!-- Employee Management -->
                @can('employee_management')
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                            <g id="Group_23" data-name="Group 23" transform="translate(-126 -590)">
                                <path id="Subtraction_31" data-name="Subtraction 31"
                                    d="M15,16H1a1,1,0,0,1-1-1V1A1,1,0,0,1,1,0H4.8V4.4a2,2,0,0,0,2,2H9.2a2,2,0,0,0,2-2V0H15a1,1,0,0,1,1,1V15A1,1,0,0,1,15,16Z"
                                    transform="translate(126 590)" fill="#707070" />
                                <path id="Rectangle_93" data-name="Rectangle 93"
                                    d="M0,0H4A0,0,0,0,1,4,0V4A1,1,0,0,1,3,5H1A1,1,0,0,1,0,4V0A0,0,0,0,1,0,0Z"
                                    transform="translate(132 590)" fill="#707070" />
                            </g>
                        </svg>
                        <span class="aiz-side-nav-text">{{ translate('Employee Management') }}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <!--Submenu-->

                    <ul class="aiz-side-nav-list level-2">
                        @can('attendance_report_create')
                            <li class="aiz-side-nav-item">
                                <a href="{{route('employee.attendance.report_generate_view')}}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['employee.attendance.report_generate_view']) }}">
                                    <span class="aiz-side-nav-text">
                                        {{ translate('Attendance Report Generate') }}
                                    </span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
                @endcan

                    <!--booking system-->
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 16 16" >
                                <path d="M8.627,7.885C8.499,8.388,7.873,8.101,8.13,8.177L4.12,7.143c-0.218-0.057-0.351-0.28-0.293-0.498c0.057-0.218,0.279-0.351,0.497-0.294l4.011,1.037C8.552,7.444,8.685,7.667,8.627,7.885 M8.334,10.123L4.323,9.086C4.105,9.031,3.883,9.162,3.826,9.38C3.769,9.598,3.901,9.82,4.12,9.877l4.01,1.037c-0.262-0.062,0.373,0.192,0.497-0.294C8.685,10.401,8.552,10.18,8.334,10.123 M7.131,12.507L4.323,11.78c-0.218-0.057-0.44,0.076-0.497,0.295c-0.057,0.218,0.075,0.439,0.293,0.495l2.809,0.726c-0.265-0.062,0.37,0.193,0.495-0.293C7.48,12.784,7.35,12.562,7.131,12.507M18.159,3.677v10.701c0,0.186-0.126,0.348-0.306,0.393l-7.755,1.948c-0.07,0.016-0.134,0.016-0.204,0l-7.748-1.948c-0.179-0.045-0.306-0.207-0.306-0.393V3.677c0-0.267,0.249-0.461,0.509-0.396l7.646,1.921l7.654-1.921C17.91,3.216,18.159,3.41,18.159,3.677 M9.589,5.939L2.656,4.203v9.857l6.933,1.737V5.939z M17.344,4.203l-6.939,1.736v9.859l6.939-1.737V4.203z M16.168,6.645c-0.058-0.218-0.279-0.351-0.498-0.294l-4.011,1.037c-0.218,0.057-0.351,0.28-0.293,0.498c0.128,0.503,0.755,0.216,0.498,0.292l4.009-1.034C16.092,7.085,16.225,6.863,16.168,6.645 M16.168,9.38c-0.058-0.218-0.279-0.349-0.498-0.294l-4.011,1.036c-0.218,0.057-0.351,0.279-0.293,0.498c0.124,0.486,0.759,0.232,0.498,0.294l4.009-1.037C16.092,9.82,16.225,9.598,16.168,9.38 M14.963,12.385c-0.055-0.219-0.276-0.35-0.495-0.294l-2.809,0.726c-0.218,0.056-0.351,0.279-0.293,0.496c0.127,0.506,0.755,0.218,0.498,0.293l2.807-0.723C14.89,12.825,15.021,12.603,14.963,12.385"  fill="#707070"></path>
                            </svg>
                            <span class="aiz-side-nav-text">{{ translate('Booking') }}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            @can('show_orders')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('booking') }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['orders.index', 'orders.show']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('New Book') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('show_seller_orders')
                                <li class="aiz-side-nav-item">
                                    <a href="{{route('booking_list')}}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{ translate('Book List') }}</span>
                                        @if (env('DEMO_MODE') == 'On')
                                            <span class="badge badge-inline badge-danger">Addon</span>
                                        @endif
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>


            </ul><!-- .aiz-side-nav -->
        </div><!-- .aiz-side-nav-wrap -->
    </div><!-- .aiz-sidebar -->
    <div class="aiz-sidebar-overlay"></div>
</div><!-- .aiz-sidebar -->
