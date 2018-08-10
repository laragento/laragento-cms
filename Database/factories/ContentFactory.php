<?php


use Faker\Generator as Faker;
use Laragento\Cms\Models\BlockType;
use Laragento\Cms\Models\Element\Element;
use Laragento\Cms\Models\Element\ElementField;
use Laragento\Cms\Models\Element\ElementType;
use Laragento\Cms\Models\Page;
use Laragento\Cms\Models\Block;

/** @var \Illuminate\Database\Eloquent\Factory $factory **/

$factory->define(BlockType::class, function (Faker $faker) {
    return [
        'title' => $faker->word,
    ];
});

/*$factory->define(ElementType::class, function (Faker $faker) {
    return [
        'title' => $faker->word,
    ];
});*/

$factory->define(Block::class, function (Faker $faker) {
    return [
        'title' => $faker->word,
        'page_id' => factory(Page::class)->create()->id
    ];
});

$factory->define(Page::class, function (Faker $faker) {

    return [
        'title' => $faker->word,
        'slug' => str_slug($faker->word),
        'user_id' => 1,
    ];
});

$factory->define(Element::class, function (Faker $faker) {

    return [
        'title' => $faker->word,
        'sort_nr' => 1,
        'block_type_id' => factory(BlockType::class)->create()->id,
        'element_type_id' => factory(ElementType::class)->create()->id
    ];
});

$factory->define(ElementType::class, function (Faker $faker) {

    return [
        'title' => $faker->word,
    ];
});

$factory->define(ElementField::class, function (Faker $faker) {

    return [
        'title' => $faker->word,
        'element_type_id' => factory(ElementType::class)->create()->id
    ];
});
