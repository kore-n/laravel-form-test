<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->timestamp('published_at')->nullable()->after('content'); // 公開日時
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade')->after('published_at'); // 投稿者のID
        });
    }

    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('published_at');
            $table->dropForeign(['user_id']); // 外部キー制約を削除
            $table->dropColumn('user_id');
        });
    }
};