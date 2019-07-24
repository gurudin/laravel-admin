@extends(config('admin.extends_blade'))

@section('title')
Routes
@endsection

@section('css')

@endsection

@section('content')
<div class="col col-header">
  <div class="float-left">
    <h5 class="text-monospace">Routes</h5>
  </div>
  <div class="float-right text-monospace">
  </div>
</div>

<div class="col-12" id="app">
  <div class="col-body rounded-sm">
    <div class="row">
      <div class="col-5">
        <input type="text" placeholder="Search for available" v-model.trim="searchKey.available" class="form-control multiple-input">
        <select multiple class="form-control multiple-select" v-model="active.available">
          <option v-for="item in availableData" :value="item">[@{{item.method.toUpperCase()}}] &nbsp; @{{item.name}}</option>
        </select>
      </div>

      <div class="col-1 text-center multiple-center">
        <button type="button" class="btn btn-success" @click="addRoutes"><i class="fa fa-angle-double-right"></i></button>
        <br><br>
        <button type="button" class="btn btn-danger" @click="removeRoutes"><i class="fa fa-angle-double-left"></i></button>
      </div>

      <div class="col-5">
        <input type="text" placeholder="Search for assigned" v-model.trim="searchKey.assigned" class="form-control multiple-input">
        <select multiple class="form-control multiple-select" v-model="active.assigned">
          <option v-for="item in assignedData" :value="item">[@{{item.method.toUpperCase()}}] &nbsp; @{{item.name}}</option>
        </select>
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
      available: @json($local_routes),
      assigned: @json($routes),
      searchKey: {
        available: '',
        assigned: ''
      },
      active: {
        available: [],
        assigned: []
      },
    };
  },
  computed: {
    availableData () {
      var data = this.available;
      
      data = this.available.filter((available,inx) =>{
        var check = true;
        this.assigned.filter(assigned =>{
          if (assigned.method + assigned.name == available.method + available.name) {
            check = false;
          }
        });

        return check;
      });

      var availableKey = this.searchKey.available && this.searchKey.available.toLowerCase();
      data = data.filter(row =>{
        return String(row.method).toLowerCase().indexOf(availableKey) > -1
          || String(row.name).toLowerCase().indexOf(availableKey) > -1;
      });

      return data;
    },
    assignedData() {
      var assignedKey = this.searchKey.assigned && this.searchKey.assigned.toLowerCase();
      var data = this.assigned.filter(row =>{
        return String(row.method).toLowerCase().indexOf(assignedKey) > -1
          || String(row.name).toLowerCase().indexOf(assignedKey) > -1;
      });

      return data;
    }
  },
  methods: {
    addRoutes(event) {
      if (this.active.available.length == 0) {
        return false;
      }
      
      this.assigned = this.assigned.concat(this.active.available);
      
      var _this = this;
      var $loading = $(event.currentTarget);
      $loading.loading('<i class="fas fa-spinner fa-spin"></i>');
      axios.post('{{route("admin.post.route.create")}}', this.active.available).then(function (response) {
        _this.active.available = [];
        $loading.loading('reset');
      });
    },
    removeRoutes(event) {
      if (this.active.assigned.length == 0) {
        return false;
      }

      var _this = this;
      var $loading = $(event.currentTarget);
      $loading.loading('<i class="fas fa-spinner fa-spin"></i>');
      axios.delete('{{route("admin.delete.route.destroy")}}', {
        data: this.active.assigned
      }).then(function (response) {
        _this.active.assigned.forEach(row =>{
          for (let i = _this.assigned.length-1; i >= 0; i--) {
            if (row.method+row.name == _this.assigned[i].method+_this.assigned[i].name) {
              _this.assigned.splice(i, 1);
            }
          }
        });

        $loading.loading('reset');
      });
    }
  }
});
</script>
@endsection
