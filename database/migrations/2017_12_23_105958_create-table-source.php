<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSource extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('source', function (Blueprint $table) {

			$table->engine    = 'InnoDB';
			$table->charset   = 'utf8';
			$table->collation = 'utf8_czech_ci';

			$table->increments('id');
			$table->string('title', 255);
			$table->text('description')->nullable();
			$table->boolean('active')->default(true);
			$table->string('url', 255)->nullable();
			$table->string('language', 8);
			$table->integer('frequency')->default(1200);
			$table->integer('category_id')->unsigned();
			$table->timestamps();
			$table->softDeletes();

			$table->foreign('category_id', 'fx_source_category_id_category_id')
				  ->references('id')
				  ->on('category');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('source');
	}
}
