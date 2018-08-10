<?php

Route::group(['middleware' => 'web', 'prefix' => 'static', 'namespace' => 'Laragento\Cms\Http\Controllers'],
    function () {
        Route::get('/{pageSlug}', 'PageController@show');
    });

Route::group(['middleware' => 'web', 'prefix' => 'admin', 'namespace' => 'Laragento\Cms\Http\Controllers'],
    function () {
        Route::group(['middleware' => 'auth:admins', 'prefix' => 'cms', ], function()
        {
            Route::get('/', 'CmsController@index');

            Route::prefix('page')->group(function () {
                Route::get('/', 'PageController@index')->name('cms.page');
                Route::get('/new', 'PageController@create')->name('cms.page.new');
                Route::post('/new', 'PageController@store')->name('cms.page.store');
                Route::get('/{page}', 'PageController@edit')->name('cms.page.edit');
                Route::patch('/{page}', 'PageController@update')->name('cms.page.update');
                Route::delete('/{page}', 'PageController@destroy')->name('cms.page.destroy');

                Route::prefix('{page}/block')->group(function () {
                    Route::get('/', 'BlockController@index')->name('cms.block');
                    Route::get('/add', 'BlockController@create')->name('cms.block.add.redirect');
                    Route::post('/add', 'BlockController@create')->name('cms.block.add');
                    Route::post('/new', 'BlockController@store')->name('cms.block.store');
                    Route::get('/{block}', 'BlockController@edit')->name('cms.block.edit');
                    Route::patch('/{block}', 'BlockController@update')->name('cms.block.update');
                    Route::patch('/{block}/content', 'BlockController@updateContent')->name('cms.block.update.content');
                    Route::delete('/{block}', 'BlockController@destroy')->name('cms.block.destroy');
                });
            });
            Route::prefix('blocktype')->group(function () {
                Route::get('/', 'BlockTypecontroller@index')->name('cms.blocktype');
                Route::get('/new', 'BlockTypecontroller@create')->name('cms.blocktype.new');
                Route::post('/new', 'BlockTypecontroller@store')->name('cms.blocktype.store');
                Route::get('/{blockType}', 'BlockTypecontroller@edit')->name('cms.blocktype.edit');
                Route::post('/{blockType}/element', 'BlockTypecontroller@addElement')->name('cms.blocktype.element.add');
                Route::patch('/element/{element}', 'BlockTypecontroller@updateElement')->name('cms.blocktype.element.update');
                Route::delete('/element/{element}', 'BlockTypecontroller@removeElement')->name('cms.blocktype.element.remove');
                Route::patch('/{blockType}', 'BlockTypecontroller@update')->name('cms.blocktype.update');
                Route::delete('/{blockType}', 'BlockTypecontroller@destroy')->name('cms.blocktype.destroy');
            });
        });
    });


