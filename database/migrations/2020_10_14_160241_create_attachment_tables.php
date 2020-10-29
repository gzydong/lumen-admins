<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttachmentTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attachment', function (Blueprint $table) {
            $table->id()->comment('资源ID');
            $table->unsignedInteger('parent_id')->comment('父节点');
            $table->enum('drive', ['local', 'oos', 'cos', 'qi_niu'])->comment('文件保存驱动[local:本地存储; oos:阿里云oos存储; cos:腾讯云cos存储; qi_niu:七牛云存储]');
            $table->tinyInteger('type')->default(0)->comment('资源类型[0:目录;1:图片;2:视频;3:附件]');
            $table->string('name', 100)->default('')->comment('目录名/文件上传原名');
            $table->string('hash', 100)->default('')->comment('哈希值');
            $table->string('ext', 100)->default('')->comment('文件后缀');
            $table->string('mime', 100)->default('')->comment('mime');
            $table->string('size', 100)->default('')->comment('文件大小');
            $table->string('path', 100)->default('')->comment('路径');
            $table->string('url', 100)->default('')->comment('文件地址');
            $table->string('cover', 100)->default('')->comment('视频文件封面图');
            $table->tinyInteger('is_catalogue')->default(0)->comment('资源类型[0:目录;1:文件]');
            $table->integer('created_at', 11)->default(0)->comment('创建时间');
            $table->integer('update_at', 11)->default(0)->comment('更新时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attachment_tables');
    }
}
