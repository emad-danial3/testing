<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="" class="brand-link">
        <img src="{{url('dashboard')}}/dist/img/AdminLTELogonew.png" alt="AdminLTE Logo"
             class="brand-image  elevation-3"
        >
        <span class="brand-text font-weight-light">4UNettingHub</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->

                @if(Auth::guard('admin')->user()->id == 24 )
                    <li class="nav-item">
                        <a href="{{route('storeInvoicesPrint.index')}}" class="nav-link">
                            <i class="nav-icon fas fa-shopping-bag"></i>
                            <p>Store Print Report</p>
                        </a>
                    </li>
                @else

                    <li class="nav-item">
                        <a href="{{route('AcceptedVersion.index')}}" class="nav-link">
                            <i class="nav-icon fas fa-mobile"></i>
                            <p>App Setting</p>
                        </a>
                    </li>
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Users
                                <i class="fas fa-angle-right right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item ps-1">
                                <a href="{{route('users.index')}}" class="nav-link">
                                    <i class="far  fa-eye"></i>
                                    <p>All Users</p>
                                </a>
                            </li>
                            <li class="nav-item ps-1">
                                <a href="{{route('users.index',['has_credit_cart'=>0])}}" class="nav-link">
                                    <i class="far  fa-eye"></i>
                                    <p>Users without Cried card</p>
                                </a>
                            </li>
                            <li class="nav-item ps-1">
                                <a href="{{url('admin/usersnotinoracle')}}" class="nav-link">
                                    <i class="far  fa-eye"></i>
                                    <p>Users Not registerd in orcale</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @can('users')
                        <li class="nav-item">
                            <a href="{{route('accountTypes.index')}}" class="nav-link">
                                <i class="nav-icon fas fa-people-arrows"></i>
                                <p>Account types</p>
                            </a>
                        </li>
                    @endcan

                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-address-card"></i>
                            <p>
                                Country
                                <i class="fas fa-angle-right right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item ps-1">
                                <a href="{{route('countries.index')}}" class="nav-link">
                                    <i class="far  fa-eye"></i>
                                    <p>All Countries</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-city"></i>
                            <p>
                                City
                                <i class="fas fa-angle-right right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item ps-1">
                                <a href="{{route('cities.index')}}" class="nav-link">
                                    <i class="far  fa-eye"></i>
                                    <p>All Cities</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-address-book"></i>
                            <p>
                                Area
                                <i class="fas fa-angle-right right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item ps-1">
                                <a href="{{route('areas.index')}}" class="nav-link">
                                    <i class="far  fa-eye"></i>
                                    <p>All Areas</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-ad"></i>
                            <p>
                                Banners
                                <i class="fas fa-angle-right right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item ps-1">
                                <a href="{{route('banners.index')}}" class="nav-link">
                                    <i class="far  fa-eye"></i>
                                    <p>All Banners</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-address-card"></i>
                            <p>
                                Digital Brochure
                                <i class="fas fa-angle-right right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item ps-1">
                                <a href="{{route('digital_brochure.index')}}" class="nav-link">
                                    <i class="far  fa-eye"></i>
                                    <p>All Digital Brochures</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="{{route('companies.index')}}" class="nav-link">
                            <i class="nav-icon fas fa-building"></i>
                            <p>Companies</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{route('filters.index')}}" class="nav-link">
                            <i class="nav-icon fas fa-pager"></i>
                            <p>Filters</p>
                        </a>
                    </li>


                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-yen-sign"></i>
                            <p>
                                Product Category
                                <i class="fas fa-angle-right right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item ps-1">
                                <a href="{{route('productsCategories.mainCategory',['parent_id' => null])}}"
                                   class="nav-link">
                                    <i class="nav-icon fas fa-pager"></i>
                                    <p>Main Category</p>
                                </a>
                            </li>

                            <li class="nav-item ps-1">
                                <a href="{{route('productsCategories.subCategory')}}" class="nav-link">
                                    <i class="nav-icon fas fa-pager"></i>
                                    <p>Sub Category</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="{{route('welcome_program.index')}}" class="nav-link">
                            <i class="nav-icon fas fa-shopping-bag"></i>
                            <p>Welcome Program</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('reviews.index')}}" class="nav-link">
                            <i class="nav-icon fas fa-message"></i>
                            <p>Reviews</p>
                        </a>
                    </li>
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-cart-plus"></i>
                            <p>
                                Product
                                <i class="fas fa-angle-right right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item ps-1">
                                <a href="{{route('products.index')}}" class="nav-link">
                                    <i class="nav-icon fas fa-shopping-bag"></i>
                                    <p>Products</p>
                                </a>
                            </li>
                             <li class="nav-item ps-1">
                                <a href="{{route('getViewOracleProducts')}}" class="nav-link">
                                    <i class="nav-icon fas fa-shopping-bag"></i>
                                    <p>Oracle Products Availability</p>
                                </a>
                            </li>

                        </ul>
                    </li>

                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-atom"></i>
                            <p>
                                Orders
                                <i class="fas fa-angle-right right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item ps-1">
                                <a href="{{route('orderHeaders.index')}}" class="nav-link">
                                    <i class="nav-icon fas fa-shopping-bag"></i>
                                    <p>Orders</p>
                                </a>
                            </li>

                            <li class="nav-item ps-1">
                                <a href="{{route('orderHeaders.ExportShippingSheetSheet')}}" class="nav-link">
                                    <i class="nav-icon fas fa-shopping-bag"></i>
                                    <p>Orders Use Wallet</p>
                                </a>
                            </li>

                            <li class="nav-item ps-1">
                                @if(Auth::guard('admin')->user()->id == 1 || Auth::guard('admin')->user()->id == 17 )
                                    <a href="{{route('orderHeaders.ChangeStatusForOrder')}}" class="nav-link">
                                        <i class="nav-icon fas fa-shopping-bag"></i>
                                        <p>Change Order Status</p>
                                    </a>
                                @endif
                            </li>
                            <li class="nav-item ps-1">
                                <a href="{{route('orderHeaders.create')}}" class="nav-link">
                                    <i class="nav-icon fas fa-shopping-bag"></i>
                                    <p>Add Bazar Order</p>
                                </a>
                            </li>
                            <li class="nav-item ps-1">
                                <a href="{{route('orderHeaders.employeeorder')}}" class="nav-link">
                                    <i class="nav-icon fas fa-shopping-bag"></i>
                                    <p>Add Employee Order</p>
                                </a>
                            </li>

                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="{{route('oracleInvoices.index')}}" class="nav-link">
                            <i class="nav-icon fas fa-shopping-bag"></i>
                            <p>Oracle Invoice Report</p>
                        </a>
                    </li>
                     <li class="nav-item">
                        <a href="{{route('oracleInvoices.all_view')}}" class="nav-link">
                            <i class="nav-icon fas fa-shopping-bag"></i>
                            <p>Oracle Invoice enhanced</p>
                        </a>
                    </li>

                    @if(Auth::guard('admin')->user()->id == 1 )
                        <li class="nav-item">
                            <a href="{{route('storeInvoicesPrint.index')}}" class="nav-link">
                                <i class="nav-icon fas fa-shopping-bag"></i>
                                <p>Store Print Report</p>
                            </a>
                        </li>
                    @endif

                    <li class="nav-item">
                        <a href="{{route('wallets')}}" class="nav-link">
                            <i class="nav-icon fas fa-people-arrows"></i>
                            <p>Users Wallts</p>
                        </a>
                    </li>
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-angry"></i>
                            <p>
                                commissions
                                <i class="fas fa-angle-right right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item ps-1">
                                <a href="{{route('commissions.index')}}" class="nav-link">
                                    <i class="nav-icon fas fa-shopping-bag"></i>
                                    <p>Quarter & Annual</p>
                                </a>
                            </li>
                            <li class="nav-item ps-1">
                                <a href="{{route('monthcommissions.index')}}" class="nav-link">
                                    <i class="nav-icon fas fa-shopping-bag"></i>
                                    <p>Monthly Commission</p>
                                </a>
                            </li>
                            <li class="nav-item ps-1">
                                <a href="{{route('monthcommissions.financecommissionreport')}}" class="nav-link">
                                    <i class="nav-icon fas fa-shopping-bag"></i>
                                    <p>Finance Commission Report</p>
                                </a>
                            </li>
                            <li class="nav-item ps-1">
                                <a href="{{route('monthcommissions.finandetailscecommission')}}" class="nav-link">
                                    <i class="nav-icon fas fa-shopping-bag"></i>
                                    <p>Finance Commission Order Report</p>
                                </a>
                            </li>

                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('levels')}}" class="nav-link">
                            <i class="nav-icon fas fa-building"></i>
                            <p>User Levels</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('notifications.index')}}" class="nav-link">
                            <i class="nav-icon fas fa-comments"></i>
                            <p>Notifications</p>
                        </a>
                    </li>


                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-atom"></i>
                            <p>
                                Purchase Invoices
                                <i class="fas fa-angle-right right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item ps-1">
                                <a href="{{route('purchaseInvoices.index')}}" class="nav-link">
                                    <i class="nav-icon fas fa-shopping-bag"></i>
                                    <p>Purchase Invoices</p>
                                </a>
                            </li>


                            <li class="nav-item ps-1">

                                <a href="{{route('purchaseInvoices.create')}}" class="nav-link">
                                    <i class="nav-icon fas fa-shopping-bag"></i>
                                    <p>Add Invoice</p>
                                </a>

                            </li>
                            <li class="nav-item ps-1">
                                <a href="{{route('orderHeaders.reports')}}" class="nav-link">
                                    <i class="nav-icon fas fa-shopping-bag"></i>
                                    <p>Reports</p>
                                </a>
                            </li>

                        </ul>
                    </li>

                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-list"></i>
                            <p>
                                Reports
                                <i class="fas fa-angle-right right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item ps-1">
                                <a href="{{route('generalReports.report')}}" class="nav-link">
                                    <i class="nav-icon fas fa-shopping-bag"></i>
                                    <p>Sales Report Total</p>
                                </a>
                            </li>


                            <li class="nav-item ps-1">
                                <a href="{{route('generalReports.reports')}}" class="nav-link">
                                    <i class="nav-icon fas fa-shopping-bag"></i>
                                    <p>Report Product Sales</p>
                                </a>
                            </li>
                            <li class="nav-item ps-1">
                                <a href="{{route('generalReports.active_members')}}" class="nav-link">
                                    <i class="nav-icon fas fa-shopping-bag"></i>
                                    <p>Report Active Members</p>
                                </a>
                            </li>
                            <li class="nav-item ps-1">
                                <a href="{{route('generalReports.product_quantites_sold_view')}}" class="nav-link">
                                    <i class="nav-icon fas fa-shopping-bag"></i>
                                    <p>shortage products report</p>
                                </a>
                            </li>

                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="{{route('orderHeaders.getOracleNumberByOrderId')}}" class="nav-link">
                            <i class="nav-icon fas fa-shopping-bag"></i>
                            <p>Oracle Number</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('products.barcode')}}" class="nav-link">
                            <i class="nav-icon fas fa-list"></i>
                            <p>Product barcode</p>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
