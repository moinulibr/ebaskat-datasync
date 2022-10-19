<?php

namespace App\Console\Commands;

use App\Console\Commands\Tables\AdminLogHistoriesChanges;
use App\Console\Commands\Tables\AdminsTableChanges;
use App\Console\Commands\Tables\AdminUserConversationsChanges;
use App\Console\Commands\Tables\AdminUserMessagesTable;
use App\Console\Commands\Tables\AttributeOptionsTable;
use App\Console\Commands\Tables\AttributeTable;
use App\Console\Commands\Tables\BannersTableChanges;
use App\Console\Commands\Tables\BlogsTableChanges;
use App\Console\Commands\Tables\BrandMerchantTableChanges;
use App\Console\Commands\Tables\BrandsTableChanges;
use App\Console\Commands\Tables\CategoriesTableChanges;
use App\Console\Commands\Tables\ChildcategoriesTableChanges;
use App\Console\Commands\Tables\CommentsTableChanges;
use App\Console\Commands\Tables\ContactUsTableChanges;
use App\Console\Commands\Tables\ConversationsTableChanges;
use App\Console\Commands\Tables\CountersTableChanges;
use App\Console\Commands\Tables\CountriesTable;
use App\Console\Commands\Tables\CouponsTableChanges;
use App\Console\Commands\Tables\CurrenciesTableChanges;
use App\Console\Commands\Tables\EmailTemplatesTableChanges;
use App\Console\Commands\Tables\FavoriteSellersTable;
use App\Console\Commands\Tables\GalleriesTableChanges;
use App\Console\Commands\Tables\GeneralsettingsTableChages;
use App\Console\Commands\Tables\GlobalVatTaxesTableChanges;
use App\Console\Commands\Tables\IndexingColumns;
use App\Console\Commands\Tables\LanguagesTableChanges;
use App\Console\Commands\Tables\MessagesTableChanges;
use App\Console\Commands\Tables\NotificationsTableChanges;
use App\Console\Commands\Tables\OrderPackagesTableChanges;
use App\Console\Commands\Tables\OrderProductsTable;
use App\Console\Commands\Tables\OrdersTableChanges;
use App\Console\Commands\Tables\OrderTracksTable;
use App\Console\Commands\Tables\PackagesTableChanges;
use App\Console\Commands\Tables\PermissionsTable;
use App\Console\Commands\Tables\PickupsTableChanges;
use App\Console\Commands\Tables\ProductsTableChanges;
use App\Console\Commands\Tables\ProductVariantsTable;
use App\Console\Commands\Tables\RatingsTable;
use App\Console\Commands\Tables\RepliesTable;
use App\Console\Commands\Tables\ReportsTable;
use App\Console\Commands\Tables\ReviewsTable;
use App\Console\Commands\Tables\RolesTable;
use App\Console\Commands\Tables\ServicesTableChanges;
use App\Console\Commands\Tables\ShippingsTableChanges;
use App\Console\Commands\Tables\SlidersTableChanges;
use App\Console\Commands\Tables\SubscriptionsTableChanges;
use App\Console\Commands\Tables\SubCategoryTableChanges;
use App\Console\Commands\Tables\SubscribersTable;
use App\Console\Commands\Tables\UserNotificationsTable;
use App\Console\Commands\Tables\UsersTableChanges;
use App\Console\Commands\Tables\UserSubscriptionsTableChanges;
use App\Console\Commands\Tables\VendorInformationTableChanges;
use App\Console\Commands\Tables\WishlistsTable;
use App\Console\Commands\Tables\WithdrawsTable;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SyncTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:table {param?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync table structure command.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $param = $this->argument('param');

        // first call artisan
        Artisan::call('migrate');

        if($param == "varchar_apply")
        {
            new TextToVarchar();
            return;
        }

        /* ========================== Roles ========================= */
        // Table : roles
        // rename column : section to permissions
        
        if (!Schema::hasColumn('roles', 'permissions'))
        {
            echo "\n<br/><h3> Alter column section to permissions in role</h3>";
            DB::statement('ALTER TABLE `roles` CHANGE COLUMN `section` `permissions` TEXT NULL;');
        }
        
        /* ======================= Permission ======================= */
        // Table : permissions
        // adding column : is_special
        
        if (!Schema::hasColumn('permissions', 'is_special'))
        {
            echo "\n<br/><h3> Adding column is_special in permissions </h3>";
            
            Schema::table('permissions', function (Blueprint $table) {
                $table->boolean('is_special')->default(false)->nullable()->after('name');
            });

        }

        new AdminLogHistoriesChanges();
        new AdminsTableChanges();
        new AdminUserConversationsChanges();
        new AdminUserMessagesTable();
        new AttributeOptionsTable();
        new AttributeTable();
        new BannersTableChanges();
        new BlogsTableChanges();
        new BrandMerchantTableChanges();
        new BrandsTableChanges();
        new CategoriesTableChanges();
        new ChildcategoriesTableChanges();
        new CommentsTableChanges();
        new ContactUsTableChanges();
        new ConversationsTableChanges();
        new CountersTableChanges();
        new CountriesTable();
        new CouponsTableChanges();
        new CurrenciesTableChanges();
        new EmailTemplatesTableChanges();
        new FavoriteSellersTable();
        new GalleriesTableChanges();
        new GeneralsettingsTableChages();
        new GlobalVatTaxesTableChanges();
        new LanguagesTableChanges();
        new MessagesTableChanges();
        new NotificationsTableChanges();
        new OrderPackagesTableChanges();
        new OrderProductsTable();
        new OrdersTableChanges();
        new OrderTracksTable();
        new PackagesTableChanges();
        new PermissionsTable();
        new PickupsTableChanges();
        new ProductsTableChanges();
        new ProductVariantsTable();
        new RatingsTable();
        new RepliesTable();
        new ReportsTable();
        new ReviewsTable();
        new RolesTable();
        new ServicesTableChanges();
        new ShippingsTableChanges();
        new SlidersTableChanges();
        new SubCategoryTableChanges();
        new SubscribersTable();
        new SubscriptionsTableChanges();
        new UserNotificationsTable();
        new UsersTableChanges();
        new UserSubscriptionsTableChanges();
        new VendorInformationTableChanges();
        new WishlistsTable();
        new WithdrawsTable();

    }
}
