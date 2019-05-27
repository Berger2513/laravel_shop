@extends('layouts.app')
@section('title', '收货地址列表')

@section('content')
<div class="row">
    <div class="col-md-10 offset-md-1">
      <div class="card panel-default">
        <div class="card-header">
            收货地址列表
            <a href="{{ route('user_addresses.create') }}" class="float-right">新增收货地址</a>
        <div class="card-body">
          <table class="table table-bordered table-striped">
            <thead>
            <tr>
              <th>收货人</th>
              <th>地址</th>
              <th>邮编</th>
              <th>电话</th>
              <th>操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($addresses as $address)
              <tr>
                <td>{{ $address->contact_name }}</td>
                <td>{{ $address->full_address }}</td>
                <td>{{ $address->zip }}</td>
                <td>{{ $address->contact_phone }}</td>
                <td>
                  <a href="{{ route('user_addresses.edit', array('user_address' => $address->id))}}" class="btn btn-primary">修改</a>
                  <button class="btn btn-danger btn-del" data-url = "{{ route('user_addresses.destroy', ['user_address' => $address->id]) }}">删除</button>
  </form>
                </td>
              </tr>
            @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@stop


@section('js')
<script type="text/javascript">

$('div.alert').not('.alert-important').delay(3000).fadeOut(350);

$(document).ready(function(){
      $('.btn-del').on('click', function(){
            var url = $(this).data('url');

            layui.use(['layer', 'form'], function () {
              var layer = layui.layer;
              var button = layer.confirm('确认删除吗？', {icon: 3, title:'提示'}, function(index){
                //loading
                var loading = layer.load(1);
                //close button
                layer.close(button);
                var token = '{{ csrf_token()}}';
                //do delete
                 $.ajax({
                    url: url,
                    type: 'delete',
                    dataType: 'JSON',
                    data: { '_token': token},
                    success: function (data) {
                      console.log(data)
                        //撤回加载层
                        layer.close(loading);
                        if (data.status == 1) {
                            layer.msg('删除成功', function () {
                                //操作成功刷新页面
                                location.reload();
                            });
                        } else if(data.status == -1) {
                            layer.msg('删除失败', {icon: 5});
                        } else {
                            layer.msg('没有权限', {icon: 5});
                        }

                    }
                })


              });
        })
      })
})
</script>
@endsection