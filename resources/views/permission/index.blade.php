@extends(config('admin.extends_blade'))

@section('title')
{{__('admin::messages.permission.permissions')}}
@endsection

@section('css')

@endsection

@section('content')
<div class="col col-header">
  <div class="float-left">
    <h5 class="text-monospace">{{__('admin::messages.permission.permissions')}}</h5>
  </div>
  <div class="float-right text-monospace">
    <button @click="openModal('create')" class="btn btn-sm btn-success"><i class="fa fa-edit"></i> {{__('admin::messages.common.create')}}</button>
  </div>
</div>

<div class="col-12">
  <div class="col-body rounded-sm">
    <vue-tables
    :headings="init.headings"
    :settings="init.settings"
    :options="init.options"></vue-tables>
  </div>

  <!-- Save Modal -->
  <div class="modal fade" id="saveModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">@{{modal.type == 'create' ? 'Create' : 'Update' }} permission</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
              <label>{{__('admin::messages.permission.name')}}</label>
              <input
                type="text"
                class="form-control"
                maxlength="50"
                v-model="modal.new.name"
                :class="{'is-invalid': modal.isValid && modal.new.name==''}"
                placeholder="{{__('admin::messages.permission.textname')}}">
            </div>

            <div class="form-group">
              <label>{{__('admin::messages.permission.description')}}</label>
              <textarea class="form-control"
                v-model="modal.new.description"
                placeholder="{{__('admin::messages.permission.textdescription')}}"
                ></textarea>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light-success" @click="save">{{__('admin::messages.common.save')}}</button>
        </div>
      </div>
    </div>
  </div>
  <!-- /Save Modal -->
</div>
@endsection

@section('script')
<script src="{{ URL::asset('vendor/gurudin/js/vue-2.6.10.js') }}"></script>
<script src="{{ URL::asset('vendor/gurudin/js/vue-tables.js') }}"></script>
<script>
const vm = new Vue({
  el: '.lay-body',
  data() {
    return {
      init: {
        options: @json($permissions),
        headings: {
          name: "{{__('admin::messages.permission.name')}}",
          description: "{{__('admin::messages.permission.description')}}",
          action: "{{__('admin::messages.common.action')}}"
        },
        settings: {
          description: {type: 'callback', func: this.descCall},
          action: {
            type: 'action',
            actions: {
              'view': {icon: 'fa fa-eye', class: 'btn-info', func: this.callView},
              'edit': {icon: 'fa fa-edit', class: 'btn-warning', func: this.callEdit},
              'remove': {icon: 'fa fa-trash-alt', class: 'btn-danger', func: this.callRemove},
            }
          }
        }
      },
      modal: {
        type: 'create',
        isValid: false,
        new: {
          name: '',
          method: '',
          type: 2,
          description: ''
        },
        old: {
          name: '',
          method: '',
          type: 2,
          description: ''
        }
      }
    };
  },
  computed: {
    
  },
  methods: {
    descCall(obj) {
      return obj.value == null ? '<span class="text-muted">{{__("admin::messages.common.not-set")}}</span>' : obj.value;
    },
    openModal(type) {
      this.modal.type = type;
      
      $('#saveModal').modal('show');
    },
    callEdit(obj) {
      this.modal.old = obj.item;
      this.modal.new = $.extend(true, {}, obj.item);
      this.openModal('update');
    },
    save(event) {
      this.modal.isValid = true;
      if (this.modal.new.name == '') {
        return false;
      }

      var $loading = $(event.currentTarget);
      $loading.loading();
      axios.post('{{route("admin.post.permission.save")}}', this.modal).then(function (response) {
        return response.data;
      }).then(function(response) {
        if (response.status) {
          window.location.reload();
        } else {
          alert(response.msg);
          $loading.loading('reset');
        }
      });
    },
    callRemove(obj) {
      if (!confirm("{{__('admin::messages.common.are-you-sure-to-delete-this-item')}}")) {
        return false;
      }

      var _this = this;
      var $btn = $(event.currentTarget);
      $btn.loading('<i class="fas fa-spinner fa-spin"></i>');
      axios.delete('{{route("admin.delete.permission.destroy")}}', { data: {
        name: obj.item.name,
        method: obj.item.method
      }}).then(function (response) {
        if (response.data.status) {
          _this.init.options.splice(obj.inx, 1);
        } else {
          alert(response.data.msg);
        }
        $btn.loading("reset");
      });
    },
    callView(obj) {
      window.location = new URL("{{ route('admin.permission.view') }}" + '/' + obj.item.name);
    }
  }
});
</script>
@endsection
