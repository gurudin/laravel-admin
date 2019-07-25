@extends(config('admin.extends_blade'))

@section('title')
{{__('admin::messages.permission.permissions')}}
@endsection

@section('css')

@endsection

@section('content')
<div class="col col-header">
  <div class="float-left">
    <h5 class="text-monospace">{{__('admin::messages.permission.permissions')}}: {{ $name }}</h5>
  </div>
  <div class="float-right text-monospace">
    <a href="{{ route('admin.permission') }}" class="text-dark"><i class="fa fa-angle-left"></i> {{__('admin::messages.common.back')}}</a>
  </div>
</div>

<div class="col-12" id="app">
  <div class="col-body rounded-sm">
    <div class="row">
        <div class="col">
          <div class="form-group">
            <input type="text" class="form-control multiple-input" v-model="searchKey.route" placeholder="{{__('admin::messages.permission.search-for-routes')}}">
            <select multiple class="form-control multiple-select" size="20" ref="select-route">
              <optgroup label="Routes">
                <option v-for="route in routeData" :value="route.method+' '+route.name">@{{route.method.toUpperCase()}} @{{route.name}}</option>
              </optgroup>
            </select>
          </div>
        </div>
    
        <div class="col-1 text-center multiple-center">
          <button type="button" class="btn btn-success" @click="addRoutes"><i class="fa fa-angle-double-right"></i></button>
          <br><br>
          <button type="button" class="btn btn-danger" @click="removeRoutes"><i class="fa fa-angle-double-left"></i></button>
        </div>
    
        <div class="col">
          <div class="form-group">
            <input type="text" class="form-control multiple-input" v-model="searchKey.permission" placeholder="{{__('admin::messages.permission.search-for-assigned')}}">
            <select multiple class="form-control multiple-select" size="20" ref="select-assigned">
              <optgroup label="Routes">
                <option v-for="route in permissionRouteData" :value="route.method+' '+route.child">@{{route.method.toUpperCase()}} @{{route.child}}</option>
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
      routeItem: @json($routes),
      permissionRouteItem: @json($child_items),
      name: '{{$name}}',
      searchKey: { route: "", permission: "" }
    };
  },
  computed: {
    permissionRouteData() {
      var keyWord = this.searchKey.permission && this.searchKey.permission.toLowerCase();
      var data = this.permissionRouteItem;
      if (!keyWord) {
        return data;
      }
      data = data.filter(row => {
        return (
          String(row.child).toLowerCase().indexOf(keyWord) > -1
        );
      });
      return data;
    },
    routeData() {
      var keyWord = this.searchKey.route && this.searchKey.route.toLowerCase();
      var data = this.routeItem;
      
      if (data) {
        data = data.filter(row => {
          var check = true;
          this.permissionRouteItem.forEach(function(val) {
            if (row.method+row.name == val.method+val.child) {
              check = false;
            }
          });
          return check;
        });
      }
      if (!keyWord) {
        return data;
      }
      data = data.filter(row => {
        return (
          String(row.name)
            .toLowerCase()
            .indexOf(keyWord) > -1
        );
      });
      return data;
    }
  },
  methods: {
    addRoutes(event) {
      var select_routes = $(this.$refs["select-route"]).val();
      if (select_routes.length == 0) {
        return false;
      }
      var childs = [];
      select_routes.forEach(row =>{
        let tmp = row.split(" ");
        childs.push({method: tmp[0], child: tmp[1]});
      });
      var _this = this;
      var $btn = $(event.currentTarget);
      $btn.loading('<i class="fas fa-spinner fa-spin"></i>');
      axios.post('{{route("admin.post.permission.batchRouteChild")}}', {
        parent: _this.name,
        childs: childs
      }).then(function (response) {
        if (response.data.status) {
          select_routes.forEach(row => {
            let tmp = row.split(" ");
            _this.permissionRouteItem.push({
              parent: _this.name,
              method: tmp[0],
              child: tmp[1]
            });
          });
        } else {
          alert(response.data.msg);
        }
        $btn.loading("reset");
      });
    },
    removeRoutes(event) {
      var select_assigned = $(this.$refs["select-assigned"]).val();
      if (select_assigned.length == 0) {
        return false;
      }
      var childs = [];
      select_assigned.forEach(row =>{
        let tmp = row.split(" ");
        childs.push({method: tmp[0], child: tmp[1]});
      });
      var _this = this;
      var $btn = $(event.currentTarget);
      $btn.loading('<i class="fas fa-spinner fa-spin"></i>');
      axios.delete('{{route("admin.delete.permission.batchRouteChild")}}', { data: {
        parent: _this.name,
        childs: childs
      }}).then(function (response) {
        if (response.data.status) {
          for (let i = _this.permissionRouteItem.length-1; i >= 0; i--) {
            if (select_assigned.indexOf(_this.permissionRouteItem[i]['method'] + ' ' + _this.permissionRouteItem[i]['child']) > -1) {
              _this.permissionRouteItem.splice(i, 1);
            }
          }
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
