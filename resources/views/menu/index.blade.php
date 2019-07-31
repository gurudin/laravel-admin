@extends(config('admin.extends_blade'))

@section('title')
  @lang('admin::messages.menu.menu')
@endsection

@section('css')

@endsection

@section('content')
<div class="col col-header">
  <div class="float-left">
    <h5 class="text-monospace">@lang('admin::messages.menu.menu')</h5>
  </div>
  <div class="float-right text-monospace">
    <a href="{{ route('admin.menu.save') }}" class="btn btn-sm btn-success">
      <i class="fa fa-edit"></i> @lang('admin::messages.common.create')
    </a>
  </div>
</div>

<div class="col" id="app">
  <div class="col-body rounded-sm">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th scope="col">@lang('admin::messages.menu.title')</th>
          <th scope="col">@lang('admin::messages.menu.parent')</th>
          <th scope="col">@lang('admin::messages.menu.route')</th>
          <th scope="col">@lang('admin::messages.menu.order')</th>
          <th scope="col">@lang('admin::messages.common.action')</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><input type="text" class="form-control" v-model.trim="searchKey.title"></td>
          <td><input type="text" class="form-control" v-model.trim="searchKey.parent"></td>
          <td><input type="text" class="form-control" v-model.trim="searchKey.route"></td>
          <td><input type="number" class="form-control" v-model.trim="searchKey.order"></td>
          <td></td>
        </tr>
        <tr v-for="(menu,inx) in menuListData">
          <td>@{{ menu.title }}</td>
          <td v-html="menu.parent ? menu.parentName : '<span class=\'text-muted\'>(not set)'"></td>
          <td v-html="menu.route ? menu.route : '<span class=\'text-muted\'>(not set)'"></td>
          <td>@{{ menu.order }}</td>
          <td>
            <button class="btn btn-xs btn-warning" @click="toEdit(menu)"><i class="fa fa-edit"></i></button>
            <button class="btn btn-xs btn-danger" @click="remove($event, menu, inx)"><i class="fa fa-trash-alt"></i></button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
@endsection

@section('script')
<script src="{{ URL::asset('vendor/gurudin/js/vue-2.6.10.js') }}"></script>
<script>
const vm = new Vue({
  el: '#app',
  data() {
    return {
      menuList: @json($list),
      searchKey: {
        title: '',
        parent: '',
        route: '',
        order: ''
      },
    };
  },
  computed: {
    menuListData() {
      var data = this.menuList;
      data = data.filter(row =>{
        return String(row.title).toLowerCase().indexOf(this.searchKey.title) > -1
          && String(row.route).toLowerCase().indexOf(this.searchKey.route) > -1
          && String(row.order).indexOf(this.searchKey.order) > -1
          && String(row.parentName).toLowerCase().indexOf(this.searchKey.parent) > -1;
      });

      return data;
    },
  },
  methods: {
    toEdit(menu) {
      window.location = new URL("{{route('admin.menu.save')}}/" + menu.id);
    },
    remove(event, menu, inx) {
      if (!confirm("{{__('admin::messages.common.are-you-sure-to-delete-this-item')}}")) {
        return false;
      }

      var _this = this;
      var $btn = $(event.currentTarget);
      $btn.loading('<i class="fas fa-spinner fa-spin"></i>');
      axios.delete('{{route("admin.delete.menu.destroy")}}', { data: {
        id: menu.id
      }}).then(function (response) {
        if (response.data.status) {
          _this.menuList.splice(inx, 1);
        } else {
          alert(response.data.msg);
        }
        $btn.loading("reset");
      });
    }
  }
});
</script>
@endsection
