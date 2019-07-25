@extends(config('admin.extends_blade'))

@section('title')
  @lang('admin::messages.role.role')
@endsection

@section('css')

@endsection

@section('content')
<div class="col col-header">
  <div class="float-left">
    <h5 class="text-monospace">@lang('admin::messages.role.role')</h5>
  </div>
  <div class="float-right text-monospace">
    <button @click="showModal('create')" class="btn btn-sm btn-success">
      <i class="fa fa-edit"></i> @lang('admin::messages.common.create')
    </button>
  </div>
</div>

<div class="col-12" id="app">
  <div class="col-body rounded-sm">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">@lang('admin::messages.role.name')</th>
          <th scope="col">@lang('admin::messages.role.description')</th>
          <th scope="col">@lang('admin::messages.common.action')</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td></td>
          <td><input type="text" class="form-control" v-model.trim="searchKey.name"></td>
          <td><input type="text" class="form-control" v-model.trim="searchKey.description"></td>
          <td></td>
        </tr>
        <tr v-for="(item,inx) in rolesData">
          <th>@{{inx + 1}}</th>
          <td>@{{item.name}}</td>
          <td v-html="item.description ? item.description : '<span class=\'text-muted\'>@lang("admin::messages.common.not-set")</span>'"></td>
          <td>
            <button class="btn btn-xs btn-info" @click="toView(item)"><i class="fa fa-eye"></i></button>
            <button class="btn btn-xs btn-warning" @click="showModal('update', item)"><i class="fa fa-edit"></i></button>
            <button class="btn btn-xs btn-danger" @click="deleteRole($event, inx, item)"><i class="fa fa-trash-alt"></i></button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Save role modal -->
  <div class="modal fade" id="saveModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">@lang('admin::messages.role.role') @{{modalTitle=='update' ? ': ' + roleModel.old.name : ''}}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form>
            <div class="form-group">
              <label for="recipient-name" class="col-form-label">@lang('admin::messages.role.name'):</label>
              <input
                type="text"
                class="form-control"
                :class="{'is-invalid':isValid && !roleModel.new.name}"
                v-model.trim="roleModel.new.name"
                placeholder="@lang('admin::messages.role.name')">
            </div>
            <div class="form-group">
              <label for="message-text" class="col-form-label">@lang('admin::messages.role.description'):</label>
              <input
                type="text"
                class="form-control"
                :class="{'is-invalid':isValid && !roleModel.new.description}"
                v-model.trim="roleModel.new.description"
                placeholder="@lang('admin::messages.role.description')">
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light-success" @click="save">@lang('admin::messages.common.save')</button>
        </div>
      </div>
    </div>
  </div>
  <!-- /Save role modal -->
</div>
@endsection

@section('script')
<script src="{{ URL::asset('vendor/gurudin/js/vue-2.6.10.js') }}"></script>
<script>
const vm = new Vue({
  el: '.lay-body',
  data() {
    return {
      roles: @json($roles),
      modalTitle: 'create',
      roleModel: {
        old: {},
        new: {}
      },
      isValid: false,
      searchKey: {
        name: '',
        description: '',
      },
    };
  },
  computed: {
    rolesData() {
      var data = this.roles;

      data = data.filter(row =>{
        return row.name.indexOf(this.searchKey.name) > -1
          && row.description.indexOf(this.searchKey.description) > -1;
      });

      return data;
    }
  },
  methods: {
    showModal(method, item={}) {
      this.modalTitle = method;
      if (method == 'create') {
        this.roleModel.old = {};
        this.roleModel.new = {};
      } else {
        this.roleModel.old = item;
        this.roleModel.new = $.extend({}, item);
      }
      
      $('#saveModal').modal('show');
    },
    save(event) {
      this.isValid = true;
      if (!this.roleModel.new.name
        || !this.roleModel.new.description
      ) {
        return false;
      }

      this.roleModel.new.type = 1;
      this.roleModel.new.method = '';
      var $btn = $(event.currentTarget);
      var _this = this;
      $btn.loading('<i class="fas fa-spinner fa-spin"></i>');
      if (JSON.stringify(this.roleModel.old) == "{}") {
        // Create
        axios.post('{{route("admin.post.role.create")}}', this.roleModel.new).then(function (response) {
          if (response.data.status) {
            window.location.reload();
          } else {
            alert(response.data.msg);
            $btn.loading('reset');
          }
        });
      } else {
        // Update
        axios.put('{{route("admin.put.role.update")}}', this.roleModel).then(function (response) {
          if (response.data.status) {
            window.location.reload();
          } else {
            alert(response.data.msg);
            $btn.loading('reset');
          }
        });
      }
    },
    deleteRole(event, inx, item) {
      if (!confirm('@lang("admin::messages.common.are-you-sure-to-delete-this-item")')) {
        return false;
      }

      var _this = this;
      var $btn = $(event.currentTarget);
      $btn.loading('<i class="fas fa-spinner fa-spin"></i>');
      axios.delete('{{route("admin.delete.role.destroy")}}', { data: {
        name: item.name,
        method: item.method
      }}).then(function (response) {
        if (response.data.status) {
          _this.roles.splice(inx, 1);
        } else {
          alert(response.data.msg);
        }
        $btn.loading('reset');
      });
    },
    toView(item) {
      window.location = new URL("{{route('admin.role.view')}}/" + item.name);
    }
  }
});
</script>
@endsection
