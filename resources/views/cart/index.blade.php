@extends('layouts.app')
@section('title', '购物车')

@section('content')
<div class="row">
<div class="col-lg-10 offset-lg-1">
<div class="card">
  <div class="card-header">我的购物车</div>
  <div class="card-body">
    <table class="table table-striped">
      <thead>
      <tr>
        <th><input type="checkbox" id="select-all"></th>
        <th>商品信息</th>
        <th>单价</th>
        <th>数量</th>
        <th>操作</th>
      </tr>
      </thead>
      <tbody class="product_list">
      @foreach($cartItems as $item)
        <tr data-id="{{ $item->productSku->id }}">
          <td>
            <input type="checkbox" name="select" value="{{ $item->productSku->id }}" {{ $item->productSku->product->on_sale ? 'checked' : 'disabled' }}>
          </td>
          <td class="product_info">
            <div class="preview">
              <a target="_blank" href="{{ route('products.show', [$item->productSku->product_id]) }}">
                <img src="{{ $item->productSku->product->image_url }}">
              </a>
            </div>
            <div @if(!$item->productSku->product->on_sale) class="not_on_sale" @endif>
              <span class="product_title">
                <a target="_blank" href="{{ route('products.show', [$item->productSku->product_id]) }}">{{ $item->productSku->product->title }}</a>
              </span>
              <span class="sku_title">{{ $item->productSku->title }}</span>
              @if(!$item->productSku->product->on_sale)
                <span class="warning">该商品已下架</span>
              @endif
            </div>
          </td>
          <td><span class="price">￥{{ $item->productSku->price }}</span></td>
          <td>
            <input type="text" class="form-control form-control-sm amount" @if(!$item->productSku->product->on_sale) disabled @endif name="amount" value="{{ $item->amount }}">
          </td>
          <td>
            <button class="btn btn-sm btn-danger btn-remove">移除</button>
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>

    <!-- 开始 -->
<div>
  <form class="form-horizontal" role="form" id="order-form">
    <div class="form-group row">
      <label class="col-form-label col-sm-3 text-md-right">选择收货地址</label>
      <div class="col-sm-9 col-md-7">
        <select class="form-control" name="address">
          @foreach($addresses as $address)
            <option value="{{ $address->id }}">{{ $address->full_address }} {{ $address->contact_name }} {{ $address->contact_phone }}</option>
          @endforeach
        </select>
      </div>
    </div>
    <div class="form-group row">
      <label class="col-form-label col-sm-3 text-md-right">备注</label>
      <div class="col-sm-9 col-md-7">
        <textarea name="remark" class="form-control" rows="3"></textarea>
      </div>
    </div>
    <div class="form-group">
      <div class="offset-sm-3 col-sm-3">
        <button type="button" class="btn btn-primary btn-create-order">提交订单</button>
      </div>
    </div>
  </form>
</div>
<!-- 结束 -->
  </div>
</div>
</div>
</div>
@endsection
@section('js')
<script type="text/javascript">
  $(document).ready(function () {

     // 监听 移除 按钮的点击事件
    $('.btn-remove').click(function () {
      // $(this) 可以获取到当前点击的 移除 按钮的 jQuery 对象

      var id = $(this).closest('tr').data('id');

        layui.use(['layer', 'form'], function () {
            var layer = layui.layer;

            var button = layer.confirm('确认删除吗？', {icon: 3, title:'提示'}, function(index){
            //loading
            var loading = layer.load(2);
            //close button
            layer.close(button);

            //do delete
            $.ajax({
                url: '/cart/'+ id,
                type: 'get',
                dataType: 'JSON',
                success: function (data) {
                  console.log(data);
                    //撤回加载层
                    layer.close(loading);

                    layer.msg('删除成功', function () {
                        //操作成功刷新页面
                        location.reload();
                    });
                },
                error:  function (data) {
                    console.log(data);
                   layer.msg('未知错误', {icon: 5});
                   layer.close(loading);
              },
            })
        })
        })

    });


    // 监听 全选/取消全选 单选框的变更事件
    $('#select-all').change(function() {
      // 获取单选框的选中状态
      // prop() 方法可以知道标签中是否包含某个属性，当单选框被勾选时，对应的标签就会新增一个 checked 的属性
      var checked = $(this).prop('checked');
      // 获取所有 name=select 并且不带有 disabled 属性的勾选框
      // 对于已经下架的商品我们不希望对应的勾选框会被选中，因此我们需要加上 :not([disabled]) 这个条件
      $('input[name=select][type=checkbox]:not([disabled])').each(function() {
        // 将其勾选状态设为与目标单选框一致
        $(this).prop('checked', checked);
      });
    });

    //订单 create
    $('.btn-create-order').click(function () {
      var req = {
        address_id: $('#order-form').find('select[name=address]').val(),
        items: [],
        _token: '{{ csrf_token()}}',
        remark: $('#order-form').find('textarea[name=remark]').val(),
      };
    //   console.log(req);
        layui.use(['layer', 'form'], function () {
            var layer = layui.layer;
            //loading
            var loading = layer.load(2);
            // 拿取商值
            $('table tr[data-id]').each(function () {

              // 获取当前行的单选框
              var $checkbox = $(this).find('input[name=select][type=checkbox]');
              // 如品的果单选框被禁用或者没有被选中则跳过
              if ($checkbox.prop('disabled') || !$checkbox.prop('checked')) {
                return;
              }

              var $input = $(this).find('input[name=amount]');
              // 如果用户将数量设为 0 或者不是一个数字，则也跳过

              if ($input.val() == 0 || isNaN($input.val())) {
                return;
              }

              // 把 SKU id 和数量存入请求参数数组中
              req.items.push({
                sku_id: $(this).data('id'),
                amount: $input.val(),
              })
            })
            console.log(req);


            // return;

            if (req.items.length < 1) {

              layer.close(loading);
              layer.msg('请选择商品',{icon: 5});
              return ;
            }
            //ajax
            $.ajax({
              url: '{{route("orders.store")}}',
              type: 'POST',
              dataType: 'JSON',
              data: req,
              success: function (data) {

                console.log(data);
                  // //撤回加载层
                   layer.close(loading);

                 layer.msg('购买成功');
              }
          })

        });
    });


  });
</script>

@endsection
