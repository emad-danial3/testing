<?php

namespace App\Providers;

use App\Http\Repositories\AcceptedVersionRepository;
use App\Http\Repositories\AccountCommissionRepository;
use App\Http\Repositories\AccountLevelRepository;
use App\Http\Repositories\AccountTypeRepository;
use App\Http\Repositories\AreaRepository;
use App\Http\Repositories\BannerRepository;
use App\Http\Repositories\CartRepository;
use App\Http\Repositories\CategoriesRepository;
use App\Http\Repositories\CityRepository;
use App\Http\Repositories\CommissionRepository;
use App\Http\Repositories\CompanyRepository;
use App\Http\Repositories\CountryRepository;
use App\Http\Repositories\DigitalBrochureRepository;
use App\Http\Repositories\ForgotPasswordRepository;
use App\Http\Repositories\GeneralRepository;
use App\Http\Repositories\IAcceptedVersionRepository;
use App\Http\Repositories\IAccountCommissionRepository;
use App\Http\Repositories\IAccountLevelRepository;
use App\Http\Repositories\IAccountTypeRepository;
use App\Http\Repositories\IAreaRepository;
use App\Http\Repositories\IBannerRepositories;
use App\Http\Repositories\ICartRepository;
use App\Http\Repositories\ICategoriesRepository;
use App\Http\Repositories\ICityRepository;
use App\Http\Repositories\ICommissionRepository;
use App\Http\Repositories\ICompanyRepository;
use App\Http\Repositories\ICountryRepository;
use App\Http\Repositories\IDigitalBrochureRepository;
use App\Http\Repositories\IForgotPasswordRepository;
use App\Http\Repositories\IGeneralRepository;
use App\Http\Repositories\INettingJoinRepository;
use App\Http\Repositories\INotificationRepository;
use App\Http\Repositories\IOracleProductRepository;
use App\Http\Repositories\IOracleInvoiceRepository;
use App\Http\Repositories\OracleInvoiceRepository;
use App\Http\Repositories\IOrderLinesRepository;
use App\Http\Repositories\IOrderRepository;
use App\Http\Repositories\IPaymentLogRepository;
use App\Http\Repositories\IProductCategoriesRepository;
use App\Http\Repositories\IProductRepository;
use App\Http\Repositories\IRegisterLinkRepository;
use App\Http\Repositories\IRegisterLinksRepository;
use App\Http\Repositories\ISharePageCategoryRepository;
use App\Http\Repositories\ISharePageRepository;
use App\Http\Repositories\ISpinnerCategoriesRepository;
use App\Http\Repositories\ISpinnerRepository;
use App\Http\Repositories\IStaticPageRepository;
use App\Http\Repositories\IFilterRepository;
use App\Http\Repositories\IWelcomeProgramRepository;
use App\Http\Repositories\IReviewsRepository;
use App\Http\Repositories\IUserCommissionRepositories;
use App\Http\Repositories\IUserNotificationRepository;
use App\Http\Repositories\IUserRepository;
use App\Http\Repositories\IUserWalletRepository;
use App\Http\Repositories\IWalletEvaluationRepository;
use App\Http\Repositories\NettingJoinRepository;
use App\Http\Repositories\NotificationRepository;
use App\Http\Repositories\OracleProductRepository;
use App\Http\Repositories\OrderLinesRepository;
use App\Http\Repositories\OrderRepository;
use App\Http\Repositories\PaymentLogRepository;
use App\Http\Repositories\ProductCategoriesRepository;
use App\Http\Repositories\ProductRepository;
use App\Http\Repositories\RegisterLinkRepository;
use App\Http\Repositories\RegisterLinksRepository;
use App\Http\Repositories\PurchaseInvoiceLinesRepository;
use App\Http\Repositories\PurchaseInvoiceRepository;
use App\Http\Repositories\IPurchaseInvoiceRepository;
use App\Http\Repositories\IPurchaseInvoicesLineRepository;
use App\Http\Repositories\SharePageCategoryRepository;
use App\Http\Repositories\SharePageRepository;
use App\Http\Repositories\SpinnerCategoriesRepository;
use App\Http\Repositories\SpinnerRepository;
use App\Http\Repositories\StaticPageRepository;
use App\Http\Repositories\FilterRepository;
use App\Http\Repositories\WelcomeProgramRepository;
use App\Http\Repositories\ReviewsRepository;
use App\Http\Repositories\UserCommissionRepositories;
use App\Http\Repositories\UserNotificationRepository;
use App\Http\Repositories\UserRepository;
use App\Http\Repositories\UserWalletRepository;
use App\Http\Repositories\WalletEvaluationRepository;
use App\Http\Services\OrderTypesCommissions\AbstractCommission;
use App\Http\Services\OrderTypesCommissions\CreateFreeUserCommission;
use App\Http\Services\OrderTypesCommissions\CreateSingleUserCommission;
use App\Http\Services\OrderTypesCommissions\CreateUserCommission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCreateUser();
        $this->registerCreateFreeUser();
        $this->registerSingleUser();
        $this->app->bind(CartRepository::class, ICartRepository::class);
        $this->app->bind(AccountCommissionRepository::class, IAccountCommissionRepository::class);
        $this->app->bind(AccountLevelRepository::class, IAccountLevelRepository::class);
        $this->app->bind(AccountTypeRepository::class, IAccountTypeRepository::class);
        $this->app->bind(CommissionRepository::class, ICommissionRepository::class);
        $this->app->bind(ForgotPasswordRepository::class, IForgotPasswordRepository::class);
        $this->app->bind(OrderLinesRepository::class, IOrderLinesRepository::class);
        $this->app->bind(OrderRepository::class, IOrderRepository::class);
        $this->app->bind(PurchaseInvoiceRepository::class, IPurchaseInvoiceRepository::class);
        $this->app->bind(PurchaseInvoiceLinesRepository::class, IPurchaseInvoicesLineRepository::class);
        $this->app->bind(PaymentLogRepository::class, IPaymentLogRepository::class);
        $this->app->bind(ProductRepository::class, IProductRepository::class);
        $this->app->bind(RegisterLinkRepository::class, IRegisterLinkRepository::class);
        $this->app->bind(SpinnerCategoriesRepository::class, ISpinnerCategoriesRepository::class);
        $this->app->bind(SpinnerRepository::class, ISpinnerRepository::class);
        $this->app->bind(UserCommissionRepositories::class, IUserCommissionRepositories::class);
        $this->app->bind(UserRepository::class, IUserRepository::class);
        $this->app->bind(UserWalletRepository::class, IUserWalletRepository::class);
        $this->app->bind(WalletEvaluationRepository::class, IWalletEvaluationRepository::class);
        $this->app->bind(GeneralRepository::class, IGeneralRepository::class);
        $this->app->bind(UserNotificationRepository::class, IUserNotificationRepository::class);
        $this->app->bind(RegisterLinksRepository::class, IRegisterLinksRepository::class);
        $this->app->bind(ProductCategoriesRepository::class, IProductCategoriesRepository::class);
        $this->app->bind(CategoriesRepository::class, ICategoriesRepository::class);

        $this->app->bind(AcceptedVersionRepository::class, IAcceptedVersionRepository::class);

        $this->app->bind(AreaRepository::class, IAreaRepository::class);
        $this->app->bind(CountryRepository::class, ICountryRepository::class);
        $this->app->bind(DigitalBrochureRepository::class, IDigitalBrochureRepository::class);
        $this->app->bind(CityRepository::class, ICityRepository::class);
        $this->app->bind(BannerRepository::class, IBannerRepositories::class);
        $this->app->bind(NettingJoinRepository::class, INettingJoinRepository::class);
        $this->app->bind(SharePageCategoryRepository::class, ISharePageCategoryRepository::class);
        $this->app->bind(SharePageRepository::class, ISharePageRepository::class);
        $this->app->bind(CompanyRepository::class, ICompanyRepository::class);
        $this->app->bind(StaticPageRepository::class, IStaticPageRepository::class);
        $this->app->bind(FilterRepository::class, IFilterRepository::class);
        $this->app->bind(ReviewsRepository::class, IReviewsRepository::class);
        $this->app->bind(WelcomeProgramRepository::class, IWelcomeProgramRepository::class);
        $this->app->bind(NotificationRepository::class, INotificationRepository::class);
        $this->app->bind(OracleProductRepository::class, IOracleProductRepository::class);
        $this->app->bind(OracleInvoiceRepository::class, IOracleInvoiceRepository::class);
    }
    public function registerCreateUser() {
        return $this->app->bind(AbstractCommission::class, CreateUserCommission::class);
    }

    public function registerCreateFreeUser() {
        return $this->app->bind(AbstractCommission::class, CreateFreeUserCommission::class);
    }

    public function registerSingleUser() {
        return $this->app->bind(AbstractCommission::class, CreateSingleUserCommission::class);
    }
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
       /* DB::listen(function ($query) {
            logger(Str::replaceArray('?',$query->bindings,$query->sql));
        });*/
         if (env('APP_ENV') !== 'local') { //so you can work on it locally
            URL::forceScheme('https');
        }
        Schema::defaultStringLength(191);
    }
}
