@extends('layouts.app')
@section('title', $product->title)

@section('content')
<div class="row">
<div class="col-lg-10 offset-lg-1">
<div class="card">
  <div class="card-body product-info">
    <div class="row">
      <div class="col-5">
        <img class="cover" src="{{ $product->image_url }}" alt="">
      </div>
      <div class="col-7">
        <div class="title">{{ $product->title }}</div>
        <div class="price"><label>价格</label><em>￥</em><span>{{ $product->price }}</span></div>
        <div class="sales_and_reviews">
          <div class="sold_count">累计销量 <span class="count">{{ $product->sold_count }}</span></div>
          <div class="review_count">累计评价 <span class="count">{{ $product->review_count }}</span></div>
          <div class="rating" title="评分 {{ $product->rating }}">评分 <span class="count">{{ str_repeat('★', floor($product->rating)) }}{{ str_repeat('☆', 5 - floor($product->rating)) }}</span></div>
        </div>
        <div class="skus">
          <label>选择</label>
          <div class="btn-group btn-group-toggle" data-toggle="buttons">
            @foreach($product->skus as $sku)
              <label
                class="btn sku-btn"
                data-price="{{ $sku->price }}"
                data-stock="{{ $sku->stock }}"
                data-toggle="tooltip"
                title="{{ $sku->description }}"
                data-placement="bottom">
                <input type="radio" name="skus" autocomplete="off" value="{{ $sku->id }}"> {{ $sku->title }}
            </label>
            @endforeach
          </div>
        </div>
        <div class="cart_amount"><label>数量</label><input type="text" class="form-control form-control-sm" value="1"><span>件</span><span class="stock"></span></div>
        <div class="buttons">
            @if($product_status)
            <button class="btn btn-success btn-unfavor">取消收藏</button>
                        @else
            <button class="btn btn-success btn-favor">❤ 收藏</button>
            @endif

          <button class="btn btn-primary btn-add-to-cart">加入购物车</button>
        </div>
      </div>
    </div>
    <div class="product-detail">
      <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" href="#product-detail-tab" aria-controls="product-detail-tab" role="tab" data-toggle="tab" aria-selected="true">商品详情</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#product-reviews-tab" aria-controls="product-reviews-tab" role="tab" data-toggle="tab" aria-selected="false">用户评价</a>
        </li>
      </ul>
      <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="product-detail-tab">
          {!! $product->description !!}
        </div>
        <div role="tabpanel" class="tab-pane" id="product-reviews-tab">
        </div>
      </div>
    </div>
  </div>
</div>
</div>
</div>
@endsection

@section('js')
<script>
  $(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip({trigger: 'hover'});
    $('.sku-btn').click(function () {
      $('.product-info .price span').text($(this).data('price'));
      $('.product-info .stock').text('库存：' + $(this).data('stock') + '件');
    });

    //添加收藏
    $('.btn-favor').on('click' , function(){
      var url  = '{{ route("products.favor") }}';
      var id  = '{{ $product->id }}';
      var _token ='{{ csrf_token() }}';

       layui.use(['layer', 'form'], function () {
          var layer = layui.layer;
          loading = layer.load(3);

          $.ajax({
              url: url,
              type: 'POST',
              dataType: 'JSON',
              data: {
                  'product_id': id,
                  '_token': _token
              },
              success: function (data) {
                  // //撤回加载层
                   layer.close(loading);
                   console.log(data);
                  if (data.status == 2) {
                      layer.msg('收藏成功', function () {
                          //操作成功刷新页面
                          location.reload();
                      });
                  } else {
                      layer.msg('收藏失败', {icon: 5});
                  }
              },
              error: function (data) {
                console.log(data);
                   layer.msg('您没有登录或者用户没有激活邮箱', {icon: 5});
                   layer.close(loading);
              },
          })

        })
    })
    //quxiao shoucan
    $('.btn-unfavor').on('click' , function(){
      var url  = '{{ route("products.disfavor") }}';
      var id  = '{{ $product->id }}';
      var _token ='{{ csrf_token() }}';

       layui.use(['layer', 'form'], function () {
          var layer = layui.layer;
          loading = layer.load(3);

          $.ajax({
              url: url,
              type: 'POST',
              dataType: 'JSON',
              data: {
                  'product_id': id,
                  '_token': _token
              },
              success: function (data) {
                  // //撤回加载层
                   layer.close(loading);
                   console.log(data);
                  if (data.status == 2) {
                      layer.msg('取消成功', function () {
                          //操作成功刷新页面
                          location.reload();
                      });
                  } else {
                      layer.msg('取消失败', {icon: 5});
                  }
              },
              error: function (data) {
                console.log(data);
                   layer.msg('您没有登录或者用户没有激活邮箱', {icon: 5});
                   layer.close(loading);
              },
          })

        })
    });
    //加入购物车
    $('.btn-add-to-cart').on('click' , function(){
      var url  = '{{ route("cart.add") }}';
      var sku_id = $('label.active input[name=skus]').val();
      var amount = $('.cart_amount input').val();
    //   var sku_id  = '{{ $product->id }}';
    //   var amount  = '{{ $product->id }}';


      var _token ='{{ csrf_token() }}';
        console.log(sku_id+'```'+ amount);
       layui.use(['layer', 'form'], function () {
          var layer = layui.layer;
          loading = layer.load(3);

        // if (amount == 0) {
        //             layer.msg('商品输入不能为0', {icon: 5});
        //             layer.close(loading);
        //             return;
        //     }
        // if (undefined == sku_id  || '' == sku_id) {
        //     layer.msg('请选择商品', {icon: 5});
        //     layer.close(loading);
        //     return;
        // }

          $.ajax({
              url: url,
              type: 'POST',
              dataType: 'JSON',
              data: {
                  'sku_id': sku_id,
                  'amount': amount,
                  '_token': _token
              },
              success: function (data) {
                  // //撤回加载层
                   layer.close(loading);
                   console.log(data);
                 layer.msg('添加成功', function () {
                              console.log(data)
                              //操作成功刷新页面
                                location.href = '{{ route('cart.index') }}';

                        })
              },
              error: function (data) {

                console.log(data.responseJSON)

                if(data.responseJSON.errors) {
                          if(data.responseJSON.errors.sku_id) {
                            layer.close(loading);
                            layer.msg(data.responseJSON.errors.sku_id[0], {icon: 5});
                          }

                          if(data.responseJSON.errors.amount) {
                            layer.close(loading);
                            layer.msg(data.responseJSON.errors.amount[0], {icon: 5});
                          }

                        } else {
                          layer.close(loading);
                          layer.msg('请先登录', {icon: 5});

                        }
              },
          })

        })
    });




  });
</script>
@endsection
