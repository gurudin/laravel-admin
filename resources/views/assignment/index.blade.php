@extends(config('admin.extends_blade'))

@section('title')
  @lang("admin::messages.assignment.assignment")
@endsection

@section('css')

@endsection

@section('content')
<div class="col col-header">
  <div class="float-left">
    <h5 class="text-monospace">@lang("admin::messages.assignment.assignment")</h5>
  </div>
  <div class="float-right text-monospace">
  </div>
</div>

<div class="col" id="app">
  <div class="col-body rounded-sm">
    <small class="text-muted">Showing 1-@{{users.length}} of @{{users.length}} items.</small>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">@lang("admin::messages.assignment.nick")</th>
          <th scope="col">@lang('admin::messages.assignment.email')</th>
          <th scope="col">@lang('admin::messages.common.action')</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th></th>
          <th><input type="text" class="form-control" v-model.trim="searchKey.name"></th>
          <th><input type="text" class="form-control" v-model.trim="searchKey.email"></th>
          <th></th>
        </tr>
        <tr v-for="(user,inx) in usersData">
          <th scope="row">@{{ user.id }}</th>
          <td>@{{ user.name }}  <small class="text-muted" v-if="user.admin">(@lang('admin::messages.assignment.administrator'))</small></td>
          <td>@{{ user.email }}</td>
          <td>
            <button class="btn btn-xs btn-info" :disabled="user.admin" @click="toView(user)"><i class="fa fa-eye"></i></button>
            <button class="btn btn-xs btn-danger" :disabled="user.admin" @click="remove($event, user)"><i class="fa fa-trash-alt"></i></button>
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
      users: @json($users),
      searchKey: {name: '', email: ''},
    };
  },
  computed: {
    usersData() {
      var data = this.users.filter(row =>{
        return String(row.name).toLowerCase().indexOf(this.searchKey.name.toLowerCase()) > -1
          && String(row.email).toLowerCase().indexOf(this.searchKey.email.toLowerCase()) > -1;
      });

      return data;
    },
  },
  methods: {
    toView(user) {
      window.location = new URL("{{ route('admin.assignment.view') }}/" + user.id);
    },
    remove(event, user) {
      console.log(user);
      
    }
  }
});
</script>
@endsection
