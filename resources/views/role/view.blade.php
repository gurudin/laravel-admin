@extends(config('admin.extends_blade'))

@section('title')
  @lang('admin::messages.role.role') {{$name}}
@endsection

@section('css')

@endsection

@section('content')
<div class="col col-header">
  <div class="float-left">
    <h5 class="text-monospace">@lang('admin::messages.role.role'): {{ $name }}</h5>
  </div>
  <div class="float-right text-monospace">
    <a href="{{ route('admin.role') }}" class="text-dark">
      <i class="fa fa-angle-left"></i> @lang('admin::messages.common.back')
    </a>
  </div>
</div>

<div class="col-12" id="app">
  <div class="col-body rounded-sm">
    <div class="row">
      <div class="col">
        <div class="form-group">
          <input type="text"
            class="form-control multiple-input"
            v-model="searchKey.available"
            placeholder="@lang('admin::messages.role.search-for-permission')">
          <select multiple class="form-control multiple-select" v-model="active.available">
            <optgroup v-for="(list,key) in permissionAndRolesData" :label="key">
              <option v-for="item in list" :value="item">@{{item.name}}</option>
            </optgroup>
          </select>
        </div>
      </div>

      <div class="col-1 text-center multiple-center">
        <button type="button" class="btn btn-success" @click="addRole"><i class="fa fa-angle-double-right"></i></button>
        <br><br>
        <button type="button" class="btn btn-danger" @click="removeRole"><i class="fa fa-angle-double-left"></i></button>
      </div>

      <div class="col">
        <div class="form-group">
          <input type="text"
            class="form-control multiple-input"
            v-model="searchKey.assigned"
            placeholder="{{__('admin::messages.role.search-for-assigned')}}">
          <select multiple class="form-control multiple-select" v-model="active.assigned">
            <optgroup label="Routes">
              <option v-for="route in permissionRoleItem" :value="route">@{{route.child}}</option>
            </optgroup>
          </select>
        </div>
      </div>
    </div>
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
      permissionAndRoles: @json($items),
      permissionRoleItem: @json($itemChildren),
      name: '{{$name}}',
      searchKey: { available: "", assigned: "" },
      active: {
        available: {},
        assigned: {}
      },
    };
  },
  computed: {
    permissionAndRolesData() {
      var searchKey = this.searchKey.available && String(this.searchKey.available.toLowerCase());
      
      var roles = this.permissionAndRoles.roles;
      var permissions = this.permissionAndRoles.permissions;
      roles = roles.filter(row =>{
        var chk = true;
        this.permissionRoleItem.forEach(item =>{
          if (item.child == row.name) {
            chk = false;
          }
        });
        return String(row.name).toLowerCase().indexOf(searchKey) > -1 && chk;
      });

      permissions = permissions.filter(row =>{
        var chk = true;
        this.permissionRoleItem.forEach(item =>{
          if (item.child == row.name) {
            chk = false;
          }
        });

        return String(row.name).toLowerCase().indexOf(searchKey) > -1 && chk;
      });
      
      return {roles: roles, permissions: permissions};
    }
  },
  methods: {
    addRole(event) {
      if (this.active.available.length == 0) {
        return false;
      }

      var childs = [];
      this.active.available.forEach(row =>{
        childs.push({
          parent: this.name,
          method: '',
          child: row.name
        });
      });

      var _this = this;
      var $btn = $(event.currentTarget);
      $btn.loading('<i class="fas fa-spinner fa-spin"></i>');
      axios.post('{{route("admin.post.permission.batchRouteChild")}}', {
        parent: _this.name,
        childs: childs
      }).then(function (response) {
        if (response.data.status) {
          _this.permissionRoleItem = _this.permissionRoleItem.concat(childs);
        } else {
          alert(response.data.msg);
        }
        $btn.loading("reset");
      });
    },
    removeRole(event) {
      if (this.active.assigned.length == 0) {
        return false;
      }

      var _this = this;
      var $btn = $(event.currentTarget);
      $btn.loading('<i class="fas fa-spinner fa-spin"></i>');
      axios.delete('{{route("admin.delete.permission.batchRouteChild")}}', { data: {
        parent: _this.name,
        childs: _this.active.assigned
      }}).then(function (response) {
        if (response.data.status) {
          _this.active.assigned.forEach(row =>{
            for (let i = _this.permissionRoleItem.length - 1; i >= 0; i--) {
              if (row.child == _this.permissionRoleItem[i].child) {
                _this.permissionRoleItem.splice(i, 1);
              }
            }
          });
        } else {
          alert(response.data.status);
        }
        $btn.loading("reset");
      });
    },
  }
});
</script>
@endsection
