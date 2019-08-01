@extends(config('admin.extends_blade'))

@section('title')
  @lang('admin::messages.assignment.assignment')
@endsection

@section('css')

@endsection

@section('content')
<div class="col col-header">
  <div class="float-left">
    <h5 class="text-monospace">@lang('admin::messages.assignment.assignment'): {{ $userDetail['email'] }}</h5>
  </div>
  <div class="float-right text-monospace">
    <a href="{{ route('admin.assignment') }}" class="text-dark">
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
            placeholder="@lang('admin::messages.assignment.search-for-permission')">
          <select multiple class="form-control multiple-select" v-model="active.available">
            <optgroup v-for="(list,key) in permissionAndRolesData" :label="key">
              <option v-for="item in list" :value="item">@{{item.name}}</option>
            </optgroup>
          </select>
        </div>
      </div>

      <div class="col-1 text-center multiple-center">
        <button type="button" class="btn btn-success" @click="addPermission"><i class="fa fa-angle-double-right"></i></button>
        <br><br>
        <button type="button" class="btn btn-danger" @click="removePermission"><i class="fa fa-angle-double-left"></i></button>
      </div>
      
      <div class="col">
        <div class="form-group">
          <input type="text"
            class="form-control multiple-input"
            v-model="searchKey.assigned"
            placeholder="@lang('admin::messages.assignment.search-for-permission')">
          <select multiple class="form-control multiple-select" v-model="active.assigned">
            <option v-for="assign in assignmentsData" :value="assign">@{{assign.item_name}}</option>
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
      assignments: @json($assignments),
      searchKey: {
        available: '',
        assigned: ''
      },
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
        this.assignments.forEach(item =>{
          if (item.item_name == row.name) {
            chk = false;
          }
        });

        return String(row.name).toLowerCase().indexOf(searchKey) > -1 && chk;
      });

      permissions = permissions.filter(row =>{
        var chk = true;
        this.assignments.forEach(item =>{
          if (item.item_name == row.name) {
            chk = false;
          }
        });

        return String(row.name).toLowerCase().indexOf(searchKey) > -1 && chk;
      });
      
      return {roles: roles, permissions: permissions};
    },
    assignmentsData() {
      var data = this.assignments;
      data = data.filter(row =>{
        return String(row.item_name).toUpperCase().indexOf(this.searchKey.assigned.toUpperCase()) > -1;
      });

      return data;
    }
  },
  methods: {
    addPermission(event) {
      if (this.active.available.length == 0) {
        return false;
      }
      
      var data = [];
      this.active.available.forEach(row =>{
        data.push({
          user_id: '{{$userDetail["id"]}}',
          item_name: row.name
        });
      });

      var _this = this;
      var $btn = $(event.currentTarget);
      $btn.loading('<i class="fas fa-spinner fa-spin"></i>');
      axios.post('{{route("admin.post.assignment.batchAssignment")}}', data).then(function(response) {
        return response.data;
      }).then(function(resp) {
        _this.assignments = _this.assignments.concat(data);
        $btn.loading("reset");
      });
    },
    removePermission(event) {
      if (this.active.assigned.length == 0) {
        return false;
      }

      var _this = this;
      var $btn = $(event.currentTarget);
      $btn.loading('<i class="fas fa-spinner fa-spin"></i>');
      axios.delete('{{route("admin.delete.assignment.batchAssignment")}}', {
        data: _this.active.assigned
      }).then(function (response) {
        _this.active.assigned.forEach(row =>{
          for (let i = _this.assignments.length - 1; i >= 0; i--) {
            if (row.item_name == _this.assignments[i].item_name) {
              _this.assignments.splice(i, 1);
            }
          }
        });
        $btn.loading("reset");
      });
    },
  }
});
</script>
@endsection
