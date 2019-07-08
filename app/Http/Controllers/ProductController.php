<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Exceptions\InvalidRequestException;

class ProductController extends Controller
{
    //
    public function index(Request $request)
    {
       $builder = Product::query()->where('on_sale', true);
        // 判断是否有提交 search 参数，如果有就赋值给 $search 变量
        // search 参数用来模糊搜索商品
        if ($search = $request->input('search', '')) {
            $like = '%'.$search.'%';
            // 模糊搜索商品标题、商品详情、SKU 标题、SKU描述
            $builder->where(function ($query) use ($like) {
                $query->where('title', 'like', $like)
                    ->orWhere('description', 'like', $like)
                    ->orWhereHas('skus', function ($query) use ($like) {
                        $query->where('title', 'like', $like)
                            ->orWhere('description', 'like', $like);
                    });
            });
        }

        // 是否有提交 order 参数，如果有就赋值给 $order 变量
        // order 参数用来控制商品的排序规则
        if ($order = $request->input('order', '')) {
            // 是否是以 _asc 或者 _desc 结尾
            if (preg_match('/^(.+)_(asc|desc)$/', $order, $m)) {
                // 如果字符串的开头是这 3 个字符串之一，说明是一个合法的排序值
                if (in_array($m[1], ['price', 'sold_count', 'rating'])) {
                    // 根据传入的排序值来构造排序参数
                    $builder->orderBy($m[1], $m[2]);
                }
            }
        }

        $products = $builder->paginate(16);
           $filters =  [
                'search' => $search,
                'order'  => $order,
           ];
        // dump($filters);
        return view('product.index',
        [
            'products' => $products,
            'filters'  => $filters,
        ]);

    }


    public function show(Product $product, Request $request)
    {
        // 判断商品是否已经上架，如果没有上架则抛出异\常。
        if (!$product->on_sale) {
            throw new InvalidRequestException('商品未上架');
        }
        $user = $request->user();
        $product_status = false;
       if($user->favoriteProducts->find($product)){
            $product_status = true;
       }
        return view('product.show', ['product' => $product, 'product_status'=> $product_status]);
    }

    public function favor(Product $product, Request $request)
    {


        $user = $request->user();
        if ($user->favoriteProducts()->find($request->product_id)) {
            return response()->json(["status" => 1]);
        }

        $user->favoriteProducts()->attach(Product::find($request->product_id));

        return response()->json(["status" => 2]);

    }

    public function disfavor(Product $product, Request $request)
    {
        $user = $request->user();

        try {
            //code...
            $user->favoriteProducts()->detach($request->product_id);
           return response()->json(["status" => 2]);


        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(["status" => 1]);

        }



    }

     public function favorites(Request $request)
    {
        $products = $request->user()->favoriteProducts()->paginate(16);

        return view('product.favorites', ['products' => $products]);
    }
}
