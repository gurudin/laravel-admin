@extends(config('admin.extends_blade'))

@section('title')
  @lang("admin::messages.assignment.assignment")
@endsection

@section('css')

@endsection

@section('content')
<div class="col col-header">
  <div class="float-left">
    <h5 class="text-monospace">@lang("admin::messages.assignment.assignment"): {{$detail['email']}}</h5>
  </div>
  <div class="float-right text-monospace">
    <a href="{{ route('admin.assignment') }}" class="text-dark">
      <i class="fa fa-angle-left"></i> @lang('admin::messages.common.back')
    </a>
  </div>
</div>

<div class="col" id="app">

  <ul class="nav nav-tabs">
    <li class="nav-item">
      <a class="nav-link" :class="{'active':activeTab=='tab-nick'}" href="#" @click="activeTab='tab-nick'">
        @lang('admin::messages.assignment.change-nick')
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" :class="{'active':activeTab=='tab-password'}" href="#" @click="activeTab='tab-password'">
        @lang('admin::messages.assignment.change-password')
      </a>
    </li>
  </ul>

  <div class="col-body rounded-sm">
    <form id="tab-nick" v-if="activeTab == 'tab-nick'">
      <div class="form-group">
        <label>@lang('admin::messages.assignment.email')</label>
        <input type="email" v-model="user.email" :disabled="true" class="form-control" placeholder="Enter email">
        <small class="form-text text-muted">@lang('admin::messages.assignment.email-text')</small>
      </div>

      <div class="form-group">
        <label>@lang('admin::messages.assignment.nick')</label>
        <input type="text" class="form-control" v-model="user.name" placeholder="@lang('admin::messages.assignment.nick')">
      </div>

      <button type="button" class="btn btn-light-primary" @click="change($event, 'nick')">
        @lang('admin::messages.common.save')
      </button>
    </form>

    <form id="tab-password" v-if="activeTab == 'tab-password'">
      <div class="form-group">
        <label>@lang('admin::messages.assignment.email')</label>
        <input type="email" v-model="user.email" :disabled="true" class="form-control" placeholder="Enter email">
        <small class="form-text text-muted">@lang('admin::messages.assignment.email-text')</small>
      </div>

      <div class="form-group">
        <label>@lang('admin::messages.assignment.password')</label>
        <input type="password" class="form-control" v-model="user.password" placeholder="@lang('admin::messages.assignment.password')">
      </div>

      <div class="form-group">
        <label>@lang('admin::messages.assignment.confirm-password')</label>
        <input type="password" class="form-control" v-model="user.c_password" placeholder="@lang('admin::messages.assignment.confirm-password')">
      </div>

      <button type="button" class="btn btn-light-success" @click="change($event, 'password')">
        @lang('admin::messages.common.save')
      </button>
    </form>
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
      user: @json($detail),
      activeTab: 'tab-nick',
    };
  },
  methods: {
    change(event, type) {
      var _this = this;
      var $btn = $(event.currentTarget);
      $btn.loading();
      axios.put('{{route("admin.put.assignment.update")}}', {
        type: type,
        data: this.user
      }).then(function (response) {
        if (response.data.status) {
          window.location.href = "{{ route('admin.assignment') }}";
        } else {
          alert(response.data.msg);
          $btn.loading('reset');
        }
      });
    }
  }
});
</script>
@endsection
