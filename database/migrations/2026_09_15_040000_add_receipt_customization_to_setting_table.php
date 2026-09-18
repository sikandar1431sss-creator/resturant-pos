<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddReceiptCustomizationToSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('setting', function (Blueprint $table) {
            $table->boolean('show_sale_terms')->default(1)->after('terms_conditions');
            $table->boolean('show_purchase_terms')->default(1)->after('show_sale_terms');
            $table->string('purchase_terms_title')->nullable()->default('خریداری رسید / سٹاک انوائس')->after('show_purchase_terms');
            $table->text('purchase_terms_conditions')->nullable()->after('purchase_terms_title');
            $table->boolean('show_logo_receipt')->default(1)->after('purchase_terms_conditions');
        });

        // Set default values for existing row
        $defaultPurchaseTerms = "یہ پرچیز رسید سٹاک میں اندراج کی تصدیق ہےـ\nتمام آئٹمز کی مقدار اور قیمت چیک کر لی گئی ہےـ";
        DB::table('setting')->update([
            'show_sale_terms' => 1,
            'show_purchase_terms' => 1,
            'purchase_terms_title' => 'خریداری رسید / سٹاک انوائس',
            'purchase_terms_conditions' => $defaultPurchaseTerms,
            'show_logo_receipt' => 1
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('setting', function (Blueprint $table) {
            $table->dropColumn([
                'show_sale_terms',
                'show_purchase_terms',
                'purchase_terms_title',
                'purchase_terms_conditions',
                'show_logo_receipt'
            ]);
        });
    }
}
