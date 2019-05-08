@extends('layouts.app')
@section('title', '新增收货地址')
@section('css')

   <style>
    .form-control{
        font-size: 0.7rem;
    }
   </style>
@endsection
@section('content')
  <div class="layui-row">
    <div class="col-md-10 offset-lg-1">
      <div class="card">
        <div class="card-header">
          <h2 class="text-center">
            {{ $address->id ? '修改': '新增' }}收货地址
          </h2>
        </div>
        @if (count($errors) > 0)
          <div class="alert alert-danger">
            <h4></h4>
            <ul>
              @foreach ($errors->all() as $error)
                <li><i class="glyphicon glyphicon-remove"></i> {{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif
        <div class="card-body">

          @if( empty($address->id))

          <form class="form-horizontal" role="form" action="{{route('user_addresses.store')}}" method="post">

          @else

          <form class="form-horizontal" role="form" action="{{ route('user_addresses.update', ['userAddress' => $address->id]) }}" method="post">
            <input type="hidden" name="address_id" value="{{$address->id}}">
            <input type="hidden" name="_method" value="PUT">
          @endif
            {{csrf_field()}}
            <!-- inline-template 代表通过内联方式引入组件 -->
            <div class="form-group row">
              <label class="col-form-label text-md-right col-sm-2">地址</label>

              <div class="row col-sm-9" id="distpicker" data-toggle="distpicker">
                  <div class="form-group col-sm-4">
                    <label class="sr-only" for="province1">Province</label>
                    <select class="form-control" name="province"></select>
                  </div>
                  <div class="form-group col-sm-4">
                    <label class="sr-only" for="city1">City</label>
                    <select class="form-control" name="city" ></select>
                  </div>
                  <div class="form-group col-sm-4">
                    <label class="sr-only" for="district1">District</label>
                    <select class="form-control" name="district"></select>
                  </div>
              </div>
            </div>

             <div class="form-group row">
              <label class="col-form-label text-md-right col-sm-2">详细地址</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" name="address" value="{{ old('address', $address->address) }}">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-form-label text-md-right col-sm-2">邮编</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" name="zip" value="{{ old('zip', $address->zip) }}">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-form-label text-md-right col-sm-2">姓名</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" name="contact_name" value="{{ old('contact_name', $address->contact_name) }}">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-form-label text-md-right col-sm-2">电话</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" name="contact_phone" value="{{ old('contact_phone', $address->contact_phone) }}">
              </div>
            </div>
            <div class="form-group row text-center">
              <div class="col-12">
                <button type="submit" class="btn btn-primary">提交</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('js')

    <script src="{{ asset('asset/distpicker/js/distpicker.data.js') }}"></script>
    <script src="{{ asset('asset/distpicker/js/distpicker.js') }}"></script>
    <script src="{{ asset('asset/distpicker/js/main.js') }}"></script>
    <script type="text/javascript">
        $("#distpicker").distpicker({
        province: '{{$address->province}}',
        city: '{{$address->city}}',
        district: '{{$address->district}}'
      });
    </script>
@endsection
