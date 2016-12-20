<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/
// $factory->define(App\Models\OrderModel::class, function (Faker\Generator $faker) {
//     return [
//         'channel_id' => $faker->channel_id,
//         'channel_account_id' => $faker->channel_account_id,
//         'order_num' => $faker->order_num,
//         'channel_ordernum' => $faker->channel_ordernum,
//         'channel_listnum' => $faker->channel_listnum,
//         'by_id' => $faker->by_id,
//         'email' => $faker->email,
//         'amount' => $faker->amount,
//         'customer_service' => $faker->customer_service,
//         'operator' => $faker->operator,
//         'currency' => $faker->currency,
//         'rate' => $faker->rate,
//         'shipping' => $faker->shipping,
//         'shipping_firstname' => $faker->shipping_firstname,
//         'shipping_lastname' => $faker->shipping_lastname,
//         'shipping_address' => $faker->shipping_address,
//         'shipping_address1' => $faker->shipping_address1,
//         'shipping_city' => $faker->shipping_city,
//         'shipping_state' => $faker->shipping_state,
//         'shipping_country' => $faker->shipping_country,
//         'shipping_zipcode' => $faker->shipping_zipcode,
//         'shipping_phone' => $faker->shipping_phone,
//     ];
// });
$factory->define(App\Models\UserModel::class, 'admin', function ($faker) {
   return [
       'name' => $faker->name,
       'email' => $faker->email,
       'password' => bcrypt(str_random(10)),
       'remember_token' => str_random(10),
   ];
});
