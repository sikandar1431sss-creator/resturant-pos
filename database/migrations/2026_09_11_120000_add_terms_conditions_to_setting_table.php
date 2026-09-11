<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddTermsConditionsToSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('setting', function (Blueprint $table) {
            $table->string('terms_title')->nullable()->default('شرائط و ضوابط')->after('mata_uang');
            $table->text('terms_conditions')->nullable()->after('terms_title');
        });

        // Set default Urdu terms
        $defaultTerms = "خریدہ ہوا مال واپس یا تبدیل نہیں ہوگاـ\nوارنٹی صرف کمپنی / مینوفیکچرر کی شرائط کے مطابق ہوگیـ\nبل کے بغیر کسی قسم کی شکایت قبول نہیں کی جائے گیـ\nادھار رقم مقررہ تاریخ تک ادا کرنا ضروری ہےـ";
        DB::table('setting')->update([
            'terms_title' => 'شرائط و ضوابط',
            'terms_conditions' => $defaultTerms
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
            $table->dropColumn(['terms_title', 'terms_conditions']);
        });
    }
}
