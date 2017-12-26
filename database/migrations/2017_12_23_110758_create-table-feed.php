<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableFeed extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('feed', function (Blueprint $table) {

			$table->engine    = 'InnoDB';
			$table->charset   = 'utf8';
			$table->collation = 'utf8_czech_ci';

			$table->increments('id');
			$table->string('title', 255);
			$table->text('description')->nullable();
			$table->boolean('active')->default(true);
			$table->string('link', 255)->nullable();
			$table->dateTime('published_at')->nullable();
			$table->integer('source_id')->unsigned();

			$table->timestamps();
			$table->softDeletes();

			$table->index(['link'], 'idx_feed_link');

			$table->foreign('source_id', 'fx_feed_source_id_source_id')
				  ->references('id')
				  ->on('source');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('feed');
	}
}
