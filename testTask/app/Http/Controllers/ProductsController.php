<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{
    public const CACHETIME = 3600;

    public function index()
    {
        $products = DB::table('products')
            ->join('order_products', 'products.id', '=', 'order_products.product_id', 'left')
            ->join('orders', 'order_products.order_id', '=', 'orders.id', 'left')
            ->join('collections', 'products.collection_id', '=', 'collections.id', 'left')
            ->select('products.name', 'collections.name as collection', 'products.cost_of_sale',
                DB::raw('count(orders.id) count, sum(order_products.price) total_income'))
            ->orderBy('count', 'desc')
            ->orderBy('total_income', 'desc')
            ->groupBy('products.id')
            ->get();
        $productsGrossMargin = array();
        foreach ($products as $key => $product) {
            if ($product->total_income != 0) {
                $productsGrossMargin[$key]['gross_margin'] =
                    ($product->total_income - $product->cost_of_sale * $product->count) / $product->total_income * 100;
            } else {
                $productsGrossMargin[$key]['gross_margin'] = 0;
            }
        }
        Cache::put('products', $products, self::CACHETIME);
        Cache::put('productsGrossMargin', $productsGrossMargin, self::CACHETIME);
        return view('products', compact(array('products', 'productsGrossMargin')));
    }
}
