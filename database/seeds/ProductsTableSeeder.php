<?php

use Illuminate\Database\Seeder;
use App\Model\Product;

class ProductsTableSeeder extends Seeder
{
    
        //preciso criar na model uma var fillabel
    public function run()
    {
        Product::create([
            'name' => 'Nome '. uniqid(date('YmdHis')),
            'description' => 'Descrição do produto '. uniqid(date('YmdHis')),
            'price' => 10.6,
            'image' => 'filme1.jpg',
        ]);
        Product::create([
            'name' => 'Nome '. uniqid(date('YmdHis')),
            'description' => 'Descrição do produto '. uniqid(date('YmdHis')),
            'price' => 11.2,
            'image' => 'filme2.jpg',
        ]);
        Product::create([
            'name' => 'Nome '. uniqid(date('YmdHis')),
            'description' => 'Descrição do produto '. uniqid(date('YmdHis')),
            'price' => 12.4,
            'image' => 'filme3.jpg',
        ]);
        Product::create([
            'name' => 'Nome '. uniqid(date('YmdHis')),
            'description' => 'Descrição do produto '. uniqid(date('YmdHis')),
            'price' => 10.6,
            'image' => 'filme1.jpg',
        ]);
        Product::create([
            'name' => 'Nome '. uniqid(date('YmdHis')),
            'description' => 'Descrição do produto '. uniqid(date('YmdHis')),
            'price' => 11.2,
            'image' => 'filme2.jpg',
        ]);
        Product::create([
            'name' => 'Nome '. uniqid(date('YmdHis')),
            'description' => 'Descrição do produto '. uniqid(date('YmdHis')),
            'price' => 12.4,
            'image' => 'filme3.jpg',
        ]);
        Product::create([
            'name' => 'Nome '. uniqid(date('YmdHis')),
            'description' => 'Descrição do produto '. uniqid(date('YmdHis')),
            'price' => 10.6,
            'image' => 'filme1.jpg',
        ]);
        Product::create([
            'name' => 'Nome '. uniqid(date('YmdHis')),
            'description' => 'Descrição do produto '. uniqid(date('YmdHis')),
            'price' => 11.2,
            'image' => 'filme2.jpg',
        ]);
        Product::create([
            'name' => 'Nome '. uniqid(date('YmdHis')),
            'description' => 'Descrição do produto '. uniqid(date('YmdHis')),
            'price' => 12.4,
            'image' => 'filme3.jpg',
        ]);
        Product::create([
            'name' => 'Nome '. uniqid(date('YmdHis')),
            'description' => 'Descrição do produto '. uniqid(date('YmdHis')),
            'price' => 10.6,
            'image' => 'filme1.jpg',
        ]);
        Product::create([
            'name' => 'Nome '. uniqid(date('YmdHis')),
            'description' => 'Descrição do produto '. uniqid(date('YmdHis')),
            'price' => 11.2,
            'image' => 'filme2.jpg',
        ]);
        Product::create([
            'name' => 'Nome '. uniqid(date('YmdHis')),
            'description' => 'Descrição do produto '. uniqid(date('YmdHis')),
            'price' => 12.4,
            'image' => 'filme3.jpg',
        ]);

    }
}
