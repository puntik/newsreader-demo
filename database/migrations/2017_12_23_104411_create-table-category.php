<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCategory extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('category', function (Blueprint $table) {

			$table->engine    = 'InnoDB';
			$table->charset   = 'utf8';
			$table->collation = 'utf8_czech_ci';

			$table->increments('id');
			$table->string('title', 255);
			$table->string('slug', 255);
			$table->boolean('active')->default(true);
			$table->timestamps();
			$table->softDeletes();

			$table->index(['slug'], 'idx_category_slug');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('category');
	}
}
